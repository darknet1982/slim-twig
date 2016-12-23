<?php
namespace Controller;

defined ('S_NICK') || exit('no rights!');

class AdminController extends ADisplayController {
	
	protected $page;
	
	public function execute($param = array()) {
		
        //var_dump ($_POST); echo '<br>';
       if (self::$app->getContainer()->request->isPost()){
           $post = self::$app->getContainer()->request->getParsedBody();
           if ($this->model->setFeedback($post)){
           return self::$app->getContainer()->response->withStatus(200)->withHeader('Location', $this->uri . 'contacts');
           }
       }
        
		
		return $this->display();
        
	}
	
	
	protected function display($tmpl = FALSE) {

		$this->title .= 'Contacts';
		$this->keywords = 'Контакты';
		$this->description = 'Контакты';
	
		$this->mainbar = self::$app->getContainer()->view->fetch('contacts.tpl.my',array(
												'app'=>$this->app,
												'uri'=>$this->uri
																));
		
		return parent::display();
	}
	
	
}