<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<div class="<?php echo $pluralVar; ?> form">
<?php 
	$file = (in_array('src', $fields) || in_array('img', $fields)) ? true :  false;
	if ($file)
		echo "<?php echo \$this->Form->create('{$modelClass}', array('type' => 'file')); ?>\n";
	else
		echo "<?php echo \$this->Form->create('{$modelClass}'); ?>\n";
?>
	<fieldset>
		<legend><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></legend>
<?php
		$foreignKeys = array();
		if (isset($associations['belongsTo']) && !empty($associations['belongsTo']))
		{
			foreach ($associations['belongsTo'] as $belongsTo)
				$foreignKeys[] = $belongsTo['foreignKey'];
		}

		echo "\t<?php\n";
		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && ($field == $primaryKey || $field == 'slug')) {
				continue;
			} elseif (strpos($action, 'add') !== false && (in_array($field, $foreignKeys))) {
				echo "\t\techo \$this->Form->input('{$field}', array('empty' => true));\n";
			} elseif (in_array($field, array('img', 'src'))) {
				echo "\t\techo \$this->Form->input('{$field}', array('type' => 'file'));\n";
			} elseif (in_array($field, array('body', 'description'))) {
				echo "\t\techo \$this->Form->input('{$field}', array('required' => false));\n";
			} elseif (!in_array($field, array('created', 'login', 'modified', 'position', 'updated'))) {
				echo "\t\techo \$this->Form->input('{$field}');\n";
			}
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\techo \$this->Form->input('{$assocName}', array('multiple' => 'checkbox'));\n";
			}
		}
		echo "\t?>\n";
?>
	</fieldset>
<?php
	$ckeditor = array();
	if (in_array('description', $fields))
		$ckeditor[] = "\t\tCKEDITOR.replace('data[{$modelClass}][description]', {toolbar: 'Basic'});\n";
	if (in_array('body', $fields))
		$ckeditor[] = "\t\tCKEDITOR.replace('data[{$modelClass}][body]', {toolbar: 'Text'});\n";

	if (!empty($ckeditor))
	{
		echo  "\t<script>\n";
		foreach ($ckeditor as $line)
			echo $line;
		echo  "\t</script>\n";
	}
	echo "<?php echo \$this->Form->end(__('Submit')); ?>\n";
?>
</div>
<div class="actions">
	<h3><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
	<ul>

<?php if (strpos($action, 'add') === false): ?>
		<li><?php echo "<?php echo \$this->Form->postLink(__('Delete'), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), null, __('Are you sure you want to delete # %s?', \$this->Form->value('{$modelClass}.{$primaryKey}'))); ?>"; ?></li>
<?php endif; ?>
		<li><?php echo "<?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index')); ?>"; ?></li>
	</ul>
</div>
