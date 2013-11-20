<?php

class CategoryHelper extends AppHelper {
	
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		
		if ($this->request->isPost()) {
			$this->_handlePost();
		}
	}
	
	public function findThreaded (){
		$this->Category = ClassRegistry::init('Categories.Category');
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
		return $categories; 
	}
	
	public function loadData($options = array()) {
		$this->Category = ClassRegistry::init('Categories.Category');
		// $joins = array(
			           // array('table'=>'categorized', 
			                 // 'alias' => 'Categorized',
			                 // 'type'=>'left',
			                 // 'conditions'=> array(
			                 	// 'Categorized.foreign_key = Classified.id'
			           // )),
			           // array('table'=>'categories', 
			                 // 'alias' => 'Category',
			                 // 'type'=>'left',
			                 // 'conditions'=> array(
			                 	// 'Category.id = Categorized.category_id'
					   // ))
			         // );
		$defaults = array('contain' => 'Categorized');
		$options = Set::merge($options, $defaults);
		$data = $this->Category->find('all', $options);
		return $data;
	}
	
	
	
	public function displayList() {
		$Category = ClassRegistry::init('Categories.Category');
		return $Category->find('list', array('conditions' => array('Category.parent_id' => '')));
	}
	
	public function displayItems($id) {
		$Category = ClassRegistry::init('Categories.Category');
		return( $Category->view($id) );
	}
	
	
	
	public function display($categories = array(), $options = array()) {
		$output = '';
		switch ($options['type']) {
			case ('selectForm'):
				$output .= $this->_selectForm($categories, $options);
				break;
			case ('ul'):
			default:
				$output .= $this->_recursiveUl($categories);
				break;
		}
		return $output;
	}
	
	protected function _recursiveUl($array) {
		$output = '';
		if (!empty($array)) {
			$output .= '<ul>';
			foreach ($array as $item) {
				$output .= '<li>';
				$output .= $item['Category']['name'];
				$output .= $this->_recursiveUl($item['children']);
				$output .= '</li>';
			}
			$output .= '</ul>';
		}
		return $output;
	}
	
	protected function _selectForm($array, $options) {
		$output = '';
		if (!empty($array)) {
			$output .= '<form method="post">';
			$output .= '<input type="hidden" name="data[Category][model]" value="'.$options['model'].'" />';
			$output .= '<input type="hidden" name="data[Category][foreignKey]" value="'.$options['foreignKey'].'" />';
			$output .= '<select name="data[Category][Category][0]">';
			$output .= '<option value="">- none -</option>';
			foreach ($array as $item) {
				$output .= '<option value="'.$item['Category']['id'].'">';
				$output .= $item['Category']['name'];
				$output .= '</option>';
			}
			$output .= '</select>';
			$output .= '<input type="submit" value="save">';
			$output .= '</form>';
		}
		return $output;
	}
	
	protected function _handlePost() {
		if (!empty($this->request->data['Category'])) {
			$categorized = array($this->request->data['Category']['model'] => array('id' => array($this->request->data['Category']['foreignKey'])));
			if (is_array($this->request->data['Category']['Category'])) {
				// this is for checkbox / multiselect submissions (multiple categories)
				$categorized['Category']['id'] = $this->request->data['Category']['Category'];
			} else {
				// this is for radio button submissions (one category)
				$categorized['Category']['id'][] = $this->request->data['Category']['Category'];
			}
			$this->Category = ClassRegistry::init('Categories.Category');
			try {
        		$this->Category->categorized($categorized, $this->request->data['Category']['model']);
			} catch (Exception $e) {
				throw new Exception ($e->getMessage());
			}
		}
	}
	
}
