<?php
/**
 *
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
<div class="<?php echo $pluralVar; ?> view">
<h2><?php echo "<?php echo __('{$singularHumanName}'); ?>"; ?></h2>
	<dl class="dl-horizontal">
<?php
foreach ($fields as $field) {
	$isKey = false;
	if (!empty($associations['belongsTo'])) {
		foreach ($associations['belongsTo'] as $alias => $details) {
			if ($field === $details['foreignKey']) {
				$isKey = true;
				echo "\t\t<dt><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></dt>\n";
				echo "\t\t<dd>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
				break;
			}
		}
	}
	if ($isKey !== true) {
		if (!in_array($field, array('position'))) {
			echo "\t\t<dt><?php echo __('" . Inflector::humanize($field) . "'); ?></dt>\n";
		}
		if (in_array($field, array('active', 'featured'))) {
			echo "\t\t<dd>\n\t\t\t<?php echo (\${$singularVar}['{$modelClass}']['{$field}']) ? 'Yes' : 'No'; ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
		} elseif (!in_array($field, array('position'))) {
			echo "\t\t<dd>\n\t\t\t<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
		}
	}
}
?>
	</dl>
</div>
<?php echo "<?php \$this->start('sidebar-left'); ?>\n"; ?>
<div class="actions">
	<h3 class="lead"><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
	<ul class="nav nav-pills nav-stacked">
<?php
	echo "\t\t<li><?php echo \$this->Html->link(__('Edit " . $singularHumanName ."'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
	echo "\t\t<li><?php echo \$this->Form->postLink(__('Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), null, __('Are you sure you want to delete \"%s\"?', \${$singularVar}['{$modelClass}']['{$displayField}'])); ?> </li>\n";
	echo "\t\t<li><?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index')); ?> </li>\n";
	echo "\t\t<li><?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add')); ?> </li>\n";
?>
	</ul>
</div>
<? echo "<?php \$this->end(); ?>"; ?>
<?php
if (!empty($associations['hasOne'])) :
	foreach ($associations['hasOne'] as $alias => $details): ?>
	<div class="related">
		<h3 class="lead"><?php echo "<?php echo __('Related " . Inflector::humanize($details['controller']) . "'); ?>"; ?></h3>
	<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
		<dl>
	<?php
			foreach ($details['fields'] as $field) {
				echo "\t\t<dt><?php echo __('" . Inflector::humanize($field) . "'); ?></dt>\n";
				echo "\t\t<dd>\n\t<?php echo \${$singularVar}['{$alias}']['{$field}']; ?>\n&nbsp;</dd>\n";
			}
	?>
		</dl>
	<?php echo "<?php endif; ?>\n"; ?>
	<?php echo "<?php \$this->start('sidebar-left'); ?>\n"; ?>
		<div class="actions">
			<ul class="nav nav-pills nav-stacked">
				<li><?php echo "<?php echo \$this->Html->link(__('Edit " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?></li>\n"; ?>
			</ul>
		</div>
	<?php echo "<?php \$this->end(); ?>"; ?>
	</div>
	<?php
	endforeach;
endif;
if (empty($associations['hasMany'])) {
	$associations['hasMany'] = array();
}
if (empty($associations['hasAndBelongsToMany'])) {
	$associations['hasAndBelongsToMany'] = array();
}
$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
foreach ($relations as $alias => $details):
	$otherSingularVar = Inflector::variable($alias);
	$otherPluralHumanName = Inflector::humanize($details['controller']);
	?>
<div class="related view">
	<h3 class="lead"><?php echo "<?php echo __('Related " . $otherPluralHumanName . "'); ?>"; ?></h3>
	<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
	<table class="table table-condensed table-bordered table-striped" cellpadding = "0" cellspacing = "0">
	<tr>
<?php
			foreach ($details['fields'] as $field) {
				if (strpos($field, '_id') !== false) {
					continue;
				} elseif (!in_array($field, array('created', 'body', 'description', 'position', 'slug'))) {
					echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
				}
			}
?>
		<th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
	</tr>
<?php
echo "\t<?php foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
		echo "\t\t<tr>\n";
			foreach ($details['fields'] as $field) {
				if (strpos($field, '_id') !== false) {
					continue;
				} elseif (in_array($field, array('active', 'featured'))) {
					echo "\t\t\t<td><?php echo (\${$otherSingularVar}['{$field}']) ? 'Yes' : 'No'; ?></td>\n";
				} elseif (!in_array($field, array('created', 'body', 'description', 'position', 'slug'))) {
					echo "\t\t\t<td><?php echo \${$otherSingularVar}['{$field}']; ?></td>\n";
				}
			}

			echo "\t\t\t<td class=\"actions btn-group\">\n";
			echo "\t\t\t\t<?php echo \$this->Html->link('<span class=\"glyphicon glyphicon-eye-open\"></span>', array('controller' => '{$details['controller']}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-default btn-sm', 'escapeTitle' => false, 'title' => 'View')); ?>\n";
			echo "\t\t\t\t<?php echo \$this->Html->link('<span class=\"glyphicon glyphicon-pencil\"></span>', array('controller' => '{$details['controller']}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-default btn-sm', 'escapeTitle' => false, 'title' => 'Edit')); ?>\n";
			echo "\t\t\t\t<?php echo \$this->Form->postLink('<span class=\"glyphicon glyphicon-trash\"></span>', array('controller' => '{$details['controller']}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('escapeTitle' => false, 'title' => 'Delete', 'class' => 'btn btn-sm btn-warning'), __('Are you sure you want to delete \"%s\"?', \${$otherSingularVar}['{$details['displayField']}'])); ?>\n";
			echo "\t\t\t</td>\n";
		echo "\t\t</tr>\n";

echo "\t<?php endforeach; ?>\n";
?>
	</table>
<?php echo "<?php endif; ?>\n\n"; ?>
<?php echo "<?php \$this->start('sidebar-left'); ?>\n"; ?>
	<div class="actions">
		<ul class="nav nav-pills nav-stacked">
			<li><?php echo "<?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>"; ?> </li>
		</ul>
	</div>
<?php echo "<?php \$this->end(); ?>"; ?>
</div>
<?php endforeach; ?>
