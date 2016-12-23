<?php
namespace Controller;

defined ('S_NICK') || exit('no rights!');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class ADisplayController extends AController {
	
	protected function getMenu() {
		$pages = $this->model->getPages();
        $subPages = $this->model->getSubPages();

		
		return self::$app->getContainer()->view->fetch('menubar.tpl.my',array(
														'pages'=>$pages, 
                                                        'subpages'=>$subPages,
														//'app'=>$this->app,
                                                        'uri'=>$this->uri
														));
		
	}
	
	protected function getSidebar() {
		//$categories = $this->model->getCategories();
		$news = $this->model->getNews();
		
		return self::$app->getContainer()->view->fetch('sidebar.tpl.my',array(
													//'categories'=>$categories,
													'news'=>$news,
													'app'=>$this->app,
													'uri'=>$this->uri
													));
	}
	
	protected function display() {
		$menu = $this->getMenu();
        
		$sidebar = $this->getSidebar();
	
		
		return self::$app->getContainer()->view->render($this->response, 'index1.tpl.my',array(
												
											'uri'=>$this->uri,//+
											'menu'=>$menu,//+
											'sidebar'=>$sidebar,//+
											'title'=>$this->title,//+
											'keywords'=>$this->keywords,
											'description'=>$this->description,
											'mainbar'=>$this->mainbar
												));
	}

}
	