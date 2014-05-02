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
 * @property Category Category
 */
App::uses('CategoriesAppModel', 'Categories.Model');
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
		if(CakePlugin::loaded('Products')) {
			$this->actsAs['Products.Purchasable'] = array('modelName' => 'Category', 'foreignKey' => 'category_id');
		}
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'foreign_key' => array(
				'required' => array('rule' => array('notEmpty'), 'required' => true, 'allowEmpty' => false, 'message' => __d('categories', 'Foreign key can not be empty'))),
			'category_id' => array(
				'required' => array('rule' => array('notEmpty'), 'required' => true, 'allowEmpty' => false, 'message' => __d('categories', 'Category id can not be empty'))),
			'model' => array(
				'required' => array('rule' => array('notEmpty'), 'required' => true, 'allowEmpty' => false, 'message' => __d('categories', 'Model field can not be empty')))
			);
	}




	public function afterSave($created, $options = array()) {
		if (!empty($this->data['Categorized']['category_id'])) {
			// update the category record count
			try {
				$this->Category->recordCount($this->data['Categorized']['category_id']);
			} catch (Exception $e) {
				// for now continue, because I don't know what to do.
				// we don't need to stop, but there should be some thing.
				// not sure what?
			}
		}
		return parent::afterSave($created, $options);

	}

}
