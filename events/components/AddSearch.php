<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	
	header ('Content-Type:text/xml; charset=utf-8');
	echo "<?xml version=\"1.0\"?>\n";
	echo "<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">\n\t";
	echo "<ShortName>" . CalName . "</ShortName>\n\t";
	echo "<Description>Use " . CalName . " to search our event calendar.</Description>\n\t";
	echo "<Image height=\"16\" width=\"16\" type=\"image/x-icon\">" . CalRoot . "/images/favicon.png</Image>\n\t";
	echo "<Url type=\"text/html\" method=\"get\" template=\"" . CalRoot . "/index.php?com=searchresult&amp;k={searchTerms}\" />\n";
	echo "</OpenSearchDescription>";