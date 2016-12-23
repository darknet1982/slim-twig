<?php 
namespace Model;

defined ('S_NICK') || exit('no rights!');

class Model {
	
	public $driver;
	
	public function __construct() {
		$this->driver = new \Model\Amodel;
	}
	
	public function getPages() {
		$sql = "SELECT `cat_id`,`cat_name_ru`,`cat_alias`
				FROM `categories`";
		
		if($this->driver instanceof AModel) {
			$result = $this->driver->query($sql);
		}
		if(!$result) {
			return FALSE;
		}
		
		return $result;
		
	}
    
    public function getSubPages() {
		$sql = "SELECT `sub_id`,`sub_name_ru`,`sub_alias`
				FROM `services`";
		
		if($this->driver instanceof AModel) {
			$result = $this->driver->query($sql);
		}
		if(!$result) {
			return FALSE;
		}
		
		return $result;
		
	}
	
	public function getItems($page,$alias = FALSE) {
		
		$where = $alias ? "`content`.`id_cat` = (SELECT id FROM `categories` WHERE `alias` = '".$alias."')" : FALSE;
		
		/*$sql = "SELECT `content`.`id`,`title`,`introtext`,`images`,`categories`.`name` AS `category`,
        `categories`.`alias` AS `alias_cat`,`content`.`alias`,`date` FROM `content` 
        LEFT JOIN `categories` ON `categories`.`id` = `content`.`id_cat`
		WHERE `content`.`id_cat` = (SELECT id FROM `categories` WHERE `alias` = '".$alias."')	
		";*/
		
		$pager = new \Libraries\Pager(
									$page,
									"`content`.`id`,`title`,`introtext`,`images`, `content`.`alias`,`date`",
									"`content`",
									$where,
									FALSE,
									QUANTITY,
									QUANTITY_LINKS,
									$this->driver
									);							
		$result = array();
		$result['items'] = $pager->get_posts();
		$result['navigation'] = $pager->get_navigation();
									
		return $result;
	}
    
    public function getBlogList($page,$alias = FALSE) {
		
		$where = $alias ? "`alias` = '".$alias."'" : FALSE;
		
		/*$sql = "SELECT `content`.`id`,`title`,`introtext`,`images`,`categories`.`name` AS `category`,
        `categories`.`alias` AS `alias_cat`,`content`.`alias`,`date` FROM `content` 
        LEFT JOIN `categories` ON `categories`.`id` = `content`.`id_cat`
		WHERE `content`.`id_cat` = (SELECT id FROM `categories` WHERE `alias` = '".$alias."')	
		";*/
		
		$pager = new \Libraries\Pager(
									$page,
									"`id`,`anons`,`title`,`text`,`alias`,`date`",
									"`news`",
									$where,
									FALSE,
									QUANTITY,
									QUANTITY_LINKS,
									$this->driver
									);							
		$result = array();
		$result['items'] = $pager->get_posts();
		$result['navigation'] = $pager->get_navigation();
									
		return $result;
	}
    
	
	public function getCategories() {
		$sql = "SELECT `id`,`name`,`alias`
				FROM `pages`";
		
		if($this->driver instanceof AModel) {
			$result = $this->driver->query($sql);
		}
		if(!$result) {
			return FALSE;
		}
		
		return $result;
		
	}
	
	public function getNews() {
		$sql = "SELECT `id`,`title`,`alias`,`anons`,`date`
				FROM `news` ORDER BY rand() LIMIT " . NEWS_QUANTITY;
		
		if($this->driver instanceof AModel) {
			$result = $this->driver->query($sql);
		}
		if(!$result) {
			return FALSE;
		}
		
		return $result;
		
	}
    
    public function setFeedback($post) {
        if (!empty($post)){
            $sql = "INSERT INTO `feedback` (`name`, `email`, `subject`, `text`) 
            VALUES ('" . $post['name'] . "', '" . $post['email'] . "', '" . $post['subj'] . "', '" . $post['text'] . "')";
        }
		if($this->driver instanceof AModel) {
			$result = $this->driver->query($sql, 'insert');
            //var_dump ($sql);
		}
		if(!$result) {
			return FALSE;
		}
        return TRUE;
	}
	
	public function getUserLoginPass($login,$pass) {
		$sql = "SELECT `id`, `username`, `password`, `role`, `sess` FROM `users` WHERE `username` = '%s'"; //AND `password` = '%s'
		//";

		$sql = sprintf($sql, $this->driver->clear_db($login));


		if($this->driver instanceof AModel) {
			$result = $this->driver->query($sql);	
		}
	
		if(!$result || (count($result) < 1)) {
			return FALSE;
		}
		if (crypt($pass, $result[0]['password']) === $result[0]['password']){
            return $result;
        }
		return FALSE;
	}
	
	public function userLogin($id,$sess) {
		$sql_update = "UPDATE `users`  SET `sess` = '$sess' 
						WHERE `id` = '%d'
						";
		$sql_update = sprintf($sql_update, (int)$id);
		
		if($this->driver instanceof AModel) {

			return $this->driver->query($sql_update,'update');	
		}
		return FALSE;				
	}
	
}