<?php
namespace Controller;

defined ('S_NICK') || exit('no rights!');

class BlogController extends ADisplayController {
	
	protected $page;
	
	public function execute($param = array()) {   // обрабатывается параметр из строки запроса после home (числовое знначение)
		
		$page = $param['page'];

		
		$this->page = ($page && ($page >= 0)) ? $page : 1;
		return $this->display();
	}
	
	
	protected function display() {
        
        $items = is_numeric($this->page)
        ? $items = $this->model->getBlogList($this->page)
        : $items = $this->model->getBlogList(1, $this->page);


		$this->title .= 'Blog';
		$this->keywords = 'Blog';
		$this->description = 'Blog';
		
		//$this->mainbar = 'hello';
		$this->mainbar = self::$app->getContainer()->view->fetch('blog.tpl.my',array(
												'items'=>$items['items'],
												'navigation'=>$items['navigation'],
												'app'=>$this->app,
												'uri'=>$this->uri
																));
		
		return parent::display();
	}
	
	
}