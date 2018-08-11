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
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/comment.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case '1':
				feedback(1,$hc_lang_comment['Feed01']);
				break;
		}//end switch
	}//end if
	
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? cIn($_GET['uID']) : 0;
	$resDiff = 5;
	$resLimit = (isset($_GET['a']) && is_numeric($_GET['a']) && abs($_GET['a']) <= 100 && $_GET['a'] % 25 == 0) ? cIn(abs($_GET['a'])) : 25;
	$resPage = (isset($_GET['p']) && is_numeric($_GET['p'])) ? cIn(abs($_GET['p'])) : 0;
	$helpDescription = ($uID > 0) ? $hc_lang_comment['InstructBrowseU'] : $hc_lang_comment['InstructBrowse'];
	
	appInstructions(0, "Comment_Management", $hc_lang_comment['TitleBrowse'], $helpDescription);
	
	$doUser = ($uID > 0) ? " AND c.PosterID = '" . cIn($uID) . "'" : '';
	$uLink = ($uID > 0) ? "&amp;uID=" . cIn($uID) : '';
	$resultC = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "comments c WHERE c.IsActive = 1 AND c.TypeID = 1" . $doUser);
	$totPages = ceil(mysql_result($resultC,0,0)/$resLimit);
	if($totPages <= $resPage && $totPages > 0){$resPage = ($totPages - 1);}
	
	$result = doQuery("SELECT c.*, e.PkID, e.Title, u.PkID, u.Identity, u.ShortName
						FROM " . HC_TblPrefix . "comments c
							LEFT JOIN " . HC_TblPrefix . "events e ON (c.EntityID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "oidusers u ON (c.PosterID = u.PkID)
						WHERE c.IsActive = 1 AND c.TypeID = 1" . $doUser . "
						ORDER BY PostTime DESC
						LIMIT " . $resLimit . " OFFSET " . ($resPage * $resLimit));
	
	if(hasRows($result)){	?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function doDelete(dID){
			if(confirm('<?php echo $hc_lang_comment['Valid01'] . "\\n\\n          " . $hc_lang_comment['Valid02'] . "\\n          " . $hc_lang_comment['Valid03'];?>')){
				document.location.href = '<?php echo CalAdminRoot . "/components/CommentDelete.php";?>?dID=' + dID + '&amp;uID=<?php echo $uID;?>';
			}//end if
		}//end doDelete
		//-->
		</script>
	<?php
		echo '<br />';
		echo ($uID > 0) ? '<a href="' . CalAdminRoot . '/index.php?com=cmntmgt" class="main">' . $hc_lang_comment['AllUsers'] . '</a><br /><br />' :'';
		
		echo '<fieldset style="border:0px;">';
		echo '<div class="frmOpt">';
		echo '<label><b>' . $hc_lang_comment['PerPage'] . '</b></label>';
		for($x = 25;$x <= 100;$x = $x + 25){
			if($x > 25){echo "&nbsp;|&nbsp;";}			
			echo ($x != $resLimit) ?
				'<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&amp;p=' . $resPage . '&amp;a=' . $x . $uLink . '" class="legend">' . $x . '</a>':
				'<b>' . $x . '</b>';
		}//end for
		echo '</div>';

		echo '<div class="frmOpt">';
		$x = (($resPage - $resDiff) > 0) ? ($resPage - $resDiff) : 0;
		$cnt = 0;
		echo '<label><b>' . $hc_lang_comment['Page'] . '</b></label>';
		if($resPage > ($resDiff)){
			echo '<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&amp;p=0&amp;a=' . $resLimit . '" class="main">1</a>&nbsp;...&nbsp;';
		}//end if
		
		while($cnt <= ($resDiff*2) && $x <= ($totPages - 1)){
			echo ($cnt > 0) ? ' | ' : '';
			echo ($resPage != $x) ?
				'<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&amp;p=' . $x . '&amp;a=' . $resLimit . $uLink . '" class="main">' . ($x + 1) . '</a>':
				'<b>' . ($x + 1) . '</b>';
			++$x;
			++$cnt;
		}//end while
		
		if($resPage < ($totPages - ($resDiff + 1))){
			echo '&nbsp;...&nbsp;<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&p=' . ($totPages - 1) . '&amp;a=' . $resLimit . $uLink . '" class="main">' . $totPages . '</a>';
		}//end if
		echo '</div></fieldset>';
		
		while($row = mysql_fetch_row($result)){
			echo '<div class="commentFrame" >';
			echo '<div class="commentTools">';
			echo '&nbsp;<a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[8] . '" class="main" target="_blank"><img src="' . CalAdminRoot . '/images/icons/iconViewPublic.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo ($adminUserEdit == 1) ? '<a href="' . CalAdminRoot . '/index.php?com=oidedit&amp;uID=' . $row[10] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconUserEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;' : '';
			echo '<a href="javascript:;" onclick="doDelete(\'' . $row[0] . '\',2);return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconCommentDelete.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
			echo ($uID == 0) ? '&nbsp;<a href="' . CalAdminRoot . '/index.php?com=cmntmgt&amp;uID=' . $row[10] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconComments.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>' : '';
			echo '<br />';
			echo ($row[6] > 0) ? '+' . $row[6] : $row[6];
			echo ' Recomnds <br /></div>';
			echo '<div class="hc_align">' . $hc_lang_comment['About'] . '</div><div class="hc_align">&nbsp;<i>' . $row[9] . '</i>,&nbsp;</div> <a href="' . $row[11] . '" target="_blank" class="cmtEvent">' . $row[12] . '</a><div class="hc_align">&nbsp;' . $hc_lang_comment['Said'] . '&nbsp;</div>';
			echo '<br /><br /><div id="comment_' . $row[0] . '" class="comment">';
			echo cOut(nl2br($row[1]));
			
			$cmntStamp = explode(" ", $row[3]);
			$cmntDate = explode("-",$cmntStamp[0]);
			$cmntTime = explode(":", $cmntStamp[1]);
			$cmntStamp = date("Y-m-d G:i:s", mktime(($cmntTime[0]+$hc_cfg35), $cmntTime[1], $cmntTime[2], $cmntDate[1], $cmntDate[2], $cmntDate[0]));
			echo '<div class="commentDate">' . stampToDate($cmntStamp, $hc_cfg24 . ' @ ' . $hc_cfg23) . '</div>';
			echo '</div></div>';
			++$cnt;
		}//end while		
	} else {
		echo '<br />';
		echo ($uID > 0) ? $hc_lang_comment['NoCommentsU'] : $hc_lang_comment['NoComments'];
		echo '<br /><br />';
		echo '<a href="' . CalAdminRoot . '/index.php?com=cmntmgt" class="main">' . $hc_lang_comment['AllUsers'] . '</a><br /><br />';
	}//end if
?>