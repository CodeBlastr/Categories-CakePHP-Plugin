<?php
App::uses('CategorizableBehavior', 'Categories.Model/Behavior');


/**
 * CategorizableBehavior Test Case
 *
 */
class CategorizableBehaviorTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Categories.Article',
		'plugin.Categories.Categorized',
		'plugin.Categories.Category',
		);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Categorizable = new CategorizableBehavior();
		$this->Model = Classregistry::init('Article'); // not tied to an actual model file
		$this->Model->Behaviors->attach('Categories.Categorizable');
		//$this->Category = ClassRegistry::init('Categories.Category');
		$this->Categorized = ClassRegistry::init('Categories.Categorized');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Categorizable);
		unset($this->Category);
		unset($this->Categorized);

		parent::tearDown();
	}
	

/**
 * Test behavior instance
 *
 * @return void
 */
	public function testBehaviorInstance() {
		$this->assertTrue(is_a($this->Model->Behaviors->Categorizable, 'CategorizableBehavior'));
	}
	

	
/**
 * testAfterSave method
 *
 * @return void
 */
	public function testAfterSave() {	
		$article['Article']['title'] = "My Test Article";
		$article['Category']['Category'] = "category-1";
		$this->Model->save($article);
		$result = $this->Categorized->find('first', array('conditions' => array('Categorized.model' => 'Article', 'Categorized.foreign_key' => $this->Model->id)));
		$this->assertNotEmpty($result);
	}
	
}