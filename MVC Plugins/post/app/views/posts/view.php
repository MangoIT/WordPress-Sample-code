<?php echo $this->html->link('Edit',array('controller' => 'posts', 'action' =>'edit','id' => $post->ID));?>
<h1><?php echo $post->post_title?></h1>

<p><?php echo $post->post_content?></p>