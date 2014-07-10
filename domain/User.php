<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

class User extends DomainObject
{
 	protected static $mapper;
	
	protected $columns = array(
		'id' => null,
		'login' => null,
		'password' => null,
		'status' => null,
		'first_name' => null,
		'last_name' => null,
		'affiliations' => null,
		'address' => null,
		'email' => null,
		'tel' => null,
		'singin_date' => null
	);
	
	function __construct($array = null)
	{
		parent::__construct($array);
	}

	function getLogin()
	{
		return $this->get('login');
	}
	
	function getPassword()
	{
		return $this->get('password');
	}	
}