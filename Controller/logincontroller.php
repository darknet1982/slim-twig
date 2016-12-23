<?php
namespace Controller;

defined ('S_NICK') || exit('no rights!');

class LoginController extends ADisplayController {
	

    protected function login() 
    {
        $post = self::$app->getContainer()->request->getParsedBody();
        if (!empty($post)){
            $login = $this->clear_str($post['name']);
            $pass = $this->clear_str($post['pass']);
        }
        if (empty($login) || empty($pass)){
            self::$app->getContainer()->flash->addMessage('Error', 'Заполните обязательные поля');   
            return self::$app->getContainer()->response->withStatus(200)->withHeader('Location', self::$app->getContainer()->router->pathFor('login'));
        }
        

        $result = $this->model->getUserLoginPass($login,$pass);
        
        if(!$result){
            self::$app->getContainer()->flash->addMessage('Error', 'Доступ запрещен'); 
            return self::$app->getContainer()->response->withStatus(200)->withHeader('Location', self::$app->getContainer()->router->pathFor('login'));
        }
        
        $sess = $this->hashPassword(microtime());
        
        if($this->model->userLogin($result[0]['id'],$sess)){
            $_SESSION['sess'] = $sess;
            self::$app->getContainer()->flash->addMessage('msg', 'Добро Пожаловать'); 
            return self::$app->getContainer()->response->withStatus(200)->withHeader('Location', self::$app->getContainer()->router->pathFor('home'));
        } else {
            self::$app->getContainer()->flash->addMessage('Error', 'Ошибка'); 
            return self::$app->getContainer()->response->withStatus(200)->withHeader('Location', self::$app->getContainer()->router->pathFor('login'));
        }
    }    


	
	public function execute() {
		
       if (self::$app->getContainer()->request->isPost()){
           if (self::$app->getContainer()->request->getParam('logout')){
                return $this->logout();
            } 
                return $this->login();
       }
		if(False) {
			$tmpl = 'logout';
		}
		else {
			$tmpl = 'login';
		}
		
		return $this->display($tmpl);
        }


	protected function display($tmpl) {

		$this->title .= 'Entrance';
		$this->keywords = 'Вход';
		$this->description = 'Вход';
	
		$this->mainbar = self::$app->getContainer()->view->fetch($tmpl.'.tpl.my',array(
                                                'loginerror' => $_SESSION['slimFlash']['Error'][0],
                                                'msg' => $_SESSION['slimFlash']['msg'][0],
												'app'=>$this->app,
												'uri'=>$this->uri
																));
		
		return parent::display();
	}
    protected function hashPassword($password)
    {
        $salt = md5(uniqid('PrE90#', true));
        $salt = substr(strtr(base64_encode($salt), '+', '.'), 0, 22);
        return crypt($password, '$2a$08$' . $salt);
    }
}
