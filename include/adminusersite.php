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
// file: adminusersite.php
//----------------------------------------------------------------------
// Purpose: Add a new non admin user that has access to statistics of one site
// TODO: adminuser.php and adminusersite.php should be combined (add an option all sites)
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$listsite = array();
$listidsite = array();

//valid form
if ($validlogin == 1) {
	if (empty($login) || empty($password2) || empty($password3) || $password2 != $password3) {
		echo "<br><br><p>" . $language['login_no_ok'] . "</p>";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='7'>\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validlogin' value='0'>\n";
		echo "<input type=\"hidden\" name ='logintype' value='$logintype'>\n";
		echo "<input type=\"hidden\" name ='login' value='$login'>\n";
		echo "<input type=\"hidden\" name ='password2' value='$password2'>\n";
		echo "<input type=\"hidden\" name ='password3' value='$password3'>\n";
		echo "<input type=\"hidden\" name ='site' value='$site'>\n";
		echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
		echo "</form>\n";
		echo "</div><br><br>\n";
	} else {
		//database connection
		require_once("db.class.php");
		require_once("accounts.class.php");

		$db = new ctDb(); // Create db connection
		$accounts = new ctAccounts($db); // Create login handling class

		//check if user already exists
		if ($accounts->username_exists($login)) {
			// User already exists. Try again.
			//login already exist
			echo "<br><br><h1>" . $language['exist_login'] . "</h1>";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='validform' value='7'>\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='validlogin' value='0'>\n";
			echo "<input type=\"hidden\" name ='logintype' value='$logintype'>\n";
			echo "<input type=\"hidden\" name ='login' value='$login'>\n";
			echo "<input type=\"hidden\" name ='password2' value='$password2'>\n";
			echo "<input type=\"hidden\" name ='password3' value='$password3'>\n";
			echo "<input type=\"hidden\" name ='site' value='$site'>\n";
			echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
			echo "</form>\n";
			echo "</div><br><br>\n";
		} else {
			// Add user credentials to the database
			if ($accounts->add_nonadmin_user($login, $password2, $site)) {
				echo "<br><br><h2>" . $language['user_site_creation'] . "</h2>\n";
				echo "<p>" . $language['login_ok'] . "</p>\n";
				echo "<div class=\"form\">\n";
				echo "<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='navig' value='6'>\n";
				echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
				echo "</form>\n";
				echo "</div><br><br>\n";
			} else {
				// Adding user account failed
				echo "<br><br><h2>" . $language['user_site_creation'] . "</h2>\n";
				echo "<p>" . $language['login_no_ok2'] . "</p>";
				echo "<div class=\"form\">\n";
				echo "<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='validform' value='7'>\n";
				echo "<input type=\"hidden\" name ='navig' value='6'>\n";
				echo "<input type=\"hidden\" name ='validlogin' value='1'>\n";
				echo "<input type=\"hidden\" name ='site' value='$site'>\n";
				echo "<input type=\"hidden\" name ='logintype' value='$logintype'>\n";
				echo "<input type=\"hidden\" name ='login' value='$login'>\n";
				echo "<input type=\"hidden\" name ='password2' value='$password2'>\n";
				echo "<input type=\"hidden\" name ='password3' value='$password3'>\n";
				echo "<input name='ok' type='submit'  value=' " . $language['retry'] . " ' size='20'>\n";
				echo "</form>\n";
				echo "</div><br><br>\n";
			}
		}
		$db->close();
	}
}

//form
else {
	echo "<br><br><h2>" . $language['user_site_creation'] . "</h2>\n";
	echo "<p>" . $language['user_site_setup'] . "</p>\n";
	echo "<p>" . $language['login_user_site_what'] . "</p>\n";
	
	//database connection
	require_once("jgbdb.php");
	$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
	
	//mysql query
	$sqlsite = "SELECT * FROM crawlt_site";
	$querysite = db_query($sqlsite, $connexion);
	$nbrresult = $querysite->num_rows;
	// TODO: Add option "All sites" (translated).
	while ($ligne = $querysite->fetch_object()) {
		$sitename = $ligne->name;
		$siteid = $ligne->id_site;
		$listsite[] = $sitename;
		$listidsite[] = $siteid;
	}
	mysqli_close($connexion);
	//preparation of site list display
	$nbrsite = sizeof($listsite);
	$nbrsiteaf = 0;
	echo "</div>\n";
	
	//data collect form
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<table class=\"centrer\">\n";
	echo "<tr><td>\n";
	echo "" . $language['site_name'] . "";
	echo "<select  size=\"1\" name=\"site\"  style=\" font-size:14px; font-weight:bold; color: #003399;
	font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">\n";
	do {
		if ($listidsite[$nbrsiteaf] == $site) {
			echo "<option value=\"$listidsite[$nbrsiteaf]\" selected style=\" font-size:14px; font-weight:bold; color: #003399;
			font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $listsite[$nbrsiteaf] . "</option>\n";
		} else {
			echo "<option value=\"$listidsite[$nbrsiteaf]\" style=\" font-size:14px; font-weight:bold; color: #003399;
			font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $listsite[$nbrsiteaf] . "</option>\n";
		}
		$nbrsiteaf = $nbrsiteaf + 1;
	} while ($nbrsiteaf < $nbrsite);
	echo "</select>\n";
	echo "<input type=\"hidden\" name ='validform' value=\"7\">";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='validlogin' value='1'>\n";
	echo "<input type=\"hidden\" name ='logintype' value='$logintype'>\n";
	echo "<td></tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['login'] . "</td>\n";
	echo "<td><input name='login'  value='$login' type='text' maxlength='20' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['password'] . "</td>\n";
	echo "<td><input name='password2' value='$password2' type='password' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
	echo "" . $language['valid_password'] . "\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['password'] . "</td>\n";
	echo "<td><input name='password3' value='$password3' type='password' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form><br><br>\n";
}
?>
