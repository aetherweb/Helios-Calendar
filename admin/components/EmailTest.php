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
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	include(HCLANG.'/admin/settings.php');
	
	$e = (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$_GET['e']) == 1) ? cIn(strip_tags($_GET['e'])) : '';
	
	echo '
<link rel="stylesheet" type="text/css" href="'.AdminRoot.'/css/admin.css">
<style>
html, body {background:#FFFFFF;padding:5px;}
</style>';
	if($e != ''){
		if(!$hc_cfg[71] == 1)
			echo '
		<p>'.$hc_lang_settings['EmailTestMail'].'</p>';
		
		reMail('', $e, CalName.' '.$hc_lang_settings['TestSubj'], $hc_lang_settings['TestMsg'], $hc_cfg[79], $hc_cfg[78],true);
	} else {
		echo '
		'.$hc_lang_settings['EmailTestError'].'
<script>
//<!--
setTimeout(\'self.close()\', 3000);
//-->
</script>';
	}
?>