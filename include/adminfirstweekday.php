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
// file: adminfirstweekday.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
echo "<h1>" . $language['firstweekday-title'] . "</h1>\n";
if ($firstdayweek == 'Monday') {
	if ($validsite == 1) {
		//update the crawlt_config_table
		
		//database connection
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		$sqlupdatepublic = "UPDATE crawlt_config SET firstdayweek='Sunday'";
		$requeteupdatepublic = db_query($sqlupdatepublic, $connexion);
		
		//clear cache table
		$sqlcache = "TRUNCATE TABLE crawlt_cache";
		$requetecache = db_query($sqlcache, $connexion);
		mysql_close($connexion);
		echo "<br><br><p>" . $language['update'] . "</p><br><br>";
		
		//continue
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<input name='ok' type='submit'  value='OK ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
	} else {
		echo "<br><br><p>" . $language['firstweekday-set-up2'] . "</p>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"22\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"1\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<input name='ok' type='submit'  value=" . $language['yes'] . " size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<input name='ok' type='submit'  value=" . $language['no'] . " size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
		echo "</div><br>\n";
	}
} else {
	if ($validsite != 1) {
		//form
		echo "<br><br><p>" . $language['firstweekday-set-up'] . "</p>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"22\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"1\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<input name='ok' type='submit'  value=" . $language['yes'] . " size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<input name='ok' type='submit'  value=" . $language['no'] . " size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
		echo "</div><br>\n";
	} else {
		//update the crawlt_config_table
		
		//database connection
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		$sqlupdatepublic = "UPDATE crawlt_config SET firstdayweek='Monday'";
		$requeteupdatepublic = db_query($sqlupdatepublic, $connexion);
		
		//clear cache table
		$sqlcache = "TRUNCATE TABLE crawlt_cache";
		$requetecache = db_query($sqlcache, $connexion);
		mysql_close($connexion);
		echo "<br><br><p>" . $language['update'] . "</p><br><br>";
		
		//continue
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<input name='ok' type='submit'  value='OK ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
	}
}
?>
