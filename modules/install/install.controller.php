<?php
    /**
     * @class  installController
     * @author NHN (developers@xpressengine.com)
     * @brief  install module의 Controller class
     **/

    class installController extends install {

        /**
         * @brief 초기화
         **/
        function init() {
            // 설치가 되어 있으면 오류
            if(Context::isInstalled()) {
                return new Object(-1, 'msg_already_installed');
            }
        }

        /**
         * @brief 입력받은 정보로 설치를 함
         **/
        function procInstall() {
            // 설치가 되어 있는지에 대한 체크
            if(Context::isInstalled()) return new Object(-1, 'msg_already_installed');

            // 설치시 임시로 최고관리자로 지정
            $logged_info->is_admin = 'Y';
            $_SESSION['logged_info'] = $logged_info;
            Context::set('logged_info', $logged_info);

            // DB와 관련된 변수를 받음
            $db_info = Context::gets('db_type','db_port','db_hostname','db_userid','db_password','db_database','db_table_prefix','time_zone','use_rewrite');
            if($db_info->use_rewrite!='Y') $db_info->use_rewrite = 'N';
            if(!$db_info->default_url) $db_info->default_url = Context::getRequestUri();
            $db_info->lang_type = Context::getLangType();

            // DB의 타입과 정보를 등록
            Context::setDBInfo($db_info);

            // DB Instance 생성
            $oDB = &DB::getInstance();

            // DB접속이 가능한지 체크
            $output = $oDB->getError();
            if(!$oDB->isConnected()) return $oDB->getError();

            // firebird는 설치시에 트랜젝션을 사용하지 않음
            if($db_info->db_type != "firebird") $oDB->begin();

            // 모든 모듈의 설치
            $this->installDownloadedModule();

            if($db_info->db_type != "firebird") $oDB->commit();

            // config 파일 생성
            if(!$this->makeConfigFile()) return new Object(-1, 'msg_install_failed');

			// load script
            $scripts = FileHandler::readDir('./modules/install/script','/(\.php)$/');
			if(count($scripts)>0){
				sort($scripts);
				foreach($scripts as $script){
					$output = include(FileHandler::getRealPath('./modules/install/script/'.$script));
				}
			}

            // 설치 완료 메세지 출력
            $this->setMessage('msg_install_completed');
        }

        /**
         * @brief FTP 정보 등록
         **/
        function procInstallFTP() {
            if(Context::isInstalled()) return new Object(-1, 'msg_already_installed');
            $ftp_info = Context::gets('ftp_host', 'ftp_user','ftp_password','ftp_port','ftp_root_path');
            $ftp_info->ftp_port = (int)$ftp_info->ftp_port;
            if(!$ftp_info->ftp_port) $ftp_info->ftp_port = 21;
			if(!$ftp_info->ftp_host) $ftp_info->ftp_host = '127.0.0.1';
			if(!$ftp_info->ftp_root_path) $ftp_info->ftp_root_path = '/';

            $buff = '<?php if(!defined("__ZBXE__")) exit();'."\n";
            foreach($ftp_info as $key => $val) {
                $buff .= sprintf("\$ftp_info->%s = '%s';\n", $key, str_replace("'","\\'",$val));
            }
            $buff .= "?>";

            // safe_mode 일 경우
            if(ini_get('safe_mode')) {
                if(!$ftp_info->ftp_user || !$ftp_info->ftp_password) return new Object(-1,'msg_safe_mode_ftp_needed');

                require_once(_XE_PATH_.'libs/ftp.class.php');
                $oFtp = new ftp();
                if(!$oFtp->ftp_connect($ftp_info->ftp_host, $ftp_info->ftp_port)) return new Object(-1,'msg_ftp_not_connected');

                if(!$oFtp->ftp_login($ftp_info->ftp_user, $ftp_info->ftp_password)) {
                    $oFtp->ftp_quit();
                    return new Object(-1,'msg_ftp_invalid_auth_info');
                }

                if(!is_dir(_XE_PATH_.'files') && !$oFtp->ftp_mkdir($ftp_info->ftp_root_path.'files')) {
                    $oFtp->ftp_quit();
                    return new Object(-1,'msg_ftp_mkdir_fail');
                }

                if(!$oFtp->ftp_site("CHMOD 777 ".$ftp_info->ftp_root_path.'files')) {
                    $oFtp->ftp_quit();
                    return new Object(-1,'msg_ftp_chmod_fail');
                }

                if(!is_dir(_XE_PATH_.'files/config') && !$oFtp->ftp_mkdir($ftp_info->ftp_root_path.'files/config')) {
                    $oFtp->ftp_quit();
                    return new Object(-1,'msg_ftp_mkdir_fail');
                }

                if(!$oFtp->ftp_site("CHMOD 777 ".$ftp_info->ftp_root_path.'files/config')) {
                    $oFtp->ftp_quit();
                    return new Object(-1,'msg_ftp_chmod_fail');
                }

                $oFtp->ftp_quit();
            } 

            $config_file = Context::getFTPConfigFile();
            FileHandler::WriteFile($config_file, $buff);
        }

        function procInstallCheckFtp() {
            $ftp_info = Context::gets('ftp_user','ftp_password','ftp_port','sftp');
            $ftp_info->ftp_port = (int)$ftp_info->ftp_port;
            if(!$ftp_info->ftp_port) $ftp_info->ftp_port = 21;
            if(!$ftp_info->sftp) $ftp_info->sftp = 'N';

            if(!$ftp_info->ftp_user || !$ftp_info->ftp_password) return new Object(-1,'msg_safe_mode_ftp_needed');

            if($ftp_info->sftp == 'Y')
            {
                $connection = ssh2_connect('localhost', $ftp_info->ftp_port);
                if(!ssh2_auth_password($connection, $ftp_info->ftp_user, $ftp_info->ftp_password))
                {
                    return new Object(-1,'msg_ftp_invalid_auth_info');
                }
            }
            else
            {
                require_once(_XE_PATH_.'libs/ftp.class.php');
                $oFtp = new ftp();
                if(!$oFtp->ftp_connect('localhost', $ftp_info->ftp_port)) return new Object(-1,'msg_ftp_not_connected');

                if(!$oFtp->ftp_login($ftp_info->ftp_user, $ftp_info->ftp_password)) {
                    $oFtp->ftp_quit();
                    return new Object(-1,'msg_ftp_invalid_auth_info');
                }

                $oFtp->ftp_quit();
            }

            $this->setMessage('msg_ftp_connect_success');
        }

        /**
         * @brief 인스톨 환경을 체크하여 결과 return 
         **/
        function checkInstallEnv() {
            // 각 필요한 항목 체크
            $checklist = array();

            // 0. php 버전 체크 (5.2.2는 설치 불가)
            if(phpversion()=='5.2.2') $checklist['php_version'] = false;
            else $checklist['php_version'] = true;

            // 1. permission 체크
            if(is_writable('./')||is_writable('./files')) $checklist['permission'] = true;
            else $checklist['permission'] = false;

            // 2. xml_parser_create함수 유무 체크
            if(function_exists('xml_parser_create')) $checklist['xml'] = true;
            else $checklist['xml'] = false;

            // 3. ini_get(session.auto_start)==1 체크
            if(ini_get(session.auto_start)!=1) $checklist['session'] = true;
            else $checklist['session'] = false;

            // 4. iconv 체크
            if(function_exists('iconv')) $checklist['iconv'] = true;
            else $checklist['iconv'] = false;

            // 5. gd 체크 (imagecreatefromgif함수)
            if(function_exists('imagecreatefromgif')) $checklist['gd'] = true;
            else $checklist['gd'] = false;

            if(!$checklist['php_version'] || !$checklist['permission'] || !$checklist['xml'] || !$checklist['session']) $install_enable = false;
            else $install_enable = true;

            // 체크 결과를 Context에 저장
            Context::set('checklist', $checklist);
            Context::set('install_enable', $install_enable);

            return $install_enable;
        }

        /**
         * @brief files 및 하위 디렉토리 생성
         * DB 정보를 바탕으로 실제 install하기 전에 로컬 환경 설저d
         **/
        function makeDefaultDirectory() {
            $directory_list = array(
                    './files/config',
                    './files/cache/queries',
                    './files/cache/js_filter_compiled',
                    './files/cache/template_compiled',
                );

            foreach($directory_list as $dir) {
                FileHandler::makeDir($dir);
            }
        }

        /**
         * @brief 모든 모듈의 설치 
         *
         * 모든 module의 schemas 디렉토리를 확인하여 schema xml을 이용, 테이블 생성
         **/
        function installDownloadedModule() { 
            $oModuleModel = &getModel('module');

            // 각 모듈의 schemas/*.xml 파일을 모두 찾아서 table 생성
            $module_list = FileHandler::readDir('./modules/', NULL, false, true);
            foreach($module_list as $module_path) {
                // 모듈 이름을 구함
                $tmp_arr = explode('/',$module_path);
                $module = $tmp_arr[count($tmp_arr)-1];

                $xml_info = $oModuleModel->getModuleInfoXml($module);
                if(!$xml_info) continue;
                $modules[$xml_info->category][] = $module;
            }

            // module 모듈은 미리 설치
            $this->installModule('module','./modules/module');
            $oModule = &getClass('module');
            if($oModule->checkUpdate()) $oModule->moduleUpdate();

            // 모듈을 category에 의거 설치 순서를 정함
            $install_step = array('system','content','member');
            // 나머지 모든 모듈 설치
            foreach($install_step as $category) {
                if(count($modules[$category])) {
                    foreach($modules[$category] as $module) {
                        if($module == 'module') continue;
                        $this->installModule($module, sprintf('./modules/%s', $module));

                        $oModule = &getClass($module);
                        if($oModule->checkUpdate()) $oModule->moduleUpdate();
                    }
                    unset($modules[$category]);
                }
            }

            // 나머지 모든 모듈 설치
            if(count($modules)) {
                foreach($modules as $category => $module_list) {
                    if(count($module_list)) {
                        foreach($module_list as $module) {
                            if($module == 'module') continue;
                            $this->installModule($module, sprintf('./modules/%s', $module));

                            $oModule = &getClass($module);
							if($oModule && method_exists($oModule, 'checkUpdate') && method_exists($oModule, 'moduleUpdate'))
							{
								if($oModule->checkUpdate()) $oModule->moduleUpdate();
							}
                        }
                    }
                }
            }

            return new Object();
        }

        /**
         * @brief 개별 모듈의 설치
         **/
        function installModule($module, $module_path) {
            // db instance생성
            $oDB = &DB::getInstance();

            // 해당 모듈의 schemas 디렉토리를 검사하여 schema xml파일이 있으면 생성
            $schema_dir = sprintf('%s/schemas/', $module_path);
            $schema_files = FileHandler::readDir($schema_dir, NULL, false, true);

            $file_cnt = count($schema_files);
            for($i=0;$i<$file_cnt;$i++) {
                $file = trim($schema_files[$i]);
                if(!$file || substr($file,-4)!='.xml') continue;
                $output = $oDB->createTableByXmlFile($file);
            }

            // 테이블 설치후 module instance를 만들고 install() method를 실행
            unset($oModule);
            $oModule = &getClass($module);
            if(method_exists($oModule, 'moduleInstall')) $oModule->moduleInstall();

            return new Object();
        }

        /**
         * @brief config 파일을 생성
         * 모든 설정이 이상없이 끝난 후에 config파일 생성
         **/
        function makeConfigFile() {
            $config_file = Context::getConfigFile();
            //if(file_exists($config_file)) return;

            $db_info = Context::getDbInfo();
            if(!$db_info) return;

            $buff = '<?php if(!defined("__ZBXE__")) exit();'."\n";
            foreach($db_info as $key => $val) {
                $buff .= sprintf("\$db_info->%s = '%s';\n", $key, str_replace("'","\\'",$val));
            }
            $buff .= "?>";

            FileHandler::writeFile($config_file, $buff);

            if(@file_exists($config_file)) return true;
            return false;
        }

		function installByConfig($install_config_file){
			include $install_config_file;
			if(!is_array($auto_config)) return false;

			$auto_config['module'] = 'install';
			$auto_config['act'] = 'procInstall';

			$fstr = "<%s><![CDATA[%s]]></%s>\r\n";
			$fheader = "POST %s HTTP/1.1\r\nHost: %s\r\nContent-Type: application/xml\r\nContent-Length: %s\r\n\r\n%s\r\n";
			$body = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\r\n<methodCall>\r\n<params>\r\n";
			foreach($auto_config as $k => $v){
				if(!in_array($k,array('host','port','path'))) $body .= sprintf($fstr,$k,$v,$k);
			}
			$body .= "</params>\r\n</methodCall>";

			$header = sprintf($fheader,$auto_config['path'],$auto_config['host'],strlen($body),$body);
			$fp = @fsockopen($auto_config['host'], $auto_config['port'], $errno, $errstr, 5); 
			if($fp){
				fputs($fp, $header);
				while(!feof($fp)) {
					$line = trim(fgets($fp, 4096));
					if(preg_match("/^<error>/i",$line)){
						fclose($fp);
						return false;
					}
				}   
				fclose($fp);
			}
			return true;

		}
    }
?>
