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
// file: adminmodifsite.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
$sitenamedisplay = htmlentities($sitename);
$siteurldisplay = htmlentities($siteurl);
if ($validsite == 1) {
	//form to enter the new data
	echo "<br><br><h1>" . $language['modif_site'] . "</h1>\n";
	echo "</div>\n";
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='validform' value=\"23\">\n";
	echo "<input type=\"hidden\" name ='validsite' value=\"2\">\n";
	echo "<input type=\"hidden\" name ='site' value='$site'>\n";
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
	echo "</form>\n";
} elseif ($validsite == 2) {
	//check if data is empty
	if (empty($sitename) || empty($siteurl)) {
		//go back to form
		echo "<br><br><p>" . $language['site_no_ok'] . "</p>";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='23'>\n";
		echo "<input type=\"hidden\" name ='validsite' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='sitename' value='$sitenamedisplay'>\n";
		echo "<input type=\"hidden\" name ='siteurl' value='$siteurldisplay'>\n";
		echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
		echo "</form>\n";
		echo "</div>\n";
	} else {
		//update database
		
		//database connection
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		
		$sql = "UPDATE crawlt_site SET name='" . sql_quote($sitename) . "',url='" . sql_quote($siteurl) . "'
			WHERE id_site= '" . sql_quote($site) . "'";
		$requete = db_query($sql, $connexion);
		mysql_close($connexion);
		echo "<br><br><h1>" . $language['modif_site'] . "</h1>\n";
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
} else {
	//first arrival on the page
	//database connection
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
	$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
	//request to get the sites datas
	$sql = "SELECT id_site, name, url FROM crawlt_site";
	$requete = db_query($sql, $connexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		while ($ligne = mysql_fetch_row($requete)) {
			$listsite2[] = $ligne[0];
			$namesite[$ligne[0]] = $ligne[1];
			$urlsite[$ligne[0]] = $ligne[2];
		}
	}
	mysql_close($connexion);
	echo "<br><br><h1>" . $language['modif_site'] . "</h1>\n";
	echo "</div>\n";
	echo "<div align=\"center\"><table cellpadding='0px' cellspacing='0'>\n";
	echo "<tr><th class='tableau1'>\n";
	echo "&nbsp;" . $language['site_name2'] . "&nbsp;\n";
	echo "</th>\n";
	echo "<th class='tableau1'>\n";
	echo "&nbsp;" . $language['site_url2'] . "&nbsp;\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "&nbsp;\n";
	echo "</th></tr>\n";
	//counter for alternate color lane
	$comptligne = 2;
	foreach ($listsite2 as $siteid) {
		if ($comptligne % 2 == 0) {
			echo "<tr><td class='tableau3'>&nbsp;" . $namesite[$siteid] . "&nbsp;</td>\n";
			echo "<td class='tableau3'>&nbsp;" . $urlsite[$siteid] . "&nbsp;</td>\n";
			echo "<td class='tableau5'>&nbsp;<a href='index.php?navig=6&amp;validform=23&amp;sitename=" . urlencode($namesite[$siteid]) . "&amp;siteurl=" . urlencode($urlsite[$siteid]) . "&amp;site=" . $siteid . "&validsite=1'>" . $language['modif_site2'] . "</a>&nbsp;</td></tr>\n";
		} else {
			echo "<tr><td class='tableau30'>&nbsp;" . $namesite[$siteid] . "&nbsp;</td>\n";
			echo "<td class='tableau30'>&nbsp;" . $urlsite[$siteid] . "&nbsp;</td>\n";
			echo "<td class='tableau50'>&nbsp;<a href='index.php?navig=6&amp;validform=23&amp;sitename=" . urlencode($namesite[$siteid]) . "&amp;siteurl=" . urlencode($urlsite[$siteid]) . "&amp;site=" . $siteid . "&validsite=1'>" . $language['modif_site2'] . "</a>&nbsp;</td></tr>\n";
		}
		$comptligne++;
	}
	echo "</table><br>\n";
}
?>
