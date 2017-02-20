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
// file: adminmail.php
//----------------------------------------------------------------------
// TODO: $settings->sitename is misleading. It is used here as email address it seems.
// TODO: Total max length of all emails is 255 bytes
// TODO: Check if email already exists before adding
// TODO: Show message if adding or removing email fails!
// More TODO's below!
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$listaddresstosuppress = array();
$listaddress = array();
$listaddresstokeep = array();

echo "<h1>" . $language['mail'] . "</h1>\n";
if ($settings->sendemail == 1) {
	if ($settings->validsite == 1) {
		// Turn off sending email was selected.
		$sqlupdatemail = "UPDATE crawlt_config SET mail='0'";
		$requeteupdatemail = db_query($sqlupdatemail, $db->connexion);
		$db->close(); // Close database
		$settings->sendemail = 0;
		$settings->email = '';
		echo "<br><br><p>" . $language['update'] . "</p><br><br>";
		
		// TODO: Message on update failure.
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
	} elseif ($settings->validsite == 2)
	{
		// Show form to add an email address
		echo "<br><br><p>" . $language['set_up_mail5'] . "</p>\n";
		echo "</div>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"20\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"3\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>" . $language['email-address'] . "</td>\n";
		echo "<td><input name='sitename'  value='$settings->sitename' type='text' maxlength='45' size='50'/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<br>\n";
		echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
	} elseif ($settings->validsite == 3)
	{
		// Add email address if a valid email was entered, or else show message invalid email.
		// Is it a valid address
		$settings->sitename = strtolower($settings->sitename);
		if (check_email($settings->sitename)) {
			$validaddress = 1;
		} else {
			$validaddress = 0;
		}
		if ($validaddress == 0) {
			// Email address was invalid.
			echo "<br><br><p>" . $language['address_no_ok'] . "</p>";
			$settings->validsite = 0;
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='validform' value='20'>\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='validsite' value=\"2\">";
			echo "<input type=\"hidden\" name ='sitename' value='$settings->sitename'>\n";
			echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
			echo "</form>\n";
			echo "</div><br>\n";
		} else {
			// Add email address to database.
			$settings->email = $settings->email . "," . $settings->sitename;
			
			//update the crawlt_config_table
			$sqlupdatemail = "UPDATE crawlt_config SET addressmail='" . crawlt_sql_quote($db->connexion, $settings->email) . "'";
			$requeteupdatemail = db_query($sqlupdatemail, $db->connexion);
			$db->close(); // Close database
			echo "<br><br><p>" . $language['update'] . "</p><br><br>";
			
			// TODO: Show message if updating failed!
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
	} elseif ($settings->validsite == 4)
	{
		// Show form to select an email address that should be removed
		$emails = explode(',', $settings->email);
		echo "<hr><p><b>" . $language['set_up_mail6'] . "</b></p>\n";
		echo "<div align='center'>";
		echo "<table width='100%'><tr><td align='right'width='40%'>";
		//echo "</td>&nbsp;<td align='left'>";
		echo "</td><td align='left'>";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"20\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"5\">";
		$i = 1;
		foreach ($emails as $address) {
			echo "<input type=\"checkbox\" name ='address" . $i . "' value=\"$address\">&nbsp;" . $address . "<br>";
			$i++;
		}
		// Note $i below is used as a count+1.
		echo "<input type=\"hidden\" name ='numberaddress' value=\"$i\">";
		echo "</td></tr></table><br>";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<input name='ok' type='submit'  value='" . $language['set_up_mail7'] . "'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
		echo "</div>";
	} elseif ($settings->validsite == 5)
	{
		// Remove one or more email addresses.
		// $numberaddress = count+1 addresses.
		$numberaddress = (int)$_POST['numberaddress'];
		for ($i = 1;$i < $numberaddress;$i++) {
			$address = 'address' . $i;
			// TODO: Do we need to sanitze _POST? Although the check for correct email might be enough???
			if (isset($_POST["$address"])) {
				$listaddresstosuppress[] = $_POST["$address"];
			}
		}
		if (count($listaddresstosuppress) == ($numberaddress - 1)) {
			// Remove all email addresses means turning off sending emails.
			$sqlupdatemail = "UPDATE crawlt_config SET mail='0', addressmail=''";
			$requeteupdatemail = db_query($sqlupdatemail, $db->connexion);
			$db->close(); // Close database
			$settings->sendemail = 0;
			$settings->email = '';
			echo "<br><br><p>" . $language['update'] . "</p><br><br>";
			
			// TODO: Message if updating fails.
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
			// Remove some of the email addresses
			$emails = explode(',', $settings->email);
			foreach ($emails as $address) {
				$listaddress[] = $address;
			}
			$listaddresstokeep = array_diff($listaddress, $listaddresstosuppress);
			$settings->email = '';
			foreach ($listaddresstokeep as $address) {
				$settings->email = $address . ',' . $settings->email;
			}
			$settings->email = rtrim($settings->email, ",");
			$sqlupdatemail = "UPDATE crawlt_config SET addressmail='" . crawlt_sql_quote($db->connexion, $settings->email) . "'";
			$requeteupdatemail = db_query($sqlupdatemail, $db->connexion);
			$db->close(); // Close database
			echo "<br><br><p>" . $language['update'] . "</p><br><br>";
			
			// TODO: Show message if updating fails!
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
	} else
	{
		// Sending email is set. Form to turn it off again or to add or remove email addresses.
		echo "<br><hr><p><b>" . $language['set_up_mail2'] . "</b></p>\n";
		echo "<br><table width='100%'><tr><td align='right'width='50%'>";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"20\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"1\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<input name='ok' type='submit'  value=" . $language['yes'] . " size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
		echo "</td><td align='left'>";
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
		echo "</td></tr></table>";
		echo "<hr><p><b>" . $language['set_up_mail3'] . "</b></p>\n";
		echo "<div align='center'>";
		$emails = explode(',', $settings->email);
		foreach ($emails as $address) {
			echo $address . "<br>";
		}
		echo "<br><table width='100%'><tr><td align='right'width='50%'>";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"20\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"2\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<input name='ok' type='submit'  value='" . $language['set_up_mail4'] . "'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
		echo "</td><td align='left'>";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"20\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"4\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<input name='ok' type='submit'  value='" . $language['set_up_mail6'] . "' >\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
		echo "</td></tr></table>";
		echo "</div>";
	}
} else {
	// We have sending email turned off.
	// If we have a valid email address then turn it on and add email.
	$addressmail = strtolower($settings->sitename);
	if (check_email($addressmail)) {
		$validaddress = 1;
	} else {
		$validaddress = 0;
	}
	if ($settings->validsite == 1 && $validaddress == 0) {
		// Invalid email address entered.
		echo "<br><br><p>" . $language['address_no_ok'] . "</p>";
		$settings->validsite = 0;
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='20'>\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='sitename' value='$settings->sitename'>\n";
		echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
		echo "</form>\n";
		echo "</div><br>\n";
	} else {
		if ($settings->validsite != 1) {
			// Shown first when we don't have it turned on yet. Ask for email.
			echo "<br><br><p>" . $language['set_up_mail'] . "</p>\n";
			echo "</div>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='validform' value=\"20\">";
			echo "<input type=\"hidden\" name ='validsite' value=\"1\">";
			echo "<table class=\"centrer\">\n";
			echo "<tr>\n";
			echo "<td>" . $language['email-address'] . "</td>\n";
			echo "<td><input name='sitename'  value='$settings->sitename' type='text' maxlength='45' size='50'/></td>\n";
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
			// User entered a valid email address: turn sending email on and add email to database.
			$settings->email = $settings->sitename;
			$sqlupdatemail = "UPDATE crawlt_config SET mail='1', addressmail='" . crawlt_sql_quote($db->connexion, $settings->email) . "'";
			$requeteupdatemail = db_query($sqlupdatemail, $db->connexion);
			$db->close(); // Close database
			$settings->sendemail = 1;
			echo "<br><br><p>" . $language['update'] . "</p><br><br>";
			
			// TODO: Show message if updating failed!
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
}
?>
