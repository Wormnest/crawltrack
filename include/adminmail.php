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
// file: adminmail.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$listaddresstosuppress = array();
$listaddress = array();
$listaddresstokeep = array();
echo "<h1>" . $language['mail'] . "</h1>\n";
if ($crawltmail == 1) {
	if ($validsite == 1) {
		//update the crawlt_config_table
		
		//database connection
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		$sqlupdatemail = "UPDATE crawlt_config SET mail='0'";
		$requeteupdatemail = db_query($sqlupdatemail, $connexion);
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
	} elseif ($validsite == 2) //add the first email address
	{
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
		echo "<td><input name='sitename'  value='$sitename' type='text' maxlength='45' size='50'/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<br>\n";
		echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br>\n";
	} elseif ($validsite == 3) //add a new email address
	{
		//valid address
		$sitename = strtolower($sitename);
		if (check_email($sitename)) {
			$validaddress = 1;
		} else {
			$validaddress = 0;
		}
		if ($validaddress == 0) {
			echo "<br><br><p>" . $language['address_no_ok'] . "</p>";
			$validsite = 0;
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='validform' value='20'>\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='validsite' value=\"2\">";
			echo "<input type=\"hidden\" name ='sitename' value='$sitename'>\n";
			echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
			echo "</form>\n";
			echo "</div><br>\n";
		} else {
			//database connection
			$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
			$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
			
			$sql = "SELECT addressmail FROM crawlt_config";
			$requete = db_query($sql, $connexion);
			$ligne = mysql_fetch_assoc($requete);
			$addressmail = $ligne['addressmail'];
			$sitename = $addressmail . "," . $sitename;
			
			//update the crawlt_config_table
			$sqlupdatemail = "UPDATE crawlt_config SET addressmail='" . sql_quote($sitename) . "'";
			$requeteupdatemail = db_query($sqlupdatemail, $connexion);
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
	} elseif ($validsite == 4) //choice of email address to suppress
	{
		//database connection
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		$sql = "SELECT addressmail FROM crawlt_config";
		$requete = db_query($sql, $connexion);
		$ligne = mysql_fetch_assoc($requete);
		$addressmail = $ligne['addressmail'];
		mysql_close($connexion);
		$email = explode(',', $addressmail);
		echo "<hr><p><b>" . $language['set_up_mail6'] . "</b></p>\n";
		echo "<div align='center'>";
		echo "<table width='100%'><tr><td align='right'width='40%'>";
		echo "</td>&nbsp;<td align='left'>";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"20\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"5\">";
		$i = 1;
		foreach ($email as $adress) {
			echo "<input type=\"checkbox\" name ='adress" . $i . "' value=\"$adress\">&nbsp;" . $adress . "<br>";
			$i++;
		}
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
	} elseif ($validsite == 5) //suppress email address
	{
		$numberaddress = $_POST['numberaddress'];
		for ($i = 1;$i < $numberaddress;$i++) {
			$address = 'adress' . $i;
			if (isset($_POST["$address"])) {
				$listaddresstosuppress[] = $_POST["$address"];
			}
		}
		if (count($listaddresstosuppress) == ($numberaddress - 1)) {
			//case all the address are suppress
			
			//database connection
			$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
			$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
			
			$sqlupdatemail = "UPDATE crawlt_config SET mail='0', addressmail=''";
			$requeteupdatemail = db_query($sqlupdatemail, $connexion);
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
			//database connection
			$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
			$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
			$sql = "SELECT addressmail FROM crawlt_config";
			$requete = db_query($sql, $connexion);
			$ligne = mysql_fetch_assoc($requete);
			$addressmail = $ligne['addressmail'];
			$email = explode(',', $addressmail);
			foreach ($email as $adress) {
				$listaddress[] = $adress;
			}
			$listaddresstokeep = array_diff($listaddress, $listaddresstosuppress);
			$sitename = '';
			foreach ($listaddresstokeep as $adress) {
				$sitename = $adress . ',' . $sitename;
			}
			$sitename = rtrim($sitename, ",");
			$sqlupdatemail = "UPDATE crawlt_config SET addressmail='" . sql_quote($sitename) . "'";
			$requeteupdatemail = db_query($sqlupdatemail, $connexion);
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
	} else
	//first page when email is already set
	{
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
		//database connection
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		$sql = "SELECT addressmail FROM crawlt_config";
		$requete = db_query($sql, $connexion);
		$ligne = mysql_fetch_assoc($requete);
		$addressmail = $ligne['addressmail'];
		mysql_close($connexion);
		$email = explode(',', $addressmail);
		foreach ($email as $adress) {
			echo $adress . "<br>";
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
	//valid address
	$adressmail = strtolower($sitename);
	if (check_email($adressmail)) {
		$validaddress = 1;
	} else {
		$validaddress = 0;
	}
	if ($validsite == 1 && $validaddress == 0) {
		echo "<br><br><p>" . $language['address_no_ok'] . "</p>";
		$validsite = 0;
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='20'>\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='sitename' value='$sitename'>\n";
		echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
		echo "</form>\n";
		echo "</div><br>\n";
	} else {
		if ($validsite != 1) {
			//form to add site in the database
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
			echo "<td><input name='sitename'  value='$sitename' type='text' maxlength='45' size='50'/></td>\n";
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
			//update the crawlt_config_table
			
			//database connection
			$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
			$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
			
			$sqlupdatemail = "UPDATE crawlt_config SET mail='1', addressmail='" . sql_quote($sitename) . "'";
			$requeteupdatemail = db_query($sqlupdatemail, $connexion);
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
}
?>
