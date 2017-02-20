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
// file: adminusersuppress.php
//----------------------------------------------------------------------
// Purpose: Delete a non admin user account
// TODO: Show which site(s) a user can view
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$loginuser = array();
if (isset($_POST['suppressuser'])) {
	$suppressuser = (int)$_POST['suppressuser'];
} else {
	$suppressuser = 0;
}
if (isset($_POST['suppressuserok'])) {
	$suppressuserok = (int)$_POST['suppressuserok'];
} else {
	$suppressuserok = 0;
}
if ($suppressuser == 1) {
	if (isset($_POST['logintosuppress'])) {
		$logintosuppress = $_POST['logintosuppress'];
	} else {
		header("Location:../index.php");
		exit;
	}
	if ($suppressuserok == 1) {
		// Delete a non admin user account
		require_once("accounts.class.php");
		$accounts = new ctAccounts($db);

		if ($accounts->delete_user_account($logintosuppress)) {
			echo "<br><br><h1>" . $language['user_suppress_ok'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</form>\n";
			echo "</div><br><br>\n";
		} else {
			echo "<br><br><h1>" . $language['user_suppress_no_ok'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</form>\n";
			echo "</div><br><br>\n";
		}
		$accounts = null;
		$db->close();
	} else {
		//validation of suppression
		
		//display
		$logintosuppress = stripslashes($logintosuppress);
		$logintosuppressdisplay = htmlentities($logintosuppress);
		echo "<br><br><h1>" . $language['user_suppress_validation'] . "</h1>\n";
		echo "<h1>" . $language['login'] . ":&nbsp;$logintosuppressdisplay</h1>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"8\">";
		echo "<input type=\"hidden\" name ='suppressuser' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='suppressuserok' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='logintosuppress' value=\"$logintosuppress\">\n";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<input name='ok' type='submit'  value=' " . $language['yes'] . " ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</div>";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"8\">";
		echo "<input type=\"hidden\" name ='suppressuser' value=\"0\">\n";
		echo "<input type=\"hidden\" name ='suppressuserok' value=\"0\">\n";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<input name='ok' type='submit'  value=' " . $language['no'] . " ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</div><br><br>";
	}
} else {
	// Show all non admin user accounts
	require_once("accounts.class.php");
	$accounts = new ctAccounts($db);
	$users = $accounts->get_all_nonadmin_users();
	$accounts = null;
	$db->close();
	if (count($users) > 0) {
		//display
		echo "<br><br><h1>" . $language['user_suppress'] . "</h1>\n";
		echo "<div class='tableau' align='center'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
		echo "<tr><th class='tableau2' colspan='2'>\n";
		echo "" . $language['user_list'] . "\n";
		echo "</th></tr>\n";
		foreach ($users as $user1) {
			$user1display = htmlentities($user1);
			echo "<tr><td class='tableau3' width='300px'>\n";
			echo "" . $user1display . "\n";
			echo "</td><td class='tableau4'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"8\">\n";
			echo "<input type=\"hidden\" name ='suppressuser' value=\"1\">\n";
			echo "<input type=\"hidden\" name ='logintosuppress' value=\"$user1\">\n";
			echo "<input type='submit' class='button4' value='" . $language['suppress_user'] . "'>\n";
			echo "</form>\n";
			echo "</td></tr>\n";
		}
		echo "</table></div>\n";
		echo "<br><br>\n";
	} else {
		echo "<br><br><h1>" . $language['user_suppress'] . "</h1>\n";
		echo "<div class='tableau' align='center'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='450px'>\n";
		echo "<tr><th class='tableau2' colspan='2'>\n";
		echo "" . $language['user_list'] . "\n";
		echo "</th></tr>\n";
		echo "</table></div>\n";
		echo "<br><br>\n";
	}
}
?>
