<?php
class CategoryOptionsController extends CategoriesAppController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'CategoryOptions';
	
/**
 * Uses
 *
 * @var string
 */
	public $uses = 'Categories.CategoryOption';

/**
 * Index of options.
 */
	public function index() {
		$this->helpers[] = 'Utils.Tree';
		$this->CategoryOption->recursive = 0;
		$categoryOptions = $this->CategoryOption->find('threaded');
		$this->set(compact('categoryOptions')); 
	}

/**
 * Edit for category option.
 *
 * @param string $id, category option id 
 */
	public function edit($id = null) {
		if (!empty($this->request->data)) {
			try {
				$this->CategoryOption->saveAll($this->request->data);
				$this->Session->setFlash(__('Saved'));
				$this->redirect(array('action' => 'index'));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}
		$categories = $this->CategoryOption->Category->generateTreeList(); 
		$this->request->data = $this->CategoryOption->find('first', array(
			'conditions' => array(
				'CategoryOption.id' => $id
				)
			));
		$this->set(compact('categories'));
	}

/**
 * Delete for category options.
 *
 * @param string $id, category id 
 */
	public function delete($id = null) {
		$this->__delete('CategoryOption', $id);
	} 
}
?>