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
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
	post_only();
	
	include(HCLANG . '/public/news.php');
	
	$proof = $challenge = '';
	if($hc_cfg[65] == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg[65] == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}
	spamIt($proof,$challenge,4);

	$firstname = (isset($_POST['hc_f1'])) ? cIn(strip_tags($_POST['hc_f1'])) : '';
	$lastname = (isset($_POST['hc_f2'])) ? cIn(strip_tags($_POST['hc_f2'])) : '';
	$email = (isset($_POST['hc_f3'])) ? cIn(strip_tags($_POST['hc_f3'])) : '';
	$catID = (isset($_POST['catID'])) ? array_map('cIn',$_POST['catID']) : array();
	$uID = (isset($_POST['uID']) && is_numeric($_POST['uID'])) ? cIn($_POST['uID']) : 0;
	$occupation = (isset($_POST['occupation'])) ? cIn($_POST['occupation']) : '';
	$zip = (isset($_POST['hc_f4'])) ? cIn($_POST['hc_f4']) : '';
	$birthyear = (isset($_POST['hc_fa'])) ? cIn($_POST['hc_fa']) : '';
	$gender = (isset($_POST['hc_fb'])) ? cIn($_POST['hc_fb']) : '';
	$referral = (isset($_POST['hc_fc'])) ? cIn($_POST['hc_fc']) : '';
	$format = (isset($_POST['format'])) ? cIn($_POST['format']) : '';;
	$stop = ($firstname != '') ? 0 : 1;
	$stop += ($lastname != '') ? 0 : 1;
	$stop += (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$email) == 1) ? 0 : 1;
	$stop += (is_array($_POST['catID'])) ? 0 : 1;
	$stop += ($birthyear < (date("Y") - 13)) ? 0 : 1;
	
	if($stop > 0){
		header('Location: '.CalRoot.'/index.php?com=signup&t=2');
		exit;}
		
	if($uID > 0){
		doQuery("UPDATE " . HC_TblPrefix . "subscribers
				SET FirstName = '" . $firstname . "',LastName = '" . $lastname . "',OccupationID = '" . $occupation . "',
					Email = '" . $email . "',Zip = '" . $zip . "',BirthYear = '" . $birthyear . "',Gender = '" . $gender . "',
					Referral = '" . $referral . "',Format = '" . $format . "'
				WHERE PkID = '" . $uID . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = '" . $uID . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = '" . $uID . "'");

		if(isset($_POST['grpID'])){
			foreach ($_POST['grpID'] as $val)
				doQuery("INSERT INTO " . HC_TblPrefix . "subscribersgroups(UserID,GroupID) Values('".$uID."', '".cIn($val)."')");	
		}
		if(isset($_POST['catID'])){
			foreach ($_POST['catID'] as $val)
				doQuery("INSERT INTO " . HC_TblPrefix . "subscriberscategories(UserID,CategoryID) Values('".$uID."', '".cIn($val)."')");
		}

		header('Location: '.CalRoot.'/index.php?com=signup&t=5');
	} else {
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE email = '" . $email . "'");
		if(hasRows($result)){
			header('Location: '.CalRoot.'/index.php?com=signup&t=2');
			exit;}
			
		doQuery("INSERT INTO " . HC_TblPrefix . "subscribers(FirstName,LastName,Email,OccupationID,Zip,IsConfirm,GUID,RegisteredAt,RegisterIP,BirthYear,Gender,Referral,Format)
				VALUES(	'" . $firstname . "','" . $lastname . "','" . $email . "','" . $occupation . "','" . $zip . "',
						0,MD5(CONCAT(rand(UNIX_TIMESTAMP()) * (RAND()*1000000),'" . $email . "')),
						'" . date("Y-m-d") . "','" . cIn(strip_tags($_SERVER["REMOTE_ADDR"])) . "','" . $birthyear . "','" . $gender . "','" . $referral . "', '" . $format . "')");
		$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "subscribers");
		$newID = cIn(mysql_result($result,0,0));
		if(isset($_POST['catID'])){
			foreach ($_POST['catID'] as $val)
				doQuery("INSERT INTO " . HC_TblPrefix . "subscriberscategories(UserID, CategoryID) Values('" . $newID . "', '" . cIn($val) . "')");
		}
		if(isset($_POST['grpID'])){
			foreach ($_POST['grpID'] as $val)
				doQuery("INSERT INTO " . HC_TblPrefix . "subscribersgroups(UserID,GroupID) Values('" . $newID . "', '" . cIn($val) . "')");
		}

		$result = doQuery("SELECT GUID FROM " . HC_TblPrefix . "subscribers WHERE PkID = '" . cIn($newID) . "'");
		$GUID = (hasRows($result)) ?  mysql_result($result,0,0) : '';
		$subject = $hc_lang_news['Subject'] . ' - ' . CalName;
		$message = '<p>' . $hc_lang_news['RegEmailA'] . ' <a href="' . CalRoot . '/a.php?a=' . $GUID . '">' . CalRoot . '/a.php?a=' . $GUID . '</a></p>';
		$message .= '<p>' .  $firstname . $hc_lang_news['RegEmailB'] . '</p>';
		$message .= '<p>' . $hc_lang_news['RegEmailC'] . ' ' . $hc_cfg[78] . '</p>';

		reMail(trim($firstname . ' ' . $lastname),$email,$subject,$message,$hc_cfg[79],$hc_cfg[78]);

		header('Location: ' . CalRoot . '/index.php?com=signup&t=1');
	}
?>