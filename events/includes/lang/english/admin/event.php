<?php
$hc_lang_event = array(

//	Add Event
'TitleAdd'			=>	'Add Event',
'InstructAdd'		=>	'To add an event to please fill out the form below.<br /><br />(<span style="color: #DC143C;">*</span>) = Required Fields<br />(<span style="color: #0000FF;">*</span>) = Optional Fields, but required for dynamic driving directions<br />(<span style="color: #008000;">*</span>) = Required for events <b>with registration</b>',

//	Edit Event
'TitleEdit'			=>	'Edit Event',
'InstructEdit'		=>	'You can make changes to the event via the form below then click \'Save Event\'.<br /><br />(<span style="color: #DC143C">*</span>) = Required Fields<br />(<span style="color: #0000FF;">*</span>) = Optional Fields, but required for dynamic driving directions<br />(<span style="color: green;">*</span>) = Required for events <b>with registration</b>',
'TitleGroup' 		=>	'Group Event Edit',
'InstructGroup'		=>	'You can make changes to the events via the form below then click \'Save Event\'.<br />If you make a mistake and wish to start over click the \'Reset Form\' button below.<br /><br />(<span class="eventReqTag">*</span>) = Required Fields<br />(<span style="color: blue;">*</span>) = Optional Fields, but required for dynamic driving directions<br />(<span style="color: green;">*</span>) = Required for events <b>with registration</b>',
'LiveClipEdit'		=>	'Copy Event Using Live Clipboard',
'EditWarning'		=>	'You are attempting to edit an invalid event.',
'GroupNotice'		=>	'You are editing this event for the following dates:',
'GroupCombine'		=>	'Combine Events to New Series',

//	Pending Event
'TitlePendingA'		=>	'Pending Events',
'InstructPendingA'	=>	'The list below contains all pending events, and event series, currently stored in your Helios Calendar, sorted from oldest submissions to newest.<br /><br />To perform a batch decline/delete check the checkbox beside the events you want to decline and click "Decline &amp; Delete Selected Events" below.<br /><br /><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> = Review Event Details (Approve/Decline Single Event)<br /><img src="' . CalAdminRoot . '/images/icons/iconEditGroup.png" width="15" height="15" alt="" border="0" align="middle" /> = Review Event Series Details (Approve/Decline Event Series)',
'TitlePendingB'		=>	'Pending Event Status Update',
'InstructPendingB'	=>	'Complete the form below to Approve or Decline this Event',
'PendingIndividual'	=>	'Pending Individual Events',
'PendingSeries'		=>	'Pending Event Series',
'ApproveWarning'	=>	'You are attempting to approve an invalid event.',
'EmailSubject'		=>	'Event Status Change',

//	Form Elements
'EventDetail'		=>	'Event Details',
'Title'				=>	'Title:',
'Description'		=>	'Description:',
'Date'				=>	'Event Date:',
'StartTime'			=>	'Start Time:',
'EndTime'			=>	'End Time:',
'NoEndTime'			=>	'No End Time:',
'Override'			=>	'Override Times',
'AllDay'			=>	'All Day Event',
'TBA'				=>	'Event Times To Be Announced',
'Cost'				=>	'Cost:',
'RecurInfo'			=>	'Event Recurrence Information',
'Recur'				=>	'Event Recurs:',
'RecurCheck'		=>	'(Check If Recurring)',
'RecDaily'			=>	'Daily',
'Every'				=>	'Every',
'xDays'				=>	'day(s)',
'Daily2'			=>	'All Weekdays',
'RecWeekly'			=>	'Weekly',
'xWeeks'			=>	'week(s) on:',
'RecMonthly'		=>	'Monthly',
'Day'				=>	'Day',
'ofEvery'			=>	'of every',
'Months'			=>	'month(s)',
'First'				=>	'First',
'Second'			=>	'Second',
'Third'				=>	'Third',
'Fourth'			=>	'Fourth',
'Last'				=>	'Last',
'RecurUntil'		=>	'Recurs Until:',
'Confirm'			=>	'Click Here to Confirm Dates',
'Sun'				=>	'Sun',
'Mon'				=>	'Mon',
'Tue'				=>	'Tue',
'Wed'				=>	'Wed',
'Thu'				=>	'Thu',
'Fri'				=>	'Fri',
'Sat'				=>	'Sat',
'RegTitle'			=>	'Event Registration',
'Registration'		=>	'Registration:',
'Reg0'				=>	'Do Not Allow Registration',
'Reg1'				=>	'Allow Registration',
'Limit'				=>	'Limit:',
'LimitLabel'		=>	'(0 = Unlimited)',
'TotalReg'			=>	'Total Registrants',
'Overflow'			=>	'Registering Overflow Only',
'Registrant'		=>	'Registrant',
'RegisteredAt'		=>	'Registered At',
'RegButton1a'		=>	'Hide Registrants',
'RegButton1b'		=>	'Show Registrants',
'RegButton2'		=>	'Send Roster to Contact',
'RegButton3'		=>	'Add Registrant',
'NoReg'				=>	'There are currently no registrants for this event.',
'Settings'			=>	'Event Settings',
'Status'			=>	'Status:',
'Status0'			=>	'Declined -- Remove from Calendar',
'Status1'			=>	'Approved -- Show on Calendar',
'Status1P'			=>	'Approved -- Add to Calendar',
'Status2'			=>	'Pending -- Hidden on Calendar',
'Billboard'			=>	'Billboard:',
'Billboard0'		=>	'Do Not Show on Billboard',
'Billboard1'		=>	'Show on Billboard',
'Categories'		=>	'Categories:',
'Location'			=>	'Location Information',
'LocNew'			=>	'Save as New Location',
'Preset'			=>	'Preset:',
'Preset1'			=>	'Custom Location (Enter Location Below)',
'Name'				=>	'Name:',
'Address'			=>	'Address:',
'City'				=>	'City:',
'Postal'			=>	'Postal Code:',
'Country'			=>	'Country:',
'Contact'			=>	'Event Contact Info',
'Email'				=>	'Email:',
'Phone'				=>	'Phone:',
'Website'			=>	'Website:',
'Message'			=>	'Confirmation Message',
'SendMsg'			=>	'Send Confirmation Message',
'EventfulAdd'		=>	'Add This Event to eventful',
'EventfulUpdate'	=>	'Update This Event on eventful',
'EventfulView'		=>	'View this event on eventful',
'EventfulLabelA'	=>	'Check to Add to <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b>',
'EventfulLabelU'	=>	'Check to Update to <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b>',
'EventfulReq'		=>	'<b>eventful Username &amp; Password Required</b><br />Enter your eventful Username &amp; Password to submit this event.<br /><br />To skip this step in the future save your eventful account info in your Helios Calendar Settings',
'Username'			=>	'Username:',
'Passwrd1'			=>	'Password:',
'Passwrd2'			=>	'Confirm Password:',
'EventfulSubmit'	=>	'The following information about this event will be submitted:<ul><li>Title</li><li>Description</li><li>Start &amp; End Time</li><li>Venue ID*</li><li>Categories (Listed on eventful as "Tags")</li><li>Cost</li></ul><b>*Note:</b> If you did not select a preset location previously submitted to eventful the event location information provided will be included in the event description.',
'SelectAll'			=>	'Select All',
'DeselectAll'		=>	'Deselect All',
'Message'			=>	'Message From Event Submitter',
'NoMessage'			=>	'Submitter Email Address Unavailable. Confirmation Cannot be sent.',
'NoPending'			=>	'There are currently no pending events.',
'OverflowReg'		=>	'Overflow Registrants',
'PresetLoc'			=>	'Location selected. Please complete the rest of this form.',
'CheckLocInst'		=>	'Enter Location Name, after 4 or more characters results will appear.',
'CurLocation'		=>	'Current Location:',
'ChngLocation'		=>	'Change Location',
'DeletePreset'		=>	'Delete Preset',
'LocSearch'			=>	'Name Search:',
'ClearSearch'		=>	'Clear Search',

//	Form Buttons
'Save'				=>	'Save Event',
'SaveNew'			=>	'Save As New Event',
'Cancel'			=>	'Cancel',
'DeclineDelete'		=>	'Decline and Delete Selected Events',
'SaveWMessage'		=>	'Save Event & Send Message',
'SaveWOMessage'		=>	'Save Event',

//	Validation Feedback
'Valid01'			=>	'Event could not be added for the following reason(s):',
'Valid01b'			=>	'Event could not be updated for the following reason(s):',
'Valid02'			=>	'*Registration Limit Value Must Be Numeric',
'Valid03'			=>	'*Registration Requires Contact Name',
'Valid04'			=>	'*Registration Requires Contact Email Address',
'Valid05'			=>	'*Start Hour Must Be Numeric',
'Valid06'			=>	'*Start Hour Must Be Between',
'Valid07'			=>	'*Start Minute Must Be Numeric',
'Valid08'			=>	'*Start Minute Must Be Between',
'Valid09'			=>	'*End Hour Must Be Numeric',
'Valid10'			=>	'*End Hour Must Be Between',
'Valid11'			=>	'*End Minute Must Be Numeric',
'Valid12'			=>	'*End Minute Must Be Between',
'Valid13'			=>	'*Event Title is Required',
'Valid14'			=>	'*Event Date is Required',
'Valid15'			=>	'*Category Assignment is Required',
'Valid16'			=>	'*Location Name is Required',
'Valid18'			=>	'You have selected a past date for this event.\\nAre you sure you want to add this event as a past event?',
'Valid19'			=>	'OK = YES Continue Adding Past Event',
'Valid20'			=>	'CANCEL = NO Cancel and Change Date',
'Valid21'			=>	'*Event Contact Email Format is Invalid',
'Valid22'			=>	'Please complete the form and try again.',
'Valid23'			=>	'*Event Date is Required',
'Valid24'			=>	'*Event Date is Invalid Date or Format. Required Format:',
'Valid25'			=>	'*Event Recur End Date is Required',
'Valid26'			=>	'*Recur End Date is Invalid Date or Format. Required Format:',
'Valid27'			=>	'*Event Date Cannot Occur After End Recur Date',
'Valid28'			=>	'*Event Recur End Date Cannot Be Event Date',
'Valid29'			=>	'*Daily Recurence Days Must Be Numeric',
'Valid30'			=>	'*Daily Recurence Days Must Be Greater Than 0',
'Valid31'			=>	'*Daily Recurence Days Required',
'Valid32'			=>	'*Weekly Recurence Weeks Must Be Numeric',
'Valid33'			=>	'*Weekly Recurrence Weeks Must Be Greater Than 0',
'Valid34'			=>	'*Weekly Recurrence Weeks Required',
'Valid35'			=>	'*Weekly Recurrence Requires At Least 1 Day Selected',
'Valid36'			=>	'*Monthly Recurence Day Must Be Numeric',
'Valid37'			=>	'*Monthly Recurence Day Must Be Greater Than 0',
'Valid38'			=>	'*Monthly Recurence Day Required',
'Valid39'			=>	'*Monthly Recurence Month Must Be Numeric',
'Valid40'			=>	'*Monthly Recurence Month Must Be Greater Than 0',
'Valid41'			=>	'*Monthly Recurence Month Required',
'Valid42'			=>	'*Monthly Recurence Month Must Be Numeric',
'Valid43'			=>	'*Monthly Recurence Month Must Be Greater Than 0',
'Valid44'			=>	'*Monthly Recurence Month Required',
'Valid45'			=>	'Cannot confirm dates for the following reason(s):',
'Valid46'			=>	'To confirm dates you must enter event recurrence information.',
'Valid47'			=>	'Registrant Delete Is Permanent!\\nAre you sure you want to delete?',
'Valid48'			=>	'Ok = YES Delete Registrant',
'Valid49'			=>	'Cancel = NO Do Not Delete Registrant',
'Valid50'			=>	'This will send a full roster of all current registrants to the event contact listed below.\\nAre you sure you want to send the roster?',
'Valid51'			=>	'Ok = YES Send Roster to Event Contact',
'Valid52'			=>	'Cancel = NO Do Not Send Roster',
'Valid53'			=>	'Saving as a new event will create a new event entry based on the current settings of this event.\\nThe original event will remain unchanged.\\n\\nDo you want to create a new event?',
'Valid54'			=>	'Ok = YES, Create New Event',
'Valid55'			=>	'Cancel = NO, Save Changes to This Event',
'Valid56'			=>	'No events selected.\\nPlease select at least one event and try again.',
'Valid57'			=>	'Event Delete Is Permanent!\\nAre you sure you want to decline & delete the selected event(s)',
'Valid58'			=>	'Ok = YES Delete Event(s)',
'Valid59'			=>	'Cancel = NO Do NOT Delete Event(s)',
'Valid60'			=>	'*Registration Limit Value Must Be Be Greater Than Or Equal To 0',

//	Feedback
'Feed01'			=>	'Event Updated Successfully!',
'Feed02'			=>	'Event Added Successfully!',
'Feed03'			=>	'Registrant Added Successfully.',
'Feed04'			=>	'Registrant Updated Successfully.',
'Feed05'			=>	'Registrant Deleted Successfully.',
'Feed06'			=>	'Registrant Roster Sent to Event Contact Successfully.',
'Feed07'			=>	'Add to Helios Successful. Eventful Submission Failed: API Key Not Found.',
'Feed08'			=>	'Add to Helios Successful. Eventful Submission Failed: Connection Failed.',
'Feed09'			=>	'Add to Helios and Eventful Submission Successful.',
'Feed10'			=>	'Event Update and Eventful Re-Submission Successful.',
'Feed11'			=>	'Event Update and Eventful Submission Successful.',
'Feed12'			=>	'Event Approved Successfully!',
'Feed13'			=>	'Event Series Approved Successfully!',
'Feed14'			=>	'Event Declined Successfully!',
'Feed15'			=>	'Event Series Declined Successfully!',
'Feed16'			=>	'Event(s) Declined and Deleted Successfully!',
'Feed17'			=>	'Event Approved and Submitted to Eventful Successfully!',
'Feed18'			=>	'Event Series Approved and Submitted to Eventful Successfully!',
'Feed19'			=>	'Event Updated Successfully! Event Still Pending',
'Feed20'			=>	'Event Series Updated Successfully! Event Series Still Pending',
);	?>