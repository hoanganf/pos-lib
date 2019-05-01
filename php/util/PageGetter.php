<?php
	abstract class PageGetter extends Login{
		public function get($pageId){
			$loginResult=null;
			if(isset($_COOKIE['pos_access_token'])){
				$request=new Data();
				$request->access_token=$_COOKIE['pos_access_token'];
				$loginResult=json_decode($this->login($request));
				if(!$loginResult->status){
					$this->redirect(LOGIN_DIR.'?from='.$_SERVER['REQUEST_URI']);
					return;
				}
			}else{
				$this->redirect(LOGIN_DIR.'?from='.$_SERVER['REQUEST_URI']);
				return;
			}
      $pageResource=new Data();
			$pageResource->requester=$loginResult->user_name;
			$pageResource->role=$loginResult->role;
      //TODO get restaurant name from database
      $pageResource->pageTitle='Restaurant';
      $this->buildHtml($pageId,$pageResource);
		}
		public abstract function buildHtml($pageId,$pageResource);

		public function redirect($url){
			header('Location: '.$url);
		}
	}
?>
