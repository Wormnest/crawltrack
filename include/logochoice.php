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
// file: logochoice.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN') && !defined('IN_CRAWLT_INSTALL')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
echo "<h1>" . $language['site_name2'] . "</h1>\n";
echo "<div class=\"form3\">\n";
echo "<form action=\"index.php\" method=\"POST\" >\n";
echo "<table>\n";
echo "<tr><td>\n";
$sitechoice = 0;
foreach ($listsite as $siteid) {
	if ($sitechoice == 0) {
		echo "<input type=\"radio\" name=\"site\" value=\"" . $siteid . "\" checked>" . $namesite[$siteid] . "<br><br>\n";
	} else {
		echo "<input type=\"radio\" name=\"site\" value=\"" . $siteid . "\">" . $namesite[$siteid] . "<br><br>\n";
	}
	$sitechoice = 1;
}
echo "</td></tr>\n";
echo "</table>\n";

//continue
if ($navig == 6) {
	$validform = 3;
} elseif ($navig == 15) {
	$validform = 7;
}
echo "<input type=\"hidden\" name ='navig' value=$navig>\n";
echo "<input type=\"hidden\" name ='validform' value=$validform>";
echo "<table>\n";
echo "<tr>\n";
echo "<td>\n";
echo "<input name='ok' type='submit'  value='OK' size='40'>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";
echo "</div><br><br>";
?>
