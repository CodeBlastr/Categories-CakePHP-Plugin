<?php
App::uses('CategoriesAppModel', 'Categories.Model');
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
 * CategoryOption model
 *
 * @package categories
 * @subpackage categories.models
 */
class CategoryOption extends CategoriesAppModel {

/**
 * Name
 *
 * @var string
 */
	public $name = 'CategoryOption';

/**
 * ActsAs
 *
 * @var array
 */
	public $actsAs = array('Tree');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Table
 *
 * @var string
 */
	public $belongsTo = array(
		'Category' => array(
			'className' => 'Categories.Category',
			'foreignKey' => 'category_id',
			),
		);

/**
 * hasMany associations
 *
 * @var array $hasMany
 */
	public $hasMany = array(
		'CategorizedOption' => array(
			'className' => 'Categories.CategorizedOption',
			'foreignKey' => 'category_option_id',
			'dependent' => false
			),
		'children' => array(
			'className' => 'Categories.CategoryOption',
			'foreignKey' => 'parent_id',
			'dependent' => true,
			'finderQuery' => 'SELECT * FROM `category_options` AS `children` WHERE `children`.`parent_id` = ({$__cakeID__$})',
			),
		);

	public function __construct($id = false, $table = null, $ds = null) {
    	parent::__construct($id, $table, $ds);
 		$this->order = $this->alias . '.category_id';
    }

/**
 * categorized option
 *
 * @todo Add some comments to this.
 */
	public function categorized_option($data = null, $model) { // there was $userId here as 3rd parameter, but it's not used below..
		$ret = false;
		$catOpt = array();
		$records = array();
		foreach($data['CategoryOption'] as $key => $val) {
			if(isset($val) && !empty($val)) {
				if (is_array($val)) {
					$catOpt = array_merge($catOpt, $val);
				} else {
					$catOpt[] =  $val;
				}
			}
		}

		foreach($data as $key => $total) {
			/// this is done to support multiple record savings
			if ($key != $model)
				continue;
			$id = $total['id'];
			$this->CategorizedOption->deleteAll(
				array('CategorizedOption.foreign_key'=>$id, 'CategorizedOption.model'=>$model),
				true
			);
			foreach($catOpt as $key => $cat_opt_id) {
				$records[] = array('model'=>$model,
					'foreign_key' => $id,
					'category_option_id' => $cat_opt_id,);
			}
		}
        #debug($records);die();
		if ($this->CategorizedOption->saveAll($records)) {
			$ret = true;
		}
		return $ret;
	}

}