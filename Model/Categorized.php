<?php
/**
 * Copyright 2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Categorized model
 *
 * @package categories
 * @subpackage categories.models
 */
class Categorized extends CategoriesAppModel {

/**
 * Name
 *
 * @var string
 */
	public $name = 'Categorized';

/**
 * Table
 *
 * @var string
 */
	public $useTable = 'categorized';

/** 
 * Belongs To
 * 
 * @var array
 */
	public $belongsTo = array(
		'Category' => array(
			'className' => 'Categories.Category',
			'foreignKey' => 'category_id',			
			));
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Constructor
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}
	
	public function afterSave($created) {
		if (!empty($this->data['Categorized']['category_id'])) {
			# update the category record count
			try {
				$this->Category->recordCount($this->data['Categorized']['category_id']);
			} catch (Exception $e) {
				# for now continue, because I don't know what to do.
				# we don't need to stop, but there should be some thing. 
				# not sure what? 
			}
		}
		return true;
	}

}