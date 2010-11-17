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
// file: createsite.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_INSTALL')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
$sitenamedisplay = htmlentities($sitename);
$siteurldisplay = htmlentities($siteurl);
//valid form
if ($validsite == 1 && empty($sitename)) {
	echo "<p>" . $language['site_no_ok'] . "</p>";
	$validsite = 0;
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value='4'>\n";
	echo "<input type=\"hidden\" name ='navig' value='15'>\n";
	echo "<input type=\"hidden\" name ='sitename' value='$sitenamedisplay'>\n";
	echo "<input type=\"hidden\" name ='siteurl' value='$siteurldisplay'>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
	echo "</form>\n";
	echo "<br></div>\n";
} else {
	//database connection
	include ("include/configconnect.php");
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
	$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	if ($validsite != 1) {
		//form to add site in the database
		echo "<p>" . $language['set_up_site'] . "</p>\n";
		echo "</div>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='15'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"4\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"1\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>" . $language['site_name'] . "</td>\n";
		echo "<td><input name='sitename'  value='$sitenamedisplay' type='text' maxlength='45' size='50'/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>" . $language['site_url'] . "</td>\n";
		echo "<td><input name='siteurl'  value='$siteurldisplay' type='text' maxlength='250' size='50'/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<br>\n";
		echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
	} else {
		//add the site in the database
		
		//check if site already exist
		$sqlexist = "SELECT * FROM crawlt_site
		WHERE name='" . sql_quote($sitename) . "'";
		$queryexist = db_query($sqlexist, $connexion);
		$nbrresult = mysql_num_rows($queryexist);
		
		if ($nbrresult >= 1) {
			//site already exist
			echo "<h1>" . $language['exist_site'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='15'>\n";
			echo "<input type=\"hidden\" name ='validform' value=\"4\">";
			echo "<input type=\"hidden\" name ='validsite' value=\"0\">";
			echo "<table class=\"centrer\">\n";
			echo "<tr>\n";
			echo "<td colspan=\"2\">\n";
			echo "<br>\n";
			echo "<input name='ok' type='submit'  value=' " . $language['new_site'] . " ' size='20'>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</form>\n";
			//continue
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='15'>\n";
			echo "<input type=\"hidden\" name ='validform' value=\"6\">";
			echo "<table class=\"centrer\">\n";
			echo "<tr>\n";
			echo "<td colspan=\"2\">\n";
			echo "<input name='ok' type='submit'  value=' " . $language['step4_install'] . " ' size='20'>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</form>\n";
			echo "<br></div>";
		} else {
			//the site didn't exist, we can add it in the database
			$sqlsite2 = "INSERT INTO crawlt_site (name, url) VALUES ('" . sql_quote($sitename) . "','" . sql_quote($siteurl) . "')";
			$querysite2 = db_query($sqlsite2, $connexion);
			
			//empty the cache table
			$sqlcache = "TRUNCATE TABLE crawlt_cache";
			$querycache = db_query($sqlcache, $connexion);
			mysql_close($connexion);
			//check is query is successfull
			if ($querysite2 == 1) {
				echo "<p>" . $language['site_ok'] . "</p>\n";
				//add a new site
				echo "<div class=\"form\">\n";
				/* (suppress in 3.2.0 to avoid confusion during install)
				echo"<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='navig' value='15'>\n";
				echo "<input type=\"hidden\" name ='validform' value=\"4\">";
				echo "<input type=\"hidden\" name ='validsite' value=\"0\">";
				echo"<table class=\"centrer\">\n";
				echo"<tr>\n";
				echo"<td colspan=\"2\">\n";
				echo"<br>\n";
				echo"<input name='ok' type='submit'  value=' ".$language['new_site']." ' size='20'>\n";
				echo"</td>\n";
				echo"</tr>\n";
				echo"</table>\n";
				echo"</form>\n";
				*/
				
				//continue
				echo "<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='navig' value='15'>\n";
				echo "<input type=\"hidden\" name ='validform' value=\"6\">";
				echo "<table class=\"centrer\">\n";
				echo "<tr>\n";
				echo "<td colspan=\"2\">\n";
				echo "<input name='ok' type='submit'  value=' " . $language['step4_install'] . " ' size='20'>\n";
				echo "</td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				echo "</form>\n";
				echo "<br></div>";
			}
		}
	}
}
?>
