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
// file: adminsite.php
//----------------------------------------------------------------------
//  Last update: 12/02/2011
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
$sitenamedisplay = htmlentities($sitename);
$siteurldisplay = htmlentities($siteurl);
//valid form
if ($validsite == 1 && empty($sitename)) {
	echo "<br><br><p>" . $language['site_no_ok'] . "</p>";
	$validsite = 0;
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value='4'>\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='sitename' value='$sitenamedisplay'>\n";
	echo "<input type=\"hidden\" name ='siteurl' value='$siteurldisplay'>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
	echo "</form>\n";
	echo "</div><br><br>\n";
} else {
	//database connection
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
	$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
	if ($validsite != 1) {
		//form to add site in the database
		echo "<br><br><p>" . $language['set_up_site'] . "</p>\n";
		echo "</div>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
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
		echo "</form><br><br>\n";
	} else {
		//add the site in the database
		
		//check if site already exist
		$sqlexist = "SELECT * FROM crawlt_site
			WHERE name='" . sql_quote($sitename) . "'";
		$queryexist = db_query($sqlexist, $connexion);
		$nbrresult = mysql_num_rows($queryexist);
		if ($nbrresult >= 1) {
			//site already exist
			echo "<br><br><h1>" . $language['exist_site'] . "</h1>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<table class=\"centrer\">\n";
			echo "<tr>\n";
			echo "<td colspan=\"2\">\n";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</form><br><br>\n";
		} else {
			//the site didn't exist, we can add it in the database
			$sqlsite2 = "INSERT INTO crawlt_site (name, url) VALUES ('" . sql_quote($sitename) . "','" . sql_quote($siteurl) . "')";
			$querysite2 = db_query($sqlsite2, $connexion);
			
			//check is query is successfull
			if ($querysite2 == 1) {
				echo "<br><br><p>" . $language['site_ok'] . "</p>\n";
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
				echo "</form><br><br>\n";
			}
		}
	}
mysql_close($connexion);
}
?>
