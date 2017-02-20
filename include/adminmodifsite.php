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
// file: adminmodifsite.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

$sitenamedisplay = htmlentities($settings->sitename);
$siteurldisplay = htmlentities($settings->siteurl);
if ($settings->validsite == 1) {
	//form to enter the new data
	echo "<br><br><h1>" . $language['modif_site'] . "</h1>\n";
	echo "</div>\n";
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='validform' value=\"23\">\n";
	echo "<input type=\"hidden\" name ='validsite' value=\"2\">\n";
	echo "<input type=\"hidden\" name ='site' value='$settings->siteid'>\n";
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
	echo "</form><br><br>\n";
} elseif ($settings->validsite == 2) {
	//check if data is empty
	if (empty($settings->sitename) || empty($settings->siteurl)) {
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
		echo "</div><br><br>\n";
	} else {
		//update database
		$sql = "UPDATE crawlt_site SET name='" . crawlt_sql_quote($db->connexion, $settings->sitename) . "',url='" . crawlt_sql_quote($db->connexion, $settings->siteurl) . "'
			WHERE id_site= '" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'";
		$requete = db_query($sql, $db->connexion);
		$db->close(); // Close database
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
		echo "</form><br><br>\n";
	}
} else {
	//first arrival on the page
	//request to get the sites datas
	$sql = "SELECT id_site, name, url FROM crawlt_site";
	$requete = db_query($sql, $db->connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		while ($ligne = $requete->fetch_row()) {
			$listsite2[] = $ligne[0];
			$namesite[$ligne[0]] = $ligne[1];
			$urlsite[$ligne[0]] = $ligne[2];
		}
	}
	$db->close(); // Close database
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
	echo "</table><br><br><br>\n";
}
?>
