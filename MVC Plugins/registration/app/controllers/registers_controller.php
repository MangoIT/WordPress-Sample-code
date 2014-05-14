<?php

class RegistersController extends MvcPublicController {

	public function search() {
		$th='Documentation';
		$this->set('th',$th);
	}

	public function show() {
    		$this->set_object();
  	}


}

?>