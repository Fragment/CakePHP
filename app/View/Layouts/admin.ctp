<?
	/**==============================================
	*	Site Meta Info
	*==============================================*/
	$meta = array(
		'site_name' => 'CakePHP',
		'controllers' => App::objects('Controller')
	);
	
	foreach($meta['controllers'] as $i => $c)
		$meta['controllers'][$i] = str_replace('Controller', '', $c);

	array_shift($meta['controllers']);
	
	/**==============================================
	*	Links to exclude from navigation.
	*==============================================*/
	$meta['disable'] = array(
		'ex. Applications'
	);
	
	foreach($meta['disable'] as $q) {
		$i = array_search($q, $meta['controllers']);
		if($i > 0) 
			array_splice($meta['controllers'], $i, 1);
		else if($i === 0)
			array_shift($meta['controllers']);
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
			echo $this->Html->css('/js/cakejax');
?>
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?
			echo $this->Html->script('cakejax');
			echo $this->Html->script('app');
			echo $this->Html->script('admin');

			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
			echo $this->Html->script('ckeditor/ckeditor');
?>
</head>
<body>
	<div id="container">
		<div id="header" class="clrafter">
			<div id="logo"><a href="/"><?=$meta['site_name'];?></a></div>
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
				<nav class="navbar">
					<div class="navbar-inner shiny">
					<ul class="nav">
<?
						foreach($meta['controllers'] as $index => $cont):
?>
						<li>
							<a href="/admin/<?= Inflector::tableize($cont); ?>" title="<?=$cont;?>"><?=$cont;?></a>
							<!-- <ul class="<?=($this->params['controller'] == $cont) ? 'current' : '';?>">
								<li>
									<a href="/admin/<?=strtolower($cont);?>/add" title="New <?=$cont;?>">
										+ New <?=$cont;?></a>
								</li>
							</ul> -->
						</li>
<?
						endforeach;
?>
						<li class="divider-vertical"></li>
						<li><a href="/logout" title="Logout">Logout</a></li>
					</ul>
				</nav>
			<div id="view" class="shiny clrafter flex">
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
		<div id="footer" class="clr">
			
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
