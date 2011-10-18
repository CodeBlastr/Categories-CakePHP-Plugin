<?php
class CategoryOptionsController extends CategoriesAppController {

	/**
	 * Name
	 *
	 * @var string
	 */
	var $name = 'CategoryOptions';

	/**
	 * Edit for category option.
	 *
	 * @param string $id, category option id 
	 */
	public function edit($id = null) {
		$categories = $this->CategoryOption->Category->generatetreelist(); 
		$this->request->data = $this->CategoryOption->find('first', array('conditions' => array('CategoryOption.id' => $id)));
		$this->set(compact('categories'));
	}
}
?>