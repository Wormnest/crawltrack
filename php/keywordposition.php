<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.5
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
// file: keywordposition.php
//----------------------------------------------------------------------
//  Last update: 05/09/2010
//----------------------------------------------------------------------
error_reporting(0);
//initialize array
$listlangcrawlt = array();

//connection to database
include ("../include/configconnect.php");
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//Get lang
$sqlcrawltconfig = "SELECT  lang, typecharset FROM crawlt_config";
$requetecrawltconfig = mysql_query($sqlcrawltconfig, $connexion) or die("MySQL query error");
$nbrresultcrawlt = mysql_num_rows($requetecrawltconfig);
if ($nbrresultcrawlt >= 1) {
	$lignecrawlt = mysql_fetch_row($requetecrawltconfig);
	$crawltlang = $lignecrawlt[0];
	$crawltcharset = $lignecrawlt[1];
}
if ($crawltcharset != 1) {
	$crawltlang = $crawltlang . "iso";
}
mysql_close($connexion);
//get the listlang files
include ("../include/listlang.php");
//get the functions files
$times = 0;
$period = 0;
include ("../include/functions.php");

//language file include
if (file_exists("../language/" . $crawltlang . ".php") && in_array($crawltlang, $listlangcrawlt)) {
	include ("../language/" . $crawltlang . ".php");
} else {
	exit('<h1>No language files available !!!!</h1>');
}

//display
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>CrawlTrack</title>\n";
echo "<meta NAME=\"author\" CONTENT=\"Jean-Denis Brun\">\n";
echo "<meta NAME=\"description\" CONTENT=\"CrawlTrack spiders and crawlers tracker\">\n";
echo "<meta NAME=\"keywords\" CONTENT=\"crawler,tracker,webmaster,statistics,robots,site,webmestre,statistiques,searchengines,moteur de recherche\">\n";
echo "<meta http-equiv=\"Content-Language\" content=\"fr\">\n";
if ($crawltcharset == 1) {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
} else {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
}
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../styles/stylewindow.css\">\n";
echo "</head>\n";
echo "<body >\n";
echo "<div id=\"header\">\n";
echo "CrawlTrack  <span class=\"headertext\">" . $language['webmaster_dashboard'] . "</span>";
echo "</div>\n";

//get the dataa
if (isset($_GET['keyword'])) {
	$keyword = $_GET['keyword'];
	$keyworddisplay = stripslashes(crawltcuturl($keyword, '55'));
	$keywordurl = urlencode(stripslashes($keyword));
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
echo "<p align=\"right\"><input type=button name=\"close\" value=\"" . $language['close'] . "\" onClick=\"self.close();\">&nbsp;&nbsp;</p> \n";
echo "<h1>" . $language['keyword'] . ":<span class=\"browntitle\"> " . $keyworddisplay . "</span></h1>\n";
echo "<div align=\"center\">\n";
echo "<hr>\n";
echo "<iframe name=\"I1\" src=\"http://www.ask.com/web?q=$keywordurl \" marginwidth=\"1\" marginheight=\"1\" scrolling=\"auto\" border=\"1\" bordercolor=\"#003399\" frameborder=\"1px\" width=\"900px\" height=\"300px\"></iframe>\n";
echo "<hr>\n";
echo "<iframe name=\"I2\" src=\"http://www.google.com/search?q=$keywordurl\" marginwidth=\"1\" marginheight=\"1\" scrolling=\"auto\" border=\"1\" bordercolor=\"#003399\" frameborder=\"1px\" width=\"900px\" height=\"300px\"></iframe>\n";
echo "<hr>\n";
echo "<iframe name=\"I3\" src=\"http://search.live.com/results.aspx?q=$keywordurl\" marginwidth=\"1\" marginheight=\"1\" scrolling=\"auto\" border=\"1\" bordercolor=\"#003399\" frameborder=\"1px\" width=\"900px\" height=\"300px\"></iframe>\n";
echo "<hr>\n";
echo "<iframe name=\"I4\" src=\"http://search.yahoo.com/search?p=$keywordurl \" marginwidth=\"1\" marginheight=\"1\" scrolling=\"auto\" border=\"1\" bordercolor=\"#003399\" frameborder=\"1px\" width=\"900px\" height=\"300px\"></iframe>\n";
echo "<hr>\n";
echo "</div>\n";
echo "<p align=\"right\"><input type=button name=\"close\" value=\"" . $language['close'] . "\" onClick=\"self.close();\">&nbsp;&nbsp;</p> \n";
echo "</body>\n";
echo "</html>\n";
?>
