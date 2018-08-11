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
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/tools.php');
	
	appInstructions(0, "Event_Export", $hc_lang_tools['TitleExport'], $hc_lang_tools['InstructExport']);?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION['LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_tools['Valid01'];?>";
	startDate = document.frmEventExport.startDate.value;
	endDate = document.frmEventExport.endDate.value;
		
		if(!isDate(document.frmEventExport.startDate.value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid02'] . " " . strtolower($hc_cfg24);?>';
		} else if(document.frmEventExport.startDate.value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_tools['Valid03'];?>";
		}//end if 
		
		if(!isDate(document.frmEventExport.endDate.value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid04'] . " " . strtolower($hc_cfg24);?>';
		} else if(document.frmEventExport.endDate.value == ''){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_tools['Valid05'];?>";
		}//end if
		
		if(compareDates(startDate, '<?php echo $hc_cfg51;?>', endDate, '<?php echo $hc_cfg51;?>') == 1){
			dirty = 1;
			warn = warn + "\n<?php echo $hc_lang_tools['Valid06'];?>";
		}//end if
	
		if(validateCheckArray('frmEventExport','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_tools['Valid07'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_tools['Valid08'];?>');
			return false;
		} else {
			if(document.frmEventExport.mID.value == 1){
				document.frmEventExport.target='_blank';
			} else if(document.frmEventExport.mID.value == 2) {
				document.frmEventExport.target='_self';
			}//end if
			return true;
		}//end if
	}//end chkFrm
	
	var calx = new CalendarPopup("dsCal");
	calx.showNavigationDropdowns();
	calx.setCssPrefix("hc_");
	//-->
	</script>
	<form name="frmEventExport" id="frmEventExport" method="post" action="<?php echo CalAdminRoot . "/components/ToolExportAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="dateFormat" id="dateFormat" value="<?php echo $hc_cfg24;?>" />
	<input type="hidden" name="timeFormat" id="timeFormat" value="<?php echo $hc_cfg23;?>" />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Export'];?></legend>
		<div class="frmReq">
		<?php 
			$result = doQuery("SELECT PkID, Name, Extension FROM " . HC_TblPrefix . "templates WHERE IsActive = 1 AND TypeID = 1 ORDER BY Name");
			if(hasRows($result)){
				echo '<select name="tID" id="tID">';
				while($row = mysql_fetch_row($result)){
					echo '<option value="' . $row[0] . '">' . $row[1] . ' (' . $row[2] . ')</option>';
				}//end while
				echo '</select>';
			} else {
				$fail = 1;
			}//end if?>	
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Delivery'];?></legend>
		<div class="frmReq">
			<select name="mID" id="mID">
				<option value="1"><?php echo $hc_lang_tools['Delivery1'];?></option>
				<option value="2"><?php echo $hc_lang_tools['Delivery2'];?></option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Range'];?></legend>
		<div class="frmReq">
			<input size="12" maxlength="10" type="text" name="startDate" id="startDate" value="<?php echo strftime($hc_cfg24);?>" /><div class="hc_align">&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventExport.startDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>&nbsp;</div>
			<div class="hc_align">&nbsp;<?php echo $hc_lang_tools['To'];?>&nbsp;&nbsp;</div>
			<input size="12" maxlength="10" type="text" name="endDate" id="endDate" value="<?php echo strftime($hc_cfg24, mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));?>" /><div class="hc_align">&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventExport.endDate,'anchor2','<?php echo $hc_cfg51;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a></div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['CategoriesLabel'];?></legend>
		<div class="frmReg">
	<?php 	getCategories('frmEventExport',3);?>
		</div>
	</fieldset>
	<br />
	<?php 
		if(!isset($fail)){
			echo '<input type="submit" name="submit" id="submit" value="  ' . $hc_lang_tools['Generate'] . '  " class="button" />';
		}//end if?>
	</form>
	<div id="dsCal" class="datePicker"></div>