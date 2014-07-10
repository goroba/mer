<?php

namespace mer;

header("Content-Type: text/html; charset=utf-8");

define ('MER_EXISTS', true);

session_start();

//--- Settings ---//
require_once 'settings/settings.php';
require_once 'settings/mvc_scheme.php';


if (MER_TESTMODE)
{
	ini_set('display_errors',1);
	error_reporting(E_ALL);
}

require_once('base/AccessManager.php');
require_once('domain/User.php');

//--- Base ---//
require_once 'base/ApplicationFactory.php';
require_once 'base/AccessManager.php';

//--- Domain model ---//
require_once 'domain/DomainObject.php';
require_once 'domain/User.php';

//--- Command Loader Decorator ---//
require_once 'command/loader/CommandLoader.php';
require_once 'command/loader/CommandLoaderDecorator.php';
require_once 'command/loader/AdminDecorator.php';
require_once 'command/loader/LanguageDecorator.php';
require_once 'command/loader/AuthenticateDecorator.php';

//--- Templater ---//
require_once 'base/Templater.php';


require_once 'controller/FrontController.php';


\mer\controller\FrontController::run();

//\mer\base\SessionRegistry::instance()->pushSysMessage("");
//$tpl = \mer\base\Templater::instance();