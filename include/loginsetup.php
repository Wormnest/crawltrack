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
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: loginsetup.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_INSTALL')) {
	exit('<h1>No direct access</h1>');
}

if (isset($_POST['login'])) {
    $login = trim($_POST['login']); // trim whitespace at begin and end
    $login = filter_var($login, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$changed = $_POST['login'] != $login;
} else {
	$login = '';
	$changed = false;
}

//valid form
if ($settings->validlogin == 1) {
	// Init password variables
	// There is no need for sanitizing your password as you need to hash it anyway and passwords with random characters are allowed.
	// In case of mistakes and showing the form again just leave the fields empty since user can't know or see what was entered wrong!
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

	if (empty($login) || $changed || empty($password2) || empty($password3) || $password2 != $password3) {
		echo "<p>" . $language['login_no_ok'] . "</p>";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='6'>\n";
		echo "<input type=\"hidden\" name ='navig' value='15'>\n";
		echo "<input type=\"hidden\" name ='validlogin' value='0'>\n";
		echo "<input type=\"hidden\" name ='lang' value='$settings->language'>\n";
		echo "<input type=\"hidden\" name ='login' value='$login'>\n";
		echo "<input type=\"hidden\" name ='password2' value=''>\n";
		echo "<input type=\"hidden\" name ='password3' value=''>\n";
		echo "<input type=\"hidden\" name ='site' value='$settings->siteid'>\n";
		echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
		echo "</form>\n";
		echo "<br></div>\n";
	} else {
		require_once("accounts.class.php");
		$accounts = new ctAccounts($db);
		
		if ($accounts->username_exists($login)) {
			// This username already exists
			echo "<h1>" . $language['exist_login'] . "</h1>";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='validform' value='6'>\n";
			echo "<input type=\"hidden\" name ='navig' value='15'>\n";
			echo "<input type=\"hidden\" name ='validlogin' value='0'>\n";
			echo "<input type=\"hidden\" name ='lang' value='$settings->language'>\n";
			echo "<input type=\"hidden\" name ='login' value='$login'>\n";
			echo "<input type=\"hidden\" name ='password2' value=''>\n";
			echo "<input type=\"hidden\" name ='password3' value=''>\n";
			echo "<input type=\"hidden\" name ='site' value='$settings->siteid'>\n";
			echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
			echo "</form>\n";
			echo "<br></div>\n";
		} else {
			// Add the user to the database
			if ($accounts->add_admin($login, $password2)) {
				// Admin user was added
				echo "<h2>" . $language['admin_creation'] . "</h2>\n";
				echo "<p>" . $language['login_ok'] . "</p>\n";
				echo "<h5>" . $language['login_finish'] . "</h5>\n";
				echo "<div class=\"form\">\n";
				echo "<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
				echo "</form>\n";
				echo "<br></div>\n";
			} else {
				// Adding admin user failed
				echo "<h2>" . $language['admin_creation'] . "</h2>\n";
				echo "<p>" . $language['login_no_ok2'] . "</p>";
				echo "<div class=\"form\">\n";
				echo "<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='validform' value='6'>\n";
				echo "<input type=\"hidden\" name ='navig' value='15'>\n";
				echo "<input type=\"hidden\" name ='validlogin' value='1'>\n";
				echo "<input type=\"hidden\" name ='lang' value='$settings->language'>\n";
				echo "<input type=\"hidden\" name ='login' value='$login'>\n";
				echo "<input type=\"hidden\" name ='password2' value=''>\n";
				echo "<input type=\"hidden\" name ='password3' value=''>\n";
				echo "<input name='ok' type='submit'  value=' " . $language['retry'] . " ' size='20'>\n";
				echo "</form>\n";
				echo "<br></div>\n";
			}
		}
		$accounts = null;
		$db->close();
	}
}
//form
else {
	echo "<h2>" . $language['admin_creation'] . "</h2>\n";
	echo "<p>" . $language['admin_setup'] . "</p>\n";
	echo "<p>" . $language['admin_rights'] . "</p>\n";
	echo "</div>\n";
	
	//data collect form
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"6\">";
	echo "<input type=\"hidden\" name ='navig' value='15'>\n";
	echo "<input type=\"hidden\" name ='validlogin' value='1'>\n";
	echo "<input type=\"hidden\" name ='lang' value='$settings->language'>\n";
	echo "<table class=\"centrer\">\n";
	echo "<tr>\n";
	echo "<td>" . $language['login'] . "</td>\n";
	echo "<td><input name='login'  value='$login' type='text' maxlength='20' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['password'] . "</td>\n";
	echo "<td><input name='password2' value='' type='password' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
	echo "" . $language['valid_password'] . "\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['password'] . "</td>\n";
	echo "<td><input name='password3' value='' type='password' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form><br>\n";
}
?>
