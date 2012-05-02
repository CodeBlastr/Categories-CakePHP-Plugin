<?php
App::uses('AppModel', 'Model');

class CategoriesAppModel extends AppModel {
	
	public function __construct($id = false, $table = null, $ds = null) {
		$this->_upgrade();
		parent::__construct($id, $table, $ds);
	}
	
	
/**
 * Upgrade
 * 
 * Upgrades the database to the latest version.
 *
 * @todo 	 Looks like this upgrade function and the other(s) need to be made into a plugin or core behavior
 */
	protected function _upgrade() {
		// automatic upgrade the categories table 5/2/2012
		if (defined('__SYSTEM_ZUHA_DB_VERSION') && __SYSTEM_ZUHA_DB_VERSION < 0.0191) {
			$db = ConnectionManager::getDataSource('default');
			$tables = $db->listSources();
			if (array_search('categorizeds', $tables)) {
				$this->uses = false;
				$this->useTable = false;
				$this->query('RENAME TABLE `categorizeds` TO `categorized`');
				header('Location: ' . $_SERVER['REQUEST_URI']); // refresh the page to establish new table name
				break;
			}
		}
	}
	
}