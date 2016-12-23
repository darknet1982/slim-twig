<?php
namespace Controller;
defined ('S_NICK') || exit('no rights!');
//use \Psr\Http\Message\ServerRequestInterface as Request;
//use \Psr\Http\Message\ResponseInterface as Response;


abstract class AController  {
	protected static $app;
	protected $model;
	public $uri;
	protected $title;
	protected $response;
	protected static $instance;
	
	public function __construct() {
        
		

		$this->uri = $this->getUri();
		
		$this->model = $this->getModel();
		
		$this->title = 'Slim-TWIG | ';
        
        $this->response = new \Slim\Http\Response;
        

	}
	public static function getSlimInstance () {
        if (self::$app instanceof \Slim\App){
            return self::$app;
        } else {
            self::$app = new \Slim\App(["settings" => [
                                                        'displayErrorDetails' => TRUE,
                                                        //'routerCacheFile' => 'cache.txt',
                                                        'determineRouteBeforeAppMiddleware' => true,
                                                        'db' => [
                                                            'host' => 'localhost',
                                                            'user' => 'root',
                                                            'pass' => '',
                                                            'dbname' => 'test.cv_base'
                                                            
            ]]]);
        }
        return self::$app;
    }
	
	public static function getInstance($prefix) {

		$class = '\Controller\\'.ucfirst($prefix).'Controller';
		
		if(self::$instance instanceof $class) {
			return self::$instance;
		}
		
		if(class_exists($class)) {
			self::$instance = new $class;
		}
		else {
			throw new \Exception('Class not found - '.$class);
		}
		
		return self::$instance;
	}
	
	
	//
	public function execute($param = array()) {
		return TRUE;
	}
	
	protected function getUri() {

		$env = self::$app->getContainer()->get('environment');//$_SERVER
		
		if(isset($env["REQUEST_SCHEME"])) {
			$https = $env["REQUEST_SCHEME"].'://'; /// http:// https://
		}
		
		if(!empty($env['HTTP_HOST'])) {
			$theURI = $https.$env['HTTP_HOST'];
		}
		/*if(!empty($env["REQUEST_URI"])) {
			$theURI .= $env["REQUEST_URI"];
		}*/
		
		$theURI .= '/';
		
		$theURI = str_replace(array("'",'"','<','>'),array("%27", "%22", "%3C", "%3E"),$theURI);
		
		return $theURI;
	}
	
	protected function getModel() {
		return new \Model\Model();
	}
	
	protected function clear_str($var) {
		return htmlspecialchars(strip_tags(trim($var)));
	}
	
	//abstract protected function getMenu();
	//abstract protected function getSidebar();
	
	//abstract protected function display();
	
	
	
}