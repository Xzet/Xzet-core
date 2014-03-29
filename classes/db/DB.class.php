<?php
    /**
     * @class DB
     * @author NHN (developers@xpressengine.com)
     * @brief  base class of db* classes
     * @version 0.1
     *
     * usage of db in XE is via xml
     * there are 2 types of xml - query xml, schema xml
     * in case of query xml, DB::executeQuery() method compiles xml file into php code and then execute it
     * query xml has unique query id, and will be created in module
     *
     * queryid = module_name.query_name
     **/

    class DB {

        var $count_cache_path = 'files/cache/db';

        var $cond_operation = array( ///< operations for condition
            'equal' => '=',
            'more' => '>=',
            'excess' => '>',
            'less' => '<=',
            'below' => '<',
            'notequal' => '<>',
            'notnull' => 'is not null',
            'null' => 'is null',
        );

        var $fd = NULL; ///< connector resource or file description

        var $result = NULL; ///< result

        var $errno = 0; ///< error code (0 means no error)
        var $errstr = ''; ///< error message
        var $query = ''; ///< query string of latest executed query
        var $elapsed_time = 0; ///< elapsed time of latest executed query

        var $transaction_started = false; ///< transaction flag

        var $is_connected = false; ///< is db connected

        var $supported_list = array(); ///< list of supported db, (will be written by classes/DB/DB***.class.php)

        var $cache_file = 'files/cache/queries/'; ///< location of query cache

        /**
         * @brief returns instance of certain db type
         * @param[in] $db_type type of db
         * @return instance
         **/
        function &getInstance($db_type = NULL) {
            if(!$db_type) $db_type = Context::getDBType();
            if(!$db_type && Context::isInstalled()) return new Object(-1, 'msg_db_not_setted');

            if(!$GLOBALS['__DB__']) {
                $class_name = 'DB'.ucfirst($db_type);
                $class_file = _XE_PATH_."classes/db/$class_name.class.php";
                if(!file_exists($class_file)) return new Object(-1, 'msg_db_not_setted');

				// get a singletone instance of the database driver class
				require_once($class_file);
                $GLOBALS['__DB__'][$db_type] = call_user_func(array($class_name, 'create'));
            }

            return $GLOBALS['__DB__'][$db_type];
        }

		function create() {
			return new DB;
		}

        /**
         * @brief constructor
         * @return none
         **/
        function DB() {
            $this->count_cache_path = _XE_PATH_.$this->count_cache_path;
            $this->cache_file = _XE_PATH_.$this->cache_file;
        }

        /**
         * @brief returns list of supported db
         * @return list of supported db
         **/
        function getSupportedList() {
            $oDB = new DB();
            return $oDB->_getSupportedList();
        }

        /**
         * @brief returns list of supported db
         * @return list of supported db
         **/
        function _getSupportedList() {
            $db_classes_path = _XE_PATH_."classes/db/";
            $filter = "/^DB([^\.]+)\.class\.php/i";
            $supported_list = FileHandler::readDir($db_classes_path, $filter, true);
            sort($supported_list);

            // after creating instance of class, check is supported
            for($i = 0; $i < count($supported_list); $i++) {
                $db_type = $supported_list[$i];

                if(version_compare(phpversion(), '5.0') < 0 && preg_match('/pdo/i',$db_type)) continue;

                $class_name = sprintf("DB%s%s", strtoupper(substr($db_type,0,1)), strtolower(substr($db_type,1)));
                $class_file = sprintf(_XE_PATH_."classes/db/%s.class.php", $class_name);
                if(!file_exists($class_file)) continue;

                unset($oDB);
                require_once($class_file);
				$tmp_fn = create_function('', "return new {$class_name}();");
				$oDB    = $tmp_fn();

                if(!$oDB) continue;

                $obj = null;
                $obj->db_type = $db_type;
                $obj->enable = $oDB->isSupported() ? true : false;

                $this->supported_list[] = $obj;
            }

            return $this->supported_list;
        }

        /**
         * @brief check if the db_type is supported
         * @param[in] $db_type type of db to check
         * @return true: is supported, false: is not supported
         **/
        function isSupported($db_type) {
            $supported_list = DB::getSupportedList();
            return in_array($db_type, $supported_list);
        }

        /**
         * @brief check if is connected
         * @return true: connected, false: not connected
         **/
        function isConnected() {
            return $this->is_connected ? true : false;
        }

        /**
         * @brief start recording log
         * @return none
         **/
        function actStart($query) {
            $this->setError(0, 'success');
            $this->query = $query;
            $this->act_start = getMicroTime();
            $this->elapsed_time = 0;
        }

        /**
         * @brief finish recording log
         * @return none
         **/
        function actFinish() {
            if(!$this->query) return;
            $this->act_finish = getMicroTime();
            $elapsed_time = $this->act_finish - $this->act_start;
            $this->elapsed_time = $elapsed_time;
            $GLOBALS['__db_elapsed_time__'] += $elapsed_time;

            $log['query'] = $this->query;
            $log['elapsed_time'] = $elapsed_time;

            // leave error log if an error occured (if __DEBUG_DB_OUTPUT__ is defined)
            if($this->isError()) {
                $site_module_info = Context::get('site_module_info');
                $log['module'] = $site_module_info->module;
                $log['act'] = Context::get('act');
                $log['query_id'] = $this->query_id;
                $log['time'] = date('Y-m-d H:i:s');
                $log['result'] = 'Failed';
                $log['errno'] = $this->errno;
                $log['errstr'] = $this->errstr;

                if(__DEBUG_DB_OUTPUT__ == 1)  {
                    $debug_file = _XE_PATH_."files/_debug_db_query.php";
                    $buff = array();
                    if(!file_exists($debug_file)) $buff[] = '<?php exit(); ?>';
                    $buff[] = print_r($log, true);

                    if(@!$fp = fopen($debug_file, "a")) return;
                    fwrite($fp, implode("\n", $buff)."\n\n");
                    fclose($fp);
                }
            } else {
                $log['result'] = 'Success';
            }
            $GLOBALS['__db_queries__'][] = $log;

            // if __LOG_SLOW_QUERY__ if defined, check elapsed time and leave query log
            if(__LOG_SLOW_QUERY__ > 0 && $elapsed_time > __LOG_SLOW_QUERY__) {
                $buff = '';
                $log_file = _XE_PATH_.'files/_db_slow_query.php';
                if(!file_exists($log_file)) {
                    $buff = '<?php exit();?>'."\n";
                }

                $buff .= sprintf("%s\t%s\n\t%0.6f sec\tquery_id:%s\n\n", date("Y-m-d H:i"), $this->query, $elapsed_time, $this->query_id);

                if($fp = fopen($log_file, 'a')) {
                    fwrite($fp, $buff);
                    fclose($fp);
                }
            }
        }

        /**
         * @brief set error
         * @param[in] $errno error code
         * @param[in] $errstr error message
         * @return none
         **/
        function setError($errno = 0, $errstr = 'success') {
            $this->errno = $errno;
            $this->errstr = $errstr;
        }

        /**
         * @brief check if an error occured
         * @return true: error, false: no error
         **/
        function isError() {
            return $this->errno === 0 ? false : true;
        }

        /**
         * @brief returns object of error info
         * @return object of error
         **/
        function getError() {
            $this->errstr = Context::convertEncodingStr($this->errstr);
            return new Object($this->errno, $this->errstr);
        }

        /**
         * @brief query xml 파일을 실행하여 결과를 return
         * @param[in] $query_id query id (module.queryname
         * @param[in] $args arguments for query
         * @return result of query
         * @remarks this function finds xml file or cache file of $query_id, compiles it and then execute it
         **/
        function executeQuery($query_id, $args = NULL, $arg_columns = NULL) {
            if(!$query_id) return new Object(-1, 'msg_invalid_queryid');
            $this->query_id = $query_id;

            $id_args = explode('.', $query_id);
            if(count($id_args) == 2) {
                $target = 'modules';
                $module = $id_args[0];
                $id = $id_args[1];
            } elseif(count($id_args) == 3) {
                $target = $id_args[0];
                if(!in_array($target, array('addons','widgets'))) return;
                $module = $id_args[1];
                $id = $id_args[2];
            }
            if(!$target || !$module || !$id) return new Object(-1, 'msg_invalid_queryid');

            $xml_file = sprintf('%s%s/%s/queries/%s.xml', _XE_PATH_, $target, $module, $id);
            if(!file_exists($xml_file)) return new Object(-1, 'msg_invalid_queryid');

            // look for cache file
            $cache_file = $this->checkQueryCacheFile($query_id, $xml_file);

            // execute query
            return $this->_executeQuery($cache_file, $args, $query_id, $arg_columns);
        }


        /**
         * @brief look for cache file
         * @param[in] $query_id query id for finding
         * @param[in] $xml_file original xml query file
         * @return cache file
         **/
        function checkQueryCacheFile($query_id,$xml_file){

            // first try finding cache file
            $cache_file = sprintf('%s%s%s.cache.php', _XE_PATH_, $this->cache_file, $query_id);

            if(file_exists($cache_file)) $cache_time = filemtime($cache_file);
            else $cache_time = -1;

            // if there is no cache file or is not new, find original xml query file and parse it
            if($cache_time < filemtime($xml_file) || $cache_time < filemtime(_XE_PATH_.'classes/db/DB.class.php') || $cache_time < filemtime(_XE_PATH_.'classes/xml/XmlQueryParser.class.php')) {
                require_once(_XE_PATH_.'classes/xml/XmlQueryParser.class.php');
                $oParser = new XmlQueryParser();
                $oParser->parse($query_id, $xml_file, $cache_file);
            }

            return $cache_file;
        }


        /**
         * @brief execute query and return the result
         * @param[in] $cache_file cache file of query
         * @param[in] $source_args arguments for query
         * @param[in] $query_id query id
         * @return result of query
         **/
        function _executeQuery($cache_file, $source_args, $query_id, $arg_columns) {
            global $lang;

            if(!file_exists($cache_file)) return new Object(-1, 'msg_invalid_queryid');

            if($source_args) $args = @clone($source_args);

            $output = @include($cache_file);

            if( (is_a($output, 'Object') || is_subclass_of($output, 'Object')) && !$output->toBool()) return $output;
            $output->_tables = ($output->_tables && is_array($output->_tables)) ? $output->_tables : array();
			
            // execute appropriate query
            switch($output->action) {
                case 'insert' :
                        $this->resetCountCache($output->tables);
                        $output = $this->_executeInsertAct($output);
                    break;
                case 'update' :
                        $this->resetCountCache($output->tables);
                        $output = $this->_executeUpdateAct($output);
                    break;
                case 'delete' :
                        $this->resetCountCache($output->tables);
                        $output = $this->_executeDeleteAct($output);
                    break;
                case 'select' :
						$output->arg_columns = is_array($arg_columns)?$arg_columns:array();
                        $output = $this->_executeSelectAct($output);
                    break;
            }
			
            if($this->isError()) $output = $this->getError();
            else if(!is_a($output, 'Object') && !is_subclass_of($output, 'Object')) $output = new Object();
            $output->add('_query', $this->query);
            $output->add('_elapsed_time', sprintf("%0.5f", $this->elapsed_time));

            return $output;
        }

        /**
         * @brief check $val with $filter_type
         * @param[in] $key key value
         * @param[in] $val value of $key
         * @param[in] $filter_type type of filter to check $val
         * @return object
         * @remarks this function is to be used from XmlQueryParser
         **/
        function checkFilter($key, $val, $filter_type) {
            global $lang;

            switch($filter_type) {
                case 'email' :
                case 'email_address' :
                        if(!preg_match('/^[_0-9a-z-]+(\.[_0-9a-z-]+)*@[0-9a-z-]+(\.[0-9a-z-]+)*$/is', $val)) return new Object(-1, sprintf($lang->filter->invalid_email, $lang->{$key} ? $lang->{$key} : $key));
                    break;
                case 'homepage' :
                        if(!preg_match('/^(http|https)+(:\/\/)+[0-9a-z_-]+\.[^ ]+$/is', $val)) return new Object(-1, sprintf($lang->filter->invalid_homepage, $lang->{$key} ? $lang->{$key} : $key));
                    break;
                case 'userid' :
                case 'user_id' :
                        if(!preg_match('/^[a-zA-Z]+([_0-9a-zA-Z]+)*$/is', $val)) return new Object(-1, sprintf($lang->filter->invalid_userid, $lang->{$key} ? $lang->{$key} : $key));
                    break;
                case 'number' :
                case 'numbers' :
						if(is_array($val)) $val = join(',', $val);
                        if(!preg_match('/^(-?)[0-9]+(,\-?[0-9]+)*$/is', $val)) return new Object(-1, sprintf($lang->filter->invalid_number, $lang->{$key} ? $lang->{$key} : $key));
                    break;
                case 'alpha' :
                        if(!preg_match('/^[a-z]+$/is', $val)) return new Object(-1, sprintf($lang->filter->invalid_alpha, $lang->{$key} ? $lang->{$key} : $key));
                    break;
                case 'alpha_number' :
                        if(!preg_match('/^[0-9a-z]+$/is', $val)) return new Object(-1, sprintf($lang->filter->invalid_alpha_number, $lang->{$key} ? $lang->{$key} : $key));
                    break;
            }

            return new Object();
        }

        /**
         * @brief returns type of column
         * @param[in] $column_type_list list of column type
         * @param[in] $name name of column type
         * @return column type of $name
         * @remarks columns are usually like a.b, so it needs another function
         **/
        function getColumnType($column_type_list, $name) {
            if(strpos($name, '.') === false) return $column_type_list[$name];
            list($prefix, $name) = explode('.', $name);
            return $column_type_list[$name];
        }

        /**
         * @brief returns the value of condition
         * @param[in] $name name of condition
         * @param[in] $value value of condition
         * @param[in] $operation operation this is used in condition
         * @param[in] $type type of condition
         * @param[in] $column_type type of column
         * @return well modified $value
         * @remarks if $operation is like or like_prefix, $value itself will be modified
         * @remarks if $type is not 'number', call addQuotes() and wrap with ' '
         **/
        function getConditionValue($name, $value, $operation, $type, $column_type) {

            if(!in_array($operation,array('in','notin','between')) && $type == 'number') {
				if(is_array($value)){
					$value = join(',',$value);
				}
                if(strpos($value, ',') === false && strpos($value, '(') === false) return (int)$value;
                return $value;
            }
			
            if(!is_array($value) && strpos($name, '.') !== false && strpos($value, '.') !== false) {
                list($table_name, $column_name) = explode('.', $value);
                if($column_type[$column_name]) return $value;
            }

            switch($operation) {
                case 'like_prefix' :
						if(!is_array($value)) $value = preg_replace('/(^\'|\'$){1}/', '', $value);
                        $value = $value.'%';
                    break;
                case 'like_tail' :
						if(!is_array($value)) $value = preg_replace('/(^\'|\'$){1}/', '', $value);
                        $value = '%'.$value;
                    break;
                case 'like' :
						if(!is_array($value)) $value = preg_replace('/(^\'|\'$){1}/', '', $value);
                        $value = '%'.$value.'%';
                    break;
                case 'notin' :
						if(is_array($value))
						{
							$value = $this->addQuotesArray($value);
							if($type=='number') return join(',',$value);
							else return "'". join("','",$value)."'";
						}
						else
						{
							return $value;
						}
                    break;
                case 'in' :
						if(is_array($value))
						{
							$value = $this->addQuotesArray($value);
							if($type=='number') return join(',',$value);
							else return "'". join("','",$value)."'";
						}
						else
						{
							return $value;
						}
                    break;
                case 'between' :
						if(!is_array($value)) $value = array($value);
			            $value = $this->addQuotesArray($value);
						if($type!='number')
						{
							foreach($value as $k=>$v)
							{
								$value[$k] = "'".$v."'";
							}
						}

						return $value;
                    break;
				default:
					if(!is_array($value)) $value = preg_replace('/(^\'|\'$){1}/', '', $value);
            }

            return "'".$this->addQuotes($value)."'";
        }

        /**
         * @brief returns part of condition
         * @param[in] $name name of condition
         * @param[in] $value value of condition
         * @param[in] $operation operation that is used in condition
         * @return detail condition
         **/
        function getConditionPart($name, $value, $operation) {
            switch($operation) {
                case 'equal' :
                case 'more' :
                case 'excess' :
                case 'less' :
                case 'below' :
                case 'like_tail' :
                case 'like_prefix' :
                case 'like' :
                case 'in' :
                case 'notin' :
                case 'notequal' :
                        // if variable is not set or is not string or number, return
                        if(!isset($value)) return;
                        if($value === '') return;
                        if(!in_array(gettype($value), array('string', 'integer'))) return;
				break;
                case 'between' :
					if(!is_array($value)) return;
					if(count($value)!=2) return;

            }

            switch($operation) {
                case 'equal' :
                        return $name.' = '.$value;
                    break;
                case 'more' :
                        return $name.' >= '.$value;
                    break;
                case 'excess' :
                        return $name.' > '.$value;
                    break;
                case 'less' :
                        return $name.' <= '.$value;
                    break;
                case 'below' :
                        return $name.' < '.$value;
                    break;
                case 'like_tail' :
                case 'like_prefix' :
                case 'like' :
                        return $name.' like '.$value;
                    break;
                case 'in' :
                        return $name.' in ('.$value.')';
                    break;
                case 'notin' :
                        return $name.' not in ('.$value.')';
                    break;
                case 'notequal' :
                        return $name.' <> '.$value;
                    break;
                case 'notnull' :
                        return $name.' is not null';
                    break;
                case 'null' :
                        return $name.' is null';
                    break;
				case 'between' :
                        return $name.' between ' . $value[0] . ' and ' . $value[1];
					break;
            }
        }

        /**
         * @brief returns condition key
         * @param[in] $output result of query
         * @return array of conditions of $output
         **/
        function getConditionList($output) {
            $conditions = array();
            if(count($output->conditions)) {
                foreach($output->conditions as $key => $val) {
                    if($val['condition']) {
                        foreach($val['condition'] as $k => $v) {
                            $conditions[] = $v['column'];
                        }
                    }
                }
            }

            return $conditions;
        }

        /**
         * @brief returns counter cache data
         * @param[in] $tables tables to get data
         * @param[in] $condition condition to get data
         * @return count of cache data
         **/
        function getCountCache($tables, $condition) {
            return false;
            if(!$tables) return false;
            if(!is_dir($this->count_cache_path)) return FileHandler::makeDir($this->count_cache_path);

            $condition = md5($condition);

            if(!is_array($tables)) $tables_str = $tables;
            else $tables_str = implode('.',$tables);

            $cache_path = sprintf('%s/%s%s', $this->count_cache_path, $this->prefix, $tables_str);
            if(!is_dir($cache_path)) FileHandler::makeDir($cache_path);

            $cache_filename = sprintf('%s/%s.%s', $cache_path, $tables_str, $condition);
            if(!file_exists($cache_filename)) return false;

            $cache_mtime = filemtime($cache_filename);

            if(!is_array($tables)) $tables = array($tables);
            foreach($tables as $alias => $table) {
                $table_filename = sprintf('%s/cache.%s%s', $this->count_cache_path, $this->prefix, $table) ;
                if(!file_exists($table_filename) || filemtime($table_filename) > $cache_mtime) return false;
            }

            $count = (int)FileHandler::readFile($cache_filename);
            return $count;
        }

        /**
         * @brief save counter cache data
         * @param[in] $tables tables to save data
         * @param[in] $condition condition to save data
         * @param[in] $count count of cache data to save
         * @return none
         **/
        function putCountCache($tables, $condition, $count = 0) {
            return false;
            if(!$tables) return false;
            if(!is_dir($this->count_cache_path)) return FileHandler::makeDir($this->count_cache_path);

            $condition = md5($condition);

            if(!is_array($tables)) $tables_str = $tables;
            else $tables_str = implode('.',$tables);

            $cache_path = sprintf('%s/%s%s', $this->count_cache_path, $this->prefix, $tables_str);
            if(!is_dir($cache_path)) FileHandler::makeDir($cache_path);

            $cache_filename = sprintf('%s/%s.%s', $cache_path, $tables_str, $condition);

            FileHandler::writeFile($cache_filename, $count);
        }

        /**
         * @brief reset counter cache data
         * @param[in] $tables tables to reset cache data
         * @return true: success, false: failed
         **/
        function resetCountCache($tables) {
            return false;
            if(!$tables) return false;
            if(!is_dir($this->count_cache_path)) return FileHandler::makeDir($this->count_cache_path);

            if(!is_array($tables)) $tables = array($tables);
            foreach($tables as $alias => $table) {
                $filename = sprintf('%s/cache.%s%s', $this->count_cache_path, $this->prefix, $table);
                FileHandler::removeFile($filename);
                FileHandler::writeFile($filename, '');
            }

            return true;
        }

        /**
         * @brief returns supported database list
         * @return list of supported database
         **/
        function getSupportedDatabase(){
            $result = array();

            if(function_exists('mysql_connect')) $result[] = 'MySQL';
            if(function_exists('cubrid_connect')) $result[] = 'Cubrid';
            if(function_exists('ibase_connect')) $result[] = 'FireBird';
            if(function_exists('pg_connect')) $result[] = 'Postgre';
            if(function_exists('sqlite_open')) $result[] = 'sqlite2';
            if(function_exists('mssql_connect')) $result[] = 'MSSQL';
            if(function_exists('PDO')) $result[] = 'sqlite3(PDO)';

            return $result;
        }

        function dropTable($table_name){
            if(!$table_name) return;
            $query = sprintf("drop table %s%s", $this->prefix, $table_name);
            $this->_query($query);
        }

		function addQuotesArray($arr)
		{
			if(is_array($arr))
			{
				foreach($arr as $k => $v)
				{
					$arr[$k] = $this->addQuotes($v);
				}
			}
			else
			{
				$arr = $this->addQuotes($arr);
			}

			return $arr;
		}

        /**
         * @brief Just like numbers, and operations needed to remove the rest
         **/
		function _filterNumber(&$value)
		{
			$value = preg_replace('/[^\d\w\+\-\*\/\.\(\)]/', '', $value);
			$value = preg_replace('@\b(?:select|update|delete)\b|[/+\*]{2,}|(-){2,}@i', '$1', $value);
			if(!$value) $value = 0;
		}
    }
?>
