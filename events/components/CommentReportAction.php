<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/comment.php');
	
	$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
	spamIt($proof, 7);
	
	$name = (isset($_POST['reportName'])) ? $_POST['reportName'] : '';
	$email = (isset($_POST['reportEmail'])) ? $_POST['reportEmail'] : '';
	$report = (isset($_POST['reportDetails'])) ? htmlspecialchars(strip_tags($_POST['reportDetails'])) : '';
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? $_POST['eID'] : 0;
	$cID = (isset($_POST['cID']) && is_numeric($_POST['cID'])) ? $_POST['cID'] : 0;
	$tID = (isset($_POST['tID']) && is_numeric($_POST['tID'])) ? $_POST['tID'] : 0;
	$uID = (isset($_POST['uID']) && is_numeric($_POST['uID'])) ? $_POST['uID'] : 0;
	$returnURL = CalRoot;
	
	if($eID > 0 && $cID > 0 && $tID > 0 && $report != ''){
		$query = "SELECT * FROM " . HC_TblPrefix . "commentsreportlog crl
					WHERE crl.CommentID = '" . $cID . "' AND (ReportedIP = '" . $_SERVER["REMOTE_ADDR"] . "'";
		if($uID > 0){$query .= " OR crl.UserID = '" . $uID . "'";}
		$query .= ')';
		$result = doQuery($query);
		
		if(!hasRows($result)){
			doQuery("INSERT INTO " . HC_TblPrefix . "commentsreportlog(CommentID, TypeID, UserID, ReportedName, ReportedEmail, ReportedMsg, ReportedIP, IsActive)
						VALUES('" . cIn($cID) . "','" . cIn($tID) . "','" . cIn($uID) . "','" . cIn($name) . "','" . cIn($email) . "','" . cIn($report) . "','" . $_SERVER["REMOTE_ADDR"] . "',1)");
			if($hc_cfg54 == 1){
				$result = doQuery("SELECT oid.ShortName, c.Comment
									FROM " . HC_TblPrefix . "comments c
										LEFT JOIN " . HC_TblPrefix . "oidusers oid ON (c.PosterID = oid.PkID)
									WHERE c.PkID = '" . cIn($cID) . "' AND c.IsActive = 1 AND c.TypeID = '" . $tID . "'");
				
				$headers = "From: " . CalAdminEmail . "\n";
				$headers .= "MIME-Version: 1.0\n";
				$headers .= "Reply-To: " . CalAdminEmail . "\n";
				$headers .= "Content-Type: text/html; charset=UTF-8;\n";
				
				$subject = CalName . ' -- ' . $hc_lang_comment['NoticeSubject'];
				
				$message = $hc_lang_comment['NoticeEmail1'] . "<br><br>";
				
				$message .= '<b>' . $hc_lang_comment['NoticeReportBy'] . '</b> ' . $name . ' (' . $email . ')<br />';
				$message .= '<b>' . $hc_lang_comment['NoticeReportFrom'] . '</b> ' . $_SERVER['REMOTE_ADDR'] . '<br />' . $report . '<br /><br />';
				$message .= '<b>' . $hc_lang_comment['NoticeCommentBy'] . '</b> ' . mysql_result($result,0,0) . '<br />';
				$message .= '<b>' . $hc_lang_comment['NoticeComment'] . '</b> ' . mysql_result($result,0,1);
				
				$message .= "<br><br><a href=\"" . CalAdminRoot . "\">" . CalAdminRoot . "</a>";
				
				mail(CalAdminEmail,$subject,$message,$headers);
			}//end if
			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=6#comments';
		} else {
			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=5#comments';
		}//end if
	}//end if
	header('Location: ' . $returnURL);?>