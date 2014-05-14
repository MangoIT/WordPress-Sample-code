<h2>Registers</h2>
<?php foreach ($objects as $object): ?>

	<?php $this->render_view('_item', array('locals' => array('object' => $object))); ?>

<?php endforeach; ?>
<h2>Add Register</h2>

<?php echo $this->Form->create($model->name); ?>
<?php echo $this->Form->input('username'); ?>
<?php echo $this->Form->input('email'); ?>
<?php echo $this->Form->input('contactno'); ?>
<?php echo $this->Form->input('city'); ?>
<?php echo $this->Form->input('password'); ?>
<?php echo $this->Form->end('Add'); ?>
<?php/* echo $this->pagination(); ?>
<?php // echo $this->form->create($model->name); ?>
<?php echo $this->form->input('username'); ?>
<?php echo $this->form->input('email'); ?>
<?php echo $this->form->input('contactno'); ?>
<?php echo $this->form->input('city'); ?>
<?php echo $this->form->input('password'); ?>
<?php echo $this->form->end('Add'); ?>
<?php
echo $tes;*/
?>