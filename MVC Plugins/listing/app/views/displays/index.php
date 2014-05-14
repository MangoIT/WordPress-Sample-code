<h2>Countries</h2>
<ul>
<?php foreach ($objects as $obj): ?>
	<li>
		<?php 
			if(isset($obj->id)){
			echo $this->html->object_link($object, array('controller' => 'displays/1?country='.urlencode($obj->name).'&', 'text' => $obj->name));
			}
		?>
	</li>
<?php endforeach; ?>
</ul>
<?php echo $this->pagination(); ?>

