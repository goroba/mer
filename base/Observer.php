<?php
namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

interface Observer
{
	function update(\mer\base\Observable $observable);
}
