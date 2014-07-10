<?php

namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

abstract class Registry 
{
	abstract protected function get($key);
	abstract protected function set($key, $val);
}