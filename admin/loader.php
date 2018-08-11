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
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	/** So Core Files Work*/
	define('isHC',true);
	/** Local Path to Helios Admin*/
	define('HCADMIN', dirname(__FILE__));
	/** Local Path to Helios Root*/
	define('HCPATH', dirname(HCADMIN.'../'));
	/** Includes Directory*/
	define('HCINC', '/inc');
		
	include_once(HCPATH . HCINC . '/config.php');
	include_once(HCPATH . HCINC . '/functions/shared.php');
	include_once(HCADMIN . HCINC . '/functions/admin.php');
	
	if(file_exists(HCPATH.'/setup')){
		echo 'Setup directory still present. Please delete it.';
		exit();}
	
	$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME,$dbc);
	
	buildCache(0);
	buildCache(3);
	include_once(HCPATH . '/cache/settings.php');
	
	session_name($hc_cfg[00]);
	session_start();
	
	if(!isset($_SESSION['LangSet']))
		$_SESSION['LangSet'] = $hc_cfg[28];
	
	if(!isset($_SESSION['hc_whoami']))
		$_SESSION['hc_whoami'] = md5($_SERVER['REMOTE_ADDR'] . session_id());
	elseif(md5($_SERVER['REMOTE_ADDR'] . session_id()) != $_SESSION['hc_whoami'])
		killAdminSession();
	
	if(isset($_SESSION['hc_SessionReset']) && $_SESSION['hc_SessionReset'] < date("U"))
		startNewSession();
	
	define('HCVersion',$hc_cfg[49]);
	/** Local Path to Active Language Pack*/
	define('HCLANG', HCPATH . HCINC . '/lang/' . $_SESSION['LangSet']);
	include_once(HCLANG . '/config.php');
	include_once(HCLANG . '/admin/core.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);
	
	$hc_captchas = explode(",", cOut($hc_cfg[32]));
	$hc_time['input'] = cOut($hc_cfg[31]) == 12 ? 12 : 23;			/* $hc_timeInput */
	$hc_time['format'] = ($hc_time['input'] == 23) ? "H" : "h";		/* $hc_hourFormat | $hrFormat */
	$hc_time['minHr'] = ($hc_time['input'] == 23) ? 0 : 1;			/* minHr */
	
	$sys_stamp = mktime((date("G")+($hc_cfg[35])),date("i"),date("s"),date("m"),date("d"),date("Y"));
	/** Current System Date YYYY-MM-DD (Including Timezone Offset)*/
	define("SYSDATE", date("Y-m-d", $sys_stamp));
	/** Current System TIME YYYY-MM-DD (Including Timezone Offset)*/
	define("SYSTIME", date("H:i:s", $sys_stamp));
?>