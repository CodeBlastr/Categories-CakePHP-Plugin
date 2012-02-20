<?php
/**
 * Copyright 2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.categories
 * @since         Zuha(tm) v 0.0.1
 * @copyright Copyright 2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::import('Model', 'Categories.Category'); 

/**
 * Categories controller
 *
 * @package categories
 * @subpackage categories.controllers
 */
class CategoriesController extends CategoriesAppController {
	
	public $allowedActions = array('requestForItems');
	public $uses = 'Categories.Category';
	public $paginate = array();

/**
 * Name
 *
 * @var string
 */
	public $name = 'Categories';

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html', 'Form');

/**
 * beforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view', 'index');
		//debug($this->modelClass);
		$this->set('modelName', $this->modelClass); 
	}

/**
 * Index method.
 * 
 */
	public function index() {
		$this->Category->recursive = 0;
		$this->set('categories', $this->paginate()); 
	}

/**
 * View for category.
 *
 * @param string $slug, category slug 
 */
	public function view($slug = null) {
		try {
			# this is put here specifically for the CatalogItems category, so if you change it
			# make sure that /categories/categories/view/X  (where X = a catalog item related category) looks good still.
			$category = $this->Category->view($slug);  // equals the category, and contains related items grouped by model
			$this->paginate['conditions'] = array('ChildCategory.parent_id' => $category['Category']['id']); 
			$this->paginate['fields'] = array('id', 'name');
			$childCategories = $this->paginate('ChildCategory');
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('category', 'childCategories')); 
	}
	

/**
 * Add for category.
 * 
 */
	public function add($categoryId = null) {
		if (!empty($this->request->params['named']['parent']) && empty($this->request->params['named']['model'])) :
			$parent = $this->Category->find('first', array(
				'conditions' => array(
					'Category.id' => $this->request->params['named']['parent']
					)
				)); 
			if (!empty($parent['Category']['model'])) : 
				$this->redirect(array('action' => 'add', 'model' => $parent['Category']['model'], 'parent' => $parent['Category']['id']));
			endif;
		endif;
		
		if(!empty($this->request->data)) {
			try {
				$result = $this->Category->add($this->request->data);
				if ($result === true) {
					if (!empty($this->request->data['Category']['parent_id']) && empty($this->request->data['Category']['type'])) {
						# if there was a parent_id then we can assign categories to items
						$this->Session->setFlash(__d('categories', 'Category Saved.  You can assign categories here.', true));
						$this->redirect(array('action' => 'categorized', 'type' => $this->request->data['Category']['model']));
					} else {
						$this->Session->setFlash(__d('categories', 'Category Saved', true));
						$this->redirect(array('action' => 'tree', 'model' => $this->request->data['Category']['model']));
					} 
				}
			} catch (Exception $e) {
				$this->request->data['Category']['model'] = !empty($this->request->data['Category']['model']) ? $this->request->data['Category']['model'] : null;
				$this->Session->setFlash($e->getMessage());
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->redirect(array('action' => 'index'));
			}
		} // end data check
		
		if (!empty($this->request->data) && !empty($categoryId)) {
			$this->request->data['Category']['category_id'] = $categoryId;
		}
		
		#_viewVars
		$models = $this->Category->listModels();
		$parents = $this->Category->generateTreeList();		
		$parentId = !empty($this->request->params['named']['parent']) ? $this->request->params['named']['parent'] : null;
		
		if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'Attribute Type') {
			$options = $this->Category->CategoryOption->find('threaded');
			$parentOptions = Set::combine($options, '{n}.CategoryOption.id', '{n}.CategoryOption.name');
			if (!empty($parentOptions) && !empty($parentOptions[$parentId])) {
				$parentOption = $parentOptions[$parentId];
			}
			if (!empty($parentOption)) {
				$parentCategories = Set::combine($options, '{n}.CategoryOption.name', '{n}.CategoryOption.category_id');
				if (!empty($parentCategories[$parentOption])) {
					$optionParentId = $parentCategories[$parentOption];
				}
			}
		}
		
		$parent = !empty($optionParentId) && !empty($parents[$optionParentId]) ? $parents[$optionParentId] : ''; // Shirts
		$parent = !empty($parents[$parentId]) ? $parents[$parentId] : $parent; // Shirts
		$parent = !empty($parentOption) ? Inflector::singularize($parent) . ' ' . $parentOption : $parent; // Shirt Sizes
		// shirt sizes
		$type = !empty($this->request->params['named']['type']) ? $this->request->params['named']['type'] : 'Category';
		$model = !empty($this->request->params['named']['model']) ? $this->request->params['named']['model'] : '';
		$types = $this->Category->getTypes();
		$pageTitleForLayout = !empty($parent) ? __('Add %s %s to %s', $model, $type, $parent) : __('Add %s %s', $model, $type);
		
		$this->request->data['Category']['model'] = $model;
		$this->request->data['Category']['type'] = $type;
		$this->request->data['Category']['parent_id'] = $parentId;
		$this->set('page_title_for_layout', $pageTitleForLayout);
		$this->set(compact('models', 'parents', 'types', 'model', 'parent', 'type', 'parentId'));
	}

/**
 * Edit for category.
 *
 * @param string $id, category id 
 */
	public function edit($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid order coupon'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			try {
				$result = $this->Category->edit($id, $this->request->data);
				if ($result === true) {
					$this->Session->setFlash(__d('categories', 'Category saved', true));
					$this->redirect(array('action' => 'tree'));
					
				} else {
					$this->request->data = $result;
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->redirect('/');
			}
		} else {
			$this->request->data = $this->Category->read(null, $id);
			$parents = $this->Category->generateTreeList();
			$options = $this->Category->CategoryOption->find('all', array(
				'conditions' => array(
					'CategoryOption.category_id' => $id, 
					'CategoryOption.type' => 'Attribute Group'
					)
				));
			$this->set(compact('parents', 'options'));
		}
 
	}

/**
 * Delete for category.
 *
 * @param string $id, category id 
 */
	public function delete($id = null) {
		$this->__delete('Category', $id);
	} 

/**
 * Tree method
 *
 * @param void
 * @return void
 */
	public function tree() {
		$this->helpers[] = 'Utils.Tree';
		$model = !empty($this->request->params['named']['model']) ? $this->request->params['named']['model'] : null;
		if (!empty($model)) {
			$params['conditions']['Category.model'] = $model;
		} else {
			$params = null;
		}
		$categories = $this->Category->treeCategoryOptions('threaded', $params);
		$this->set(compact('categories', 'model'));		
	}

	

/**
 * This function sets the variables when picking a category for a model/foreign_key combo
 *
 */
	public function choose_category($categoryId = null) {
		App::Import('Model', 'Catalogs.Catalog');
		$catalog = new Catalog();
		if (!empty($this->request->params['named']['catalog'])) {
			$this->set('catalogIdUrl', $this->request->params['named']['catalog']);
		}
		else if (!empty($this->request->data)) {
			$catalog_id = $this->request->data['CatalogItem']['catalog_id'];
			$this->set('catalogs', $catalog->find('list', array('conditions'=>array('Catalog.id'=>$catalog_id))));
		} else {
			$this->set('catalogs', $catalog->Catalog->find('list'));
		}
	}
	
	
/**
 * get_children()
 * Get children of parentId if parent id is category id else if catalog id find direct children
 * todo: if used further move this logic to model
 */
	public function get_children() {
		$parent = null;
		$parentId = null;
		if (isset($this->request->params['named']['parentId']))
			$parentId = ($this->request->params['named']['parentId']);
		if (isset($this->request->params['named']['parent']))
			$parent = ($this->request->params['named']['parent']);

		$directChildren = array();
		if ($parent && $parentId) {
			$data = ClassRegistry::init($parent)->find('first',
					array('fields' => 'id',
							'conditions' => array($parent.'.id' => $parentId),
					)) ;
			$catList = $this->Category->Categorized->find('list', array(
				'conditions' => array('Categorized.foreign_key'=>$data[$parent]['id'],
					'Categorized.model'=>$parent),
				'fields' => array('Categorized.category_id'),
				'contains' => array('Category')
			));
			$directChildren = $this->Category->find('list', array('conditions'=>array('Category.id' => $catList),
				'fields' => array('Category.id' , 'Category.name'),));
		} else if ($parentId){
			$data = $this->Category->children($parentId, true); // a flat array with 2 items
			foreach($data as $key => $val) {
				if ($val['Category']['type'] == 'Category')
					$directChildren[$val['Category']['id']] = $val['Category']['name'];
			}
		}
		if ($directChildren)
			echo json_encode($directChildren);
	}
	
/*
 * get_all()
 * Get all of the options based on type
 */
	public function get_all($type, $model = null) {
		$this->layout = false;
		$conditions = !empty($model) ? array('Category.model' => $model) : array('Category.model' => null);
				
		if ($type == 'Category' || $type == 'Attribute Group' || $type == 'Option Group') {
			$data = $this->Category->generateTreeList($conditions);
		} else if ($model == 'Catalog') {
			$parent_type = explode(' ', $type);
			$data = $this->Category->CategoryOption->find('all', array(
				'conditions' => array(
					'CategoryOption.type' => $parent_type[0]. ' Group',
					),
				'contain' => array(
					'Category',
					),
				)) ;
			$data = Set::combine($data, '{n}.CategoryOption.id', array('{0} {1}', '{n}.Category.name', '{n}.CategoryOption.name'));		
		}
		echo json_encode($data);
	}
	
/**
 * categorized()
 * Assigns categories to the model passed in Named Parameter
 *
 */
	public function categorized() {
		if (!empty($this->request->data)) {
			try {
				$result = $this->Category->categorized($this->request->data, $this->request->data['Model']['type']);
				if ($result) {
					$this->Session->setFlash(__d('categories', 'Category assigned', true));
					$this->redirect( $this->request->data['Model']['Referer']);
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->redirect(array('action' => 'index'));
			}
		} else {
			if (!empty($this->request->params['named']['type'])) {
				$model = $this->request->params['named']['type'];
				$this->set('referer', $this->referer());
				$this->set('models', ClassRegistry::init($model)->find('list', array()));
				$this->set('categories', $this->Category->find('list', array(
					'conditions' => array(
						'Category.parent_id' => null,
						'Category.model' => $model,
						),
					)));
			} else {
				$this->Session->setFlash('Invalid Input');
				$this->redirect($this->referer());
			}
		}
	}
	
	
/**
 * Find category items and return them for a requestAction call.
 * 
 * @param {int}		The id of the category
 * @param {int}		The number of records to return per page
 * @param {string}	The model name we're looking for.
 * @todo			We had a problem (explained below) with pulling recursive data. It should be fixed.
 */
	public function requestForItems($id = null, $limit = 20, $model = null) {
		if (!$this->RequestHandler->isAjax() && !$this->_isRequestedAction()) {
			return $this->cakeError('404');
		}

		if (!empty($model)) {		
			$this->Category->Categorized->bindModel(array(
				'hasAndBelongsToMany' => array(
					'CategoryItem'  => array(
						'className' => $model, 
       				'joinTable' => 'categorizeds',
	    				'associationForeignKey' => 'foreign_key',
	            		'foreignKey' => 'id',
						))));
			$categorized = $this->Category->Categorized->find('all', array(
				'conditions' => array(
					'Categorized.category_id' => $id,
					'Categorized.model' => $model,
					),
				));
			$categorized = Set::extract('/Categorized/foreign_key', $categorized);
		}
		# I did set to recursive but it caused multiple instances of the same item to be returned.
		# it looked to be correlated to the number of images that were in the gallery for catalog items
		# @todo : find a fix for this because having related data will be necesary at some point.
		#$this->Category->Categorized->CategoryItem->recursive = 0;
		
		# to set custom params for a categorized model, go to the model, and set the __construct() function
		# to output a variable called, "categorizedParams"  (an example can be found in the __construct() function of CatalogItem																										
		$params = !empty($this->Category->Categorized->CategoryItem->categorizedParams) ? $this->Category->Categorized->CategoryItem->categorizedParams : array();
		$params = array_merge(array('conditions' => array('CategoryItem.id' => $categorized), 'limit' => $limit), $params);
		$this->paginate['CategoryItem'] = $params;
		$categoryItems = $this->paginate($this->Category->Categorized->CategoryItem);
		
		return $categoryItems;

		#$this->viewPath = 'elements/categories';
		#$this->render('category_items');
	}
	
	
	
	private function _isRequestedAction() {
		return array_key_exists('requested', $this->request->params);
	}
}
?>