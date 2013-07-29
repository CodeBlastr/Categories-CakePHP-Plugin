<?php
App::uses('ModelBehavior', 'Model');
App::uses('Category', 'Categories.Model');

/**
 * Categorizable Behavior class file.
 * 
 * Usage is :
 * Attach behavior to a model, and when you save if valid category data exists the object will be categorized automatically. 
 *
 * @filesource
 * @author			Richard Kersey
 * @copyright       RazorIT LLC
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link            https://github.com/zuha/Categories-Zuha-Cakephp-Plugin
 */
class CategorizableBehavior extends ModelBehavior {

/**
 * Behavior settings
 * 
 * @access public
 * @var array
 */
	public $settings = array();

/**
 * The full results of Model::find() that are modified and saved
 * as a new copy.
 *
 * @access public
 * @var array
 */
	public $record = array();

/**
 * Default values for settings.
 *
 * - recursive: whether to copy hasMany and hasOne records
 * - habtm: whether to copy hasAndBelongsToMany associations
 * - stripFields: fields to strip during copy process
 * - ignore: aliases of any associations that should be ignored, using dot (.) notation.
 * will look in the $this->contain array.
 *
 * @access private
 * @var array
 */
    protected $defaults = array(
		'modelAlias' => null, // changed to $Model->alias in setup()
		//'foreignKeyName' => null,
		);


/**
 * Configuration method.
 *
 * @param object $Model Model object
 * @param array $config Config array
 * @access public
 * @return boolean
 */
    public function setup(Model $Model, $config = array()) {
    	
    	$this->settings = array_merge($this->defaults, $config);
		$this->modelName = !empty($this->settings['modelAlias']) ? $this->settings['modelAlias'] : $Model->alias;
		//don't think this is necessary, but will save it for future reference in the case that there is a different primary key than id
		//$this->foreignKey =  !empty($this->settings['foreignKeyName']) ? $this->settings['foreignKeyName'] : $Model->primaryKey;
		$this->Category = new Category;
		
    	return true;
	}
	

/**
 * Before save method.
 *
 * Remove category data, and pass to the after save function for manual entry.
 *
 * @param object $Model model object
 * @access public
 * @return boolean
 */
	public function beforeSave(Model $Model) {
		if (isset($Model->data['Category']['Category'])) {
			$this->data = $Model->data;
			unset($Model->data['Category']['Category']);
		}
		
		return true;
	}
	

/**
 * After save method.
 *
 * If the data array object contains a value $data['Draft']['status'] = 1, then save draft.
 *
 * @param object $Model model object
 * @param mixed $id String or integer model ID
 * @access public
 * @return boolean
 */
	public function afterSave(Model $Model, $created) {
		// this is how the categories data should look when coming in.
		if (isset($this->data['Category']['Category'])) {
			$categorized = array($this->modelName => array('id' => array($Model->id)));
			// line following was changed, for courses categorization (not sure if it broke anything)
			// $categorized['Category']['id'][] = $this->data['Category']['Category'];
			$categorized['Category']['id'] = $this->data['Category']['Category'];
			try {
        		$this->Category->categorized($categorized, $Model->alias);
			} catch (Exception $e) {
				throw new Exception ($e->getMessage());
			}
		}

	}
	
}