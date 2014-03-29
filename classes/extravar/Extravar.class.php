<?php
    /**
     * @class ExtraVar
     * @author NHN (developers@xpressengine.com)
     * @brief 게시글, 회원등에서 사용하는 확장변수를 핸들링하는 클래스
     *
     **/
    class ExtraVar {

        var $module_srl = null;
        var $keys = null;

        /**
         * @brief constructor
         **/
        function &getInstance($module_srl) {
            return new ExtraVar($module_srl);
        }

        /**
         * @brief constructor
         **/
        function ExtraVar($module_srl) {
            $this->module_srl = $module_srl;
        }

        /**
         * @brief 확장변수 키를 등록
         * @param module_srl, idx, name, type, default, desc, is_required, search, value
         **/
        function setExtraVarKeys($extra_keys) {
            if(!is_array($extra_keys) || !count($extra_keys)) return;
            foreach($extra_keys as $key => $val) {
                $obj = null;
                $obj = new ExtraItem($val->module_srl, $val->idx, $val->name, $val->type, $val->default, $val->desc, $val->is_required, $val->search, $val->value,  $val->eid);
                $this->keys[$val->idx] = $obj;
            }
        }

        /**
         * @brief 확장변수 객체 배열 return
         **/
        function getExtraVars() {
            return $this->keys;
        }
    }

    /**
     * @class ExtraItem
     * @author NHN (developers@xpressengine.com)
     * @brief 확장변수의 개별 값
     **/
    class ExtraItem {
        var $module_srl = 0;
        var $idx = 0;
        var $name = 0;
        var $type = 'text';
        var $default = null;
        var $desc = '';
        var $is_required = 'N';
        var $search = 'N';
        var $value = null;
        var $eid = '';

        /**
         * @brief constructor
         **/
        function ExtraItem($module_srl, $idx, $name, $type = 'text', $default = null, $desc = '', $is_required = 'N', $search = 'N', $value = null, $eid = '') {
            if(!$idx) return;
            $this->module_srl = $module_srl;
            $this->idx = $idx;
            $this->name = $name;
            $this->type = $type;
            $this->default = $default;
            $this->desc = $desc;
            $this->is_required = $is_required;
            $this->search = $search;
            $this->value = $value;
            $this->eid = $eid;
        }

        /**
         * @brief 값 지정
         **/
        function setValue($value) {
            $this->value = $value;
        }

        /**
         * @brief type에 따라서 주어진 값을 변형하여 원형 값을 return
         **/
        function _getTypeValue($type, $value) {
            $value = trim($value);
            if(!isset($value)) return;
            switch($type) {
                case 'homepage' :
                        if($value && !preg_match('/^([a-z]+):\/\//i',$value)) $value = 'http://'.$value;
                        return htmlspecialchars($value);
                    break;
                case 'tel' :
                        if(is_array($value)) $values = $value;
                        elseif(strpos($value,'|@|')!==false) $values = explode('|@|', $value);
                        elseif(strpos($value,',')!==false) $values = explode(',', $value);
                        $values[0] = $values[0];
                        $values[1] = $values[1];
                        $values[2] = $values[2];
                        return $values;
                    break;
                    break;
                case 'checkbox' :
                case 'radio' :
                case 'select' :
                        if(is_array($value)) $values = $value;
                        elseif(strpos($value,'|@|')!==false) $values = explode('|@|', $value);
                        elseif(strpos($value,',')!==false) $values = explode(',', $value);
                        else $values = array($value);
                        for($i=0;$i<count($values);$i++) $values[$i] = htmlspecialchars($values[$i]);
                        return $values;
                    break;
                case 'kr_zip' :
                        if(is_array($value)) $values = $value;
                        elseif(strpos($value,'|@|')!==false) $values = explode('|@|', $value);
                        elseif(strpos($value,',')!==false) $values = explode(',', $value);
                        return $values;
                    break;
                //case 'date' :
                //case 'email_address' :
                //case 'text' :
                //case 'textarea' :
                default :
                        return htmlspecialchars($value);
                    break;
            }
        }

        /**
         * @brief 값을 return
         * 원형 값을 HTML 결과물로 return
         **/
        function getValueHTML() {
            $value = $this->_getTypeValue($this->type, $this->value);
            switch($this->type) {
                case 'homepage' :
                        return ($value)?(sprintf('<a href="%s" target="_blank">%s</a>', $value, strlen($value)>60?substr($value,0,40).'...'.substr($value,-10):$value)):"";
                case 'email_address' :
                        return ($value)?sprintf('<a href="mailto:%s">%s</a>', $value, $value):"";
                    break;
                case 'tel' :
                        return sprintf('%s - %s - %s', $value[0],$value[1],$value[2]);
                    break;
                case 'textarea' :
                        return nl2br($value);
                    break;
                case 'checkbox' :
                        if(is_array($value)) return implode(', ',$value);
                        else return $value;
                    break;
                case 'date' :
                        return zdate($value,"Y-m-d");
                    break;
                case 'select' :
                case 'radio' :
                        if(is_array($value)) return implode(', ',$value);
                        else return $value;
                    break;
                case 'kr_zip' :
                        if(is_array($value)) return implode(' ',$value);
                        else return $value;
                    break;
                // case 'text' :
                default :
                    return $value;
            }
        }

        /**
         * @brief type에 따른 form을 리턴
         **/
        function getFormHTML() {
			static $id_num = 1000;

            $type = $this->type;
            $name = $this->name;
            $value = $this->_getTypeValue($this->type, $this->value);
            $default = $this->_getTypeValue($this->type, $this->default);
            $column_name = 'extra_vars'.$this->idx;
			$tmp_id = $column_name.'-'.$id_num++;

            $buff = '';
            switch($type) {
                // 홈페이지 주소
                case 'homepage' :
                        $buff .= '<input type="text" name="'.$column_name.'" value="'.$value.'" class="homepage" />';
                    break;

                // Email 주소
                case 'email_address' :
                        $buff .= '<input type="text" name="'.$column_name.'" value="'.$value.'" class="email_address" />';
                    break;

                // 전화번호
                case 'tel' :
                        $buff .=
                            '<input type="text" name="'.$column_name.'" value="'.$value[0].'" size="4" class="tel" />'.
                            '<input type="text" name="'.$column_name.'" value="'.$value[1].'" size="4" class="tel" />'.
                            '<input type="text" name="'.$column_name.'" value="'.$value[2].'" size="4" class="tel" />';
                    break;

                // textarea
                case 'textarea' :
                        $buff .= '<textarea name="'.$column_name.'" class="textarea">'.$value.'</textarea>';
                    break;

                // 다중 선택
                case 'checkbox' :
                        $buff .= '<ul>';
                        foreach($default as $v) {
                            if($value && in_array($v, $value)) $checked = ' checked="checked"';
                            else $checked = '';

							// Temporary ID for labeling
							$tmp_id = $column_name.'-'.$id_num++;

                            $buff .='<li><input type="checkbox" name="'.$column_name.'" id="'.$tmp_id.'" value="'.htmlspecialchars($v).'" '.$checked.' /><label for="'.$tmp_id.'">'.$v.'</label></li>';
                        }
                        $buff .= '</ul>';
                    break;

                // 단일 선택
                case 'select' :
                        $buff .= '<select name="'.$column_name.'" class="select">';
                        foreach($default as $v) {
                            if($value && in_array($v,$value)) $selected = ' selected="selected"';
                            else $selected = '';
                            $buff .= '<option value="'.$v.'" '.$selected.'>'.$v.'</option>';
                        }
                        $buff .= '</select>';
                    break;

                // radio
                case 'radio' :
                        $buff .= '<ul>';
                        foreach($default as $v) {
                            if($value && in_array($v,$value)) $checked = ' checked="checked"';
                            else $checked = '';

							// Temporary ID for labeling
							$tmp_id = $column_name.'-'.$id_num++;

                            $buff .= '<li><input type="radio" name="'.$column_name.'" id="'.$tmp_id.'" '.$checked.' value="'.$v.'"  class="radio" /><label for="'.$tmp_id.'">'.$v.'</label></li>';
                        }
                        $buff .= '</ul>';
                    break;

                // 날짜 입력
                case 'date' :
                        // datepicker javascript plugin load
                        Context::loadJavascriptPlugin('ui.datepicker');

                        $buff .=
                            '<input type="hidden" name="'.$column_name.'" value="'.$value.'" />'.
                            '<input type="text" id="date_'.$column_name.'" value="'.zdate($value,'Y-m-d').'" readonly="readonly" class="date" />'."\n".
                            '<script type="text/javascript">'."\n".
                            '(function($){'."\n".
                            '    $(function(){'."\n".
                            '        var option = { changeMonth:true, changeYear:true, gotoCurrent: false,yearRange:\'-100:+10\', onSelect:function(){'."\n".
                            '            $(this).prev(\'input[type="hidden"]\').val(this.value.replace(/-/g,""))}'."\n".
                            '        };'."\n".
                            '        $.extend(option,$.datepicker.regional[\''.Context::getLangType().'\']);'."\n".
                            '        $("#date_'.$column_name.'").datepicker(option);'."\n".
                            '    });'."\n".
                            '})(jQuery);'."\n".
                            '</script>';
                    break;

                // 주소 입력
                case "kr_zip" :
                        // krzip address javascript plugin load
                        Context::loadJavascriptPlugin('ui.krzip');

                        $buff .=
                            '<div id="addr_searched_'.$column_name.'" style="display:'.($value[0]?'block':'none').';">'.
                                '<input type="text" readonly="readonly" name="'.$column_name.'" value="'.$value[0].'" class="address" />'.
                                '<a href="#" onclick="doShowKrZipSearch(this, \''.$column_name.'\'); return false;" class="button red"><span>'.Context::getLang('cmd_cancel').'</span></a>'.
                            '</div>'.

                            '<div id="addr_list_'.$column_name.'" style="display:none;">'.
                                '<select name="addr_list_'.$column_name.'"></select>'.
                                '<a href="#" onclick="doSelectKrZip(this, \''.$column_name.'\'); return false;" class="button blue"><span>'.Context::getLang('cmd_select').'</span></a>'.
                                '<a href="#" onclick="doHideKrZipList(this, \''.$column_name.'\'); return false;" class="button red"><span>'.Context::getLang('cmd_cancel').'</span></a>'.
                            '</div>'.

                            '<div id="addr_search_'.$column_name.'" style="display:'.($value[0]?'none':'block').'">'.
                                '<input type="text" name="addr_search_'.$column_name.'" class="address" value="" />'.
                                '<a href="#" onclick="doSearchKrZip(this, \''.$column_name.'\'); return false;" class="button green"><span>'.Context::getLang('cmd_search').'</span></a>'.
                            '</div>'.

                            '<input type="text" name="'.$column_name.'" value="'.htmlspecialchars($value[1]).'" class="address" />'.
                            '';
                    break;

                // 일반 text
                default :
                        $buff .=' <input type="text" name="'.$column_name.'" value="'.$value.'" class="text" />';
                    break;
            }
            if($this->desc) $buff .= '<p>'.$this->desc.'</p>';
            return $buff;
        }
    }
?>
