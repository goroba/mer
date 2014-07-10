<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

class LanguageDecorator extends CommandLoaderDecorator
{

	function doProcess()
	{
		$lang = \mer\base\RequestRegistry::getRequest()->getProperty('lang');
		if (!isset($lang) || is_null($lang))
		{
			
			$lang = \mer\base\SessionRegistry::instance()->getValue('lang');
			if (!isset($lang) || is_null($lang))
			{
				$lang = @$_COOKIE['lang'];
				if (!isset($lang) || is_null($lang))
				{
					$this->setLang(MER_DEFAULT_LANGUAGE);
				}
				else
				{
					\mer\base\SessionRegistry::instance()->setValue('lang', $lang);
				}
			}
		}
		else 
		{
			$this->setLang($lang);		
		}
		
		if ($lang != 'ru' && $lang != 'ua' && $lang != 'en')
		{
			$this->setLang(MER_DEFAULT_LANGUAGE);
		}
	}
	
	function setLang($lang)
	{
		\mer\base\SessionRegistry::instance()->setValue('lang', $lang);
		setcookie('lang', $lang, time()+3600*24*365);
	}

}