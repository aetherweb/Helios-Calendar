<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');
	
	$eID = 0;
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
		$efCheck = $eID;
	} elseif(isset($_POST['eventID'])){
		$editString = "0";
		$eventID = $_POST['eventID'];
		$ecnt = 0;
		foreach ($eventID as $val){
			if($ecnt == 0){$eID = $val;}
			$editString = $editString . "," . $val;
			++$ecnt;
		}//end for
		$efCheck = $editString;
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$resultL = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
	$hasLoc = (hasRows($resultL)) ? 1 : 0;
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($eID) . "'");
	if(!hasRows($result)){
		echo '<br />';
		echo '<div style="padding-bottom:150px;">' . $hc_lang_event['EditWarning'] . '</div>';
	} else {
		$hrFormat = ($hc_timeInput == 23) ? "H" : "h";
		$minHr = ($hc_timeInput == 23) ? 0 : 1;
		$eventStatus = cOut(mysql_result($result,0,17));
		$eventBillboard = cOut(mysql_result($result,0,18));
		$eventTitle = cOut(mysql_result($result,0,1));
		$eventDesc = cOut(mysql_result($result,0,8));
		$locName = cOut(mysql_result($result,0,2));
		$locAddress = cOut(mysql_result($result,0,3));
		$locAddress2 = cOut(mysql_result($result,0,4));
		$locCity = cOut(mysql_result($result,0,5));
		$locState = cOut(mysql_result($result,0,6));
		$locZip = cOut(mysql_result($result,0,7));
		$tbd = cOut(mysql_result($result,0,11));
		$startTime = cOut(mysql_result($result,0,10));
		$endTime = cOut(mysql_result($result,0,12));
		$contactName = cOut(mysql_result($result,0,13));
		$contactEmail = cOut(mysql_result($result,0,14));
		$contactPhone = cOut(mysql_result($result,0,15));
		$allowRegistration = cOut(mysql_result($result,0,25));
		$maxReg = cOut(mysql_result($result,0,26));
		$views = cOut(mysql_result($result,0,28));
		$locID = cOut(mysql_result($result,0,35));
		$cost = cOut(mysql_result($result,0,36));
		$locCountry = cOut(mysql_result($result,0,37));
		$contactURL = (mysql_result($result,0,24) == '') ? 'http://' : cOut(mysql_result($result,0,24));
		$eventDate = stampToDate(mysql_result($result,0,9), $hc_cfg24);
		$AllDay = '';
		
		if($tbd == 0){
			$startTimeParts = explode(":", $startTime);
			$startTimeHour = date($hrFormat, mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
			$startTimeMins = $startTimeParts[1];
			$startTimeAMPM = date("A", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
			
			if($endTime != ''){
				$endTimeParts = explode(":", $endTime);
				$endTimeHour = date($hrFormat, mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
				$endTimeMins = $endTimeParts[1];
				$endTimeAMPM = date("A", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
			} else {
				$endTimeHour = date($hrFormat, mktime($startTimeParts[0] + 1, $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				$endTimeMins = $startTimeParts[1];
				$endTimeAMPM = date("A", mktime($startTimeParts[0] + 1, $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				$noEndTime = 1;
			}//end if
		} else {
			$startTimeHour = date($hrFormat);
			$startTimeMins = "00";
			$startTimeAMPM = date("A");
			$endTimeHour = date($hrFormat, mktime($startTimeHour + 1, 0, 0, 1, 1, 1971));
			$endTimeMins = "00";
			$endTimeAMPM = date("A", mktime($startTimeHour + 1, 0, 0, 1, 1, 1971));
		}//end if
		
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,$hc_lang_event['Feed01']);
					break;
				case "2" :
					feedback(1,$hc_lang_event['Feed02']);
					break;
				case "3" :
					feedback(1,$hc_lang_event['Feed03']);
					break;
				case "4" :
					feedback(1,$hc_lang_event['Feed04']);
					break;
				case "5" :
					feedback(1,$hc_lang_event['Feed05']);
					break;
				case "6" :
					feedback(1,$hc_lang_event['Feed06']);
					break;
				case "7" :
					feedback(2,$hc_lang_event['Feed07']);
					break;
				case "8" :
					feedback(2,$hc_lang_event['Feed08']);
					break;
				case "9" :
					feedback(1,$hc_lang_event['Feed09']);
					break;
				case "10" :
					feedback(1,$hc_lang_event['Feed10']);
					break;
				case "11" :
					feedback(1,$hc_lang_event['Feed11']);
					break;
			}//end switch
		}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(who){
		who.style.display == 'none' ? who.style.display = 'block':who.style.display = 'none';
		return false;
	}//end toggleMe()
	
	function cloneEvent(){
		if(confirm('<?php echo $hc_lang_event['Valid53'] . "\\n\\n          " . $hc_lang_event['Valid54'] . "\\n          " . $hc_lang_event['Valid55'];?>')){
			document.frmEventEdit.action='<?php echo CalAdminRoot;?>/components/EventAddAction.php';
		}//end if
	}//end cloneEvent()
	
	function chngClock(obj,inc,maxVal){
		if(obj.disabled == false){
			var val = parseInt(obj.value,10);
			val += inc;
			
			if(maxVal == 59){
				if(val > maxVal) val = 0;
				if(val < 0) val = maxVal;
			} else {
				if(val > maxVal) val = <?php echo $minHr;?>;
				if(val < <?php echo $minHr;?>) val = maxVal;
			}//end if
			
			if(val < 10) val = "0" + val;
			obj.value = val;
		}//end if
	}//end chngClock()
	
	function togRegistrants(){
		if(document.getElementById('registrant').style.display == 'none'){
			document.getElementById('registrant').style.display = 'block';
			document.frmEventEdit.eventRegistrants.value = '<?php echo $hc_lang_event['RegButton1a'];?>';
		} else {
			document.getElementById('registrant').style.display = 'none';
			document.frmEventEdit.eventRegistrants.value = '<?php echo $hc_lang_event['RegButton1b'];?>';
		}//end if
	}//end showPanel()
	
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_event['Valid01b'];?>";
		
		if(document.frmEventEdit.eventRegistration.value == 1){
			if(isNaN(document.frmEventEdit.eventRegAvailable.value) == true){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid02'];?>';
			} else if(document.frmEventEdit.eventRegAvailable.value < 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid60'];?>';
			}//end if
			
			if(document.frmEventEdit.contactName.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid03'];?>';
			}//end if
			
			if(document.frmEventEdit.contactEmail.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid04'];?>';
			}//end if
		}//end if
		
		if(validateCheckArray('frmEventEdit','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid15'];?>';
		}//end if
	
		if(document.frmEventEdit.eventTitle.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid13'];?>';
		}//end if
		
		if(!isDate(document.frmEventEdit.eventDate.value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid24'] . " " . strtolower($hc_cfg51);?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.startTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid05'];?>';
		} else if((document.frmEventEdit.startTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventEdit.startTimeHour.value < <?php echo $minHr;?>)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid06'] . " " . $minHr . " - " . $hc_timeInput;?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.startTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid07'];?>';
		} else if((document.frmEventEdit.startTimeMins.value > 59) || (document.frmEventEdit.startTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid08'];?> 0 - 59';
		}//end if
		
		if(isNaN(document.frmEventEdit.endTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid09'];?>';
		} else if((document.frmEventEdit.endTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventEdit.endTimeHour.value < <?php echo $minHr;?>)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid10'] . " " . $minHr . " - " . $hc_timeInput;?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.endTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid11'];?>';
		} else if((document.frmEventEdit.endTimeMins.value > 59) || (document.frmEventEdit.endTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid12'];?> 0 - 59';
		}//end if
		
<?php 	if($hasLoc > 0){	?>
		if(document.frmEventEdit.locPreset.value == 0){
			if(document.frmEventEdit.locName.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
			}//end if
		}//end if
<?php 	} else {	?>
		if(document.frmEventEdit.locName.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
		}//end if
<?php 	}//end if	?>

		if(compareDates(document.frmEventEdit.eventDate.value, '<?php echo $hc_cfg51;?>', '<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d")-1,date("Y")));?>', '<?php echo $hc_cfg51;?>') == 0){
			if(!confirm('<?php echo $hc_lang_event['Valid18'] . "\\n\\n          " . $hc_lang_event['Valid19'] . "\\n          " . $hc_lang_event['Valid20'];?>')){
				return false;
			}//end if
		}//end if
	
		if(document.frmEventEdit.contactEmail.value != '' && chkEmail(document.frmEventEdit.contactEmail) == 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid21'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_event['Valid22'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	
	function togOverride(){
		if(document.frmEventEdit.overridetime.checked){
			document.frmEventEdit.specialtimeall.disabled = false;
			document.frmEventEdit.specialtimetbd.disabled = false;
			document.frmEventEdit.startTimeHour.disabled = true;
			document.frmEventEdit.startTimeMins.disabled = true;
			document.frmEventEdit.endTimeHour.disabled = true;
			document.frmEventEdit.endTimeMins.disabled = true;
			document.frmEventEdit.ignoreendtime.disabled = true;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventEdit.startTimeAMPM.disabled = true;
				document.frmEventEdit.endTimeAMPM.disabled = true;
			}//end if
		} else {
			document.frmEventEdit.specialtimeall.disabled = true;
			document.frmEventEdit.specialtimetbd.disabled = true;
			document.frmEventEdit.startTimeHour.disabled = false;
			document.frmEventEdit.startTimeMins.disabled = false;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventEdit.startTimeAMPM.disabled = false;
			}//end if
			if(document.frmEventEdit.ignoreendtime.checked == false){
				document.frmEventEdit.endTimeHour.disabled = false;
				document.frmEventEdit.endTimeMins.disabled = false;
				if(<?php echo $hc_timeInput;?> == 12){
					document.frmEventEdit.endTimeAMPM.disabled = false;
				}//end if
			}//end if
			document.frmEventEdit.ignoreendtime.disabled = false;
		}//end if
	}//end togOverride()
	
	function togEndTime(){
		if(document.frmEventEdit.ignoreendtime.checked){
			document.frmEventEdit.endTimeHour.disabled = true;
			document.frmEventEdit.endTimeMins.disabled = true;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventEdit.endTimeAMPM.disabled = true;
			}//end if
		} else {
			document.frmEventEdit.endTimeHour.disabled = false;
			document.frmEventEdit.endTimeMins.disabled = false;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventEdit.endTimeAMPM.disabled = false;
			}//end if
		}//end if
	}//end togEndTime()
	
	function togRegistration(){
		if(document.frmEventEdit.eventRegistration.value == 0){
			document.frmEventEdit.eventRegAvailable.disabled = true;
		} else {
			document.frmEventEdit.eventRegAvailable.disabled = false;
		}//end if
	}//end togRegistration()
	
	function sendReg(eID){
		window.location.href=('<?php echo CalAdminRoot;?>/components/RegisterSendRoster.php?eID=' + eID);
	}//end if
	
	function delReg(dID){
		if(confirm('<?php echo $hc_lang_event['Valid47'] . "\\n\\n          " . $hc_lang_event['Valid48'] . "\\n          " . $hc_lang_event['Valid49'];?>')){
			window.location.href=('<?php echo CalAdminRoot;?>/components/RegisterAddAction.php?dID=' + dID + '&eID=<?php echo $eID;?>');
		}//end if
	}//end delReg()
	
	function togLocation(){
		if(document.frmEventEdit.locPreset.value == 0){
			document.frmEventEdit.locName.disabled = false;
			document.frmEventEdit.locAddress.disabled = false;
			document.frmEventEdit.locAddress2.disabled = false;
			document.frmEventEdit.locCity.disabled = false;
			document.frmEventEdit.locState.disabled = false;
			document.frmEventEdit.locZip.disabled = false;
			document.frmEventEdit.locCountry.disabled = false;
			document.getElementById('customLoc').style.display = 'block';
			document.getElementById('customLocNotice').style.display = 'none';
		} else {
			document.frmEventEdit.locName.disabled = true;
			document.frmEventEdit.locAddress.disabled = true;
			document.frmEventEdit.locAddress2.disabled = true;
			document.frmEventEdit.locCity.disabled = true;
			document.frmEventEdit.locState.disabled = true;
			document.frmEventEdit.locZip.disabled = true;
			document.frmEventEdit.locCountry.disabled = true;
			document.getElementById('customLoc').style.display = 'none';
			document.getElementById('customLocNotice').style.display = 'block';
		}//end if
	}//end togLocation()
	
	function searchLocations($page){
		if(document.frmEventEdit.locSearch.value.length > 3){
			var qStr = 'LocationSearch.php?np=1&q=' + escape(document.frmEventEdit.locSearch.value) + '&o=' + $page;
			ajxOutput(qStr, 'locSearchResults', '<?php echo CalRoot;?>');
		}//end if
	}//end searchLocations()
	
	function setLocation($id){
		document.frmEventEdit.locPreset.value = $id;
		togLocation();
		if($id == 0){
			document.getElementById('locSearchResults').innerHTML = '';
			document.frmEventEdit.locSearch.value = '';
			document.getElementById('locSearch').style.display = 'block';

			document.getElementById('locSetting').style.display = 'none';
		}//end if
	}//end setLocation
	
	var calx = new CalendarPopup("dsCal");
	calx.setCssPrefix("hc_");
	//-->
	</script>
<?php
	if(!isset($eventID)){
		appInstructions(0, "Editing_Events", $hc_lang_event['TitleEdit'], $hc_lang_event['InstructEdit']);
	} else {
		appInstructions(0, "Group_Editing_Events", $hc_lang_event['TitleGroup'], $hc_lang_event['InstructGroup']);
	}//end if
	echo "<br />";?>
	
	<form id="frmEventEdit" name="frmEventEdit" method="post" action="<?php echo CalAdminRoot . "/components/EventEditAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="eID" id="eID" value="<?php echo $eID;?>" />
<?php
	if(isset($editString)){	?>
	<input type="hidden" name="editString" id="editString" value="<?php echo $editString;?>" />	<?php
		$resultD = doQuery("SELECT StartDate FROM " . HC_TblPrefix . "events WHERE PkID IN(" . cIn($editString) . ")");
		if(hasRows($resultD)){	?>
		<b><?php echo $hc_lang_event['GroupNotice'];?></b><br />
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($resultD)){	
			if($cnt > 0){echo ", ";}
			echo stampToDate($row[0], $hc_cfg24);
			$cnt++;
		}//end while	?>
		<br /><br />
		<label for="makeseries"><input type="checkbox" name="makeseries" id="makeseries" class="noBorderIE" /><?php echo $hc_lang_event['GroupCombine'];?></label>
		<br />
<?php 	}//end if
	}//end if	?>
	
	<fieldset>
		<legend><?php echo $hc_lang_event['EventDetail'];?></legend>
		<div class="frmReq">
			<label for="eventTitle"><?php echo $hc_lang_event['Title'];?></label>
			<input name="eventTitle" id="eventTitle" type="text" maxlength="150" style="width:75%;" value="<?php echo $eventTitle;?>" />&nbsp;<span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="eventDescription"><?php echo $hc_lang_event['Description'];?></label>
	<?php	makeTinyMCE('eventDescription', '525px', 'advanced', $hc_cfg24, $eventDesc);?>
		</div>
<?php	if(!isset($editString)){	?>
		<div class="frmReq">
			<label for="eventDate"><?php echo $hc_lang_event['Date'];?></label>
			<input name="eventDate" id="eventDate" type="text" value="<?php echo $eventDate;?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventEdit.eventDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
	    </div>
<?php	}//end if	?>
		<div class="frmOpt">
			<label><?php echo $hc_lang_event['StartTime'];?></label>
			<table cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?php echo $startTimeHour;?>" size="2" maxlength="2" <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="startTimeMins" id="startTimeMins" type="text" value="<?php echo $startTimeMins;?>" size="2" maxlength="2"  <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
			<?php 	if($hc_timeInput == 12){	?>
					<td>
						<select name="startTimeAMPM" id="startTimeAMPM" <?php if($tbd > 0){echo "disabled=\"disabled\" ";}?>>
							<option <?php if($startTimeAMPM == "AM"){echo "selected=\"selected\"";}?> value="AM">AM</option>
							<option <?php if($startTimeAMPM == "PM"){echo "selected=\"selected\"";}?> value="PM">PM</option>
						</select>
					</td>
			<?php 	}//end if	?>
				</tr>
			</table>
	    </div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_event['EndTime'];?></label>
			<table cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td><input name="endTimeHour" id="endTimeHour" type="text" value="<?php echo $endTimeHour;?>" size="2" maxlength="2" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="endTimeMins" id="endTimeMins" type="text" value="<?php echo $endTimeMins;?>" size="2" maxlength="2" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
			<?php 	if($hc_timeInput == 12){	?>
					<td>
						<select name="endTimeAMPM" id="endTimeAMPM" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}?>>
							<option <?php if($endTimeAMPM == "AM"){echo "selected=\"selected\"";}?> value="AM">AM</option>
							<option <?php if($endTimeAMPM == "PM"){echo "selected=\"selected\"";}?> value="PM">PM</option>
						</select>
					</td>
			<?php 	}//end if	?>
					<td><label for="ignoreendtime" style="padding-left:20px;" class="radio"><?php echo $hc_lang_event['NoEndTime'];?></label></td>
					<td><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" <?php if(isset($noEndTime)){echo "checked=\"checked\" ";}//end if?> <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
				</tr>
			</table>
	    </div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="overridetime"><?php echo str_replace(" ","&nbsp;",$hc_lang_event['Override']);?></label>&nbsp;<input <?php if($tbd > 0){echo "checked=\"checked\" ";}//end if?>type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" />
			<br />
			<label>&nbsp;</label>
			<label for="specialtimeall" class="radioWide"><input type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" <?php if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 1){echo "checked=\"checked\" ";}//end if?>/><?php echo $hc_lang_event['AllDay'];?></label>
			<br /><br />
			<label>&nbsp;</label>
			<label for="specialtimetbd" class="radioWide"><input type="radio" name="specialtime" id="specialtimetbd" value="tbd" class="noBorderIE" <?php if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 2){echo "checked=\"checked\" ";}//end if?>/><?php echo $hc_lang_event['TBA'];?></label>
			<br />
		</div>
		<br />
		<div class="frmOpt">
			<label for="cost"><?php echo $hc_lang_event['Cost'];?></label>
			<input name="cost" id="cost" type="text" value="<?php echo $cost;?>" size="25" maxlength="50" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['RegTitle'];?></legend>
		<div class="frmOpt">
			<label for="eventRegistration"><?php echo $hc_lang_event['Registration'];?></label>
			<select name="eventRegistration" id="eventRegistration" onchange="togRegistration();">
				<option <?php if($allowRegistration == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_event['Reg0'];?></option>
				<option <?php if($allowRegistration == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_event['Reg1'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventRegAvailable"><?php echo $hc_lang_event['Limit'];?></label>
			<input <?php if($allowRegistration == 0){echo "disabled=\"disabled\"";}?> name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="<?php echo $maxReg;?>" />&nbsp;<?php echo $hc_lang_event['LimitLabel'];?>
		</div>
<?php 	if($allowRegistration == 1){	?>
		<div class="frmOpt">
			<label>&nbsp;</label>
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = '" . $eID . "'");
			$regUsed = mysql_result($result,0,0);
			$regAvailable = $maxReg;
			
			if($maxReg == 0) {
				echo "<b>" . $regUsed . " " . $hc_lang_event['TotalReg'] . "</b>";
			} elseif($maxReg <= mysql_result($result,0,0)){	?>
				<img src="<?php echo CalAdminRoot;?>/images/meter/regOverflow.gif" width="100" height="7" alt="" border="0" style="border-left: solid #000000 0.5px; border-right: solid #000000 0.5px;" />
		<?php 	echo "<b>" . $regUsed . " " . $hc_lang_event['TotalReg'] . " </b> -- " . $hc_lang_event['Overflow'];
			} else {
				if($regAvailable > 0){
					if($regUsed > 0){
						$regWidth = ($regUsed / $regAvailable) * 100;
						$fillWidth = 100 - $regWidth;
					} else {
						$regWidth = 0;
						$fillWidth = 100;
					}//end if	?>
					<img src="<?php echo CalAdminRoot;?>/images/meter/meterGreen.gif" width="<?php echo $regWidth;?>" height="7" alt="" border="0" style="border-left: solid #000000 0.5px;" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: solid #000000 0.5px;" />
		<?php 		echo "<b>" . $regUsed . " " . $hc_lang_event['TotalReg']  . "</b>";
				}//end if
			}//end if	?>
		</div>
		<label>&nbsp;</label>
		<input style="width: 120px;" name="eventRegistrants" id="eventRegistrants" type="button" value="<?php if(isset($_GET['r'])){echo $hc_lang_event['RegButton1a'];}else{echo $hc_lang_event['RegButton1b'];}?>" onclick="togRegistrants();" class="button" />&nbsp;&nbsp;&nbsp;<input style="width: 160px;" name="eventSendRoster" id="eventSendRoster" type="button" value="<?php echo $hc_lang_event['RegButton2'];?>" onclick="javascript:if(confirm('<?php echo $hc_lang_event['Valid50'] . "\\n\\n          " . $hc_lang_event['Valid51'] . "\\n          " . $hc_lang_event['Valid52'];?>')){sendReg(<?php echo $eID;?>);};" class="button" />&nbsp;&nbsp;&nbsp;<input style="width: 120px;" name="addRegistrant" id="addRegistrant" type="button" value="<?php echo $hc_lang_event['RegButton3'];?>" onclick="window.location.href='<?php echo CalAdminRoot;?>/index.php?com=eventregister&amp;eID=<?php echo $eID;?>';" class="button" />
		<br /><br />
		<div id="registrant" style="display:<?php echo (isset($_GET['r'])) ? 'block' : 'none';?>;">
			<label>&nbsp;</label>
	<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE EventID = " . cIn($eID) . " ORDER BY RegisteredAt, GroupID, PkID");
			if(hasRows($result)){
				echo '<table cellpadding="2" cellspacing="0" border="0"><tr>';
				echo '<td width="160"><b>' . $hc_lang_event['Registrant'] . '</b></td>';
				echo '<td width="90"><b>' . $hc_lang_event['Phone'] . '</b></td>';
				echo '<td width="150"><b>' . $hc_lang_event['RegisteredAt'] . '</b></td>';
				echo '<td width="50" align="center">&nbsp;</td></tr>';
		 		$cnt = 0;
				$shown = false;
				while($row = mysql_fetch_row($result)){
					if($cnt == $maxReg && $maxReg > 0 && !$shown){
						$shown = true;
						$cnt = 0;
						echo '<tr><td colspan="4"><br /><b>' . $hc_lang_event['OverflowReg'] . '</b></td></tr>';
					}//end if
					
					echo '<tr>';
					echo ($cnt % 2 == 0) ? '<td class="main">' : '<td class="tblListHL">';
					echo ($cnt + 1 < 10) ? '0' . strval($cnt + 1) : $cnt + 1;
					echo ')&nbsp;<a href="mailto:' . cOut($row[2]) . '" class="main">' . cOut($row[1]) . '</a></td>';
					
					echo ($cnt % 2 == 0) ? '<td class="main">' : '<td class="tblListHL">';
					echo ($row[3] != '') ? cOut($row[3]) : 'N/A';
					echo '</td>';
					
					echo ($cnt % 2 == 0) ? '<td class="main">' : '<td class="tblListHL">';
					echo ($row[11] != '') ? stampToDate(cOut($row[11]), $hc_cfg24 . ' at ' . $hc_cfg23) : 'N/A';
					echo '</td>';
					
					echo ($cnt % 2 == 0) ? '<td class="main" align="center">' : '<td class="tblListHL" align="center">';
					echo '<a href="' . CalAdminRoot . '/index.php?com=eventregister&amp;rID=' . $row[0] . '&amp;eID=' . $eID . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
					echo '<a href="javascript:;" onclick="delReg(' . $row[0] . ');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></td>';
					echo '</tr>';
			 		++$cnt;
				}//end while
			echo '</table>';
			} else {
				echo $hc_lang_event['NoReg'] . '<br /><br />';
			}//end if	?>
		</div>
<?php 	}//end if?>
	</fieldset>
	<br />
	<fieldset>
		<input type="hidden" name="prevStatus" id="prevStatus" value="<?php echo $eventStatus;?>" />
		<legend><?php echo $hc_lang_event['Settings'];?></legend>
		<div class="frmOpt">
			<label for="eventStatus"><?php echo $hc_lang_event['Status'];?></label>
			<select name="eventStatus" id="eventStatus">
				<option <?php if($eventStatus == 1){echo "selected=\"selected\"";}//end if?> value="1"><?php echo $hc_lang_event['Status1'];?></option>
				<option <?php if($eventStatus == 2){echo "selected=\"selected\"";}//end if?> value="2"><?php echo $hc_lang_event['Status2'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventBillboard"><?php echo $hc_lang_event['Billboard'];?></label>
			<select name="eventBillboard" id="eventBillboard">
				<option <?php if($eventBillboard == 0){echo "selected=\"selected\"";}//end if?> value="0"><?php echo $hc_lang_event['Billboard0'];?></option>
				<option <?php if($eventBillboard == 1){echo "selected=\"selected\"";}//end if?> value="1"><?php echo $hc_lang_event['Billboard1'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_event['Categories'];?></label>
		<?php	$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, ec.EventID as Selected
							FROM " . HC_TblPrefix . "categories c 
							 	LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID AND ec.EventID = " . cIn($eID) . ")
							WHERE c.ParentID = 0 AND c.IsActive = 1 
							GROUP BY c.PkID 
							UNION 
							SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, ec.EventID as Selected 
							FROM " . HC_TblPrefix . "categories c
								LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID AND ec.EventID = " . cIn($eID) . ")
							WHERE c.ParentID > 0 AND c.IsActive = 1 
							GROUP BY c.PkID
							ORDER BY Sort, ParentID, CategoryName";
				getCategories('frmEventEdit', 3, $query);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['Location'];?></legend>
		<input type="hidden" id="locPreset" name="locPreset" value="<?php echo $locID;?>" />
		<div id="locSetting">
<?php 	if($locID > 0){
			echo "<div class=\"frmOpt\">";
			$resultL = doQuery("SELECT PkID, Name, Address, Address2, City, State, Country
								FROM " . HC_TblPrefix . "locations
								WHERE PkID = " . $locID);
			echo "<label>" . $hc_lang_event['CurLocation'] . "</label>";
			echo "<b>" . mysql_result($resultL,0,1) . "</b>";
			echo "</div><div class=\"frmOpt\">";
			echo "<label>&nbsp;</label>";
			echo mysql_result($resultL,0,2) . " " . mysql_result($resultL,0,3) . " " . mysql_result($resultL,0,4) . " " . mysql_result($resultL,0,5) . " " . mysql_result($resultL,0,6);
			echo "&nbsp;&nbsp;<a href=\"javascript:;\" onclick=\"setLocation(0);\" class=\"eventMain\">" . $hc_lang_event['ChngLocation'] . "</a>";
			echo "</div>";
		}//end if	?>
		</div>
		<div id="locSearch"<?php if($locID > 0){echo " style=\"display:none;\"";}?>>
<?php 	if($hasLoc > 0){	?>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<?php echo $hc_lang_event['CheckLocInst'];?>
			</div>
			<div class="frmReq">
				<label for="locSearch"><?php echo $hc_lang_event['LocSearch']?></label>
				<input type="text" name="locSearch" id="locSearch" onkeyup="searchLocations();" value = "" size="25" maxlength="100" />
				<a href="javascript:;" onclick="setLocation(0);" class="eventMain"><?php echo $hc_lang_event['ClearSearch']?></a>
			</div>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<div id="locSearchResults">&nbsp;</div>
			</div>
			<div id="customLocNotice" style="display:none;">
				<label>&nbsp;</label>
				<b><?php echo $hc_lang_event['PresetLoc'];?></b>
			</div>
<?php 	}//end if	?>
		</div>
		<div id="customLoc"<?php if($locID > 0){echo " style=\"display:none;\"";}?>>
		<div class="frmReq">
			<label for="locName"><?php echo $hc_lang_event['Name'];?></label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locName" id="locName" value="<?php echo $locName;?>" type="text" maxlength="50" size="40" /><span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress"><?php echo $hc_lang_event['Address'];?></label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress" id="locAddress" value="<?php echo $locAddress;?>" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress2">&nbsp;</label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress2" id="locAddress2" value="<?php echo $locAddress2;?>" type="text" maxlength="75" size="25" />
		</div>
		<div class="frmOpt">
			<label for="locCity"><?php echo $hc_lang_event['City'];?></label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locCity" id="locCity" value="<?php echo $locCity;?>" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locState"><?php echo $hc_lang_config['RegionLabel'];?></label>
		<?php 	$state = $locState;
				if($locID > 0){
					$stateDisabled = 1;
					$state = $hc_cfg21;
				}//end if
				include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['RegionFile']);?><span style="color: #0000FF;">*</span>
		</div>
		<div class="frmOpt">
			<label for="locZip"><?php echo $hc_lang_event['Postal'];?></label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locZip" id="locZip" value="<?php echo $locZip;?>" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locCountry"><?php echo $hc_lang_event['Country'];?></label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locCountry" id="locCountry" value="<?php echo $locCountry;?>" type="text" maxlength="50" size="5" />
		</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['Contact'];?></legend>
		<div class="frmOpt">
			<label for="contactName"><?php echo $hc_lang_event['Name'];?></label>
			<input name="contactName" id="contactName" type="text" value="<?php echo $contactName;?>" maxlength="50" size="20" /><span style="color: #008000;">*</span>
		</div>
		<div class="frmOpt">
			<label for="contactEmail"><?php echo $hc_lang_event['Email'];?></label>
			<input name="contactEmail" id="contactEmail" type="text" value="<?php echo $contactEmail;?>" maxlength="75" size="30" /><span style="color: #008000;">*</span>
		</div>
		<div class="frmOpt">
			<label for="contactPhone"><?php echo $hc_lang_event['Phone'];?></label>
			<input name="contactPhone" id="contactPhone" type="text" value="<?php echo $contactPhone;?>" maxlength="25" size="20" />
		</div>
		<div class="frmOpt">
			<label for="contactURL"><?php echo $hc_lang_event['Website'];?></label>
			<input name="contactURL" id="contactURL" type="text" value="<?php echo $contactURL;?>" maxlength="100" size="40" />
	<?php 	if($contactURL != 'http://'){	?>
				<a href="<?php echo $contactURL;?>" target="_blank"><img src="<?php echo CalAdminRoot;?>/images/icons/iconWebsite.png" width="16" height="16" alt="" border="0" /></a>
	<?php 	}//end if	?>
		</div>
	</fieldset>
	<br />
	<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(hasRows($result)){
		if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) != ''){	?>
		<br />
		<fieldset>
		<?php
			$resultEF = doQuery("SELECT * FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . $efCheck . ")");
			if(hasRows($resultEF)){	?>
				<legend><?php echo $hc_lang_event['EventfulUpdate'];?></legend>
				<a href="http://eventful.com/events/<?php echo mysql_result($resultEF,0,1)?>" class="eventMain" target="_blank"><?php echo $hc_lang_event['EventfulView'];?></a><br /><br />
				<input type="hidden" name="efSetting" id="efSetting" value="modify" />
				<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;<?php echo $hc_lang_event['EventfulLabelU'];?></label>
		<?php
			} else {?>
				<legend><?php echo $hc_lang_event['EventfulAdd'];?></legend>
				<input type="hidden" name="efSetting" id="efSetting" value="new" />
				<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;<?php echo $hc_lang_event['EventfulLabelA'];?></label>
		<?php
			}//end if	?>
			<div id="eventful" style="display:none;clear:both;">
		<?php
			if(mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){	?>
			<div style="width:70%;padding:5px;border:solid 1px #0043FF;background:#EFEFEF;">
			<?php echo $hc_lang_event['EventfulReq'];?>
			<br /><br />
				<div class="frmOpt">
					<label for="efUser" class="settingsLabel"><?php echo $hc_lang_event['Username'];?></label>
					<input name="efUser" id="efUser" type="text" value="" size="20" maxlength="150" />
				</div>
				<div class="frmOpt">
					<label for="efPass" class="settingsLabel"><?php echo $hc_lang_event['Passwrd1'];?></label>
					<input name="efPass" id="efPass" type="password" value="" size="15" maxlength="30" />
				</div>
				<div class="frmOpt">
					<label for="efPass2" class="settingsLabel"><?php echo $hc_lang_event['Passwrd2'];?></label>
					<input name="efPass2" id="efPass2" type="password" value="" size="15" maxlength="30" />
				</div>
			</div>
		<?php			
			}//end if	
			echo $hc_lang_event['EventfulSubmit'];	?>
			</div>
		</fieldset>
<?php
		}//end if
	}//end if	?>
	<br />
	<input name="submit" id="submit" type="submit" value=" <?php echo $hc_lang_event['Save'];?> " class="button" />
<?php
	if(!isset($_POST['eventID'])){	?>
	&nbsp;&nbsp;|&nbsp;&nbsp;
	<input name="makeClone" id="makeClone" type="submit" value=" <?php echo $hc_lang_event['SaveNew'];?> " class="button" onclick="cloneEvent();" />
<?php
	}//end if	?>
	</form>
<?php
	}//end if	?>
	<div id="dsCal" class="datePicker"></div>