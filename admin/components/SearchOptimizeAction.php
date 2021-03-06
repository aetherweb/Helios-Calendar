<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	post_only();
	
	$token = (isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : '';
	if(!check_form_token($token))
		go_home();
	
	$allowIndex = (isset($_POST['indexing']) && is_numeric($_POST['indexing'])) ? cIn($_POST['indexing']) : '0';
	$sitemap = (isset($_POST['sitemap']) && is_numeric($_POST['sitemap'])) ? cIn($_POST['sitemap']) : '100';
	$bots = isset($_POST['bots']) ? cIn($_POST['bots']) : '//';
	$expires = (isset($_POST['expires']) && is_numeric($_POST['expires'])) ? cIn($_POST['expires']) : '0';
	
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($allowIndex, '7'));
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($bots, '85'));
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($sitemap, '87'));
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = ? WHERE PkID = ?", array($expires, '134'));
	
	$ids = (isset($_POST['ids'])) ? $_POST['ids'] : array();
	$keywords = (isset($_POST['keywords'])) ? $_POST['keywords'] : array();
	$descriptions = (isset($_POST['descriptions'])) ? $_POST['descriptions'] : array();
	$titles = (isset($_POST['titles'])) ? $_POST['titles'] : array();
	$cnt = 0;

	foreach ($keywords as $val){
		doQuery("UPDATE " . HC_TblPrefix . "settingsmeta
				SET Keywords = ?,
				Description = ?,
				Title = ?
				WHERE PkID = ?", 
				array(
					cIn(strip_tags($keywords[$cnt])), 
					cIn(strip_tags($descriptions[$cnt])), 
					cIn(strip_tags($titles[$cnt])), 
					cIn(strip_tags($ids[$cnt]))
				)
			);
		++$cnt;
	}
	
	clearCache();
	
	header("Location: " . AdminRoot . "/index.php?com=seo&msg=1");
?>