<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?= Configure::read('Site.name').' - '.$title_for_layout;?>
	</title>
<?
	$meta = array(
		'site_name' => (Configure::read('Site.name') ? Configure::read('Site.name') : 'CakePHP'),
		'controllers' => array(),
		'disable' => array('App')
	);

	foreach(App::objects('Controller') as $c) {
		$table = str_replace('Controller', '', $c);
		if(!in_array($table, $meta['disable'])) {
			$meta['controllers'][] = $table;
		}
	}

	echo $this->Html->tag('meta', null, array('name' => 'charset', 'content' => 'utf-8'));
	echo $this->Html->meta('icon');
	echo $this->Html->tag('meta', null, array('http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge'));
	echo $this->Html->tag('meta', null, array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1'));
	echo $this->Html->css(array(
		'admin.bootstrap.min',
		'admin.bootstrap-theme.min',
		'admin'
	));
	echo $this->Html->script(array(
		'modernizr-2.6.2',
		'jquery.1.10.1.min',
		'admin.bootstrap.min'
	));
	// echo $this->Html->script('ckeditor/ckeditor');
?>
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
	<div class="navbar navbar-inverse" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/"><?= $meta['site_name']; ?></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="/admin/users"><span class="glyphicon glyphicon-user"></span> Users</a></li>
					<li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
					<li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				</ul>
				<form class="navbar-form navbar-right">
					<!-- <input type="text" class="form-control" placeholder="Search..."> -->
				</form>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
<?
				echo $this->fetch('sidebar-left');
?>
				<hr>
				<h4 class="lead">Tables</h4>
				<ul class="nav nav-pills nav-stacked">
<?
					foreach($meta['controllers'] as $idx => $name):
?>
					<li class="<?= ($this->params->controller === Inflector::tableize($name)) ? 'active' : ''; ?>"><a href="/admin/<?= Inflector::tableize($name); ?>"><?= $name; ?></a></li>
<?
					endforeach;
?>
				</ul>
			</div>
			<div class="col-sm-9 col-md-10 main">
				<div class="table-responsive">
					<?php echo $this->Session->flash(); ?>
					<?php echo $this->fetch('content'); ?>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
