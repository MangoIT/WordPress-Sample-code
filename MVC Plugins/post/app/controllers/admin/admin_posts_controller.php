<?php

class AdminPostsController extends MvcAdminController {
	
	var $default_columns = array('name');
	function add() {

		$collection = $this->Post->paginate(array(
		'selects' => array('Post.ID', 'Post.post_title', 'Post.post_date'),
		'conditions' => array(
			'Post.post_content !=' => '',
			'Post.post_type' => 'post'
			),
		'order' => 'Post.ID',
		'page' => $this->params['page'],
		'per_page' => 5
		));
             $this->set('posts', $collection['objects']);
             $this->set_pagination($collection);
	}
}

?>
