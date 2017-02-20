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
// file: adminchangepassword.php
//----------------------------------------------------------------------
// Purpose: Change password
// TODO: Minimum pw length and strength
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

if ($settings->validlogin == 1) {
	// Init password variables
	// There is no need for sanitizing your password as you need to hash it anyway and passwords with random characters are allowed.
	// In case of mistakes and showing the form again just leave the fields empty since user can't know or see what was entered wrong!
	if (isset($_POST['password1'])) {
		$password1 = $_POST['password1'];
	} else {
		$password1 = '';
	}
	if (isset($_POST['password2'])) {
		$password2 = $_POST['password2'];
	} else {
		$password2 = '';
	}
	if (isset($_POST['password3'])) {
		$password3 = $_POST['password3'];
	} else {
		$password3 = '';
	}

	require_once("accounts.class.php");
	$accounts = new ctAccounts($db);

	if (!$accounts->is_valid_login($_SESSION['userlogin'], $password1) || empty($password2) || empty($password3) || $password2 != $password3) {
		// User made a mistake.
		echo "<p>" . $language['login_no_ok'] . "</p>";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='30'>\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validlogin' value='0'>\n";
		echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
		echo "</form>\n";
		echo "</div><br><br>\n";
	} else {
		// Update the currently logged in users password
		$result = $accounts->change_password($_SESSION['userlogin'], $password2);
		
		if (!$result) {
			echo "<h1>Warning: changing password failed!</h1>";
		} else {
			echo "<br><br><p>" . $language['update'] . "</p><br><br>";
		}
		
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
	$accounts = null;
	$db->close();
} else {
	// First arrival on the page or arriving back here when we made a mistake.
	// In case of making a mistake we do not fill in the previously entered passwords. Since the user can't
	// see what he/she typed they will have to entere everything again anyway since they can't see what was typed wrong.
	// This also saves us the job of filtering passwords because they should not be filtered.
	echo "<h1>" . $language['change_password'] . "</h1>\n";
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"30\">";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='validlogin' value='1'>\n";
	echo "<table class=\"centrer\">\n";
	echo "<tr>\n";
	echo "<td>" . $language['old_password'] . "</td>\n";
	echo "<td><input name='password1'  value='' type='password' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['new_password'] . "</td>\n";
	echo "<td><input name='password2' value='' type='password' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
	echo "" . $language['valid_new_password'] . "\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['new_password'] . "</td>\n";
	echo "<td><input name='password3' value='' type='password' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "</div><br><br>\n";
}
?>
