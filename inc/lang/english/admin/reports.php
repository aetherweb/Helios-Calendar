<?php
$hc_lang_reports = array(

//	Event Activity
'TitleOver'			=>	'Calendar Overview Report',
'InstructOver'		=>	'The report below contains a variety of realtime statistics about your Helios Calendar.<br /><br /><b>Note:</b> Information available in other specialized reports is not included in this report.',

//	Event Activity
'TitleAct'			=>	'Event Activity Report',
'InstructAct'		=>	'The report below contains activity data for your selected events. The user interaction stats below are tracked in realtime.<br /><br /><img src="' . AdminRoot . '/img/icons/edit_new.png" width="16" height="16" alt="" /> = Edit Event in New Window<br /><img src="' . AdminRoot . '/img/icons/calendar.png" width="16" height="16" alt="" /> = View Event in Public Calendar',

//	Recently Added
'TitleAdd'			=>	'Recently Added Events',
'InstructAdd'		=>	'The report below contains up to one hundred of the most recently added events and event series. The event ID listed for event series is the ID of the first event within the series.<br /><br /><img src="' . AdminRoot . '/img/icons/edit.png" width="16" height="16" alt="" /> = Edit Event<br /><img src="' . AdminRoot . '/img/icons/edit_group.png" width="16" height="16" alt="" /> = Edit Event Series<br /><img src="' . AdminRoot . '/img/icons/view_series.png" width="16" height="16" alt="" /> = View Events in Series<br /><img src="' . AdminRoot . '/img/icons/billboard.png" width="16" height="16" alt="" /> = Event Appears on Billboard (Click to Administer Billboard)',

//	Popular Events
'TitlePop'			=>	'Most Popular Events',
'InstructPop'		=>	'The report below contains up to one hundred of the most popular active events. Once an event has occured it will be removed from this list. Popularity is ranked by average daily views: Total Views / Days Published = Popularity<br /><br /><img src="' . AdminRoot . '/img/icons/edit.png" width="16" height="16" alt="" /> = Edit Event in New Window<br /><img src="' . AdminRoot . '/img/icons/billboard.png" width="16" height="16" alt="" /> = Event Appears on Billboard (Click to Administer Billboard)',

//	Duplicate Events
'TitleDup'			=>	'Duplicate Events',
'InstructDup'		=>	'The follow list contains events that appear to have duplicates present in the public calendar. The first event is the earliest occurrence of the event and under it are it\'s believed duplicates.<br /><br />By default event titles are included in the comparison. To exclude title as a required component for duplicate events (and compare only location, date &amp; time) uncheck the box below.<br /><br /><img src="' . AdminRoot . '/img/icons/edit.png" width="16" height="16" alt="" /> = Edit Event<br /><img src="' . AdminRoot . '/img/icons/delete.png" width="16" height="16" alt="" /> = Delete Event',

//	Failed Login
'TitleFail'			=>	'Failed Admin Sign Ins',
'InstructFail'		=>	'The follow list contains the last 200 failed admin sign in attempts. A failed attempt is only logged when the sign in is for a valid account. Deleting individual failed records can unlock accounts with too many failed attempts allowing admin users to access their account early.<br /><br />To view the detailed sign in history of an individual admin edit their account to view their account summary.<br /><br /><img src="' . AdminRoot . '/img/icons/user_edit.png" width="16" height="16" alt="" /> = Edit Admin<br /><img src="' . AdminRoot . '/img/icons/delete.png" width="16" height="16" alt="" /> = Delete Failed Record',

//	Page Elements
'NewReport'			=>	'Generate New Report',
'CalAve'			=>	'Average:',
'CalBest'			=>	'Best:',
'Views'				=>	'Views',
'Directions'		=>	'Directions',
'Downloads'			=>	'Downloads',
'EmailTo'			=>	'Email',
'URL'				=>	'URL',
'EventDate'			=>	'Event Date:',
'PublishDate'		=>	'Published Date:',
'DaysPublished'		=>	'Days Published:',
'InvalidEvent'		=>	'Invalid event(s).',
'ClickEvent'		=>	'Click here to find an event.',
'EventReport'		=>	'Event Report',
'PoweredBy'			=>	'Powered by',
'EventID'			=>	'Event (ID)',
'Added'				=>	'Added By',
'Occurs'			=>	'Occurs',
'Views'				=>	'Views',
'NoEvent'			=>	'There are currently no events available for this report.',
'Overview'			=>	'Overview Report',
'General'			=>	'General Calendar Statistics',
'AllEvents'			=>	'All Events Activity',
'Active'			=>	'Current Events Activity',
'Passed'			=>	'Passed Events Activity',
'ActiveLabel'		=>	'Current Events',
'PassedLabel'		=>	'Passed Events',
'TotalLabel'		=>	'All Events',
'Billboard'			=>	'Active Billboard Events',
'Orphan'			=>	'Orphan Events',
'Today'				=>	'Events Today',
'Next7'				=>	'Events Through Next 7 Days',
'Next30'			=>	'Events Through Next 30 Days',
'ActiveUsers'		=>	'Confirmed Newsletter Subscribers',
'Earliest'			=>	'Earliest Event',
'Latest'			=>	'Latest Event',
'AvePerCat'			=>	'Average Active Events Per Category',
'DriveDir'			=>	'Driving Directions',
'Generated'			=>	'Generated By',
'ActiveStats'		=>	'Active Event Stats',
'Average'			=>	'Average',
'Total'				=>	'Total',
'PastStats'			=>	'Past Event Stats',
'TotalStats'		=>	'Total Event Stats',
'Date'				=>	'Date',
'Count'				=>	'Count',
'CreatedAt'			=>	'Created at:',
'Calendar'			=>	'Calendar:',
'GeneratedBy'		=>	'Generated by:',
'MostViewed'		=>	'Most Views',
'MostMViewed'		=>	'Most Mobile Viewed Event',
'MostDirections'	=>	'Most Driving Directions',
'MostDownloads'		=>	'Most Downloads',
'MostEmail'			=>	'Most Email to a Friend',
'MostURL'			=>	'Most URL Clicks',
'RecentEvent'		=>	'Recently Added Events',
'EventDate'			=>	'Event Date',
'Published'			=>	'Published',
'RecentSubmit'		=>	'Recently Submitted Events',
'NewestUsers'		=>	'Recent Newsletter Subscriptions',
'Email'				=>	'Email',
'Registered'		=>	'Registered',
'NoEvents'			=>	'There are no events currently available for this report:',
'Location'			=>	'Location',
'IncludeTitle'		=>	'Include Event Title in Comparison',
'NA'				=>	'N/A',
'DaysActive'		=>	'Days',
'Daily'				=>	'Daily Avg.',
'ByAdmin'			=>	'Admin User',
'ByPub'				=>	'Public User',
'Delete'			=>	'Delete Selected Events',
'IP'				=>	'IP Address',
'User'				=>	'User Agent',
'NoFails'			=>	'There are currently no failed sign ins for this report.',

//	Validation
'Valid01'			=>	'Event Delete Is Permanent!\nAre you sure you want to delete the selected event?',
'Valid02'			=>	'Ok = YES Delete Events',
'Valid03'			=>	'Cancel = NO Do NOT Delete Events',
'Valid04'			=>	'Sign In History Delete Is Permanent!\nAre you sure you want to delete the selected failed record?',
'Valid05'			=>	'Ok = YES Delete Failed Record',
'Valid06'			=>	'Cancel = NO Do NOT Delete Failed Record',
'Valid09'			=>	'No events selected.\\nPlease select at least one event and try again.',

//	Feedback
'Feed01'			=>	'Duplicate events deleted successfully.',
'Feed02'			=>	'Failed sign in history record deleted successfully.',
);	?>