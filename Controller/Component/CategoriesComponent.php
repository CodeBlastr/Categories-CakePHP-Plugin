<?php

App::uses('Component', 'Controller');
class CategoriesComponent extends Component {
/**
 * Components
 *
 * @var array $components
 * @access public
 */
	public $components = array();

/**
 * Enabled
 *
 * @var boolean $enabled
 * @access public
 */
	public $enabled = true;

/**
 * Controller
 *
 * @var mixed $controller
 * @access public
 */
	public $controller = null;

/**
 * The add action the component should use to 
 * prepopulate the category selection boxes
 * 
 */
	public $addAction = array('add');
	
/**
 * The add action the component should use to
 * contain searches by
 *
 */
	public $searchAction = array('index');

/**
 * Constructor.
 *
 * @param ComponentCollection $collection
 * @param array $settings
 * @return void
 */
	function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
		$this->settings = $settings;
    }
/**
 * Initialize Callback
 *
 * @param object
 * @return void
 * @access public
 */
	public function initialize(Controller $controller) {
		foreach ($this->settings as $setting => $value) {
			if (isset($this->{$setting})) {
				$this->{$setting} = $value;
			}
		}
		$this->Controller = $controller;
		$this->modelName = $controller->modelClass;
		if (!$controller->{$this->modelName}->Behaviors->attached('Categorizable')) {
			$controller->{$this->modelName}->Behaviors->attach('Categories.Categoizable');
		}
	}
	
	function shutdown() { }
	function beforeRedirect() { }

/**
 * Callback
 *
 * @param object Controller
 * @return void
 * @access public
 */
	public function startup(Controller $controller) {
		$catids = array();
		$this->Controller->loadModel('Categories.Category');
		$this->categories = $this->Controller->Category->find('list', array('conditions' => array('model' => $this->modelName)));
		$this->Controller->set('categories', $this->categories);
		if(in_array($this->Controller->action, $this->searchAction)) {
			if(isset($this->Controller->request->query['category'])) {
				$params = explode('__', $this->Controller->request->query['category']);
				debug($params);
				foreach($params as $cat) {
					if(Zuha::is_uuid($cat)) {
						$catids[] = $cat;
					}else {
						$id	= array_search($cat, $this->categories);
						if($id) {
							$catids[] = $id;
						}
					}
				}
			$modelids = $this->Controller->Category->Categorized->find('all', array('conditions' => array('Categorized.model' => $this->modelName, 'Categorized.category_id' => $catids)));	
			$modelids = Set::classicExtract($modelids, '{n}.Categorized.foreign_key');
			$modelids = array_unique($modelids);
			$this->Controller->paginate['conditions'][$this->modelName.'.id'] = $modelids;
		}
	}
	}

/**
 * Callback
 *
 * @return void
 * @access public
 */
	public function beforeRender($viewFile) {
		//Set a global variable for all the categories attached to the model
		if(in_array($this->Controller->action, $this->addAction)) {
			if(isset($this->Controller->request->query['category'])) {
				if(Zuha::is_uuid($this->Controller->request->query['category'])) {
					$this->Controller->request->data['Category']['Category'] = $this->Controller->request->query['category'];
				}else {
					$this->Controller->request->data['Category']['Category'] = array_search($this->Controller->request->query['category'], $this->categories);
				}
					
			}
		}
	}

}
?>