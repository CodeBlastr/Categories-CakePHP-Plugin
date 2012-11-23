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

App::uses('CategoriesAppModel', 'Categories.Model');
/**
 * Category model
 *
 * @package categories
 * @subpackage categories.models
 */
class Category extends CategoriesAppModel {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Category';

/**
 * Validate
 * 
 * @var array $validate
 */
	public $validate = array(
		'model' => array('notempty'),
		);

/**
 * Behaviors
 *
 * @var array $actsAs
 */
	public $actsAs = array(
		'Tree' => array('parent' => 'parent_id')
		);

/**
 * belongsTo associations
 *
 * @var array $belongsTo
 */
	public $belongsTo = array(
		'ParentCategory' => array('className' => 'Categories.Category',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
		);

	/* CakeDC Version
	public $belongsTo = array(
		'ParentCategory' => array('className' => 'Categories.Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''));*/

/**
 * hasMany associations
 *
 * @var array $hasMany
 */
	public $hasMany = array(
		'ChildCategory' => array(
			'className' => 'Categories.Category',
			'foreignKey' => 'id',
			),
		'CategoryOption' => array(
			'className' => 'Categories.CategoryOption',
			'foreignKey' => 'category_id',
			'dependent' => true,
			),
		'Categorized' => array(
			'className' => 'Categories.Categorized',
			'foreignKey' => 'category_id',
			'dependent' => false,
			),
		);



/**
 * hasOne associations
 *
 * @var array $hasOne
 */
	public $hasOne = array(
		'Gallery' => array(
			'className' => 'Galleries.Gallery',
			'foreignKey' => 'foreign_key',
			'dependent' => false,
			'conditions' => array('Gallery.model' => 'Category'),
			'fields' => '',
			'order' => ''
		),
	);

/**
 * Before find callback
 */
	function beforeFind($queryData) {
		$this->order = array("{$this->alias}.name", "{$this->alias}.lft");
		return $queryData;
	}
    
/**
 * Before save callback
 * 
 * @param type $options
 * @return boolean
 */
    public function beforeSave($options) {
        $this->Behaviors->attach('Galleries.Mediable');
        return true;
    }


/**
 * Adds a new Category or CategoryOption.
 *
 * @param array post data, should be Contoller->data
 * @return array
 */
	public function add($data = null) {
		if (!empty($data['Category']['type']) && $data['Category']['type'] != 'Category') {
			$type = $data['Category']['type'];
			if ( $type == 'Attribute Group' || $type == 'Option Group') {
				$data['Category']['category_id'] = $data['Category']['parent_id'];
				$data['Category']['parent_id'] = null;
			} else {
				$data['Category']['category_id'] = $this->CategoryOption->field('CategoryOption.category_id', array('CategoryOption.id' => $data['Category']['parent_id']));
			}

			$this->CategoryOption->create();
			$result = $this->CategoryOption->save($data['Category']);
		} else {
			$this->create();
			$result = $this->save($data);
		}

		if ($result !== false) {
			$this->data = array_merge($data, $result);
			return true;
		} else {
			throw new Exception(__d('categories', 'Could not save the category.'));
		}
		return true;
	}
	
	
/**
 * Edits an existing Category.
 *
 * @param string $id, category id
 * @param string $userId, user id
 * @param array $data, controller post data usually $this->request->data
 * @return mixed True on successfully save else post data as array
 * @throws Exception If the element does not exists
 */
	public function edit($id = null, $data = null) {
		$conditions = array("{$this->alias}.{$this->primaryKey}" => $id);

		$category = $this->find('first', array(
			'contain' => array('ParentCategory'),
			'conditions' => $conditions));

		if (empty($category)) {
			throw new Exception(__d('categories', 'Invalid Category', true));
		}
		$this->set($category);

		if (!empty($data)) {
			$this->set($data);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $data;
			}
		} else {
			return $category;
		}
	}


	public function view($slug = null, $params = null) {
		// if models is empty that means nothing falls in this category
		$models = $this->Categorized->find('all', array(
			'order' => 'Categorized.model',
			'conditions' => array(
				'Categorized.category_id' => $slug),
		));
		$category = $this->find('first', array(
			#'contain' => array(
			#	'ParentCategory'
			#	),
			'conditions' => array(
				'or' => array(
					'Category.id' => $slug,
					'Category.slug' => $slug,
					),
				),
			$params,
			));

		if (empty($category)) {
			throw new Exception(__d('categories', 'Invalid Category'));
		} else {
			$temp = '';
			$associated = null;
			foreach($models as $mod) {
				$mod = $mod['Categorized'];
				if ($temp != $mod['model']) {
					$temp = $mod['model'];
				}
				$associated[$temp][] =  $mod['foreign_key'];
			}
			if (!empty($associated)) {
				foreach($associated as $model => $records) {
					App::uses($model, ZuhaInflector::pluginize($model).'.Model');
					$Model = new $model;
					$res = $Model->find('all', array(
						'conditions' => array(
							$model.'.id' => $records
							),
						));
					$category['Associated'][$model] = $res;
					//$category['Associated'][] = $model;
				} // end associated loop
			} // end associated !empty check
		} // end category empty check
		return $category;
	}

/**
 * Validates the deletion
 *
 * @param string $id, category id
 * @param array $data, controller post data usually $this->request->data
 * @return boolean True on success
 * @throws Exception If the element does not exists
 */
	public function validateAndDelete($id = null, $data = array()) {
		$category = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id,
				)));

		if (empty($category)) {
			throw new Exception(__d('categories', 'Invalid Category'));
		}

		$this->request->data['category'] = $category;
		if (!empty($data)) {
			$data['Category']['id'] = $id;
			$tmp = $this->validate;
			$this->validate = array(
				'id' => array('rule' => 'notEmpty'),
				'confirm' => array('rule' => '[1]'));

			$this->set($data);
			if ($this->validates()) {
				if ($this->delete($data['Category']['id'])) {
					return true;
				}
			}
			$this->validate = $tmp;
			throw new Exception(__d('categories', 'You need to confirm to delete this Category', true));
		}
	}

	public function getTypes() {
		return array(
			'Category' => 'Category',
			'Attribute Group' => '--Attribute Group',
		 	'Attribute Type' => '-- --Attribute Type',
			//'Option Group' => '--Option Group',
			//'Option Type' => '-- --Option Type'
			);
	}


	public function categorized($data = null, $model) {
		$modelData = null;
		$ret = false;
		foreach($data[$model]['id'] as $id) {
			$this->Categorized->deleteAll(
				array('Categorized.foreign_key'=>$id, 'Categorized.model'=>$model),
				true
			);
			foreach($data['Category']['id'] as $catId) {
				$modelData[] = array(
					'model' => $model,
					'foreign_key' => $id,
					'category_id' => $catId,
					);
			}
		}
		if (!empty($data)) {
			$this->Categorized->saveAll($modelData);
			$ret = true;
		}
		return $ret;
	}

	public function recordCount($categoryId) {
		$count = $this->Categorized->find('count', array('conditions' => array('Categorized.category_id' => $categoryId)));
		$data['Category']['id'] = $categoryId;
		$data['Category']['record_count'] = $count;
		if ($this->save($data)) {
			return true;
		} else {
			throw new Exception(__d('categories', 'Categor record count update failed.'));
		}
	}


	public function treeCategoryOptions($type = 'threaded', $options) {
		$options['fields'] = array('id', 'parent_id', 'name');
		/*$options['contain'] = array(
			'CategoryOption' => array(
				'conditions' => array(
					'CategoryOption.parent_id' => null,
					),
				'fields' => array(
					'name',
					'category_id',
					'id',
					),
				)
			);*/
		$categories = $this->find('threaded', $options);

		$categories = $this->_addCategoryOptionToChildren($categories);

		return $categories;
	}


/**
 * @todo		Make this truly recursive.  Its hard coded to two levels deep right now.
 */
	private function _addCategoryOptionToChildren($categories, $recursed = false) {
		$i=0;
		foreach ($categories as $category) {
			$oldChildren = $category['children'];
			$n=0;
			foreach ($oldChildren as $oldChild) {
				$attributeGroups = $this->CategoryOption->find('all', array(
					'conditions' => array(
						'CategoryOption.category_id' => $oldChild['Category']['id'],
						'CategoryOption.type' => 'Attribute Group',
						),
					'contain' => array(
						'children',
						),
					));
				$attributeGroups = $this->_reformatAttributeGroups($attributeGroups);
				$categories[$i]['children'][$n] = array_merge($oldChild, array('children' => $attributeGroups));
				$n++;
			}
			unset($attributeGroups);
			$attributeGroups = $this->CategoryOption->find('all', array(
				'conditions' => array(
					'CategoryOption.category_id' => $category['Category']['id'],
					'CategoryOption.type' => 'Attribute Group',
					),
				'contain' => array(
					'children',
					),
				));
			$attributeGroups = $this->_reformatAttributeGroups($attributeGroups);
			foreach ($attributeGroups as $attributeGroup) {
				array_push($categories[$i]['children'], $attributeGroup);
			}
			$i++;
		}

		return $categories;
	}


	protected function _reformatAttributeGroups($attributeGroups) {
		$a=0;
		foreach ($attributeGroups as $attributeGroup) {
			$b=0;
			foreach ($attributeGroup['children'] as $type) {
				unset($attributeGroups[$a]['children'][$b]);
				$attributeGroups[$a]['children'][$b]['CategoryOption'] = $type;
				$b++;
			}
			$a++;
		}
		return $attributeGroups;
	}

}
