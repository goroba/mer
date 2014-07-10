<?php

namespace mer\domain;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/domain/DomainObject.php';

class Content extends DomainObject
{
	function __construct($array = null)
	{
		parent::__construct($array);
	}
	
}