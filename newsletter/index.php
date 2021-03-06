<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	
	include('../loader.php');
	action_headers();
	include(HCLANG.'/public/news.php');
	
	if(!isset($_SESSION['hc_trailn'])){$_SESSION['hc_trailn'] = array();}

	$nID = (isset($_GET['n'])) ? cIn(strip_tags($_GET['n'])) : '';

	$result = doQuery("SELECT Subject, SentDate, ArchiveContents FROM " . HC_TblPrefix . "newsletters WHERE md5(PkID) = ? AND Status > 0", array($nID));
	if(hasRows($result)){
		if(!preg_match($hc_cfg[85],$_SERVER['HTTP_USER_AGENT']) && !in_array($nID,$_SESSION['hc_trailn'])){
			array_push($_SESSION['hc_trailn'], $nID);
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET ArchViews = ArchViews + 1 WHERE md5(PkID) = ?", array($nID));}
		
		echo '<!doctype html>
<html lang="en">
<head>
	<meta charset="'.$hc_lang_config['CharSet'].'">
	'.(($hc_cfg[7] == 1) ? '<meta name="robots" content="all, index, follow" />' : '<meta name="robots" content="noindex, nofollow" />').'
	<meta http-equiv="expires" content="never" />
	<link rel="shortcut icon" href="'.CalRoot.'/favicon.ico">
	<meta http-equiv="generator" content="Helios Calendar ' . $hc_cfg[49] . '" /> <!-- leave this for stats -->
	<meta name="description" content="' . CalName . ' ' . $hc_lang_news['MetaDesc'] . ' ' . stampToDate(hc_mysql_result($result,0,1), $hc_cfg[24]) . '" />
	<title>' . html_entity_decode(cOut(hc_mysql_result($result,0,0))) . '</title>
</head>
	'.(!stristr(hc_mysql_result($result,0,2),'<body') ? '
<body>
'.hc_mysql_result($result,0,2).'
</body>' : hc_mysql_result($result,0,2)).'
</html>';
	} else {
		header("Location: " . CalRoot);
	}
?>