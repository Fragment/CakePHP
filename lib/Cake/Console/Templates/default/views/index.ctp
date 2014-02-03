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
<div class="<?php echo $pluralVar; ?> index">
	<h2><?php echo "<?php echo __('{$pluralHumanName}'); ?>"; ?></h2>
	<table class="table table-striped table-condensed table-bordered" cellpadding="0" cellspacing="0">
	<tr>
<?
		foreach ($fields as $field): 
			if (!in_array($field, array('created', 'body', 'description', 'position', 'slug', 'password'))) {
				echo "\t\t<th><?php echo \$this->Paginator->sort('{$field}'); ?></th>\n";
			}
		endforeach;
?>
		<th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
	</tr>
	<?php
	echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
	echo "\t<tr>\n";
		foreach ($fields as $field) {
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
				if (in_array($field, array('active', 'featured'))) {
					echo "\t\t<td><?php echo (\${$singularVar}['{$modelClass}']['{$field}']) ? 'Yes' : 'No'; ?>&nbsp;</td>\n";
				} elseif (!in_array($field, array('created', 'body', 'description', 'position', 'slug', 'password'))) {
					echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
				}
			}
		}

		echo "\t\t<td class=\"actions btn-group\">\n";
		echo "\t\t\t<?php echo \$this->Html->link('<span class=\"glyphicon glyphicon-eye-open\"></span>', array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-default btn-sm', 'escapeTitle' => false, 'title' => 'View')); ?>\n";
		echo "\t\t\t<?php echo \$this->Html->link('<span class=\"glyphicon glyphicon-pencil\"></span>', array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-default btn-sm', 'escapeTitle' => false, 'title' => 'Edit')); ?>\n";
		echo "\t\t\t<?php echo \$this->Form->postLink('<span class=\"glyphicon glyphicon-trash\"></span>', array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-warning btn-sm', 'escapeTitle' => false, 'title' => 'Delete'), __('Are you sure you want to delete \"%s\"?', \${$singularVar}['{$modelClass}']['{$displayField}'])); ?>\n";
		echo "\t\t</td>\n";
	echo "\t</tr>\n";

	echo "<?php endforeach; ?>\n";
	?>
	</table>
	<p>
	<?php echo "<?php
	echo \$this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>"; ?>
	</p>
	<ul class="pagination">
	<?php
		echo "<?php\n";
		echo "\t\techo \$this->Paginator->prev('< ' . __('previous'), array('tag' => 'li'), null, array('class' => 'prev disabled', 'tag' => 'li', 'disabledTag' => 'a'));\n";
		echo "\t\techo \$this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));\n";
		echo "\t\techo \$this->Paginator->next(__('next') . ' >', array('tag' => 'li'), null, array('class' => 'next disabled', 'tag' => 'li', 'disabledTag' => 'a'));\n";
		echo "\t?>\n";
	?>
	</ul>
</div>
<?php echo "<?php \$this->start('sidebar-left'); ?>\n"; ?>
<div class="actions">
	<h3 class="lead"><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?php echo "<?php echo \$this->Html->link(__('<span class=\"glyphicon glyphicon-plus\"></span> New " . $singularHumanName . "'), array('action' => 'add'), array('escapeTitle' => false)); ?>"; ?></li>
	</ul>
</div>
<?php echo "<?php \$this->end(); ?>"; ?>
