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

/**
 * Categorized fixture
 *
 * @package 	categories
 * @subpackage	categories.tests.fixtures
 */
class CategorizedFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Categorized';
    
/**
 * Import
 * 
 * @var array 
 */
	public $import = array('config' => 'Categories.Categorized', 'uses' => 'categorized');


/**
 * Records
 *
 * @var array $records
 */
	public $records = array();
}
