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
// file: adminlang.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

if ($validlogin == 1) {
	//update the crawlt_config_table
	//database connection
	require_once("jgbdb.php");
	$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

	$sqlupdatemail = "UPDATE crawlt_config SET lang='" . crawlt_sql_quote($connexion, $crawltnewlang) . "'";
	$requeteupdatemail = db_query($sqlupdatemail, $connexion);
	if ($crawltnewlang == "russian" || $crawltnewlang == "bulgarian" || $crawltnewlang == "turkish" || $crawltnewlang == "italian") {
		$sqlupdate = "UPDATE crawlt_config SET typecharset='1'";
		$requeteupdate = db_query($sqlupdate, $connexion);
	mysqli_close($connexion);
	}
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
	//language choice
	echo "<h1>" . $language['choose_language'] . "</h1><br><br>\n";
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=25>\n";
	echo "<input type=\"hidden\" name ='validlogin' value=1>\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	if ($crawltlang == 'german' || $crawltlang == 'germaniso') {
		echo "<h2><input type=\"radio\" name=\"newlang\" value=\"german\" checked>" . $language['german'] . "\n";
	} else {
		echo "<h2><input type=\"radio\" name=\"newlang\" value=\"german\" >" . $language['german'] . "\n";
	}
	if ($crawltlang == 'english' || $crawltlang == 'englishiso') {
		echo "<input type=\"radio\" name=\"newlang\" value=\"english\" checked>" . $language['english'] . "\n";
	} else {
		echo "<input type=\"radio\" name=\"newlang\" value=\"english\" >" . $language['english'] . "\n";
	}
	if ($crawltlang == 'spanish' || $crawltlang == 'spanishiso') {
		echo "<input type=\"radio\" name=\"newlang\" value=\"spanish\" checked>" . $language['spanish'] . "\n";
	} else {
		echo "<input type=\"radio\" name=\"newlang\" value=\"spanish\" >" . $language['spanish'] . "\n";
	}
	if ($crawltlang == 'french' || $crawltlang == 'frenchiso') {
		echo "<input type=\"radio\" name=\"newlang\" value=\"french\" checked>" . $language['french'] . "\n";
	} else {
		echo "<input type=\"radio\" name=\"newlang\" value=\"french\" >" . $language['french'] . "\n";
	}
	if ($crawltlang == 'dutch' || $crawltlang == 'dutchiso') {
		echo "<input type=\"radio\" name=\"newlang\" value=\"dutch\" checked>" . $language['dutch'] . "\n";
	} else {
		echo "<input type=\"radio\" name=\"newlang\" value=\"dutch\" >" . $language['dutch'] . "\n";
	}
	if ($crawltlang == 'turkish' || $crawltlang == 'turkishiso') {
		echo "<input type=\"radio\" name=\"newlang\" value=\"turkish\" checked>" . $language['turkish'] . "<br>\n";
	} else {
		echo "<input type=\"radio\" name=\"newlang\" value=\"turkish\" >" . $language['turkish'] . "<br>\n";
	}
	if ($crawltlang == 'russian') {
		echo "<input type=\"radio\" name=\"newlang\" value=\"russian\" checked>" . $language['russian'] . "\n";
	} else {
		echo "<input type=\"radio\" name=\"newlang\" value=\"russian\" >" . $language['russian'] . "\n";
	}
	if ($crawltlang == 'bulgarian') {
		echo "<input type=\"radio\" name=\"newlang\" value=\"bulgarian\" checked>" . $language['bulgarian'] . "\n";
	} else {
		echo "<input type=\"radio\" name=\"newlang\" value=\"bulgarian\" >" . $language['bulgarian'] . "\n";
	}
	if ($crawltlang == 'italian') {
		echo "<input type=\"radio\" name=\"newlang\" value=\"italian\" checked>" . $language['italian'] . "</h2>\n";
	} else {
		echo "<input type=\"radio\" name=\"newlang\" value=\"italian\" >" . $language['italian'] . "</h2>\n";
	}
	echo "<input name=\"ok\" type=\"submit\"  value=\"OK\" >\n";
	echo "</form>\n";
	echo "<br></div>\n";
}
?>
