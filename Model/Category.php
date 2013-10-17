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
 * Order
 *
 * @var string $order
 */
	public $order = 'Category.lft';

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
		'Tree' => array('parent' => 'parent_id'),
		'Galleries.Mediable'
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
			'foreignKey' => 'parent_id',
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
 */
    
/**
 * Constructor
 *
 * @return void
 */
    public function __construct($id = false, $table = null, $ds = null) {
		if(CakePlugin::loaded('Products')) {
			$this->actsAs[] = 'Products.Purchasable';
		}
		parent::__construct($id, $table, $ds);
		$this->order = $this->alias.'.lft';
	}

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
    public function beforeSave($options = array()) {
		$data = $this->cleanData($this->data);
        $this->Behaviors->attach('Galleries.Mediable');
        return parent::beforeSave($options);
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
					$Model = ClassRegistry::init(ZuhaInflector::pluginize($model).'.'.$model);
					// App::uses($model, ZuhaInflector::pluginize($model).'.Model');
					// $Model = new $model;
					$res = $Model->find('all', array(
						'contain' => array('Category'),
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


	public function categorized($data = null, $model) {
		$modelData = null;
		$ret = false;
		if(is_array($data[$model]['id'])) {
			foreach($data[$model]['id'] as $id) {
				$this->Categorized->deleteAll(
					array('Categorized.foreign_key' => $id, 'Categorized.model'=>$model),
					true
				);
			}
            if (!empty($data['Category']['id'])) {
            	// was this (not sure changing it didn't break something (changed to support BlogPost::add()) (and have since added an is_array check)
                // foreach($data['Category']['id'][0] as $catId) {
                if (is_array($data['Category']['id'])) {
	                foreach($data['Category']['id'] as $catId) {
	                    $modelData[] = array(
	                        'model' => $model,
	                        'foreign_key' => $id,
	                        'category_id' => $catId,
	                        );
	                }
                } else {
                    $modelData[] = array(
                        'model' => $model,
                        'foreign_key' => $id,
                        'category_id' => $data['Category']['id'],
                        );
                }
            }
		}
		if (!empty($modelData)) {
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
	


/**
 * List models method
 * 
 * Get all the models that actsAs Categorizable
 */
	public function listModels() {
		$models = App::objects($plugin . '.Model');
		
		$plugins = CakePlugin::loaded();
	    foreach ($plugins as $plugin) {
	    	// the else here was App::objects($pluginPath . '.Model')  // not totally sure the changing to just plugin, won't break something
			$models = !empty($models) ? array_merge($models, App::objects($plugin . '.Model')) : App::objects($plugin . '.Model');
	    }
    	sort($models);
	    foreach ($models as $model) {
			strpos($model, 'AppModel') || strpos($model, 'AppModel') === 0 ? null : $return[$model] = $model;
	    }
		foreach ($return as $key => $model) {
			$model = ZuhaInflector::pluginize($model) ? ZuhaInflector::pluginize($model).'.'.$model : $model;
			$Model = ClassRegistry::init($model);

			if (!is_array($Model->actsAs) || !array_search('Categories.Categorizable', $Model->actsAs)) {
				// remove models which aren't categorizable
				unset($return[$key]);
			}
		}
    	return $return;
	}

/**
 * Clean data
 * 
 */
 	public function cleanData($data) {
		if ($data[$this->alias]['parent_id'] == '') {
			$data[$this->alias]['parent_id'] = null;
		}
 		return $data;
 	}
    
}
