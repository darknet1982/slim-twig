<?php
namespace Controller;

defined ('S_NICK') || exit('no rights!');

class IndexController extends ADisplayController {
	
	protected $page;
	
	public function execute($param = array()) {   // обрабатывается параметр из строки запроса после home (числовое знначение)
		
		$page = $param['page'];
		
		$this->page = ($page && ($page >= 0)) ? $page : 1;
		return $this->display();
	}
	
	
	protected function display() {

		//$items = $this->model->getPages();//($this->page);
        $items = $this->model->getItems($this->page);
        $row = array();
		foreach($items['items'] as $item) {
			$item['images'] = json_decode($item['images'])->img;
			$row[] = $item;
		}
		$items['items'] = $row;
        //var_dump($items['items']);

		$this->title .= 'HOME';
		$this->keywords = 'Главная';
		$this->description = 'Главная страница';
		
		//$this->mainbar = 'hello';
		$this->mainbar = self::$app->getContainer()->view->fetch('mainbar.tpl.my',array(
												'items'=>$items['items'],
												'navigation'=>$items['navigation'],
												'app'=>$this->app,
												'uri'=>$this->uri
																));
		
		return parent::display();
	}
	
	
}