<h1>Blog posts</h1>

<table width="100%">
<tr>
<th width="10%"><a href="<?php echo $_SERVER["REQUEST_URI"].'?&ord='.$order.'&field=ID'; ?>">ID</a></th>
<th width="30%"><a href="<?php echo $_SERVER["REQUEST_URI"].'?&ord='.$order.'&field=post_title'; ?>">Title</th>
</tr>

<!-- Here is where we loop through our $posts array, printing out post info -->
<?php foreach ($posts as $post):?>
<tr>
<td><?php echo $post->ID;?></td>
<td>
<?php echo $this->html->link($post->post_title,array('controller' => 'posts', 'action' =>'view','id' => $post->ID));

/*<a href="<?php echo mvc_public_url(array('controller' => 'posts','action' => 'view','id' => $post->ID)); ?>"></a>*/
?>
</td>
</tr>
<?php endforeach; ?>

     <?php echo $this->html->link('Add Post', array('controller' => 'posts', 'action' => 'add/0')); ?>

</table>
<?php echo $this->pagination()."<br>" ?>
