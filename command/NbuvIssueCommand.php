<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';
require_once MER_SITE . '/libs/createzip/CreateZipFile.inc.php';


class NbuvIssueCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class NbuvIssueCommand->doExecute(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$issue_id = $request->getProperty('issue_id');

		$this->createFile($issue_id);
		
		
		$req = \mer\base\ApplicationFactory::instance()->getRequestConstructor();
		$req->bindParam('cmd', 'edit_issue');
		$req->bindParam('issue_id', $issue_id);
		
		$this->forward($req);
	}
	
	
	
	protected function createFile($issue_id)
	{
		$issue = \mer\base\ApplicationFactory::instance()->getDomain('Issue');
		if (is_null($issue->selectId($issue_id)))
			$this->pushMultilangError(MER_ERROR_ISSUE_NOT_FOUND);
		
		$content = \mer\base\ApplicationFactory::instance()->getCollection('Content');
		$res = $content->getContent($issue_id);
		
		if (is_null($res))
			return MER_CMD_RESP_ERROR;		
				
		$text = 'Механiзм регулювання економiки, ' . $issue->get('year') . ', № ' . $issue->get('issue');
		
		if ($issue->get('suffix'))
			$text .= "'" . $issue->get('suffix') . "\n";
		
		$text .= "\n[Титул] \n[Зміст] ???-???\n";
		
		mb_internal_encoding();		
		//setlocale (LC_ALL, array("uk_UA.UTF-8", "ru_RU.UTF-8"));
		
		$createZip = new \CreateZipFile;
		$createZip->addDirectory('JRN');
		$createZip->addDirectory('JRN/PDF');
		
		$num = 3;
		
		foreach ($content as $item)
		{
			if ($item instanceof \mer\domain\Article)  
			{
				$author = $item->get('authors_ua');
				$author = mb_ereg_replace('( )+ ', ' ', $author);
				$author = mb_ereg_replace('( )+,',',', $author);
				$author = mb_ereg_replace("([^\.\,\s]+)\.\s*([^\.\,\s]+)\.\s*([^\.\,\s]+)", "\\3 \\1. \\2.", $author);
				$author = mb_ereg_replace("([^\.\,\s]+)\.([^\.\,\s]+)\.", "\\1. \\2.", $author);
				$author = mb_convert_case($author, MB_CASE_TITLE, "UTF-8") . ' ';
				
				$text .= $author;
				$text .= '[' . $item->get('caption_ua') . '] ';
				
				if ($item->get('language') == 'en')
					$text .= '{' . $item->get('caption_en') . '} ';
					
				if ($item->get('page_from'))
					$text .= $item->get('page_from') . '-';
					
				if ($item->get('page_to'))
					$text .= $item->get('page_to') . "\n";
					
				if ($item->get('link') && file_exists($item->get('link')))
				{
					$fileContents = file_get_contents($item->get('link'));
					$createZip->addFile($fileContents, "JRN/PDF/$num.pdf");
					$num++;
				}
			}
		}
			
		$createZip->addFile($text, "JRN/zmist.txt");
		
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=mer_$issue_id.zip");
		
		echo $createZip->getZippedfile();
		
		exit();
		
	}
	
	
	protected function ucwords_additional($str)
	{
   
		return $str; 
	}
	
	protected function strotolower_additional($str)
	{
		$small = array('а','б','в','г','д','е','ё','ж','з','и','й',
					'к','л','м','н','о','п','р','с','т','у','ф',
					'х','ч','ц','ш','щ','э','ю','я','ы','ъ','ь',
					'э', 'ю', 'я', 'є', 'і', 'ї');
		$large = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й',
					'К','Л','М','Н','О','П','Р','С','Т','У','Ф',
					'Х','Ч','Ц','Ш','Щ','Э','Ю','Я','Ы','Ъ','Ь',
					'Э', 'Ю', 'Я', 'Є', 'І', 'Ї');
		return str_replace($large, $small, $str);
	}
	
}

