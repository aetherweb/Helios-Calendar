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
/*
	NOTE: You may not alter any logos, legends, branding elements or copyright notices withing the
	Helios Calendar admin console (/admin) unless you have been granted express written permission
	by Refresh Web Development to do so.
	
	Visit: http://www.helioscalendar.com/developers.php for details.
*/
	define('hcAdmin', true);
	include('loader.php');
	
	$hc_Side[] = array(CalRoot.'/','view_public.png',$hc_lang_core['Link'],1);
	
	define("HC_LogOut", "components/SignOut.php");
	define("HC_InstructionsSwitch", "components/InstructionSwitch.php");

	include(HCLANG.'/admin/core.php');
	include(HCLANG.'/admin/menu.php');
	
	echo '<!doctype html>
<html lang="en">
<head>
	<meta charset="'.$hc_lang_config['CharSet'].'">
	<meta name="robots" content="noindex, nofollow">
	<meta name="author" content="Refresh Web Development LLC">
	
	<link rel="stylesheet" type="text/css" href="'.AdminRoot.'/css/admin.css">
	<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" href="'.AdminRoot.'/css/admin_ie8.css">
		<script src="'.AdminRoot.'/inc/javascript/ie8.js"></script>
	<![endif]-->
	
	<link rel="shortcut icon" href="'.CalRoot.'/admin/favicon'.((has_pending() > 0) ? '_e':'').'.ico">
	<title>'.CalName.', powered by Helios Calendar</title>
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	if(top.location != location)
		top.location.href = document.location.href;';
	
	if(!isset($_SESSION['AdminLoggedIn'])){
		include_once(HCADMIN.'/inc/javascript/login.php');
	} else {
		echo '
	var freq = 300000;
	function do_favicon(address){var http_request = false;if(window.XMLHttpRequest){http_request = new XMLHttpRequest();if(http_request.overrideMimeType){http_request.overrideMimeType("text/xml");}} else if (window.ActiveXObject){try {http_request = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) {try {http_request = new ActiveXObject("Microsoft.XMLHTTP");} catch(e) {}}}if (!http_request) {return false;}http_request.onreadystatechange = function(){if(http_request.readyState == 4){if(http_request.status == 200){var status = http_request.responseText;var use_ico = (status == "1") ? "favicon_e.ico" : "favicon.ico";var link = document.createElement("link");link.type = "image/x-icon";link.rel = "shortcut icon";link.href = "'.AdminRoot.'/" + use_ico;document.getElementsByTagName("head")[0].appendChild(link);}}};http_request.open("GET", address, true);http_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");http_request.send(null);setTimeout("do_favicon(\''.AdminRoot.'/check.php?go=1\')",freq);}
	do_favicon("'.AdminRoot.'/check.php?go=1",freq);';
	}
	echo '
	//-->
	</script>
</head>
'.((isset($_SESSION['AdminLoggedIn'])) ? '<body>' : '<body class="login">');
	
	if(isset($_SESSION['AdminLoggedIn'])){
		echo '
	<div id="menu">
	<nav>';
		include(HCADMIN.'/components/AdminMenu.php');

		echo '
	</nav>
	</div>
	<div id="content">
		<section>
		<div id="date">' . strftime($hc_cfg[14]) . '</div>';
		include(HCADMIN.'/inc/core.php');
		
		echo '
		</section>
		<aside>
			<div id="side">
			<img src="' . AdminRoot . '/img/logo.png" width="235" height="56" alt="" id="logo" /><br /><br />';

			include(HCADMIN.'/components/Side.php');
			echo '
			</div>
		</aside>
	</div>';
	} else {
		include(HCADMIN.'/components/Login.php');}
		
	echo '
	<div id="footer">
		Helios Calendar '.HCVersion.'<br />Copyright &copy; 2004-'.date("Y").'<br />
		<a href="http://www.refreshmy.com" class="copyright" target="_blank">Refresh Web Development LLC</a><br />
		ALL RIGHTS RESERVED
	</div>
</body>
</html>';
?>