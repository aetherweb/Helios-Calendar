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
	
	include(HCLANG . '/public/rsvp.php');

	$proof = $challenge = '';
	if($hc_cfg[65] == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg[65] == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}
	spamIt($proof,$challenge,3);
	
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? cIn(strip_tags($_POST['eID'])) : 0;
	$regName = isset($_POST['hc_f1']) ? cIn(cleanBreaks($_POST['hc_f1'])) : '';
	$regEmail = isset($_POST['hc_f2']) ? cIn(cleanBreaks($_POST['hc_f2'])) : '';
	$phone = isset($_POST['hc_f3']) ? cIn($_POST['hc_f3']) : '';
	$address = isset($_POST['hc_f4']) ? cIn($_POST['hc_f4']) : '';
	$address2 = isset($_POST['hc_f5']) ? cIn($_POST['hc_f5']) : '';
	$city = isset($_POST['hc_f6']) ? cIn($_POST['hc_f6']) : '';
	$state = isset($_POST['locState']) ? cIn($_POST['locState']) : '';
	$country = isset($_POST['hc_f9']) ? cIn($_POST['hc_f9']) : '';
	$zip = isset($_POST['hc_f8']) ? cIn($_POST['hc_f8']) : '';
	$partySize = (is_numeric($_POST['hc_f7'])) ? $_POST['hc_f7'] + 1 : 0;

	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "registrants WHERE Email = '" . $regEmail . "' AND EventID = '" . $eID . "'");
	if(hasRows($result)){
		header("Location: " . CalRoot . "/index.php?com=rsvp&eID=".$eID."&msg=1");
	} else {
		$result = doQuery("SELECT Title, StartDate, StartTime, TBD, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'");
		
		$eventTitle = mysql_result($result,0,0);
		$eventDate = stampToDate(mysql_result($result,0,1), $hc_cfg[14]);
		$conEmail = mysql_result($result,0,4);
		$groupID = ($partySize > 1) ? md5($regName . $eventTitle . date("U")) : '';
		
		$eMsg = '<p><b>' . mysql_result($result,0,0) . '</b><br />' . stampToDate(mysql_result($result,0,1), $hc_cfg[14]) . ' - ';
		if(mysql_result($result,0,3) == 0)
			$eMsg .= stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg[23]);
		elseif(mysql_result($result,0,3) == 1)
			$eMsg .= $hc_lang_rsvp['AllDay'];
		elseif(mysql_result($result,0,3) == 2)
			$eMsg .= $hc_lang_rsvp['TBA'];
		
		$eMsg .= '<br /><a href="' . CalRoot . '/index.php?eID=' . $eID . '">' . CalRoot . '/index.php?eID=' . $eID . '</a></p>';
		
		for($x=1;$x<=$partySize;$x++){
			$addName = ($partySize > 1) ? $regName . " - " . $x . "/" . $partySize : $regName;
			doQuery("INSERT into " . HC_TblPrefix . "registrants(Name, Email, Phone, Address, Address2, City, State, Zip, EventID, IsActive, RegisteredAt, GroupID)
					Values(	'" . cIn($addName) . "',
							'" . $regEmail . "',
							'" . $phone . "',
							'" . $address . "','" . $address2 . "','" . $city . "','" . $state . "','" . $zip . "',
							'" . $eID . "',
							1, NOW(),
							'" . cIn($groupID) . "');");
		}
		
		$result = doQuery("SELECT COUNT(r.EventID), e.SpacesAvailable
							FROM " . HC_TblPrefix . "registrants r
								LEFT JOIN " . HC_TblPrefix . "events e ON (r.EventID = e.PkID)
							WHERE r.EventID = '" . $eID . "'
							GROUP BY EventID");
		$eOver = $eLimit = 0;
		if(mysql_result($result,0,0) > mysql_result($result,0,1) && mysql_result($result,0,1) != 0)
  			$eOver = 1;
		elseif(mysql_result($result,0,0) == mysql_result($result,0,1) && mysql_result($result,0,1) != 0)
			$eLimit = 1;
		
		$rMsg = '<p><b>' . $hc_lang_rsvp['PartySize'] . " " . $partySize . '</b>';
		$rMsg .= '<br />' . $regName . '<br />' . $regEmail;
		$rMsg .= ($phone != '') ? '<br />' . $phone : '';
		$rMsg .= ($address != '') ?  '<br />' . strip_tags(buildAddress($address,$address2,$city,$state,$zip,$country,$hc_lang_config['AddressType']),'<br>') : '';
		$rMsg .= '</p>';
		
		//	RSVP User Email
		$regSubj = $hc_lang_rsvp['regSubject'] . $eventTitle;
		$regMsg = '<p>' . $hc_lang_rsvp['regMsg'] . '</p>';
		$regMsg .= $eMsg . $rMsg;
		$regMsg .= ($eOver == 1) ? " " . $hc_lang_rsvp['regOverflow'] : '';
		$regMsg .= '<p>' . $hc_lang_rsvp['ThankYou'] . '<br />' . $hc_cfg[79] . '</p>';
  		$regMsg .= '<p>' . $hc_lang_rsvp['regDisclaimer'] . '</p>';

		//	Event Contact Email
		$conSubj = $hc_lang_rsvp['conSubject'] . $eventTitle;
		$conMsg = '<p>' . $hc_lang_rsvp['conMsg'] . '</p>';
		$conMsg .= $eMsg;
		$conMsg .= ($eOver == 1) ? '<p>' . $hc_lang_rsvp['conOverflow'] . '</p>' : '';
		$conMsg .= ($eLimit == 1) ? '<p>' . $hc_lang_rsvp['conLimit'] . '</p>' : '';
		$conMsg .= $rMsg;
		$conMsg .= '<p>' . $hc_lang_rsvp['ThankYou'] . '<br />' . $hc_cfg[79] . '</p>';
		$conMsg .= '<p>' . $hc_lang_rsvp['conDisclaimer'] . '</p>';
		
		reMail($regName,$regEmail,$regSubj,$regMsg,$hc_cfg[79],$hc_cfg[78]);
		reMail('',$conEmail,$conSubj,$conMsg,$hc_cfg[79],$hc_cfg[78]);

		header("Location: " . CalRoot . "/index.php?com=rsvp&eID=".$eID."&msg=2");
	}
?>