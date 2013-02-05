<?php if(Configure::read('debug') >=2): App::import('Vendor','Phperror'); ?>

<nav id="debug2_widget_nav">
	<a href="#" class="debug2_btn">Top</a>
	<a href="#debug2" class="debug2_btn">Debug</a>
</nav>

<div id="debug2">

	<nav class="tabs_links" data-identifier="debug2_content">
		<a href="#infos">Infos</a>
		<a href="#sessions">Sessions</a>
		<a href="#request_data">Request data</a>
		<a href="#request_params">Request params</a>
		<a href="#sql_dump">Sql Dump</a>
	</nav>

	<div id="debug2_content" class="tabs_content">
		<section id="infos">
			<h2>Informations serveur</h2>
			Version de php : <?php echo phpversion(); ?>
			<?php 8//\php_error\reportErrors(); ?>
		</section>
		
		<section id="sessions">
			<h2>Sessions</h2>
			<?php debug($this->Session->read()); ?>
			<?php 
			$data = extract($this->Session->read());
			//$data = extract($data);
			//debug($data);
			?>
		</section>
		
		<section id="request_data">
			<h2>Request data</h2>
			<?php debug($this->request->data); ?>
		</section>
		
		<section id="request_params">
			<h2>Request params</h2>
			<?php $data = extract($this->request->params); ?>
			<h4>Controller :</h4> <?php echo $controller; ?><br>
			<h4>Action :</h4> <?php echo $action; ?><br>
			<h4>Plugin :</h4> <?php echo $plugin; ?>
			<hr>

			<h4>Named :</h4> <?php debug($named); ?><br>

			<h4>Pass :</h4>
				<?php foreach($pass as $k=>$v): ?><br>
				<?php echo $k.' => '.$v; ?><br>
				<?php endforeach; ?>

			<br>

			<h4>Models :</h4> <?php debug($models); ?>

			<br>
			<h2>Origin</h2>
			<?php debug($this->request->params); ?>
		</section>
		
		<section id="sql_dump">
			<h2>Sql Dump</h2>
			<?php echo $this->element('sql_dump'); ?>
		</section>
	</div>
</div>

<?php echo $this->Html->css('Cakeboot.debug2'); ?>
<?php echo $this->Html->script('Cakeboot.debug2'); ?>
<?php endif; ?>