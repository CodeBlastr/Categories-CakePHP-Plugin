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
    	// we have to give a model name, because multiple models use
    	$this->settings[$Model->name] = array_merge($this->defaults, $config);
		$this->settings[$Model->name]['modelAlias'] =  !empty($config['modelAlias'])? $config['modelAlias'] : $Model->alias;
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
			$categorized = array($this->settings[$Model->name]['modelAlias'] => array('id' => array($Model->id)));
			
			if (is_array($this->data['Category']['Category'])) {
				// this is for checkbox / multiselect submissions (multiple categories)
				$categorized['Category']['id'] = $this->data['Category']['Category'];
			} else {
				// this is for radio button submissions (one category)
				$categorized['Category']['id'][] = $this->data['Category']['Category'];
			}
			try {
				$Category = new Category;
        		$Category->categorized($categorized, $Model->alias);
			} catch (Exception $e) {
				throw new Exception ($e->getMessage());
			}
		}
	}
	
}