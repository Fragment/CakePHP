<?php
/**
 * Test Case bake template
 *
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.console.libs.templates.objects
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
echo "<?php\n";
echo "/* ". $className ." Test cases generated on: " . date('Y-m-d H:m:s') . " : ". time() . "*/\n";
?>
App::import('<?php echo $type; ?>', '<?php echo $plugin . $className;?>');

<?php if ($mock and strtolower($type) == 'controller'): ?>
class Test<?php echo $fullClassName; ?> extends <?php echo $fullClassName; ?> {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

<?php endif; ?>
class <?php echo $fullClassName; ?>TestCase extends CakeTestCase {
<?php if (!empty($fixtures)): ?>
	public $fixtures = array('<?php echo join("', '", $fixtures); ?>');

<?php endif; ?>
	public function startTest() {
		$this-><?php echo $className . ' =& ' . $construction; ?>
	}

	public function endTest() {
		unset($this-><?php echo $className;?>);
		ClassRegistry::flush();
	}

<?php foreach ($methods as $method): ?>
	public function test<?php echo Inflector::classify($method); ?>() {

	}

<?php endforeach;?>
}