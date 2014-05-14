<h2>Add Register</h2>

<?php echo $this->form->create($model->name); ?>
<?php echo $this->form->input('username'); ?>
<?php echo $this->form->input('email'); ?>
<?php echo $this->form->input('contactno'); ?>
<?php echo $this->form->input('city'); ?>
<?php echo $this->form->input('password'); ?>
<?php echo $this->form->end('Add'); ?>