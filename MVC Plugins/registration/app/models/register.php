<?php

class Register extends MvcModel {

	var $display_field = 'username';
var $name = 'Register';
var $validate = array(
'title' => array(
'rule' => 'notEmpty'
),
'body' => array(
'rule' => 'notEmpty'
)
);	
}

?>