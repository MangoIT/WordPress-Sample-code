<?php

class PostsController extends MvcPublicController{
	var $helpers = array ('Html','Form');
	var $name = 'Posts';
	function index() {
		$ord=$_GET['ord'];
		$field=$_GET['field'];
		
		if($ord=='asc')
			$this->set('order','desc');
		else
			$this->set('order','asc');
		
		if($ord=='' || $field==''){
			$ord='asc';
			$field='ID';
		}
		
		$collection = $this->Post->paginate(array(
		'selects' => array('Post.ID', 'Post.post_title', 'Post.post_date'),
		'conditions' => array(
			'Post.post_content !=' => '',
			'Post.post_type' => 'post'
			),
		'order' => 'Post.'.$field.' '.$ord,
		'page' => $this->params['page'],
		'per_page' => 5
		));

		$this->set('posts', $collection['objects']);
		$this->set_pagination($collection);
	}
	public function view($id = null) {
		$this->set('post', $this->Post->find_by_id($this->params['id']));
	}

	function add() {
		if (!empty($this->params['title']) && !empty($this->params['content'])) {
			if ($this->Post->create(array(
				'post_title'=>$this->params['title'],
				'post_content'=>$this->params['content']
				))) {
				$this->set('message','Your post has been saved.');
				//$id = $this->Post->insert_id;
				//$this->redirect(mvc_public_url(array('controller' => 'posts', 'action' => 'edit','id'=>$id)));
			}
		}

	}

	function edit($id = null) {
		//$this->Post->id = $id;
		if (empty($this->params['ed_title']) && empty($this->params['ed_content'])) {
			$this->set('post', $this->Post->find_by_id($this->params['id']));

		}
		else {
			$this->Post->update($this->params['id'],array(
				'post_title'=>$this->params['ed_title'],
				'post_content'=>$this->params['ed_content']
			));
			$this->set('post', $this->Post->find_by_id($this->params['id']));
			$this->set('message','Your post has been updated.');
			//$this->redirect(mvc_public_url(array('controller' => 'posts', 'action' =>'index')));
		}
	}
}

?>