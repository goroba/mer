<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/base/RequestRegistry.php';
require_once MER_SITE . '/base/SessionRegistry.php';
require_once MER_SITE . '/controller/Request.php';
require_once MER_SITE . '/command/DefaultCommand.php';

class CommandResolver
{
	
	function __construct()
	{
	}
	
	function checkParams()
	{
		$cmd = \mer\base\RequestRegistry::getRequest()->getProperty('cmd');
		
		if (!isset($cmd))
			return true;

		$params_needed = @\mer\settings\ControllerMap::$CmdMap[$cmd]['params_needed'];
		
		if (!isset($params_needed))
			return true;
			
		$params = explode(",", $params_needed);
		
		foreach ($params as $param)
		{
			$param = trim($param);
			
			$check = \mer\base\RequestRegistry::getRequest()->getProperty($param);
			if ( !isset($check) )
			{
				\mer\base\SessionRegistry::instance()->pushSysMessage("class CommandResolver->checkParams(): Needed parameter '$param' not found.");
				\mer\base\SessionRegistry::instance()->pushMultilangError('MER_SYSTEM_ERROR');
				return false;
			}
		}
		
		return true;
	}
	
	function getCommands()
	{
		\mer\base\RequestRegistry::getRequest()->setProperty('cmd', strtolower(\mer\base\RequestRegistry::getRequest()->getProperty('cmd')));
		$cmd = \mer\base\RequestRegistry::getRequest()->getProperty('cmd');
		
		$title_ua = @\mer\settings\ControllerMap::$CmdMap[$cmd]['title_ua'];
		$title_en = @\mer\settings\ControllerMap::$CmdMap[$cmd]['title_en'];
		$title_ru = @\mer\settings\ControllerMap::$CmdMap[$cmd]['title_ru'];
		
		if (!isset($title_ua))
			$title_ua = "Мiжнародний науковий журнал \"Механiзм регулювання економiки\"";
		else
			$title_ua = "Мiжнародний науковий журнал \"Механiзм регулювання економiки\" - " . $title_ua;
			
		if (!isset($title_en))
			$title_en = "The International Scientific Journal 'Mechanism of Economic Regulation'";
		else
			$title_en = "The International Scientific Journal 'Mechanism of Economic Regulation' - " . $title_en;
			
		if (!isset($title_ru))
			$title_ru = "Международный научный журнал \"Механизм регулирования экономики\"";
		else
			$title_ru = "Международный научный журнал \"Механизм регулирования экономики\" - " . $title_ru;
			
		$tpl = \mer\base\Templater::instance();
		$tpl->set('title_ua', $title_ua);
		$tpl->set('title_en', $title_en);
		$tpl->set('title_ru', $title_ru);
		
		if ( !isset($cmd) )
		{
			return $this->defaultCommand('class CommandResolver->getCommand(): Command undefined');
		}
		
		if ( preg_match('/\W/', $cmd) )
		{
			return $this->defaultCommand('class CommandResolver->getCommand(): inadmissible character in Command');
		}
		
		$cmd_string = @\mer\settings\ControllerMap::$CmdMap[$cmd]['command'];
		if (!isset($cmd_string))
		{
			return $this->defaultCommand("class CommandResolver->getCommand(): Command doesn't exist in ControllerMap");
		}
		
		$commands = array();
		
		$cmds = explode(",", $cmd_string);
		
		foreach ($cmds as $command)
		{
			$command = trim($command);
			
			$class = ucfirst($command . 'Command');
			$file = "command/{$class}.php";
			$class = "\\mer\\command\\" . $class;
		
			if ( !file_exists( $file ) )
			{
				return $commands[] = $this->defaultCommand("class CommandResolver->getCommand(): Command class file {$file} not exists");
			}
		
			require_once ($file);
		
			if ( !class_exists( $class ) )
			{
				return $commands[] = $this->defaultCommand("class CommandResolver->getCommand(): {$class} class not exists in Command file");
			}
				
			//\mer\base\SessionRegistry::instance()->pushSysMessage("class CommandResolver->getCommand(): its OK");		

			$commands[] = new $class();
		}
		
		return $commands;
	}
	
	function defaultCommand($message)
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage($message);
		//\mer\base\RequestRegistry::getRequest()->setProperty('cmd', 'default');
		return new DefaultCommand();
	}
	
	function getView()
	{
		$cmd = \mer\base\RequestRegistry::getRequest()->getProperty('cmd');

		$lang = \mer\base\SessionRegistry::instance()->getValue('lang');
		
		$view = @\mer\settings\ControllerMap::$CmdMap[$cmd]['view'];
		
		if (!isset($view) || is_null($view))
		{
			//\mer\base\SessionRegistry::instance()->pushSysMessage("class CommandResolver->getView(): view doesn't set in ControlMap. Trying view as command");
			$view = $cmd;
		}
		
		if ($view == '-1')
		{
			header("Location: ".$_SERVER['HTTP_REFERER']); 
			exit;
		}
		
		$file = "view/{$lang}/{$view}.html";

		if (!file_exists($file))
		{
			//\mer\base\SessionRegistry::instance()->pushSysMessage("class CommandResolver->getView(): view file \"{$file}\" for the command doesn\'t exists. Trying default language");
			
			$file = "view/" . MER_DEFAULT_LANGUAGE . "/{$view}.html";
			
			if (!file_exists($file))
			{
				//\mer\base\SessionRegistry::instance()->pushSysMessage("class CommandResolver: view for default language doesn\'t exists. Trying default command in current language");
				
				$view = $this->defaultVeiw();
				$file = "view/{$lang}/{$view}.html";
				
				if (!file_exists($file))
				{
					//\mer\base\SessionRegistry::instance()->pushSysMessage("class CommandResolver: view for default command in current language doesn\'t exists. Trying default command in default language");
					$file = "view/" . MER_DEFAULT_LANGUAGE . "/{$view}.html";					
				}
			}
		}
		
		/*
		$need_comp = @\mer\settings\ControllerMap::$CmdMap[$cmd]['need_comp'];
		if (isset($need_comp) && $need_comp == true)
		{
		}
		*/

		
		return $file;
	}
	
	function defaultVeiw()
	{
		return MER_DEFAULT_VIEW_PREFIX;
	}
	
	function isMobile()
	{
	    $phone_array = array('iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
		$agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );
		foreach ($phone_array as $value) 
		{
			if ( strpos($agent, $value) !== false ) return true;  // replace to true
		}
		return false;	// replace to false
	}
	
	function isMobile2()
	{
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
			return true;
		else
			return false;
	}
	
	function getFullView()
	{
		$is_mobile = $this->isMobile() || $this->isMobile2();
		if ($is_mobile == false)
			return MER_SITE . "/view/" . \mer\base\SessionRegistry::instance()->getValue('lang') . "/index.html";
		
		
		$mobile_enable = \mer\base\SessionRegistry::instance()->getValue('mobile_enable');
		if ( !isset($mobile_enable) )
		{
			\mer\base\SessionRegistry::instance()->setValue('mobile_enable', true);
			$mobile_enable = true;
		}
		
		if ($mobile_enable == true)
		{
			return MER_SITE . "/view/" . \mer\base\SessionRegistry::instance()->getValue('lang') . "/index_m.html";
		}
		
		return MER_SITE . "/view/" . \mer\base\SessionRegistry::instance()->getValue('lang') . "/index.html";
	}

}