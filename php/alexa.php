<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.8
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
// file: alexa.php
//----------------------------------------------------------------------
//  Last update: 01/12/2010
//----------------------------------------------------------------------
$url= $_GET['url'];
echo"<html><head></head><body>";
echo"<A href=\"http://www.alexa.com/siteinfo/" .$url. "\" target=\"blank\"><SCRIPT type='text/javascript' language='JavaScript' src='http://xslt.alexa.com/site_stats/js/s/c?url=".$url. "'></SCRIPT></A>";
echo"</body></html>";
?>
