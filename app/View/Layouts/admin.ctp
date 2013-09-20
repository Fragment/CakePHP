<?
	/**==============================================
	*	Site Meta Info
	*==============================================*/
	$meta = array(
		'site_name' => 'Fragment',
		'controllers' => array()
	);

	/**==============================================
	*	Links to exclude from navigation.
	*==============================================*/
	$meta['disable'] = array(
		'Groups','Sites','App','Comments','Types','Severities'
	);

	foreach(App::objects('Controller') as $c)
	{
		$table = str_replace('Controller', '', $c);
		if(!in_array($table, $meta['disable']))
			$meta['controllers'][] = $table;
	}

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?= $meta['site_name'].' - '.$title_for_layout;?>
	</title>
	<?
			echo $this->Html->meta('icon');
			echo $this->Html->css('admin.bootstrap.min');
			echo $this->Html->css('admin');
			echo $this->Html->css('cakejax');
			echo $this->Html->css('fontello-embedded');
			echo $this->Html->script('jquery.1.10.1.min');
			echo $this->Html->script('bootstrap.min');
			echo $this->Html->script('cakejax');
			echo $this->Html->script('app');
			// echo $this->Html->script('HistoryController');
			// echo $this->Html->script('admin');
			echo $this->Html->script('ckeditor/ckeditor');
?>
</head>
<body>
	<div id="container">
		<div class="header clrafter">
			<div class="container">
				<nav class="navbar navbar-default" role="navigation">
					<a href="/" class="navbar-brand" alt="<?=$meta['site_name'];?>">FRAGMENT</a>
					<ul class="nav navbar-nav">
<?
						foreach($meta['controllers'] as $index => $cont):
?>
						<li class="<?=$this->params->controller == strtolower($cont) ? 'active' : '';?>">
							<a href="/admin/<?= Inflector::tableize($cont); ?>" title="<?=$cont;?>"><?=$cont;?></a>
						</li>
<?
						endforeach;
?>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li><a href="/logout" title="Logout">Logout</a></li>
						</ul>
					</ul>
				</nav>
			</div>
		</div>
		<div class="container">
			<?php echo $this->Session->flash(); ?>
			<div id="view" class="clrafter">
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
		<div class="footer">
			<div class="container">
				
			</div>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
