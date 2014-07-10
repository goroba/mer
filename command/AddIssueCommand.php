<?php

namespace mer\command;

defined ('MER_EXISTS') or die('Illegal Access!!!');

require_once MER_SITE . '/command/ActionCommand.php';

class AddIssueCommand extends ActionCommand
{
	protected function doExecute()
	{
		//\mer\base\SessionRegistry::instance()->pushSysMessage("class LoginCommand->AddissueCommand(): executed.");
		$request = \mer\base\RequestRegistry::instance()->getRequest();
		
		$year 			= $request->getProperty('year');
		$issue 			= $request->getProperty('issue');
		$suffix_util 	= $request->getProperty('suffix_util');
		$suffix 		= trim($request->getProperty('suffix'));
		
		if (!is_numeric($year))		return $this->setError('Год должен быть числом.');
		$year = intval($year);
		
		if ($year < 1990) 			return $this->setError('Год не может быт ранее 1990 г.');
		if ($year > date('Y')) 		return $this->setError('Год не может быт позднее текущего.');
		
		if (!is_numeric($issue))	return $this->setError('Номер журнала должен быть числом.');
		$issue = intval($issue);
		
		if ($issue < 1)				return $this->setError('Неверно указан номер журнала (минимум 1).');
		if ($issue > MER_ISSUE_MAX)	return $this->setError('Номер журнала больше его периодичности.');
		
		$suff = '';
		if ($suffix_util == '1')
		{
			if (!preg_match("/^[0-9A-Za-z]$/", $suffix))
				return $this->setError('Суффиксом может являться только одна цифра и одна буква латинского алфавита.');
			$suff = $suffix;
		}
		
		$issue_obj = \mer\base\ApplicationFactory::instance()->getDomain('Issue');
		
		$issue_obj->setArray(array(
			'id' => null,
			'year' => $year,
			'issue' => $issue,
			'suffix' => $suffix
		));
		
		$mapper = \mer\base\ApplicationFactory::instance()->getMapper('Issue');
		
		if ($mapper->exists($issue_obj) > 0) return $this->setError('Номер журнала уже существует.');
		
		$mapper->insert($issue_obj);
		
		\mer\base\SessionRegistry::instance()->pushMessage('Новый номер журнала добален.');
		
		$this->forward();
	}
}