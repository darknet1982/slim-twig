<?php
namespace Controller;

defined('_Sdef') or exit();

abstract class ABCDisplayController extends AController {
	
	protected function getMenu() {
		$pages = $this->model->getPages();
		
		return $this->app->view->fetch('menu.tpl.php',array(
														'pages'=>$pages,
														'app'=>$this->app
														));
		
	}
	
	protected function getSidebar() {
		$categories = $this->model->getCategories();
		$news = $this->model->getNews();
		
		return $this->app->view->fetch('sidebar.tpl.php',array(
													'categories'=>$categories,
													'news'=>$news,
													'app'=>$this->app,
													'uri'=>$this->uri
													));
		
	}
	
	protected function display() {
		$menu = $this->getMenu();
		$sidebar = $this->getSidebar();
		
		
		$this->app->render('index.tpl.php',array(
												
											'uri'=>$this->uri,
											'menu'=>$menu,
											'sidebar'=>$sidebar,
											'title'=>$this->title,
											'keywords'=>$this->keywords,
											'description'=>$this->description,
											'mainbar'=>$this->mainbar
										
												));
	}

}
	