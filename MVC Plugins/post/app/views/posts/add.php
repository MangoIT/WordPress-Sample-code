<h1>Add Post</h1>
<?php echo $message; ?>
<form action="<?php echo mvc_public_url(array('controller' => 'posts', 'action' => 'add/0')); ?>" method="post">
	<lable>Title</lable><input type="text" name="title" value="" /><br>
	<lable>Content</lable><textarea name="content"></textarea><br>
	<input type="submit" value="Submit" />
</form>
<?php echo $this->html->link('All Posts',array('controller' => 'posts', 'action' =>'index'));?>
<?php/*
echo $this->Form->create('Post');
echo $this->Form->input('post_title');
echo $this->Form->input('post_content', array('rows' => '3'));
echo $this->Form->end('Save Post');
*/?>