<?php echo $this->Form->create(false); ?>

<fieldset>

	<legend><?php echo __d('webmaster', "Robots file"); ?>

	<?php
	echo $this->Form->input(
		'robots',
		array('label' => false, 'type' => 'textarea')
	);

	if (!$sitemap) {
		$help = $this->Html->link(
			__d('webmaster', "help?"),
			'http://www.webmasterworld.com/robots_txt/3310622.htm',
			array('target' => '_blank')
		);

		echo $this->Form->input(
			'sitemap',
			array(
				'label' => __d('webmaster', "Include sitemap (%s)", $help),
				'type' => 'checkbox',
				'default' => true
		));
	}
	?>

	<div class="form-actions submit">

		<?php
		echo $this->Form->submit(
			__d('webmaster', "Save changes"),
			array('div' => false, 'class' => 'btn btn-primary')
		);
		?>

	</div>

</fieldset>

<?php echo $this->Form->end(); ?>
