<?php
namespace mer\settings;

defined ('MER_EXISTS') or die('Illegal Access!!!');

define ("MER_TESTMODE", true);

//--- Folder settings ---//
define ("MER_SITE", ''); // $_SERVER['DOCUMENT_ROOT']);
define ("MER_COMMAND_FOLDER", MER_SITE . 'command');
define ("MER_MAPPER_FOLDER", MER_SITE . 'mapper');
define ("MER_DOMAIN_FOLDER", MER_SITE . 'domain');
define ("MER_SETTING_FOLDER", MER_SITE . 'settings');
define ("MER_VIEW_FOLDER", MER_SITE . 'view');
define ("MER_RSS_FOLDER", MER_SITE . 'content/rss/');
define ("MER_ARTICLE_FOLDER", MER_SITE . 'content/acticles/');
define ("MER_NEWS_FOLDER", MER_SITE . 'content/news/');

//--- Language settings ---//
define ("MER_DEFAULT_LANGUAGE", 'ru');

//--- Database settings ---//
define ("MER_DB_HOST", 'localhost');
define ("MER_DB_NAME", 'webfemmer');
define ("MER_DB_USER", 'webfemmer');
define ("MER_DB_PASSWORD", 'BDg1oE3vUo');
//define ("MER_DB_OPT", array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC));

//--- Regular expressions ---//
define ("MER_REGEXP_LOGIN", '/^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$/');
define ("MER_REGEXP_PASSWORD", '/(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*/');
define ("MER_REGEXP_EMAIL", '/^([0-9a-zA-Z]([-.w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-w]*[0-9a-zA-Z].)+[a-zA-Z]{2,9})$/si');
define('MER_REGEXP_KEYWORDS', "/^[^:]*:(.*)\.\s*/");


//--- Session settings ---//
define ("MER_SESS_ACCESS_MANAGER", 'acces_manager');


//--- Command response settings ---//
define("MER_CMD_RESP_ERROR", 0);
define("MER_CMD_RESP_DEFAULT", 1);
define("MER_CMD_RESP_OK", 2);
define("MER_CMD_RESP_NEED_COMP", 3);


//--- Journal settings ---//
define("MER_ISSUE_MAX", 12);
define("MER_RSS_PER_PAGE", 20);
define("MER_NEWS_PER_PAGE", 2);



class Settings
{
	public static $Messages = array (
		'MER_MESSAGE_LOGIN_SUCCESS'	=> array (
												'ru'	=> 'Вход успешный.',
												'ua'	=> 'Вхід успішний.',
												'en'	=> 'Login is successfull.'
		)
	);
	
	public static $Errors = array (
		'MER_SYSTEM_ERROR'						=> array (
												'ru'	=> 'Системная ошибка.',
												'ua'	=> 'Системна помилка.',
												'en'	=> 'System error.'		
		),
		'MER_DB_CONNECT_ERROR'						=> array (
												'ru'	=> 'Ошибка подключения к базе данных.',
												'ua'	=> 'Помилка підключення до бази даних.',
												'en'	=> 'Error while connecting to database.'		
		),		
		'MER_ERROR_LOGIN_INCORRECT_USER'		=> array (
												'ru'	=> 'Неверное имя пользователя.',
												'ua'	=> 'Невірне і\'мя користувача.',
												'en'	=> 'Incorrect user name.'
		),
		'MER_ERROR_LOGIN_INCORRECT_PASSWORD'	=> array (
												'ru'	=> 'Неверный пароль.',
												'ua'	=> 'Невірний пароль.',
												'en'	=> 'Incorrect password.'
		),
		
		'MER_ERROR_ACCESS_DENIED'				=> array (
												'ru'	=> 'У Вас недостаточно прав для посещения данной страницы.',
												'ua'	=> 'У Вас недостатньоо прав для відвідання даної сторінки.',
												'en'	=> 'User is not allowed to visit the page.'
		),
		
		'MER_ERROR_ISSUE_NOT_FOUND'				=> array (
												'ru'	=> 'Номер журнала не найден.',
												'ua'	=> 'Номер журналу не знайдено.',
												'en'	=> 'Issue not found.'
		),

		'MER_ERROR_ARTICLE_NOT_FOUND'			=> array (
												'ru'	=> 'Статья не найден.',
												'ua'	=> 'Стаття не знайдена.',
												'en'	=> 'Article not found.'
		),

		'MER_ERROR_TEMPLATE'					=> array (
												'ru'	=> '.',
												'ua'	=> '.',
												'en'	=> '.'
		)		
	);
}