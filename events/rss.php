<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('includes/include.php');
	hookDB();
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (2,14)");
	$maxShow = mysql_result($result,0,0);
	$dateFormat = mysql_result($result,1,0);
	header ('Content-Type:text/xml; charset=utf-8');
?>

<!-- Generated by Helios <?echo HC_Version;?> <?echo date("\\o\\n Y-m-d \\a\\t H:i:s");?> <?echo "\n";?> http://www.HeliosCalendar.com -->
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title><?echo CalName;?></title>
    <link><?echo CalRoot;?>/</link>
    <language>en-us</language>
    <copyright>Copyright 2004-<?echo date("Y");?> Refresh Web Development LLC</copyright>
	<generator>http://www.HeliosCalendar.com</generator>
	<description>Upcoming Event Information From The <?echo CalName;?></description>
	<?php
		
		$query = "	SELECT distinct " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events
						LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
					WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
						" . HC_TblPrefix . "events.IsApproved = 1 AND 
						" . HC_TblPrefix . "events.StartDate >= NOW() AND
						" . HC_TblPrefix . "categories.IsActive = 1
					ORDER BY StartDate, TBD, Title
					LIMIT " . $maxShow;
		
		if(isset($_GET['cID']) && is_numeric($_GET['cID'])){
			$query = "	SELECT distinct " . HC_TblPrefix . "events.*
						FROM " . HC_TblPrefix . "events
							LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
							LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
						WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
							" . HC_TblPrefix . "events.IsApproved = 1 AND 
							" . HC_TblPrefix . "events.StartDate >= NOW() AND
							" . HC_TblPrefix . "categories.IsActive = 1 AND
							" . HC_TblPrefix . "categories.PkID IN ('" . cIn($_GET['cID']) . "')
						ORDER BY StartDate, TBD, Title
						LIMIT " . $maxShow;
		} else {
			if(isset($_GET['s']) && is_numeric($_GET['s'])){
				if($_GET['s'] == 1){
					//	Newest Events
					$query = "	SELECT distinct " . HC_TblPrefix . "events.*
								FROM " . HC_TblPrefix . "events
									LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
									LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
								WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
									" . HC_TblPrefix . "events.IsApproved = 1 AND 
									" . HC_TblPrefix . "events.StartDate >= NOW() AND
									" . HC_TblPrefix . "categories.IsActive = 1
								ORDER BY PublishDate DESC, StartDate
								LIMIT " . $maxShow;
					
				} elseif($_GET['s'] == 2){
					// Most Popular Events
					$query = "	SELECT distinct " . HC_TblPrefix . "events.*
								FROM " . HC_TblPrefix . "events
									LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
									LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
								WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
									" . HC_TblPrefix . "events.IsApproved = 1 AND 
									" . HC_TblPrefix . "events.StartDate >= NOW() AND
									" . HC_TblPrefix . "categories.IsActive = 1
								ORDER BY Views DESC, StartDate
								LIMIT " . $maxShow;
				} elseif($_GET['s'] == 3){
					// Billboard Events
					$query = "	SELECT distinct " . HC_TblPrefix . "events.*
								FROM " . HC_TblPrefix . "events
									LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
									LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
								WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
									" . HC_TblPrefix . "events.IsApproved = 1 AND 
									" . HC_TblPrefix . "events.StartDate >= NOW() AND
									" . HC_TblPrefix . "categories.IsActive = 1 AND
									" . HC_TblPrefix . "events.IsBillboard = 1
								ORDER BY StartDate, TBD, Title
								LIMIT " . $maxShow;
				}//end if
			}//end if
		}//end if
		
		$result = doQuery($query);
		
		if(hasRows($result)){
			while($row = mysql_fetch_row($result)){
			?>
				<item>
			      <title><?echo stampToDate(cOut($row[9]), $dateFormat) . " - " . str_replace('& ', '&amp; ', cOut($row[1]));?></title>
			      <link><?echo CalRoot . "/index.php?com=detail&amp;eID=" . $row[0];?></link>
			      <description><?echo htmlspecialchars(str_replace('& ', '&amp; ', cOut($row[8])));?></description>
			    </item>
			<?
			}//end while
		} else {
		?>
			<item>
		      <title>No Events Available For This Feed</title>
		      <link><?echo CalRoot;?>/</link>
		      <description>Visit the <?echo CalName;?> for more information.</description>
		    </item>
		<?
		}//end if
	?>
  </channel>
</rss>