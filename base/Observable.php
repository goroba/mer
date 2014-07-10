<?php
namespace mer\base;

defined ('MER_EXISTS') or die('Illegal Access!!!');

interface Observable
{
	function attach(\mer\base\Observer $observer);
	function detach(\mer\base\Observer $observer);
	function notify();
}
