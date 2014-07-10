<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/Command.php';

abstract class ActionCommand extends Command
{

	protected function setError($message)
	{
		\mer\base\SessionRegistry::instance()->pushError($message);
		$this->forward();
	}
}