<?php
//----------------------------------------------------------------------
//  CrawlTrack
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Updating: Jacob Boerema
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// This script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: adminsite.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

$sitenamedisplay = htmlentities($settings->sitename);
$siteurldisplay = htmlentities($settings->siteurl);
//valid form
if ($settings->validsite == 1 && empty($settings->sitename)) {
	echo "<br><br><p>" . $language['site_no_ok'] . "</p>";
	$settings->validsite = 0;
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
	if ($settings->validsite != 1) {
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
		// TODO: Should it also check if the siteurl already exists? Although it should be possible
		// to have multiple entries for the same website so I guess not.
		// Maybe just make it a warning and ask if user is sure.
		$sqlexist = "SELECT * FROM crawlt_site
			WHERE name='" . crawlt_sql_quote($db->connexion, $settings->sitename) . "'";
		$queryexist = db_query($sqlexist, $db->connexion);
		$nbrresult = $queryexist->num_rows;
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
			$sqlsite2 = "INSERT INTO crawlt_site (name, url) VALUES ('" . crawlt_sql_quote($db->connexion, $settings->sitename) . "','" . crawlt_sql_quote($db->connexion, $settings->siteurl) . "')";
			$querysite2 = db_query($sqlsite2, $db->connexion);
			
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
	$db->close(); // Close database
}
?>
