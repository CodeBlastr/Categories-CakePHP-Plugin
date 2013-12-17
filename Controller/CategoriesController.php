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
 * Get Categories method
 * Funciton to retrieve Categories and Counts
 * 
 * @param string $model
 * 
 * NOTE : we don't need this, just use index with a filter named
 * EG : /categories/categories/index/filter:model:Model
 * 
 */ 
	 // function getCategories ($model = null) {
	 	// $categories = $this->Category->find('all', array(
	 		// 'conditions' => array('Category.model' => $model),
			// 'contain' => array(
				// 'Categorized',
				// 'Gallery' => array('GalleryThumb')
				// ),
			// ));
		// if($this->_isRequestedAction()) {
			// return $categories;
		// }else {
// 			
			// $this->set('catcount', $trimcategory);
			// $this->request->data = $categories;
// 			
		// }
	 // }

/**
 * Index method.
 *
 */
	public function index() {
		$this->Category->recursive = 0;
		$this->paginate['order'] = array("{$this->Category->alias}.lft" => 'asc', "{$this->Category->alias}.name" => 'asc');
		$this->set('categories', $categories = $this->paginate());
		return $categories;
	}

/**
 * View for category.
 *
 * @param string $slug, category slug
 */
	public function view($slug = null) {
		try {
			// this is put here specifically for the products category, so if you change it
			// make sure that /categories/categories/view/X  (where X = a catalog item related category) looks good still.
			$category = $this->Category->view($slug);  // equals the category, and contains related items grouped by model
			$this->paginate['conditions'] = array('ChildCategory.parent_id' => $category['Category']['id']);
			$this->paginate['fields'] = array('id', 'name');
			$this->paginate['order'] = array("{$this->Category->alias}.lft");
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
 * GET RID OF THIS FUNCTION.  USE THE DASHBOARD
	public function add($categoryId = null) {
		
		if (!empty($this->request->params['named']['parent']) && empty($this->request->params['named']['model'])) {
			$parent = $this->Category->find('first', array(
				'conditions' => array(
					'Category.id' => $this->request->params['named']['parent']
					)
				));
			if (!empty($parent['Category']['model'])) {
				$this->redirect(array('action' => 'add', 'model' => $parent['Category']['model'], 'parent' => $parent['Category']['id']));
			}
		}

		if(!empty($this->request->data['Category'])) {
			try {
				$result = $this->Category->save($this->request->data);
				
				if (!empty($result['Category']['id'])) {
					if (!empty($this->request->data['Category']['parent_id']) && empty($this->request->data['Category']['type'])) {
						// if there was a parent_id then we can assign categories to items
						$this->Session->setFlash(__d('categories', 'Category Saved.  You can assign categories here.', true));
						$this->redirect(array('action' => 'categorized', 'type' => $this->request->data['Category']['model']));
					} else {
						$this->Session->setFlash(__d('categories', 'Category Saved', true));
						$this->redirect($this->referer());
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

		//viewVars
		$models = $this->Category->listModels();
		$parents = $this->Category->generateTreeList();
		$parentId = !empty($this->request->params['named']['parent']) ? $this->request->params['named']['parent'] : null;

		if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'Attribute Type') {
			echo 'this is a table that doesn\'t exist anymore, if this shows alert admin';
			break;
		}

		$type = !empty($this->request->params['named']['type']) ? $this->request->params['named']['type'] : 'Category';
		$model = !empty($this->request->params['named']['model']) ? $this->request->params['named']['model'] : $this->request->data['model'];
		$pageTitleForLayout = !empty($parent) ? __('Add %s %s to %s', $model, $type, $parent) : __('Add %s %s', $model, $type);

		$this->request->data['Category']['model'] = $model;
		$this->request->data['Category']['type'] = $type;
		$this->request->data['Category']['parent_id'] = $parentId;
		$this->set('page_title_for_layout', $pageTitleForLayout);
		$this->set(compact('models', 'parents', 'model', 'parent', 'type', 'parentId'));
		
		//Ajax Modal Settings this should propable be in the appController beforeFilter
		if(isset($this->request->data['modal']) && $this->request->data['modal'] == true) {
			$this->layout = 'bootstrap_modal';
			
		}
	}
 */
 
/**
 * Add method
 * Usage : Straight adding of a category no view provided.  Redirect to the referring page.
 * 
 * @return void
 */
 	public function add() {
		if($this->request->is('post')) {
			try {
				$result = $this->Category->save($this->request->data);
				$this->Session->setFlash(__d('categories', 'Category Saved', true), 'flash_success');
				$this->redirect($this->referer());
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->redirect($this->referer);
			}
		} else {
			throw new NotFoundException(__('Invalid request'));
		}
 	}
 

/**
 * Edit for category.
 *
 * @param string $id, category id
 */
	public function edit($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid category'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			try {
				$result = $this->Category->edit($id, $this->request->data);
				if ($result === true) {
					$this->Session->setFlash(__d('categories', 'Category saved', true), 'flash_success');
					$this->redirect(array('action' => 'dashboard'));

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
			$this->set('parents', $this->Category->generateTreeList());
		}
		$this->layout = 'default';
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
	public function dashboard() {
		$this->redirect('admin');
		
		$this->helpers[] = 'Utils.Tree'; 
		$threaded = $this->Category->find('threaded');
		$models = Set::extract('/Category/model', $this->Category->find('all', array('group' => array('Category.model'), 'fields' => array('Category.model'))));
		foreach ($models as $model) {
			foreach ($threaded as $thread) {
				if ($thread['Category']['model'] == $model) {
					$categories[$model][] = $thread;
					$options[$model] = $this->Category->generateTreeList(array('Category.model' => $model), null, null, '--');
				}
			}
		}
		
		$this->set(compact('categories', 'options'));
		$this->set('models', $models = array_diff($this->Category->listModels(), $models));
		$this->set('page_title_for_layout', 'Categories Dashboard');
		$this->set('title_for_layout', 'Categories Dashboard');
	}


/**
 * Get children method
 * 
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
		if ($directChildren) {
			echo json_encode($directChildren);
		}
	}

/**
 * categorized method
 * 
 * Assigns categories to the model passed in Named Parameter
 * 
 * @return void
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
 */


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
       				'joinTable' => 'categorized',
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
		// I did set to recursive but it caused multiple instances of the same item to be returned.
		// it looked to be correlated to the number of images that were in the gallery for catalog items
		// @todo : find a fix for this because having related data will be necesary at some point.
		// $this->Category->Categorized->CategoryItem->recursive = 0;

		// to set custom params for a categorized model, go to the model, and set the __construct() function
		// to output a variable called, "categorizedParams"  (an example can be found in the __construct() function of Product
		$params = !empty($this->Category->Categorized->CategoryItem->categorizedParams) ? $this->Category->Categorized->CategoryItem->categorizedParams : array();
		$params = array_merge(array('conditions' => array('CategoryItem.id' => $categorized), 'limit' => $limit), $params);
		$this->paginate['CategoryItem'] = $params;
		$categoryItems = $this->paginate($this->Category->Categorized->CategoryItem);

		return $categoryItems;

		// $this->viewPath = 'elements/categories';
		// $this->render('category_items');
	}
    
/**
 * Move up method
 *
 * @param string $name
 * @param int $delta
 */
    function moveup($name = null, $delta = null){
        $cat = $this->Category->findByName($name);
        if (empty($cat)) {
            $this->Session->setFlash('There is no category named ' . $name);
            $this->redirect(array('action' => 'index'), null, true);
        }
        $this->Category->id = $cat['Category']['id'];
        if ($delta > 0) {
            $this->Category->moveUp($this->Category->id, abs($delta));
        } else {
            $this->Session->setFlash('Please provide a number of positions the category should be moved up.');
        }
        $this->redirect(array('action' => 'index'), null, true);
    }

/**
 * is requested action method
 * 
 * @return array
 * @todo Quite sure this is not necessary, you can just have any function do a return
 */
	protected function _isRequestedAction() {
		return array_key_exists('requested', $this->request->params);
	}
}
