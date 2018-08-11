<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('isHC',true);
	define('isAction',true);
	include_once('../loader.php');
	include_once(HCLANG.'/public/rss.php');
	
	$lQuery = $cQuery = $catIDs = $cityNames = '';
	$tzRSS = str_replace(':','',HCTZ);
	
	if(isset($_GET['l'])){
		$catIDs = array_filter(explode(',',$_GET['l']),'is_numeric');
		$cats = (count($catIDs) > 0) ? cIn(implode(',',$catIDs)) : '0';
		$lQuery = " AND c.PkID IN (".$cats.")";
	}
	if(isset($_GET['c'])){
		$cityNames = array_map('cIn',array_map('strip_tags',explode(',',$_GET['c'])));
		$cQuery = " AND (e.LocationCity IN ('".implode("','",$cityNames)."') OR l.City IN ('".implode("','",$cityNames)."'))";
	}
	
	$query = "SELECT DISTINCT e.PkID, e.Title, e.Description, e.StartDate, e.StartTime, e.SeriesID
			FROM " . HC_TblPrefix . "events e
				LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
				LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
				LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
			WHERE e.IsActive = 1 AND
				e.IsApproved = 1 AND e.StartDate >= '" . cIn(SYSDATE) . "' AND c.IsActive = 1
				".$lQuery.$cQuery;
	$query .= ($hc_cfg[33] == 0) ? " AND SeriesID IS NULL UNION " . $query . " AND SeriesID IS NOT NULL GROUP BY SeriesID" : '';
	$result = doQuery($query . " ORDER BY StartDate, StartTime LIMIT " . $hc_cfg[2]);
	
	header('Content-Type:application/rss+xml; charset=' . $hc_lang_config['CharSet']);
	echo '<?xml version="1.0" encoding="'.$hc_lang_config['CharSet'].'"?>
<!-- Generated by Helios Calendar '.$hc_cfg[49].' '.date("\\o\\n Y-m-d \\a\\t H:i:s").' local time. -->
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<link>'.CalRoot.'/</link>
	<atom:link href="'.CalRoot.'/rss/feed.php" rel="self" type="application/rss+xml" />
	<copyright>Copyright 2004-'.date("Y").' Refresh Web Development LLC</copyright>
	<generator>http://www.HeliosCalendar.com</generator>
	<docs>'.CalRoot.'&#47;index.php&#63;com=tools</docs>
	<description>'.$hc_lang_rss['Upcoming'].'</description>';
		
	if(hasRows($result)){
		echo '
	<title>'.$hc_lang_rss['Custom'].'</title>';
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$comment = ($hc_cfg[25] != '') ? '<comments><![CDATA['.CalRoot.'/index.php?com=detail&eID='.$row[0].'#disqus_thread'.']]></comments>' : '';
			echo '
	<item>
		<title>'.cleanXMLChars(stampToDate(cOut($row[3]), $hc_cfg[24]))." - ".cleanXMLChars(cOut($row[1])).'</title>
		<link><![CDATA['.CalRoot.'/index.php?com=detail&eID='.$row[0].']]></link>
		<description>'.cleanXMLChars(cOut($row[2])).'</description>
		'.$comment.'
		<guid>'.CalRoot.'/index.php&#63;com=detail&amp;eID='.$row[0].'</guid>
		<pubDate>'.cleanXMLChars(stampToDate($row[3].' '.$row[4], "%a, %d %b %Y %H:%M:%S").' '.$tzRSS).'</pubDate>
	</item>';
			++$cnt;
		}
	} else {
		echo '
	<title>'.$hc_lang_rss['RSSSorry'].'</title>
	<item>
		<title>'.$hc_lang_rss['RSSNoEvents'].'</title>
		<link>'.CalRoot.'/</link>
		<description>'.$hc_lang_rss['RSSNoLink'].'</description>
		<guid>' . CalRoot . '/</guid>
	</item>';
	}
	echo '
</channel>
</rss>';
?>