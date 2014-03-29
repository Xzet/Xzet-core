<?php
    /**
     * @class  rssView
     * @author NHN (developers@xpressengine.com)
     * @brief  rss module의 view class
     *
     * Feed 문서 출력
     *
     **/

    class rssView extends rss {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 피드 출력
         * 직접 RSS를 출력하려고 할때에는 $oRssView->rss($document_list)를 통해서 결과값을 직접 지정 가능
         **/
        function rss($document_list = null, $rss_title = null, $add_description = null) {
            $oDocumentModel = &getModel('document');
            $oModuleModel = &getModel('module');
            $oModuleController = &getController('module');

            // 다른 모듈에서 method로 호출되는 것이 아니라면 현재 요청된 모듈을 대상으로 글과 정보를 구함
            if(!$document_list) {
                $site_module_info = Context::get('site_module_info');
                $site_srl = $site_module_info->site_srl;
                $mid = Context::get('mid'); ///< 대상 모듈 id, 없으면 전체로
                $start_date = (int)Context::get('start_date');
                $end_date = (int)Context::get('end_date');

                $module_srls = array();
                $rss_config = array();
                $total_config = '';
                $total_config = $oModuleModel->getModuleConfig('rss');

                // 하나의 mid가 지정되어 있으면 그 mid에 대한 것만 추출
                if($mid) {
                    $module_srl = $this->module_info->module_srl;
                    $config = $oModuleModel->getModulePartConfig('rss', $module_srl);
                    if($config->open_rss && $config->open_rss != 'N') {
                       $module_srls[] = $module_srl; 
                       $open_rss_config[$module_srl] = $config->open_rss;
                    }

                // mid 가 선택되어 있지 않으면 전체
                } else {
                    if($total_config->use_total_feed != 'N') {
                        $rss_config = $oModuleModel->getModulePartConfigs('rss', $site_srl);
                        if($rss_config) {
                            foreach($rss_config as $module_srl => $config) {
                                if($config && $config->open_rss != 'N' && $config->open_total_feed != 'T_N') {
                                    $module_srls[] = $module_srl;
                                    $open_rss_config[$module_srl] = $config->open_rss;
                                }
                            }
                        }
                    }
                }

                if(!count($module_srls) && !$add_description) return $this->dispError();

                if($module_srls) {
                    $args->module_srl = implode(',',$module_srls);
                    $module_list = $oModuleModel->getMidList($args);

                    $args->search_target = 'is_secret';
                    $args->search_keyword = 'N';
                    $args->page = (int)Context::get('page');
                    $args->list_count = 15;
                    if($total_config->feed_document_count) $args->list_count = $total_config->feed_document_count;
                    if(!$args->page || $args->page < 1) $args->page = 1;
                    if($start_date || $start_date != 0) $args->start_date = $start_date;
                    if($end_date || $end_date != 0) $args->end_date = $end_date;
                    if($start_date == 0) unset($start_date);
                    if($end_date == 0) unset($end_date);

                    $args->sort_index = 'list_order'; 
                    $args->order_type = 'asc';
                    $output = $oDocumentModel->getDocumentList($args);
                    $document_list = $output->data;

                    // 피드 제목 및 정보등을 추출 Context::getBrowserTitle 
                    if($mid) {
                        $info->title = Context::getBrowserTitle();
                        $oModuleController->replaceDefinedLangCode($info->title);

                        $info->title = str_replace('\'', '&apos;',$info->title);
                        if($config->feed_description) {
                            $info->description = str_replace('\'', '&apos;', htmlspecialchars($config->feed_description));
                        }
                        else {
                            $info->description = str_replace('\'', '&apos;', htmlspecialchars($this->module_info->description));
                        }
                        $info->link = getUrl('','mid',$mid);
                        $info->feed_copyright = str_replace('\'', '&apos;', htmlspecialchars($feed_config->feed_copyright));
                        if(!$info->feed_copyright) {
                            $info->feed_copyright = str_replace('\'', '&apos;', htmlspecialchars($total_config->feed_copyright));
                        }
                    }
                }
            }

            if(!$info->title) {
                if($rss_title) $info->title = $rss_title;
                else if($total_config->feed_title) $info->title = $total_config->feed_title;
                else {
                    $site_module_info = Context::get('site_module_info');
                    $info->title = $site_module_info->browser_title;
                }

                $oModuleController->replaceDefinedLangCode($info->title);
                $info->title = str_replace('\'', '&apos;', htmlspecialchars($info->title));
                $info->description = str_replace('\'', '&apos;', htmlspecialchars($total_config->feed_description));
                $info->link = Context::getRequestUri();
                $info->feed_copyright = str_replace('\'', '&apos;', htmlspecialchars($total_config->feed_copyright));
            }
            if($add_description) $info->description .= "\r\n".$add_description;

            if($total_config->image) $info->image = Context::getRequestUri().str_replace('\'', '&apos;', htmlspecialchars($total_config->image));
            switch (Context::get('format')) {
                case 'atom':
                    $info->date = date('Y-m-d\TH:i:sP');
                    if($mid) { $info->id = getUrl('','mid',$mid,'act','atom','page',Context::get('page'),'start_date',Context::get('start_date'),'end_date',Context::get('end_date')); }
                    else { $info->id = getUrl('','module','rss','act','atom','page',Context::get('page'),'start_date',Context::get('start_date'),'end_date',Context::get('end_date')); }
                    break;
                case 'rss1.0':
                    $info->date = date('Y-m-d\TH:i:sP');
                    break;
                default:
                    $info->date = date("D, d M Y H:i:s").' '.$GLOBALS['_time_zone'];
                    break;
            }

            if($_SERVER['HTTPS']=='on') $proctcl = 'https://';
            else $proctcl = 'http://';

            $temp_link = explode('/', $info->link);
            if($temp_link[0]=='' && $info->link) {
                $info->link = $proctcl.$_SERVER['HTTP_HOST'].$info->link;
            }

            $temp_id = explode('/', $info->id);
            if($temp_id[0]=='' && $info->id) {
                $info->id = $proctcl.$_SERVER['HTTP_HOST'].$info->id;
            }

            $info->language = Context::getLangType();

            // RSS 출력물에서 사용될 변수 세팅
            Context::set('info', $info);
            Context::set('feed_config', $config);
            Context::set('open_rss_config', $open_rss_config);
            Context::set('document_list', $document_list);

            // 결과 출력을 XMLRPC로 강제 지정
            Context::setResponseMethod("XMLRPC");

            // 결과물을 얻어와서 에디터 컴포넌트등의 전처리 기능을 수행시킴
            $path = $this->module_path.'tpl/';
            //if($args->start_date || $args->end_date) $file = 'xe_rss';
            //else $file = 'rss20';
            switch (Context::get('format')) {
                case 'xe':
                    $file = 'xe_rss';
                    break;
                case 'atom':
                    $file = 'atom10';
                    break;
                case 'rss1.0':
                    $file = 'rss10';
                    break;
                default:
                    $file = 'rss20';
                    break;
            }

            $oTemplate = new TemplateHandler();

            $content = $oTemplate->compile($path, $file);
            Context::set('content', $content);

            // 템플릿 파일 지정
            $this->setTemplatePath($path);
            $this->setTemplateFile('display');
        }

        /** @brief ATOM 출력 **/
        function atom() {
            Context::set('format', 'atom');
            $this->rss();
        }

        /**
         * @brief 에러 출력
         **/
        function dispError() {
            // 출력 메세지 작성
            $this->rss(null, null, Context::getLang('msg_rss_is_disabled') );
        }

        /**
         * @brief 서비스형 모듈의 추가 설정을 위한 부분
         * rss의 사용 형태에 대한 설정만 받음
         **/
        function triggerDispRssAdditionSetup(&$obj) {
            $current_module_srl = Context::get('module_srl');
            $current_module_srls = Context::get('module_srls');

            if(!$current_module_srl && !$current_module_srls) {
                // 선택된 모듈의 정보를 가져옴
                $current_module_info = Context::get('current_module_info');
                $current_module_srl = $current_module_info->module_srl;
                if(!$current_module_srl) return new Object();
            }

            // 선택된 모듈의 rss설정을 가져옴
            $oRssModel = &getModel('rss');
            $rss_config = $oRssModel->getRssModuleConfig($current_module_srl);
            Context::set('rss_config', $rss_config);

            // 템플릿 파일 지정
            $oTemplate = &TemplateHandler::getInstance();
            $tpl = $oTemplate->compile($this->module_path.'tpl', 'rss_module_config');
            $obj .= $tpl;

            return new Object();
        }
    }
?>
