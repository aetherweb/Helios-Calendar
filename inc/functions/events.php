<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Increment event view count.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $eID ID number of the event to increment.
	 * @return void
	 */
	function event_add_session_view($eID){
		global $hc_cfg;
		
		if(preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']) || in_array($eID,$_SESSION['hc_trail']))
			return 0;
		
		array_push($_SESSION['hc_trail'], $eID);
		doQuery("UPDATE " . HC_TblPrefix . "events SET Views = Views + 1 WHERE PkID = ?", array(cIn($eID)));
	}
	/**
	 * Retrieve active ids/dates for event series.
	 * @since 2.0.0
	 * @version 2.0.2
	 * @param string $series Event Series ID
	 * @param integer $list Number of dates to return. (0 = All Active Dates in Series)
	 * @param string $format Output format for date string. (Default: Date Output Format - Admin Console Setting)
	 * @return array Event IDs / Event Dates (Ordered Chronologically by Date)
	 */
	function event_series_dates($series,$list,$format = ''){
		global $hc_cfg;
		
		$format = ($format == '') ? $hc_cfg[14] : $format;
		$limit = '';
		if ($list > 0) { $limit = " LIMIT ?"; $limitp = cIn($list); }
		$result = doQuery("SELECT PkID, StartDate FROM " . HC_TblPrefix . "events WHERE SeriesID = ? AND IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "' ORDER BY StartDate $limit", array(cIn($series), $limitp));
		
		if(!hasRows($result))
			return 0;
		
		$dates = array();
		while($row = hc_mysql_fetch_row($result)){
			$dates[$row[0]] = stampToDate($row[1],$format);}
		return $dates;
	}
	/**
	 * Output unordered list (markup - ul) of event dates in series with links to events.
	 * @since 2.0.0
	 * @version 2.0.2
	 * @param string $series Event Series ID
	 * @param string $current Date of currently viewed event, highlighted. (Format: YYYY-MM-DD)
	 * @param integer $size Number of dates to return. (Default: 5, 0 = All Active Dates in Series)
	 * @param string $format Output format for date string. (Default: Date Output Format - Admin Console Setting)
	 * @return void
	 */
	function event_series($series,$current = '',$size = 5,$format = ''){
		global $hc_lang_event;
		
		$dates = event_series_dates($series,$size,$format);
		if(count($dates) < 2)
			return 0;
		
		echo '<ul class="series">';
		foreach($dates as $id => $date){
			$today = ($current == $date) ? ' class="series_today"' : '';
			echo '
			<li'.$today.'><a href="'.CalRoot.'/index.php?eID='.$id.'">'.$date.'</a></li>';}
		echo '
		</ul>
		<a href="'.CalRoot.'/index.php?com=series&amp;sID='.$series.'" class="series">'.$hc_lang_event['AllDates'].'</a>';
	}
	/**
	 * Output unordered list of event categories.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $event Event ID
	 * @return void
	 */
	function event_categories($event){
		$result = doQuery("SELECT c.PkID, c.CategoryName
						FROM " . HC_TblPrefix . "eventcategories ec
							LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
						WHERE c.IsActive = 1 AND ec.EventID = ?
						ORDER BY c.CategoryName", array(cIn($event)));
		if(!hasRows($result))
			return 0;
		
		echo '
		<ul>';
		while($row = hc_mysql_fetch_row($result)){
			echo '
		<li><a itemprop="eventType" href="'.CalRoot.'/index.php?com=searchresult&amp;t='.$row[0].'" rel="nofollow">'.cOut($row[1]).'</a></li>';
		}
		echo '
		</ul>';
	}
	/**
	 * Retrieves array of event data.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return array Event Data
	 */
	function event_fetch(){
		global $eID, $hc_cfg, $hc_lang_event, $title, $desc, $expire;
		
		$result = doQuery("SELECT e.PkID, e.Title, e.Description, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.ContactName, e.ContactEmail, e.ContactURL, e.ContactPhone, 
							e.LocID, e.SeriesID, e.Cost, e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, e.LocationZip, e.LocCountry, 
							e.ShortURL, e.LastMod, e.Image, e.IsFeature, e.HideDays, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.URL, l.Phone, l.Email,
							l.Lat, l.Lon, en.NetworkID, er.Type,
						IF(er.Type != 1, NULL,
							IF(er.RegOption = 0, 
								(SELECT CONCAT(er.OpenDate,'|',er.CloseDate,'|',er.Space,'|',er.RegOption,'|',COUNT(r.EventID),'|',e.PkID) FROM " . HC_TblPrefix . "eventrsvps er LEFT JOIN " . HC_TblPrefix . "registrants r ON (r.EventID = er.EventID AND r.IsActive = 1) WHERE er.EventID = e.PkID GROUP BY er.OpenDate,er.CloseDate,er.Space,er.RegOption LIMIT 1),
								(SELECT CONCAT(er.OpenDate,'|',er.CloseDate,'|',er.Space,'|',er.RegOption,'|',COUNT(r.EventID),'|',MIN(e2.PkID)) FROM " . HC_TblPrefix . "eventrsvps er LEFT JOIN " . HC_TblPrefix . "registrants r ON (r.EventID = er.EventID AND r.IsActive = 1) WHERE er.EventID = MIN(e2.PkID) GROUP BY er.OpenDate,er.CloseDate,er.Space,er.RegOption LIMIT 1)
							)
						) AS RSVPOpts
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "events e2 ON (e.SeriesID = e2.SeriesID)
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							LEFT JOIN " . HC_TblPrefix . "eventnetwork en ON (e.PkID = en.EventID AND en.NetworkType = 2)
							LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (e.PkID = er.EventID)
						WHERE
						    e.IsActive = 1 AND e.IsApproved = 1
							   AND e.PkID = ?
						GROUP BY e.PkID , e.Title , e.Description , e.StartDate , e.StartTime , e.EndTime , e.TBD , e.ContactName , e.ContactEmail , e.ContactURL , 
						e.ContactPhone , e.LocID , e.SeriesID , e.Cost , e.LocationName , e.LocationAddress , e.LocationAddress2 , e.LocationCity , e.LocationState ,
						e.LocationZip , e.LocCountry , e.ShortURL , e.LastMod , e.Image , e.IsFeature , e.HideDays , l.Name , l.Address , l.Address2 , l.City , l.State , 
						l.Zip , l.Country , l.URL , l.Phone , l.Email , l.Lat , l.Lon , er.Type , er.OpenDate , er.CloseDate , er.Space , er.RegOption , en.NetworkID, e2.PkID
						LIMIT 1", array($eID));
		
		$rsvp_opts = (hc_mysql_result($result,0,"RSVPOpts") != '') ? explode('|',hc_mysql_result($result,0,"RSVPOpts")) : array_fill(0,5,NULL);
		
		if(!hasRows($result) || hc_mysql_result($result,0,0) <= 0 || ((strtotime(hc_mysql_result($result,0,"StartDate")) < strtotime(SYSDATE)) && $hc_cfg[11] == 0))
			go_home();
		
		event_add_session_view($eID);
		
		if(hc_mysql_result($result,0,6) == 0){
			$time = (hc_mysql_result($result,0,4) != '') ? stampToDate(hc_mysql_result($result,0,4), $hc_cfg[23]) : '';
			$time .= (hc_mysql_result($result,0,5) != '') ? ' - ' . stampToDate(hc_mysql_result($result,0,5), $hc_cfg[23]) : '';
			$stamp = date("Y-m-d\Th:i:00",strtotime(hc_mysql_result($result,0,3) . trim(' '.hc_mysql_result($result,0,4)))) . HCTZ;
		} else {
			$time = (hc_mysql_result($result,0,6) == 1) ? $hc_lang_event['AllDay'] : $hc_lang_event['TimeTBA'];
			$stamp = date("Y-m-d",strtotime(hc_mysql_result($result,0,3)));}
		$eBrite = (hc_mysql_result($result,0,"NetworkID") != '') ? '<iframe src="http://www.eventbrite.com/tickets-external?eid='.hc_mysql_result($result,0,"NetworkID").'" class="eventbrite"></iframe>' : '';
		$event = array(
		    'EventID'			=>	hc_mysql_result($result,0,"PkID"),
		    'Title'			=>	hc_mysql_result($result,0,"Title"),
		    'Description'		=>	hc_mysql_result($result,0,"Description") . $eBrite,
		    'Date'			=>	stampToDate(hc_mysql_result($result,0,"StartDate"), $hc_cfg[14]),
		    'Time'			=>	$time,
		    'Timestamp'		=>	$stamp,
		    'Contact'			=>	hc_mysql_result($result,0,"ContactName"),
		    'Contact_Email'		=>	hc_mysql_result($result,0,"ContactEmail"),
		    'Contact_URL'		=>	(hc_mysql_result($result,0,"ContactURL") != 'http://') ? hc_mysql_result($result,0,"ContactURL") : NULL ,
		    'Contact_Phone'		=>	hc_mysql_result($result,0,"ContactPhone"),
		    'RSVP'			=>	hc_mysql_result($result,0,"Type"),		    
		    'RSVP_Spaces'		=>	($rsvp_opts[2] > 0) ? $rsvp_opts[2] : event_lang('Unlimited'),
		    'RSVP_Taken'		=>	$rsvp_opts[4],
		    'RSVP_Active'		=>	((strtotime(SYSDATE) >= strtotime($rsvp_opts[0])) && (strtotime(SYSDATE) <= strtotime($rsvp_opts[1]))) ? 1 : 0,
		    'RSVP_Open'		=>	$rsvp_opts[0],
		    'RSVP_Close'		=>	(strtotime($rsvp_opts[1]) > strtotime(hc_mysql_result($result,0,"StartDate"))) ? hc_mysql_result($result,0,"StartDate") : $rsvp_opts[1],
		    'RSVP_Type'		=>	$rsvp_opts[3],		    
		    'VenueID'			=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"LocID") : 0,
		    'Venue_Name'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Name") : hc_mysql_result($result,0,"LocationName"),
		    'Venue_Address'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Address") : hc_mysql_result($result,0,"LocationAddress"),
		    'Venue_Address2'	=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Address2") : hc_mysql_result($result,0,"LocationAddress2"),
		    'Venue_City'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"City") : hc_mysql_result($result,0,"LocationCity"),
		    'Venue_Region'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"State") : hc_mysql_result($result,0,"LocationState"),
		    'Venue_Postal'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Zip") : hc_mysql_result($result,0,"LocationZip"),
		    'Venue_Country'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Country") : hc_mysql_result($result,0,"LocCountry"),
		    'Venue_Email'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Email") : NULL,
		    'Venue_URL'		=>	(hc_mysql_result($result,0,"LocID") > 0 && hc_mysql_result($result,0,"URL") != 'http://') ? hc_mysql_result($result,0,"URL") : NULL,
		    'Venue_Phone'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Phone") : NULL,
		    'Venue_Lat'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Lat") : NULL,
		    'Venue_Lon'		=>	(hc_mysql_result($result,0,"LocID") > 0) ? hc_mysql_result($result,0,"Lon") : NULL,
		    'SeriesID'			=>	hc_mysql_result($result,0,"SeriesID"),
		    'Cost'			=>	hc_mysql_result($result,0,"Cost"),
		    'Bitly'			=>	hc_mysql_result($result,0,"ShortURL"),
		    'CommentsURL'		=>	(hc_mysql_result($result,0,"SeriesID") != '') ? CalRoot.'/index.php?com=series&sID='.hc_mysql_result($result,0,"SeriesID") : CalRoot.'/index.php?eID='.$eID,
		    'CommentsID'		=>	(hc_mysql_result($result,0,"SeriesID") != '') ? hc_mysql_result($result,0,"SeriesID") : $eID,
		    'DateRaw'			=>	hc_mysql_result($result,0,"StartDate"),
		    'LastMod'			=>	hc_mysql_result($result,0,"LastMod"),
		    'Image'			=>	hc_mysql_result($result,0,"Image"),
		    'Featured'			=>	hc_mysql_result($result,0,"IsFeature"),
		    );
		
		$title = cOut(hc_mysql_result($result,0,"Title"));
		$desc = cOut(hc_mysql_result($result,0,"Description"));
		$limit = (hc_mysql_result($result,0,"HideDays") > 0) ? cOut(hc_mysql_result($result,0,"HideDays")) : $hc_cfg[134];
		$expire = ($limit > 0) ? date("r", (strtotime(hc_mysql_result($result,0,"StartDate")) + ($limit*86400))) : '';
		$last_mod = date("r", (strtotime(hc_mysql_result($result,0,"LastMod"))));
		
		return array_map('cOut', $event);
	}
	/**
	 * Generates event browse navigation links.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param integer $prev unix timestamp of previous date
	 * @param integer $next unix timestamp of next date
	 * @param integer $window Number of days being browsed
	 * @param string $location Location URL Argument w/ID "&amp;lID=X" (Preserves Browse by Location)
	 * @return string Browse Navigation HTML Markup
	 */
	function event_browse_nav($prev,$next,$window,$location){
		global $lID, $hc_cfg, $hc_lang_event;
		
		$m = ($window == 0) ? '&amp;m=1' : '';
		$pLink = ($window > 518400) ? date("U", mktime(0,0,0,HCMONTH-1,1,HCYEAR)) : $prev - ($window + 86400);
		$fltr = (isset($_SESSION['hc_favCat']) || isset($_SESSION['hc_favCity'])) ? ' hc_filt_active' : '';
		$bak = ($hc_cfg['First'] >= $prev || (HCDATE <= SYSDATE && $hc_cfg[11] == 0)) ? 
				'<a href="#" class="hc_leftb" title="'.$hc_lang_event['BrowseBack'].'" /></a>':
				'<a href="'.CalRoot.'?d='.date("Y-m-d",$pLink).$location.$m.'" class="hc_left" title="'.$hc_lang_event['BrowseBack'].'" /></a>';
		$fwd = ($hc_cfg['Last'] > $next) ? 
				'<a href="'.CalRoot.'?d='.date("Y-m-d",($next+86400)).$location.$m.'" class="hc_right" title="'.$hc_lang_event['BrowseForward'].'" /></a>':
				'<a href="#" class="hc_rightb" title="'.$hc_lang_event['BrowseForward'].'" /></a>';
		$loc = ($lID > 0) ? '<a href="'.CalRoot.'/index.php?com=location&lID='.$lID.'" rel="nofollow" class="hc_loc"></a>' : '';
		
		return '
		<div class="nav">
			'.$loc.'
			<a href="'.CalRoot.'/index.php?b=2'.$location.'" rel="nofollow" class="hc_daily" title="'.$hc_lang_event['ALTBrowseD'].'" /></a>
			<a href="'.CalRoot.'/index.php?b=0'.$location.'" rel="nofollow" class="hc_weekly" title="'.$hc_lang_event['ALTBrowseW'].'" /></a>
			<a href="'.CalRoot.'/index.php?b=1'.$location.'" rel="nofollow" class="hc_monthly" title="'.$hc_lang_event['ALTBrowseM'].'" /></a>
			<a href="'.CalRoot.'/index.php?com=filter'.$location.'" rel="nofollow" class="hc_filter'.$fltr.'" title="' . $hc_lang_event['Filter'] . '" /></a>
			<a href="' . CalRoot . '/?d='.SYSDATE.$location.$m.'" rel="nofollow" class="hc_home" title="' . $hc_lang_event['Home'] . '" /></a>
			'.$bak.'
			'.$fwd.'
		</div>';
	}
	/**
	 * Validate current browse options against available events and build nav markup string & event browse results object. If invalid redirect to browse default (current week/month - depeneding on default settings).
	 * @since 2.1.0
	 * @version 2.2.1
	 * @param integer $sort_featured Sort featured events first before all other events occuring on each day. 0 = List events chronologically regardless of featured status, 1 = List featured events first. (Default: 1)
	 * @param string $nav_function [optional] Browse Navigation function to use. (Default: event_browse_nav)
	 * @return void 
	 */	
	function event_browse_valid($sort_featured = 1, $nav_function = 'event_browse_nav'){
		global $lID, $hc_cfg, $hc_lang_event, $favQ1, $favQ2, $favP1, $favP2, $resultEB, $myNav;
		
		$location = $lQuery = ''; unset($lParam);
		if($lID > 0){
			$location = '&amp;lID=' . $lID;
			$lQuery = " AND e.LocID = ?";
			$lParam = $lID;
		}
		
		if(isset($_GET['m']) || $_SESSION['BrowseType'] == 2){
			$sqlStart = $sqlEnd = strtotime(HCDATE);
			$startDate = $endDate = strtotime(HCDATE);
			$window = 0;
		} else {
			if($_SESSION['BrowseType'] == 1){
				$window = (date("t", strtotime(HCDATE)) - 1) * 86400;
				$remove = ($hc_cfg[48] == 0) ? (date("j", strtotime(HCDATE)) - 1) * 86400 : 0;
			} else {
				$window = 6 * 86400;
				$remove = ($hc_cfg[48] == 0) ? (date("w", strtotime(HCDATE)) - 1) * 86400 : 0;}
			
			$startDate = (strtotime(HCDATE) - $remove);
			$endDate = $sqlEnd = ($startDate + $window);
			$sqlStart = (HCDATE != SYSDATE) ? $startDate : strtotime(SYSDATE);
		}
		
		if($endDate < $hc_cfg['First'] && $startDate != $hc_cfg['First']){
			go_home();
		} else {
			$myNav = call_user_func($nav_function,$startDate,$endDate,$window,$location);

			// this query's params, then lQuery $favQ1 $favQ2 
			$params = array(date("Y-m-d", $sqlStart), date("Y-m-d", $sqlEnd));
			// lQuery?
			if (isset($lParam)) { $params[] = $lParam; }
			// favQ1
			$params = array_merge($params, $favP1);
			// favQ2
			$params = array_merge($params, $favP2);

			$resultEB = doQuery("SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.Image, e.IsFeature, e.HideDays, 
								e.LocID, e.LocationName, e.LocationCity, e.LocationState, e.LocCountry,
								l.Name, l.City, l.State, l.Country, e.Cost, e.SeriesID
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
								LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							WHERE e.StartDate BETWEEN ? AND ?"
							.$lQuery.$favQ1.$favQ2." AND e.IsActive = 1 AND e.IsApproved = 1
							GROUP BY e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.Image, e.IsFeature, e.HideDays, e.LocID, e.LocationName, e.LocationCity, e.LocationState, e.LocCountry, l.Name, l.City, l.State, l.Country, e.Cost, e.SeriesID
							ORDER BY e.StartDate, ".($sort_featured == 1 ? "e.IsFeature DESC, ":"")."e.TBD, e.StartTime, e.Title", $params);
		}
		
	}
	/**
	 * Output event browse navigation links & event list by date.
	 * @since 2.0.0
	 * @version 2.1.0
	 * @param integer $show_images Include event images with browse output. 0 = Do NOT include images, 1 = Include images. (Default: 0)
	 * @param string $nav_function [optional] Browse Navigation function to use. (Default: event_browse_nav)
	 * @return void
	 */
	function event_browse($show_images = 0){
		global $lID, $hc_cfg, $hc_lang_event, $favQ1, $favQ2, $resultEB, $myNav;
		
		$location = ($lID > 0) ? '&amp;lID='.$lID : '';
		$cnt = $date = 0;
		echo $myNav;
		
		if(!hasRows($resultEB)){
			no_event_notice();
			return 0;}
		
		while($row = hc_mysql_fetch_row($resultEB)){
			if(($date != $row[2])){
				$date = $row[2];
				echo ($cnt > 0) ? '
			</ul>' : '';
				echo '
			<header>' . stampToDate($row[2], $hc_cfg[14]) . '</header>
			<ul>';
				$cnt = 1;
			}
			
			$limit = ($row[8] > 0) ? cOut($row[8]) : $hc_cfg[134];
			$expire = ($limit > 0) ? date("Y-m-d", (strtotime($date) + ($limit*86400))) : '';

			$cls = ($cnt % 2 == 0) ? 'hl' : '';
			$cls .= ($row[7] > 0) ? ' featured' : '';
			
			if($row[5] == 0){
				$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
				$time .= ($row[4] != '') ? ' - ' . stampToDate($row[4], $hc_cfg[23]) : '';
				$stamp = date("Y-m-d\Th:i:00",strtotime($row[2] . trim(' '.$row[3]))) . HCTZ;
			} else {
				$time = ($row[5] == 1) ? $hc_lang_event['AllDay'] : $hc_lang_event['TBA'];
				$stamp = date("Y-m-d",strtotime($row[2]));}
			echo '
			<li '.($cls != '' ? 'class="'.trim($cls).'" ':'').'itemscope itemtype="http://schema.org/Event">
				<time itemprop="startDate" datetime="'.$stamp.'">'.$time.'</time><a itemprop="url" href="'.CalRoot . '/index.php?eID='.$row[0].$location.'"'.(($expire <= SYSDATE) ? ' rel="nofollow"':'').'><span itemprop="name">'.cOut($row[1]).'</span></a>'.(($show_images == 1 && $row[6] != '') ? '
				<img itemprop="image" src="'.$row[6].'" class="eimage_b" />':'').'
			</li>';
			++$cnt;
		}
		echo '
			</ul>
		'.$myNav;
	}
	/**
	 * Output "no events" notice when the current browse criteria contains no active events to display.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return void
	 */
	function no_event_notice(){
		global $hc_cfg, $hc_lang_event;
		
		$filter = (isset($_SESSION['hc_favCat']) || isset($_SESSION['hc_favCity'])) ? '<li>' . $hc_lang_event['NoEvent4'] . '</li>' :'';
		$submit = ($hc_cfg[1] == 1) ? '<li><a href="' . CalRoot . '/index.php?com=submit" rel="nofollow">' . $hc_lang_event['NoEvent3'] . '</a></li>' : '';
		echo '
			<p>
				'.$hc_lang_event['NoEventBrowse'].'
			</p>
			<ul id="no_events">
				<li>'.$hc_lang_event['NoEvent1'] . '
				</li>
				<li>' . $hc_lang_event['NoEvent2'] . '</li>
				'.$filter.'
				'.$submit.'
			</ul>';
	}
	/**
	 * Generates save, share & location urls for passed event.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $eID Event ID
	 * @param integer $lID Location ID
	 * @return array Array of URLs for the event.
	 */
	function event_location_links($eID,$lID){
		if(!is_numeric($eID) || !is_numeric($lID))
			return 0;
		$links = array(
		    'Event_iCal'		=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=3',
		    'Event_vCal'		=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=4',
		    'Event_GoogleCal'	=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=1',
		    'Event_YahooCal'	=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=2',
		    'Event_LiveCal'		=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=5',
		    'Event_URL'		=>	CalRoot . '/link/index.php?tID=1&amp;oID='.$eID,
		    'Venue_Profile'		=>	CalRoot . '/index.php?com=location&amp;lID='.$lID,
		    'Venue_Weather'		=>	CalRoot . '/link/index.php?tID=3&amp;oID='.$eID.'&amp;lID='.$lID,
		    'Venue_Directions'	=>	CalRoot . '/link/index.php?tID=2&amp;oID='.$eID.'&amp;lID='.$lID,
		    'Venue_URL'		=>	CalRoot . '/link/index.php?tID=4&amp;oID='.$lID,
		    'This'			=>	CalRoot . '/index.php?eID='.$eID,
		);
		
		return array_map('cOut', $links);
	}
	/**
	 * Retrieves interface text entry from event language file.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $which language file array key
	 * @return string language file entry
	 */
	function event_lang($which){
		global $hc_lang_event;
		
		if(!array_key_exists($which,$hc_lang_event))
			return;
		
		return $hc_lang_event[$which];
	}
	/**
	 * Output RSVP progress meter for event.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param integer $spaces Number of RSVPs available for the event. (0 = No Limit, Text Notice Only)
	 * @param integer $taken Number of RSVPs requested.
	 * @param integer $width Variable width, in percentage, of the meter.
	 * @return void
	 */
	function event_rsvp_meter($spaces,$taken,$width = 100){
		global $eID, $hc_lang_event;
		
		if($spaces > 0 && $spaces > $taken){
			$regWidth = ($taken / $spaces) * $width;
			$fillWidth = $width - $regWidth;
			$fill = '<div class="regFull" style="width:'.round($regWidth).'%;"></div>';
		} elseif($spaces > 0 && $spaces <= $taken) {
			$fill = '<div class="regOver"></div>';
		} elseif($spaces == 0){
			echo $hc_lang_event['NoLimit'];
		}
		
		echo '
		<div class="regMeter">'.$fill.'</div>';
	}
	/**
	 * Output conditional RSVP closure notice (based on dates of availability).
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param date $open Date to begin accepting RSVPs
	 * @param date $close Date to stop accepting RSVPs
	 * @return void
	 */
	function event_rsvp_closed($open, $close){
		global $hc_cfg, $hc_lang_event;
		
		$txt = (strtotime(SYSDATE) >= strtotime($close)) ? $hc_lang_event['RSVPDisLate'].' '.stampToDate($close, $hc_cfg[24]) : $hc_lang_event['RSVPDisEarly'].' '.stampToDate($open, $hc_cfg[24]);
		echo '
		'.$txt;
	}
	/**
	 * Output conditional RSVP link (based on availability).
	 * @since 2.0.0
	 * @version 2.2.0
	 * @param integer $spaces Number of RSVPs available for the event. (0 = No Limit)
	 * @param integer $taken Number of RSVPs requested
	 * @param date $deadline Date RSVP closes for the event
	 * @return void
	 */
	function event_rsvp_link($spaces,$taken,$deadline = ''){
		global $eID, $hc_cfg, $hc_lang_event;
		
		$txt = ($spaces > 0 && $spaces <= $taken) ? $hc_lang_event['Overflow'] : $hc_lang_event['Register'];
		echo '
		<a href="'.CalRoot.'/index.php?com=rsvp&amp;eID='.$eID.'" class="icon rsvp">'.$txt.($deadline != '' ? ' '.$hc_lang_event['RSVPEnd'].' '.stampToDate($deadline, $hc_cfg[24]) : '').'</a>';
	}
	/**
	 * Output event specific iCalendar download link. Links to individual event iCalendar download when iCalendar feeds are active.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $eID ID# of the event for the download link.
	 * @return void
	 */
	function event_ical_link($eID){
		global $hc_cfg, $hc_lang_event;
		
		if($hc_cfg[108] == 0 || !is_numeric($eID) || $eID < 1)
			return 0;
		
		echo '
		<a href="'.CalRoot.'/link/SaveEvent.php?eID='.$eID.'&cID=3" class="icon ical">'.$hc_lang_event['iCalendar'].'</a><br />';
	}
	/**
	 * Retrieve ID, Title, Date, Start Time, End Time, TBD, Description, Location ID, Location Name, Location Lat, Location Lon values for all events in the current series.
	 * @since 2.0.0
	 * @version 2.2.0
	 * @param integer $type 0 = Current Events in Series Only, 1 = All Events in Series. DEFAULT:0
	 * @return resource MySQL result set
	 */
	function series_fetch($type = 0){
		global $hc_lang_event, $title, $desc;
		
		$sID = (isset($_GET['sID'])) ? cIn(strip_tags($_GET['sID'])) : 0;
		$result = doQuery("SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.Description,
						e.LocID, l.Name, l.Lat, l.Lon, e.SeriesID
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
						WHERE e.SeriesID = ? AND e.IsActive = 1 AND e.IsApproved = 1".($type == 0 ? " AND e.StartDate >= '" . cIn(SYSDATE) . "'" : "")."
						ORDER BY e.Title, e.StartDate, e.TBD, e.StartTime", array($sID));
		
		$title = trim(cOut($hc_lang_event['SeriesTitle'].' '.hc_mysql_result($result,0,1)));
		$desc = cOut(hc_mysql_result($result,0,6));
		
		return $result;
	}
	/**
	 * Build array of series metadata for use in Facebook integration.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param resource $result MySQL result set (Requires: ID, Title, Date, Start Time, End Time, TBD, Description, Location ID, Location Name, Location Lat, Location Lon, Series ID)
	 * @return array Series Facebook Metadata
	 */
	function series_meta($result){
		if(!hasRows($result))
			return array();
		
		$meta = array(hc_mysql_result($result,0,1),hc_mysql_result($result,0,6),hc_mysql_result($result,0,11));
		
		return $meta;
	}
	/**
	 * Output event series list.
	 * @since 2.0.0
	 * @version 2.2.0
	 * @param resource $result MySQL result set (Requires: ID, Title, Date, Start Time, End Time, TBD, Description, Location ID, Location Name, Location Lat, Location Lon, Series ID)
	 * @return void
	 */
	function series_list($result,$date_format = ''){
		global $hc_cfg, $hc_lang_event;		
		$cnt = $x = 0;
		$cur_event = $build = $start = $end = '';
		$date_format = ($date_format == '') ? $hc_cfg[14] : $date_format;
		$venues = array();
		
		hc_mysql_data_seek($result,0);
		while($row = hc_mysql_fetch_row($result)){
			if(strtotime($row[2]) < $start || $start == '')
				$start = strtotime($row[2]);
			if(strtotime($row[2]) > $end || $end == '')
				$end = strtotime($row[2]);
			if(!in_array($row[7], $venues))
				$venues[] = $row[7];
			if($cur_event != $row[1]){
				$cnt = 0;
				$cur_event = $row[1];
				$build .= ($build != '') ? '
		</ul>':'';
				$build .= '
		<ul>
			<header><span class="event">'.cOut($row[1]).'</span><span class="venue">'.cOut($row[8]).'</span></header>';
			}
			if($row[5] == 0){
				$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
				$time .= ($row[4] != '') ? ' - ' . stampToDate($row[4], $hc_cfg[23]) : '';
				$stamp = date("Y-m-d\Th:i:00.0",strtotime($row[2] . trim(' '.$row[3]))) . HCTZ;
				$stamp_end = date("Y-m-d\Th:i:00.0",strtotime($row[2] . trim(' '.$row[4]))) . HCTZ;
			} else {
				$time = ($row[5] == 1) ? $hc_lang_event['AllDay'] : $hc_lang_event['TBA'];
				$stamp = date("Y-m-d",strtotime($row[2]));
				$stamp_end = $stamp;
			}
				
			
			$hl = ($cnt % 2 == 1) ? ' class="hl"' : '';	
			$build .= '
				<li'.$hl.'><time>'.$time.'</time><a href="'.CalRoot . '/index.php?eID='.$row[0].'" rel="nofollow">'.stampToDate($row[2], $hc_cfg[14]).'</a></li>';
			++$cnt;
			++$x;
		}
		
		echo '
			<div id="map_canvas"></div>
			<fieldset>
				<legend>'.$hc_lang_event['SeriesLegend'].'</legend>
				<label>'.$hc_lang_event['SeriesBegins'].'</label>
				<span class="output">'.strftime($date_format, intval($start)).'</span>
				<label>'.$hc_lang_event['SeriesEnds'].'</label>
				<span class="output">'.strftime($date_format, intval($end)).'</span>
				<label>'.$hc_lang_event['SeriesEvents'].'</label>
				<span class="output">'.$x.'</span>
				<label>'.$hc_lang_event['SeriesVenues'].'</label>
				<span class="output">'.count($venues).'</span>
				
				<p class="instruct">'.$hc_lang_event['SeriesInstruction'].'</p>
			</fieldset>
		
		'.$build.'
		</ul>';
	}
	/**
	 * Output JavaScript required for current embedded map provider (configured within admin console settings). Activate map by adding call to map_init() in page onload event. Series unique wrapper for get_map_js()
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param resource $result MySQL result set (Requires: ID, Title, Date, Start Time, End Time & TBD, Description, Location ID, Location Name, Location Lat, Location Lon)
	 * @return void
	 */
	function series_map($result){
		global $hc_cfg;
		
		$venues = array();
		$found = array();
		
		hc_mysql_data_seek($result,0);
		while($row = hc_mysql_fetch_row($result)){
			if(!in_array($row[7], $found)){
				$venues[] = array($row[7],$row[8],$row[9],$row[10],"","","","","","","","","","");
				$found[] = $row[7];
			}
		}
		
		get_map_js($venues[0][2], $venues[0][3], 3, cal_url().'/img/pins/pushpin.png', 0, NULL, $venues);
	}	
	/**
	 * Generate and send new/updated public event submission notice email to subscribed admin users.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param string $subName Name of event submitter
	 * @param string $subEmail Email address of event submitter
	 * @param string $adminMessage Message to admin user from event submitter
	 * @param integer $locID Location ID
	 * @param string $locName Location Name
	 * @param string $locAddress Location Address
	 * @param string $locAddress2 Location Address Extra Line
	 * @param string $locCity Location City
	 * @param string $locState Location State
	 * @param string $locCountry Location Country
	 * @param string $locZip Location Zip
	 * @param string $eventTitle Submitted Event Title
	 * @param string $eventDesc Submitted Event Description
	 * @param string $eventDates String describing date range
	 * @param integer $occurs Number of event occurrences
	 * @return void
	 */
	function notice_public_event($subName,$subEmail,$adminMessage,$locID,$locName,$locAddress,$locAddress2,$locCity,$locState,$locCountry,$locZip,$eventTitle,$eventDesc,$eventDates,$occurs){
		global $hc_cfg, $hc_lang_config, $hc_lang_submit;
		
		$resultE = doQuery("SELECT a.FirstName, a.LastName, a.Email
						FROM " . HC_TblPrefix . "adminnotices n
							LEFT JOIN " . HC_TblPrefix . "admin a ON (n.AdminID = a.PkID)
						WHERE a.IsActive = 1 AND n.IsActive = 1 AND n.TypeID = 0");
		if(hasRows($resultE)){
			$toNotice = array();
			while($row = hc_mysql_fetch_row($resultE)){
				$toNotice[trim($row[0] . ' ' .$row[1])] = $row[2];
			}

			$user_level = (isset($_SESSION['UserLevel'])) ? cIn($_SESSION['UserLevel']) : 0;
			$subject = $hc_lang_submit['NoticeSubject'] . ' - ' . CalName;
			$message = '<p>' . $hc_lang_submit['NoticeEmail1'] . '</p>
<p>
	<b>' . $hc_lang_submit['NoticeEmail2'] . '</b> ' . $subName . ' - ' . $subEmail . '<br />
	<b>' . $hc_lang_submit['NoticeEmail5'] . '</b> '.$hc_lang_submit['NoticeEmail5'.$user_level] . '<br />
	<b>' . $hc_lang_submit['NoticeEmail3'] . '</b> ' . strip_tags($_SERVER['REMOTE_ADDR']) . '
</p>
';
			$message .= ($adminMessage != '') ? '<p><b>' . $hc_lang_submit['NoticeEmail4'] . '</b> ' . cOut(str_replace('<br />', ' ', strip_tags(cleanBreaks($adminMessage),'<br>'))) . '</p>' : '';
			$message .= '
<p>
';
			if($locID == 0){
				$message .= $locName . ', ';
				$message .= str_replace('<br />', ' ', strip_tags(buildAddress($locAddress,$locAddress2,$locCity,$locState,$locZip,$locCountry,$hc_lang_config['AddressType']),'<br>'));
			} else {
				$result = doQuery("SELECT Name, Address, Address2, City, State, Country, Zip FROM " . HC_TblPrefix . "locations WHERE PkID = ?", array(cIn($locID)));
				$message .= hc_mysql_result($result,0,0) . ', ';
				$message .= str_replace('<br />', ' ', strip_tags(buildAddress(hc_mysql_result($result,0,1),hc_mysql_result($result,0,2),hc_mysql_result($result,0,3),hc_mysql_result($result,0,4),hc_mysql_result($result,0,5),hc_mysql_result($result,0,6),$hc_lang_config['AddressType']),'<br>'));
			}
			$message .= '
</p>
<p>
	<b>'.$hc_lang_submit['EventTitle'].'</b> '.cOut($eventTitle).'<br />
	'.(($occurs > 0) ? '<b>'.$hc_lang_submit['Occurs'].'</b> '.cOut($eventDates).' (x'.$occurs.')<br />':'').'
</p>
<p>' . cOut(strip_tags($eventDesc)).'</p>
<p><a href="' . AdminRoot . '">' . AdminRoot . '</a></p>';
			
			reMail('', $toNotice, $subject, $message);
		}
	}
?>