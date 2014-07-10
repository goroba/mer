<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

abstract class CommandLoaderDecorator extends CommandLoader
{
	protected $loader;
	
	function __construct(\mer\command\CommandLoader $loader)
	{
		$this->loader = $loader;
	}
	
	final function process()
	{
		$this->doProcess();
		$this->loader->process();
	}
	
	abstract function doProcess();
}