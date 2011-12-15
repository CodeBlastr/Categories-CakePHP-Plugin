<?php
class CategoryOptionsController extends CategoriesAppController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'CategoryOptions';
	public $uses = 'Categories.CategoryOption';

/**
 * Edit for category option.
 *
 * @param string $id, category option id 
 */
	public function edit($id = null) {
		$categories = $this->CategoryOption->Category->generateTreeList(); 
		$this->request->data = $this->CategoryOption->find('first', array('conditions' => array('CategoryOption.id' => $id)));
		$this->set(compact('categories'));
	}
}
?>