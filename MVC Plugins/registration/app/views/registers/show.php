<h2><?php echo strtoupper($object->__name); ?></h2>
<ul>
<LI><?php echo $object->username; ?></LI>
<LI><?php echo $object->contactno; ?></LI>
<LI><?php echo $object->email; ?></LI>
<LI><?php echo $object->city; ?></LI>
</ul>
<p>
	<?php echo $this->html->link('&#8592; All Registers', array('controller' => 'registers')); ?>
</p>