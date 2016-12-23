<?php
namespace Controller;

defined ('S_NICK') || exit('no rights!');

class ServicesController extends ADisplayController {
	
	protected $page;
	
	public function execute($param = array()) {   // обрабатывается параметр из строки запроса после home (числовое знначение)
		
		$page = $param['page'];
		
		$this->page = $page;
		return $this->display();
	}
	
	
	protected function display() {

		$this->title .= 'Services';
		$this->keywords = 'Web Services';
		$this->description = 'Web Services';
		
		//$this->mainbar = 'hello';
		$this->mainbar = self::$app->getContainer()->view->fetch('services.tpl.my',array(
												/*'items'=>$items['items'],
												'navigation'=>$items['navigation'],*/
												'app'=>$this->app,
												'uri'=>$this->uri,
                                                'page'=>$this->page
																));
		
		return parent::display();
	}
	
	
}