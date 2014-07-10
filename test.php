<?php
header("Content-Type: text/html; charset=utf-8");



$author = "Мельник Л.Г., Д. В. Горобченко";

	$author = mb_ereg_replace("([a-zа-яієїґA-ZА-ЯІЄЇҐ]+)\.\s*([a-zа-яієїґA-ZА-ЯІЄЇҐ]+)\.\s+([a-zа-яієїґA-ZА-ЯІЄЇҐ]+)", "\\3 \\1. \\2.", $author);
	$author = mb_ereg_replace("([a-zа-яієїґA-ZА-ЯІЄЇҐ]+)\.([a-zа-яієїґA-ZА-ЯІЄЇҐ]+)\.", "\\1. \\2.", $author);

echo $author;

//echo md5('trpbcntywsz') . "\n\t";

/*
$text = "\$\$lang\$\$";
$variable = "tpl";
$text = preg_replace("/\\$\\$([_a-zA-Z0-9]+)\\$\\$/", "\$".$variable."->get('\$1')", $text);
echo $text;
*/
/*
	define('MER_REGEXP_KEYWORDS', "/^[^:]*:(.*)\.\s/");
	
	$text = "   Ключові слова : фіскальне регулювання , фіск.альна віддача, водні ресурси, орендна плата, місцеві бюджети.  ";
	if (preg_match(MER_REGEXP_KEYWORDS, $text, $keywords))
	{
		$keywords_array = explode(',', $keywords[1]);
		$keywords_array = array_map('trim', $keywords_array);
	}
	else
		$keywords_array = null;
	var_dump($keywords_array);
	
*/
	/*
	$authors_en = "  V. A. Golyan, V. M. Bardas', R. V. Busel  ";
	$caption_en = " Fiscal@regulation of water use in the region: institutional principles and ways of improvement ";
	preg_match_all("/([\w]{2,})/", $authors_en, $authors);
	$caption = implode('_', $authors[0]) . "_";
	$caption .= preg_replace("/[\W]+/", "_", trim($caption_en));
	if (strlen($caption) > 128)
		$caption = substr($caption, 0, 128);
	var_dump($caption);
	*/
	
	/*
	$path = "asd/qwe/file.txt";
	
	$dir_arr = explode ("/", $path);
	unset($dir_arr[count($dir_arr)-1]);
	
	$directory = '';
	foreach ($dir_arr as $dir)
	{
		$directory .= $dir . "/";
		if (!file_exists($directory))
			mkdir($directory);
	}
	*/
	
?>
