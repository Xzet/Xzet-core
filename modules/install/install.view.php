<?php
    /**
     * @class  installView
     * @author NHN (developers@xpressengine.com)
     * @brief  install module의 View class
     **/

    class installView extends install {

        var $install_enable = false;

        /**
         * @brief 초기화
         **/
        function init() {
            // template 경로를 지정
            $this->setTemplatePath($this->module_path.'tpl');

            // 설치가 되어 있으면 오류
            if(Context::isInstalled()) return $this->stop('msg_already_installed');

            // 컨트롤러 생성
            $oInstallController = &getController('install');
            $this->install_enable = $oInstallController->checkInstallEnv();

            // 설치 가능한 환경이라면 installController::makeDefaultDirectory() 실행
            if($this->install_enable) $oInstallController->makeDefaultDirectory();
        }

        /**
         * @brief license 메세지 노출
         **/
        function dispInstallIntroduce() {
			$install_config_file = FileHandler::getRealPath('./config/install.config.php');
			if(file_exists($install_config_file)){
				include $install_config_file;
				if(is_array($install_config)){
					foreach($install_config as $k => $v) Context::set($k,$v,true);
					unset($GLOBALS['__DB__']);
					$oInstallController = &getController('install');
					$oInstallController->procInstall();
					header("location: ./");
					exit;
				}
			}

			$this->setTemplateFile('introduce');
        }

        /**
         * @brief 설치 환경에 대한 메세지 보여줌
         **/
        function dispInstallCheckEnv() {
            $this->setTemplateFile('check_env');
        }


        /**
         * @brief DB 선택 화면
         **/
        function dispInstallSelectDB() {
            // 설치 불가능하다면 check_env를 출력
            if(!$this->install_enable) return $this->dispInstallCheckEnv();

            // ftp 정보 입력
            if(ini_get('safe_mode') && !Context::isFTPRegisted()) {
                $this->setTemplateFile('ftp');
            } else {
                $this->setTemplateFile('select_db');
            }
        }

        /**
         * @brief DB 정보/ 최고 관리자 정보 입력 화면을 보여줌
         **/
        function dispInstallForm() {
            // 설치 불가능하다면 check_env를 출력
            if(!$this->install_enable) return $this->dispInstallCheckEnv();

            // db_type이 지정되지 않았다면 다시 초기화면 출력
            if(!Context::get('db_type')) return $this->dispInstallSelectDB();

            Context::set('time_zone', $GLOBALS['time_zone']);

            // disp_db_info_form.html 파일 출력
            $tpl_filename = sprintf('form.%s', Context::get('db_type'));
            $this->setTemplateFile($tpl_filename);
        }

    }
?>
