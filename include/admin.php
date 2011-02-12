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
// file: admin.php
//----------------------------------------------------------------------
//  Last update: 12/02/2011
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
// do not modify
define('IN_CRAWLT_ADMIN', TRUE);
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//query to know the actual session id in the table
$sql = "SELECT sessionid FROM crawlt_sessionid";
$requete = db_query($sql, $connexion);
$nbrresult = mysql_num_rows($requete);
if ($nbrresult >= 1) {
	while ($ligne = mysql_fetch_row($requete)) {
		$listsessionid[] = $ligne[0];
	}
} else {
	$listsessionid = array();
}

//website list query
if ($_SESSION['rightsite'] == 0) {
	$sql = "SELECT id_site, name, url 
	FROM crawlt_site";
} else {
	$siteright = $_SESSION['rightsite'];
	$sql = "SELECT id_site, name, url 
	FROM crawlt_site	
	WHERE id_site = '" . sql_quote($siteright) . "'";
}

//request to get the sites datas
$requete = db_query($sql, $connexion);
$nbrresult = mysql_num_rows($requete);
if ($nbrresult >= 1) {
	while ($ligne = mysql_fetch_row($requete)) {
		$listsite[] = $ligne[0];
		$namesite[$ligne[0]] = $ligne[1];
		$urlsite[$ligne[0]] = $ligne[2];
	}
}

//include menu
include ("include/menumain.php");
if ($validform == 33) {
	include ("include/menusite.php");
}
echo "<div class=\"content\">\n";
if ($crawltlang == 'french' || $crawltlang == 'frenchiso') {
?>
	<div align="right">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="5631126">
	<input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
	<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form>
	</div>
	<?php
} else {
?>
	<div align="right">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="5631313">
	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form>
	</div>
	<?php
}
if ($_SESSION['rightadmin'] == 1) {
	switch($validform)
	{
		case 6:
			include ("include/adminuser.php");
		break;
		case 7:
			include ("include/adminusersite.php");
		break;
		case 4:
			include ("include/adminsite.php");
		break;
		case 3:
			include ("include/admintag.php");
		break;
		case 2:
			include ("include/admincrawler.php");
		break;
		case 8:
			include ("include/adminusersuppress.php");
		break;
		case 9:
			include ("include/adminsitesuppress.php");
		break;
		case 10:
			include ("include/admincrawlersuppress.php");
		break;
		case 11:
			include ("include/testcrawlercreation.php");
		break;
		case 12:
			include ("include/testcrawlersuppress.php");
		break;
		case 13:
			include ("include/update.php");
		break;
		case 14:
			include ("include/updateremote.php");
		break;
		case 15:
			include ("include/updatelocal.php");
		break;
		case 16:
			include ("include/logochoice.php");
		break;
		case 17:
			include ("include/admindatasuppress.php");
		break;
		case 18:
			include ("include/admintime.php");
		break;
		case 19:
			include ("include/adminipsuppress.php");
		break;
		case 20:
			include ("include/adminmail.php");
		break;
		case 21:
			include ("include/adminpublicstats.php");
		break;
		case 22:
			include ("include/testcrawlercreation.php");
		break;
		case 23:
			include ("include/adminmodifsite.php");
		break;
		case 25:
			include ("include/adminlang.php");
		break;
		case 26:
			include ("include/admindatabase.php");
		break;
		case 27:
			include ("include/updateattack.php");
		break;
		case 28:
			include ("include/updateremoteattack.php");
		break;
		case 29:
			include ("include/updatelocalattack.php");
		break;
		case 30:
			include ("include/adminchangepassword.php");
		break;
		case 31:
			include ("include/admingoodsites.php");
		break;
		case 32:
			include ("include/adminbadreferer.php");
		break;
		case 33:
			include ("include/admingoodreferer.php");
		break;
		default:
			switch($validform) {
				case 96:
					//update the crawlt_config table
					$sql = "UPDATE crawlt_config SET typecharset='" . sql_quote($crawltcharset) . "'";
					$requete = db_query($sql, $connexion);
				break;
				case 97:
					//update the crawlt_config table
					$sql = "UPDATE crawlt_config SET typemail='" . sql_quote($crawltmailishtml) . "'";
					$requete = db_query($sql, $connexion);
				break;
				case 98:
					//update the crawlt_config table
					$sql = "UPDATE crawlt_config SET rowdisplay='" . sql_quote($rowdisplay) . "'";
					$requete = db_query($sql, $connexion);
				break;
				case 99:
					//update the crawlt_config table
					$sql = "UPDATE crawlt_config SET orderdisplay='" . sql_quote($order) . "'";
					$requete = db_query($sql, $connexion);
				break;
				case 100:
					//update the crawlt_config table
					$sql = "UPDATE crawlt_config SET blockattack='" . sql_quote($crawltblockattack) . "'";
					$requete = db_query($sql, $connexion);
				break;
				case 101:
					//clear crawlt_sessionid table
					$sql = "TRUNCATE TABLE crawlt_sessionid";
					$requete = db_query($sql, $connexion);
					$listsessionid = array();
					//insert new value in the crawlt_sessionid table
					$value = "";
					$testsessionid = false;
					if ($crawltsessionid1 == 1) {
						$value.= "('PHPSESSID'),";
						$testsessionid = true;
						$listsessionid[] = 'PHPSESSID';
					}
					if ($crawltsessionid2 == 1) {
						$value.= "('phpsessid'),";
						$testsessionid = true;
						$listsessionid[] = 'phpsessid';
					}
					if ($crawltsessionid3 == 1) {
						$value.= "('ID'),";
						$testsessionid = true;
						$listsessionid[] = 'ID';
					}
					if ($crawltsessionid4 == 1) {
						$value.= "('id'),";
						$testsessionid = true;
						$listsessionid[] = 'id';
					}
					if ($crawltsessionid5 == 1) {
						$value.= "('SID'),";
						$testsessionid = true;
						$listsessionid[] = 'SID';
					}
					if ($crawltsessionid6 == 1) {
						$value.= "('sid'),";
						$testsessionid = true;
						$listsessionid[] = 'sid';
					}
					if ($crawltsessionid7 == 1) {
						$value.= "('S'),";
						$testsessionid = true;
						$listsessionid[] = 'S';
					}
					if ($crawltsessionid8 == 1) {
						$value.= "('s'),";
						$testsessionid = true;
						$listsessionid[] = 's';
					}
					if (!$testsessionid) {
						$crawltsessionid = 0;
						$listsessionid = array();
					} elseif ($crawltsessionid == 1) {
						//update the crawlt_sessionid table
						$value = rtrim($value, ",");
						$sql = "INSERT INTO crawlt_sessionid (sessionid) VALUES $value";
						$requete = db_query($sql, $connexion);
					} else {
						$listsessionid = array();
					}
					//update the crawlt_config table
					$sql = "UPDATE crawlt_config SET sessionid='" . sql_quote($crawltsessionid) . "'";
					$requete = db_query($sql, $connexion);
				break;
				case 102:
					foreach ($listsite as $idsite) {
						if (isset($_POST["idsite" . $idsite])) {
							$valstatsite[$idsite] = (int)$_POST["idsite" . $idsite];
						} else {
							$valstatsite[$idsite] = 0;
						}
					}
					foreach ($valstatsite as $key => $cookie) {
						$domain = urlencode($urlsite[$key]);
						echo "<img src=\"./php/crawltsetcookie.php?key=$key&cookie=$cookie\" width=\"1px\" height=\"1px\" border=\"0\" >";
						if ($domain != $_SERVER["HTTP_HOST"]) {
							echo "<img src=\"http://$domain/crawltsetcookie.php?key=$key&cookie=$cookie\" width=\"1px\" height=\"1px\" border=\"0\" >";
						}
						if ($cookie == 1) {
							$_COOKIE["crawltrackstats" . $key] = 'nocountinstats';
						} else {
							$_COOKIE["crawltrackstats" . $key] = 'countinstats';
						}
					}
				break;
				case 103:
					//update the crawlt_config table
					$sql = "UPDATE crawlt_config SET includeparameter='" . sql_quote($crawltincludeparameter) . "'";
					$requete = db_query($sql, $connexion);
				break;
			} // end switch($validform) - 2nd level
			mysql_close($connexion);
			
			?>
			<h1><?php echo $language['admin'] ?></h1>
			<table><tr><td width="550px" valign="top">
			<h5><img src="./images/page_white_php.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=16"><?php echo $language['see_tag'] ?></a></h5><br>
			<h5><img src="./images/tick.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=11"><?php echo $language['crawler_test_creation'] ?></a></h5>
			<h5><img src="./images/cancel.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=12"><?php echo $language['crawler_test_suppress'] ?></a></h5><br>
			<h5><img src="./images/transmit_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=13"><?php echo $language['update_crawler'] ?></a></h5>
			<h5><img src="./images/transmit_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=27"><?php echo $language['update_attack'] ?></a></h5>
			<h5><img src="./images/database_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=31"><?php echo $language['goodsite_update'] ?></a></h5>
			<h5><img src="./images/database_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=32"><?php echo $language['badreferer_update'] ?></a></h5>
			<h5><img src="./images/database_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=33"><?php echo $language['goodreferer_update'] ?></a></h5><br>
			<h5><img src="./images/email.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=20"><?php echo $language['mail'] ?></a></h5>
			<h5><img src="./images/clock.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=18"><?php echo $language['time_set_up'] ?></a></h5>
			<h5><img src="./images/calendar_view_week.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=22"><?php echo $language['firstweekday-title'] ?></a></h5>
			<h5><img src="./images/lock_open.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=21"><?php echo $language['public'] ?></a></h5>
			<h5><img src="./images/language.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=25"><?php echo $language['choose_language'] ?></a></h5><br>
			<h5><img src="./images/user_edit.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=30"><?php echo $language['change_password'] ?></a></h5>
			<h5><img src="./images/user_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=6"><?php echo $language['user_create'] ?></a></h5>
			<h5><img src="./images/user_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=7"><?php echo $language['user_site_create'] ?></a></h5>
			<h5><img src="./images/world_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=4"><?php echo $language['new_site'] ?></a></h5>
			<h5><img src="./images/world_edit.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=23"><?php echo $language['modif_site'] ?></a></h5>
			<h5><img src="./images/database_add.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=2"><?php echo $language['new_crawler'] ?></a></h5><br>
			<h5><img src="./images/user_delete.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=8"><?php echo $language['user_suppress'] ?></a></h5>
			<h5><img src="./images/world_delete.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=9"><?php echo $language['site_suppress'] ?></a></h5>
			<h5><img src="./images/database_delete.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=10"><?php echo $language['crawler_suppress'] ?></a></h5>
			<h5><img src="./images/database_delete.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=19"><?php echo $language['ip_suppress'] ?></a></h5><br>
			<h5><img src="./images/database.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=26"><?php echo $language['admin_database'] ?></a></h5>
			<h5><img src="./images/compress.png" width="16" height="16" border="0" >&nbsp;&nbsp;<a href="./index.php?navig=6&validform=17"><?php echo $language['data_suppress'] ?></a></h5><br>
			</td><td valign="top" width="450px">
			<?php
			if ($crawltlang == 'french' || $crawltlang == 'frenchiso') {
				echo "<h2>CrawlTrack infos<br><iframe name=\"I1\" src=\"http://www.crawltrack.net/news/crawltrack-news-fr.php\" marginwidth=\"1\" marginheight=\"1\" scrolling=\"auto\" border=\"1\" bordercolor=\"#003399\" frameborder=\"1px\" width=\"300px\" height=\"150px\"></iframe></h2>\n";
			} else {
				echo "<h2>CrawlTrack news<br><iframe name=\"I1\" src=\"http://www.crawltrack.net/news/crawltrack-news-en.php\" marginwidth=\"1\" marginheight=\"1\" scrolling=\"auto\" border=\"1\" bordercolor=\"#003399\" frameborder=\"1px\" width=\"300px\" height=\"150px\"></iframe></h2>\n";
			}
			echo "<br><h2>" . $language['stats_visitors'] . "</h2>";
			echo "<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:71px; margin-right:71px; font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\" >\n";
			echo $language['count_in_stats'];
			echo "<br><br><form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"102\">\n";
			echo "<table>";
			foreach ($listsite as $idsite) {
				if (isset($_COOKIE["crawltrackstats" . $idsite]) && $_COOKIE["crawltrackstats" . $idsite] == 'nocountinstats') {
					echo "<tr><td>" . $namesite[$idsite] . "</td><td><input type=\"checkbox\" name=\"idsite" . $idsite . "\" value=\"1\" checked></td></tr>\n";
				} else {
					echo "<tr><td>" . $namesite[$idsite] . "</td><td><input type=\"checkbox\" name=\"idsite" . $idsite . "\" value=\"1\"></td></tr>\n";
				}
			}
			echo "</table><div width=\"100%\" align=\"right\"><input name='ok' type='submit'  value=' OK ' size='20' >&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";
			echo "<br><div class=\"smalltext\">" . $language['stats_visitors_other_domain'] . "</div>";
			echo "</form></div>&nbsp;\n";
			echo "<br><h2>" . $language['display_parameters'] . "</h2>";
			echo "<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:71px; margin-right:71px;\" >\n";
			echo "<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"98\">\n";
			echo $language['numberrowdisplay'] . "<input name='rowdisplay'  value='$rowdisplay' type='text' maxlength='5' size='5px' style=\" font-size:13px; font-weight:bold; color: #003399;
			font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; float:left\"/><input name='ok' type='submit'  value=' OK ' size='20' style=\" float:left\">\n";
			echo "</form><br><br>\n";
			echo "<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"99\">\n";
			echo $language['ordertype'] . "<select onchange=\"form.submit()\" size=\"1\" name=\"order\"  style=\" font-size:13px; font-weight:bold; color: #003399;
			font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; float:left\">\n";
			if ($order == 0) {
				echo "<option value=\"0\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['orderbydate'] . "</option>\n";
			} else {
				echo "<option value=\"0\" style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['orderbydate'] . "</option>\n";
			}
			if ($order == 1 || $order == 4) {
				echo "<option value=\"1\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['orderbypagesview'] . "</option>\n";
			} else {
				echo "<option value=\"1\" style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['orderbypagesview'] . "</option>\n";
			}
			if ($order == 2) {
				echo "<option value=\"2\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['orderbyvisites'] . "</option>\n";
			} else {
				echo "<option value=\"2\" style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['orderbyvisites'] . "</option>\n";
			}
			if ($order == 3) {
				echo "<option value=\"3\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['orderbyname'] . "</option>\n";
			} else {
				echo "<option value=\"3\" style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['orderbyname'] . "</option>\n";
			}
			echo "</select></form><br>&nbsp;\n";
			echo "<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"97\">\n";
			echo "Email:<br>\n";
			if ($crawltmailishtml == 1) {
				echo "<input type=\"radio\" name=\"typemail\" value=\"1\" checked>HTML &nbsp;&nbsp;\n";
				echo "<input type=\"radio\" name=\"typemail\" value=\"2\">Text\n";
			} else {
				echo "<input type=\"radio\" name=\"typemail\" value=\"1\">HTML &nbsp;&nbsp;\n";
				echo "<input type=\"radio\" name=\"typemail\" value=\"2\" checked>Text\n";
			}
			echo "<input name='ok' type='submit'  value=' OK ' size='20' >\n";
			echo "</form>&nbsp;\n";
			if ($crawltlang != "russian" && $crawltlang != "bulgarian" && $crawltlang !="turkish" && $crawltlang !="italian") {
				echo "<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
			font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
				echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
				echo "<input type=\"hidden\" name ='validform' value=\"96\">\n";
				echo "Charset:<br>\n";
				if ($crawltcharset == 1) {
					echo "<input type=\"radio\" name=\"charset\" value=\"1\" checked>utf-8 &nbsp;&nbsp;\n";
					echo "<input type=\"radio\" name=\"charset\" value=\"2\">iso-8859-1\n";
				} else {
					echo "<input type=\"radio\" name=\"charset\" value=\"1\">utf-8 &nbsp;&nbsp;\n";
					echo "<input type=\"radio\" name=\"charset\" value=\"2\" checked>iso-8859-1\n";
				}
				echo "<input name='ok' type='submit'  value=' OK ' size='20' >\n";
				echo "</form>\n";
			}
			echo "</div>&nbsp;\n";
			echo "<br><h2>" . $language['attack_parameters'] . "</h2>";
			echo "<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:71px; margin-right:71px;\" >\n";
			echo "<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"100\">\n";
			echo $language['attack_action'] . ":<br><br>\n";
			if ($crawltblockattack == 1) {
				echo "<input type=\"radio\" name=\"blockattack\" value=\"1\" checked>" . $language['attack_block'] . " <br>\n";
				echo "<input type=\"radio\" name=\"blockattack\" value=\"0\">" . $language['attack_no_block'] . "\n";
			} else {
				echo "<input type=\"radio\" name=\"blockattack\" value=\"1\">" . $language['attack_block'] . " <br>\n";
				echo "<input type=\"radio\" name=\"blockattack\" value=\"0\" checked>" . $language['attack_no_block'] . "\n";
			}
			echo "<br><div width=\"100%\" align=\"right\"><input name='ok' type='submit'  value=' OK ' size='20' >&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";
			echo "</form>&nbsp;\n";
			echo "<br><h3>" . $language['attack_block_alert'] . "</h3>";
			echo "</div>";
			echo "<br><h2>" . $language['url_parameters'] . "</h2>";
			echo "<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:71px; margin-right:71px;\" >\n";
			echo "<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
				  font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"103\">\n";
			echo $language['remove_parameter'] . ":<br><br>\n";
			if ($crawltincludeparameter == 0) {
				echo "<input type=\"radio\" name=\"includeparameter\" value=\"0\" checked>" . $language['yes'] . " <br>\n";
				echo "<input type=\"radio\" name=\"includeparameter\" value=\"1\">" . $language['no'] . "\n";
			} else {
				echo "<input type=\"radio\" name=\"includeparameter\" value=\"0\">" . $language['yes'] . " <br>\n";
				echo "<input type=\"radio\" name=\"includeparameter\" value=\"1\" checked>" . $language['no'] . "\n";
			}
			echo "<div width=\"100%\" align=\"right\"><input name='ok' type='submit'  value=' OK ' size='20' >&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";
			echo "</form>&nbsp;\n";
			echo "<br><h3>" . $language['remove_parameter_alert'] . "</h3>";
			echo "</div>";
			if ($crawltincludeparameter == 1) {
				echo "<br><h2>" . $language['session_id_parameters'] . "</h2>";
				echo "<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:71px; margin-right:71px;\" >\n";
				echo "<form action=\"index.php\" method=\"POST\" z-index:0 style=\" font-size:13px; font-weight:bold; color: #003399;
				  font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
				echo "<input type=\"hidden\" name ='navig' value=\"6\">\n";
				echo "<input type=\"hidden\" name ='validform' value=\"101\">\n";
				echo $language['remove_session_id'] . ":<br><br>\n";
				if ($crawltsessionid == 1) {
					echo "<input type=\"radio\" name=\"sessionid\" value=\"1\" checked>" . $language['yes'] . " <br>\n";
					echo "<input type=\"radio\" name=\"sessionid\" value=\"0\">" . $language['no'] . "\n";
				} else {
					echo "<input type=\"radio\" name=\"sessionid\" value=\"1\">" . $language['yes'] . " <br>\n";
					echo "<input type=\"radio\" name=\"sessionid\" value=\"0\" checked>" . $language['no'] . "\n";
				}
				echo "<br><br>" . $language['session_id_used'] . ":";
				echo "<table width=\"100%\"><tr><td width=\"50%\" valign=\"top\">";
				if (in_array('PHPSESSID', $listsessionid)) {
					echo "<input type=\"checkbox\" name=\"sessionid1\" value=\"1\" checked>PHPSESSID<br>\n";
				} else {
					echo "<input type=\"checkbox\" name=\"sessionid1\" value=\"1\">PHPSESSID<br>\n";
				}
				if (in_array('phpsessid', $listsessionid)) {
					echo "<input  type=\"checkbox\" name=\"sessionid2\" value=\"1\" checked>phpsessid<br>\n";
				} else {
					echo "<input  type=\"checkbox\" name=\"sessionid2\" value=\"1\">phpsessid<br>\n";
				}
				if (in_array('ID', $listsessionid)) {
					echo "<input type=\"checkbox\" name=\"sessionid3\" value=\"1\" checked>ID<br>\n";
				} else {
					echo "<input type=\"checkbox\" name=\"sessionid3\" value=\"1\">ID<br>\n";
				}
				if (in_array('id', $listsessionid)) {
					echo "<input type=\"checkbox\" name=\"sessionid4\" value=\"1\" checked>id<br>\n";
				} else {
					echo "<input type=\"checkbox\" name=\"sessionid4\" value=\"1\">id<br>\n";
				}
				echo "</td><td valign=\"top\">";
				if (in_array('SID', $listsessionid)) {
					echo "<input type=\"checkbox\" name=\"sessionid5\" value=\"1\" checked>SID<br>\n";
				} else {
					echo "<input type=\"checkbox\" name=\"sessionid5\" value=\"1\">SID<br>\n";
				}
				if (in_array('sid', $listsessionid)) {
					echo "<input type=\"checkbox\" name=\"sessionid6\" value=\"1\" checked>sid<br>\n";
				} else {
					echo "<input type=\"checkbox\" name=\"sessionid6\" value=\"1\">sid<br>\n";
				}
				if (in_array('S', $listsessionid)) {
					echo "<input type=\"checkbox\" name=\"sessionid7\" value=\"1\" checked>S<br>\n";
				} else {
					echo "<input type=\"checkbox\" name=\"sessionid7\" value=\"1\">S<br>\n";
				}
				if (in_array('s', $listsessionid)) {
					echo "<input type=\"checkbox\" name=\"sessionid8\" value=\"1\" checked>s<br>\n";
				} else {
					echo "<input type=\"checkbox\" name=\"sessionid8\" value=\"1\">s<br>\n";
				}
				echo "</td></tr></table>";
				echo "<div width=\"100%\" align=\"right\"><input name='ok' type='submit'  value=' OK ' size='20' >&nbsp;&nbsp;&nbsp;&nbsp;</div>\n";
				echo "</form>&nbsp;\n";
				echo "<br><h3>" . $language['session_id_alert'] . "</h3>";
			}
			echo "</div>";
			echo "</td></tr><tr><td colspan=\"2\">";
			echo "<br><h2>" . $language['download_link'] . "</h2>";
			echo "<div style=\"border: 2px solid #003399 ; padding-left:5px; padding-top:5px; padding-bottom:15px; margin-left:10px; margin-right:10px; font-size:13px;color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\" >\n";
			echo $language['download_link2'] . "<br><br>";
			$urlexplode = explode('/', $_SERVER['PHP_SELF']);
			echo "http://" . $_SERVER['HTTP_HOST'] . "/" . $urlexplode[1] . "/php/countdownload.php?url=<b>" . $language['download_link3'] . "</b>";
			echo "<br><br>" . $language['download_link4'];
			echo "</div>&nbsp;\n";
			echo "</td></tr></table>";
		break;
	}  // end switch($validform) - 1st level
} // end if ($_SESSION['rightadmin'] == 1)
else
{
	if ($validform == 3) {
		include "include/admintag.php";
	} elseif ($validform == 16) {
		include "include/logochoice.php";
	} elseif ($validform == 30) {
		include "include/adminchangepassword.php";
	} else {
		echo "<h1>" . $language['admin'] . "</h1>\n";
		echo "<h5><img src=\"./images/page_white_php.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=16\">" . $language['see_tag'] . "</a></h5><br><br>\n";
		echo "<h5><img src=\"./images/user_edit.png\" width=\"16\" height=\"16\" border=\"0\" >&nbsp;&nbsp;<a href=\"./index.php?navig=6&validform=30\">" . $language['change_password'] . "</a></h5>\n";
		echo "<br><br><br><br><br><br><br><br>";
	}
}
?>
