<?php
App::import(array(
	'type' => 'File', 
	'name' => 'Categories.CategoriesConfig', 
	'file' =>  '..' . DS . 'plugins'  . DS  . 'categories'  . DS  . 'config'. DS .'core.php'
));

class CategoriesAppController extends AppController {
	
	function beforeFilter() {
		parent::beforeFilter();		
		$Config = CategoriesConfig::getInstance();
		#sets display values
		if (!empty($Config->settings[$this->params['controller'].Inflector::camelize($this->params['action']).'View'])) {
			$this->set('settings', $Config->settings[$this->params['controller'].Inflector::camelize($this->params['action']).'View']);
		}
		if (!empty($Config->settings[$this->params['controller'].Inflector::camelize($this->params['action']).'Controller'])) {
			$this->settings = $Config->settings[$this->params['controller'].Inflector::camelize($this->params['action']).'Controller'];
		}
	}
	
}
?>