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
// file: install.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
// do not modify
define('IN_CRAWLT_INSTALL', TRUE);
echo "<div class=\"content\">\n";
//test validity form
if ($validform == 1) {
	// echo text
	echo "<h1>" . $language['install'] . "</h1>\n";
	echo "<p>" . $language['welcome_install'] . "</p>\n";
	echo "<div align=\"left\">\n";
	echo "<h5>" . $language['menu_install_1'] . "</h5>\n";
	echo "<h5>" . $language['menu_install_2'] . "</h5>\n";
	echo "<h5>" . $language['menu_install_3'] . "</h5>\n";
	echo "</div>\n";
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value='2'>\n";
	echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
	echo "<input name=\"ok\" type=\"submit\"  value='" . $language['go_install'] . "' >\n";
	echo "</form>\n";
	echo "<br></div>\n";
} elseif ($validform == 2) {
	//connection data collect
	echo "<h1>" . $language['install'] . "</h1>\n";
	echo "<div align=\"left\">\n";
	echo "<h5>" . $language['menu_install_1'] . "</h5>\n";
	echo "<h4>" . $language['menu_install_2'] . "</h4>\n";
	echo "<h4>" . $language['menu_install_3'] . "</h4>\n";
	echo "</div>\n";
	echo "<p>" . $language['step1_install'] . "</p>\n";
	echo "</div>\n";
	
	//data collect form
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"3\">";
	echo "<input type=\"hidden\" name ='navig' value='15'>\n";
	echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
	echo "<table class=\"centrer\" >\n";
	echo "<tr>\n";
	echo "<td>" . $language['step1_install_login_mysql'] . "</td>\n";
	echo "<td><input name='idmysql'  value='$idmysql' type='text' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['step1_install_password_mysql'] . "</td>\n";
	echo "<td><input name='passwordmysql' value='$passwordmysql' type='password' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['step1_install_host_mysql'] . "</td>\n";
	echo "<td><input name='hostmysql' value='$hostmysql' type='text' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>" . $language['step1_install_database_mysql'] . "</td>\n";
	echo "<td><input name='basemysql' value='$basemysql' type='text' size='50'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form><br>\n";
} elseif ($validform == 3) {
	//file and tables creation
	echo "<h1>" . $language['install'] . "</h1>\n";
	echo "<div align=\"left\">\n";
	echo "<h5>" . $language['menu_install_1'] . "</h5>\n";
	echo "<h4>" . $language['menu_install_2'] . "</h4>\n";
	echo "<h4>" . $language['menu_install_3'] . "</h4>\n";
	echo "</div>\n";
	include "include/createtable.php";
} elseif ($validform == 4) {
	//site creation
	echo "<h1>" . $language['install'] . "</h1>\n";
	echo "<div align=\"left\">\n";
	echo "<h5>" . $language['menu_install_1'] . "</h5>\n";
	echo "<h5>" . $language['menu_install_2'] . "</h5>\n";
	echo "<h4>" . $language['menu_install_3'] . "</h4>\n";
	echo "</div>\n";
	include "include/createsite.php";
} elseif ($validform == 6) {
	//user right
	echo "<h1>" . $language['install'] . "</h1>\n";
	echo "<div align=\"left\">\n";
	echo "<h5>" . $language['menu_install_1'] . "</h5>\n";
	echo "<h5>" . $language['menu_install_2'] . "</h5>\n";
	echo "<h5>" . $language['menu_install_3'] . "</h5>\n";
	echo "</div>\n";
	include "include/loginsetup.php";
} else {
	//language choice
	echo "<br><h1>Welcome in the CrawlTrack installation</h1>\n";
	echo "<br><h2>First you have to choose your language:</h2><br><br>\n";
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<h1><input type=\"radio\" name=\"lang\" value=\"english\" >English\n";
	echo "<input type=\"radio\" name=\"lang\" value=\"spanish\" >Spanish\n";
	echo "<input type=\"radio\" name=\"lang\" value=\"german\" >German\n<br>";
	echo "<input type=\"radio\" name=\"lang\" value=\"turkish\" >Turkish\n";
	echo "<input type=\"radio\" name=\"lang\" value=\"dutch\" >Dutch\n";
	echo "<input type=\"radio\" name=\"lang\" value=\"russian\" >Russian\n<br>";
	echo "<input type=\"radio\" name=\"lang\" value=\"bulgarian\" >Bulgarian\n";
	echo "<input type=\"radio\" name=\"lang\" value=\"french\" >French</h1>\n";
	echo "<input type=\"hidden\" name ='navig' value=6>\n";
	echo "<input type=\"hidden\" name ='validform' value=1>\n";
	echo "<input name=\"ok\" type=\"submit\"  value=\"OK\" >\n";
	echo "</form>\n";
	echo "<br></div>\n";
}
?>
