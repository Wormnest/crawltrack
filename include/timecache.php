<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.6
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: timecache.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
echo "<div class=\"cache\" >\n";

//to display the cache hour
$timecache = time() - ($settings->timediff * 3600);
$timecache = date("H:i", $timecache);
echo "" . $language['page_cache'] . $timecache . " \n";
echo "<br>";
if (isset($_SESSION['userlogin']) && !empty($_SESSION['userlogin'])) {
	echo $language['connect'] . "&nbsp;";
} else {
	echo "<a href=\"index.php?navig=$settings->navig&amp;period=$settings->period&amp;site=$settings->siteid&amp;crawler=$crawlencode&amp;graphpos=$settings->graphpos&amp;logitself=1\">" . $language['connect_you'] . "</a>&nbsp;";
}
echo "</div>\n";
?>
