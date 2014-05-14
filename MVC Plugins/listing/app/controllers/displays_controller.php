<?php

class displaysController extends MvcPublicController {
    public function show(){
	$country=urldecode($_REQUEST['country']);
	$object=$this->display->find(array(
	   'selects' => array('cities.name','Display.id'),
   	   'joins' => array('table' => 'cities',
           'on' => 'Display.id = cities.country_id ',
           'type' => 'LEFT JOIN' ),
   	   'conditions' => array('Display.name'=>$country) //or sql string
	));
	$this->set('object', $object);
    }
	
}

?>