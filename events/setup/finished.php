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
/*
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 	|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	if(!isset($_SESSION['license']) || $_SESSION['license'] == false || !isset($_SESSION['good']) || $_SESSION['good'] == false || !isset($_SESSION['valid']) || $_SESSION['valid'] == false || !isset($_SESSION['done']) || $_SESSION['done'] == false){
		fail();
	} else {
		echo '<fieldset>';
		echo '<h3>Congratulations!...</h3>';
		echo 'Setup of your Helios Calendar is now complete. To complete the install process <b>delete the /setup directory</b> and click the links below to start using your Helios Calendar.';
		echo '<br /><br /><a href="' . CalRoot . '/" class="main" target="_blank" style="line-height:20px;">Click here to access your public calendar.</a>';
		echo '<br /><a href="' . CalAdminRoot . '/" class="main" target="_blank" style="line-height:20px;">Click here to access your administration console.</a> (after deleting /setup directory)';
		echo '<br /><h3>...and Welcome!</h3>';
		echo 'Helios Calendar is not built by Refresh Web Development alone, it is directly influenced by a very close-knit community of our fellow developers, publishers, event promoters and people from every walk of life around the world.';
		echo '<br /><br />We invite you to join the conversation and share your Helios Calendar story at <a href="http://www.refreshmy.com/forum/" class="main" target="_blank">the Refresh Community Forum</a>.';
		echo '<br /><br />Thank you for using Helios Calendar!';
		echo '</fieldset>';
	}//end if