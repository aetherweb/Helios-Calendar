<?php
/**
 * @package Helios Calendar
 * @subpackage Publisher Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	set_cat_cols(3);
	
	$active_tool = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn(strip_tags($_GET['t'])) : 0;
	/*	Add Tool Options
		$add_tools = array(10 => 'New Tool',11 => 'New Tool 2');*/
	$add_tools = array(10 => 'Internet Explorer 9 Jumplist', 11 => 'Opera Speed Dial Display');
	$crmbAdd = tool_crumb($active_tool,$add_tools);
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	
	get_header();
	
	get_tool_validation($active_tool);?>

</head>
<body itemscope itemtype="http://schema.org/WebPage">
	<header>
		<span>
			<?php echo cal_name();?>
			
			<div id="tag">Publishing awesome events.</div>
		</span>
		<aside>
			<?php mini_search('Search Events by Keyword',0);?>
		
		</aside>
	</header>
	<nav>
		<?php build_breadcrumb($crumbs);?>
	</nav>
	<section>
		<article>
		<?php	
		switch($active_tool){
			case 1:
				tool_rss();
				break;
			case 2:
				tool_syndication();
				break;
			case 3:
				tool_mobile();
				break;
			case 4:
				tool_search();
				break;
			case 5:
				tool_api();
				break;
			case 10:
				echo '<div style="margin:0;padding:0;height:300px;"><p><b>Internet Explorer 9 Users:</b></p><p>You can pin our calendar to your taskbar for easy access. Once pinned right click on our calendar icon to easily access any part of our public calendar through the custom jumplist.</p></div>';
				break;
			case 11:
				echo '<div style="margin:0;padding:0;height:300px;"><p><b>Opera Browser Users:</b></p><p>Add our calendar to your speed dial sites to see today\'s events in a custom speed dial friendly layout.</p></div>';
				break;
			default:
				tool_menu($add_tools);
		}?>
		
		</article>
		
		<aside>
	<?php 
		mini_cal_month();	
		get_side();?>
				
		</aside>
	</section>
	
	<?php get_footer(); ?>