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

App::import('Model', 'Categories.Category'); 

/**
 * Categories controller
 *
 * @package categories
 * @subpackage categories.controllers
 */
class CategoriesController extends CategoriesAppController {

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
		$this->set('modelName', $this->modelClass); 
	}

/**
 * Index for category.
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
			$category = $this->Category->view($slug);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('category')); 
	}

/**
 * Admin index for category.
 * 
 */
	public function admin_index() {
		$this->Category->recursive = 0;
		$this->set('categories', $this->paginate()); 
	}

/**
 * Admin index
 *
 */
	public function admin_tree() {
		$this->Category->recursive = 0;
		$this->helpers[] = 'Utils.Tree';
		$this->set('categories', $this->Category->find('all', array('order' => 'Category.lft')));
	}

/**
 * Admin view for category.
 *
 * @param string $slug, category slug 
 */
	public function admin_view($slug = null) {
		try {
			$category = $this->Category->view($slug);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('category')); 
	}

/**
 * Admin add for category.
 * 
 */
	public function admin_add($category_id = null) {
		try {
			$result = $this->Category->add($this->Auth->user('id'), $this->data);
			if ($result === true) {
				$this->Session->setFlash(__d('categories', 'The category has been saved', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data) && !empty($category_id)) {
			$this->data['Category']['category_id'] = $category_id;
		}
		$categories = $this->Category->find('list');
		$users = $this->Category->User->find('list');
		$this->set(compact('categories', 'users'));
	}

/**
 * Admin edit for category.
 *
 * @param string $id, category id 
 */
	public function admin_edit($id = null) {
		try {
			$result = $this->Category->edit($id, null, $this->data);
			if ($result === true) {
				$this->Session->setFlash(__d('categories', 'Category saved', true));
				$this->redirect(array('action' => 'view', $this->Category->data['Category']['slug']));
				
			} else {
				$this->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
		$categories = $this->Category->find('list');
		$users = $this->Category->User->find('list');
		$this->set(compact('categories', 'users'));
 
	}

/**
 * Admin delete for category.
 *
 * @param string $id, category id 
 */
	public function admin_delete($id = null) {
		try {
			$result = $this->Category->validateAndDelete($id, $this->Auth->user('id'), $this->data);
			if ($result === true) {
				$this->Session->setFlash(__d('categories', 'Category deleted', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->Category->data['category'])) {
			$this->set('category', $this->Category->data['category']);
		}
	}
}
