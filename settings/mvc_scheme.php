<?php

namespace mer\settings;

defined ('MER_EXISTS') or die('Illegal Access!!!');

define ("MER_DEFAULT_VIEW_PREFIX", 'main');


class ControllerMap
{
	public static $CmdMap = array
	(
		'default'			=> array (
									'command'		=> 'Default',
									'view'			=> 'main'
		),
		
		'login'				=> array (
									'command'		=> 'Login',
									'view'			=> '-1'
		),

		'logout'			=> array (
									'command'		=> 'Logout',
									'view'			=> '-1'
		),

		'enable_mobile'		=> array (
									'command'		=> 'EnableMobile',
									'view'			=> 'main'
		),
		
		'disable_mobile'	=> array (
									'command'		=> 'DisableMobile',
									'view'			=> 'main'
		),
		
		'manager'			=> array (
									'command'		=> 'Manager,EnumIssues'
		),
		
		'admin'				=> array (
									'command'		=> 'Admin'
		),
		
		'new_issue'			=> array (
									'command'		=> 'Manager'
		),
		
		'edit_issue'		=> array (
									'command'		=> 'Manager,EditIssue',
									'params_needed'	=> 'issue_id'
		),
		
		'access_issue'		=> array (
									'command'		=> 'Manager, AccessIssue',
									'params_needed'	=> 'issue_id, access',
									'forward'		=> 'edit_issue'
		),
		
		'nbuv_issue'		=> array (
									'command'		=> 'Manager, NbuvIssue',
									'params_needed'	=> 'issue_id',
									'forward'		=> '-1'
		),
		
		'add_issue'			=> array (
									'command'		=> 'Manager,AddIssue',
									'forward'		=> 'manager'
		),
		
		'delete_issue'		=>	array (
									'command'		=> 'Manager,DeleteIssue',
									'params_needed'	=> 'issue_id',
									'forward'		=> 'manager'
		),

		'edit_section'		=> array (
									'command'		=> 'Manager,EditSection'
		),

		'add_section'		=> array (
									'command'		=> 'Manager,AddSection',
									'forward'		=> 'edit_issue'
		),
		
		'delete_section'	=> array (
									'command'		=> 'Manager,DeleteSection',
									'params_needed'	=> 'issue_id,section_id',
									'forward'		=> 'edit_issue'									
		), 
		
		'edit_article'		=> array (
									'command'		=> 'Manager,EditArticle',
									'params_needed'	=> 'action'
		),

		'add_article'		=> array (
									'command'		=> 'Manager,AddArticle',
									'forward'		=> 'edit_issue',
									'params_needed'	=> 'action'
		),
		
		'delete_article'	=> array (
									'command'		=> 'Manager,DeleteArticle',
									'params_needed'	=> 'issue_id,article_id',
									'forward'		=> 'edit_issue'									
		),

		'move_content'		=> array (
									'command'		=> 'Manager,MoveContent',
									'params_needed'	=> 'issue_id,number,delta',
									'forward'		=> 'edit_issue'									
		),
		
		'rss'				=> array (
									'command'		=> 'Manager,EnumRSS'
		),

		'edit_rss'			=> array (
									'command'		=> 'Manager,EditRSS',
									'params_needed'	=> 'action'									
		),

		'add_rss'			=> array (
									'command'		=> 'Manager,AddRSS',
									'forward'		=> 'rss',
									'params_needed'	=> 'action'
		),
		
		'move_rss'		=> array (
									'command'		=> 'Manager,MoveRSS',
									'params_needed'	=> 'number,delta',
									'forward'		=> 'rss'									
		),		

		'delete_rss'		=> array (
									'command'		=> 'Manager,DeleteRSS',
									'params_needed'	=> 'id',
									'forward'		=> 'rss'									
		),
		
		'enum_news'			=> array (
									'command'		=> 'Manager,EnumNews',
									'view'			=> 'enum_news'
		),
	
		'news'				=> array (
									'command'		=> 'EnumNews',
									'view'			=> 'news',
									'title_ua'		=> 'Новини',
									'title_en'		=> 'News',
									'title_ru'		=> 'Новости'
		),
		
		'edit_news'			=> array (
									'command'		=> 'Manager,EditNews',
									'params_needed'	=> 'action'									
		),

		'add_news'			=> array (
									'command'		=> 'Manager,AddNews',
									'forward'		=> 'enum_news',
									'params_needed'	=> 'action'
		),
		
		'move_news'			=> array (
									'command'		=> 'Manager,MoveNews',
									'params_needed'	=> 'number,delta',
									'forward'		=> 'enum_news'									
		),		

		'delete_news'		=> array (
									'command'		=> 'Manager,DeleteNews',
									'params_needed'	=> 'id',
									'forward'		=> 'enum_news'									
		),
		
		'view_news'			=> array (
									'command'		=> 'EnumNews',
									'view'			=> 'view_news',
									'title_ua'		=> 'Стрічка новин',
									'title_en'		=> 'Newsline',
									'title_ru'		=> 'Лента новостей'
		),
		
		'arch'				=> array (
									'command'		=> 'EnumIssues',
									'view'			=> 'arch'									
		),
		
		'view_issue'		=> array (
									'command'		=> 'ViewIssue',
									'view'			=> 'view_issue'
		),
		
		'view_article'		=> array (
									'command'		=> 'ViewArticle',
									'view'			=> 'view_article'		
		),
		
		'view_rss'			=> array (
									'command'		=> 'EnumRSS',
									'view'			=> 'view_rss',
									'title_ua'			=> 'Стрічка новин',
									'title_en'			=> 'Newsline',
									'title_ru'			=> 'Лента новостей'
		)

	);
	

	
}