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
// file: menusite.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$listsites = array();
$urlsite = array();
$listidsite = array();
$nbrpagestotal = 0;

if ($_SESSION['rightsite'] == 0) {
	//mysql query
	$sqlsite = "SELECT * FROM crawlt_site";
	$requetesite = db_query($sqlsite, $db->connexion);
	$nbrresult = $requetesite->num_rows;
	
	if ($nbrresult >= 1) {
		while ($ligne = $requetesite->fetch_object()) {
			$sitename = $ligne->name;
			$siteurl = $ligne->url;
			$siteid = $ligne->id_site;
			$listsites[$siteid] = $sitename;
			$urlsite[$siteid] = $siteurl;
			$listsiteid[] = $siteid;
		}
		//case site 1 not in the base
		if (!in_array($settings->siteid, $listsiteid)) {
			$settings->siteid = min($listsiteid);
		}
		$sitename2 = $listsites[$settings->siteid];
		
		//to avoid problem if the url is entered in the database with http://
		if (!preg_match('#^http://#i', $urlsite[$settings->siteid])) {
			$hostsite = "http://" . $urlsite[$settings->siteid];
		} else {
			$hostsite = $urlsite[$settings->siteid];
		}
		
		//preparation of site list display
		if ($settings->navig == 2 || $settings->navig == 3) {
			if (!isset($_SESSION[$sitename2])) {
				//query to have the total number of page for the site
				$sqlstats = "SELECT COUNT(DISTINCT crawlt_pages_id_page) as numrow FROM crawlt_visits
					WHERE  crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
					AND crawlt_crawler_id_crawler !='0'
					AND crawlt_crawler_id_crawler !='65500'
					AND crawlt_crawler_id_crawler !='65501'
					";
				// TODO: Remove need for jgbdb.php (used for db_result)
				require_once("jgbdb.php");
				$nbrpagestotal = db_result(db_query($sqlstats, $db->connexion),0,"numrow");
				$_SESSION[$sitename2] = $nbrpagestotal;
			} else {
				$nbrpagestotal = $_SESSION[$sitename2];
			}
		}
		//display
		echo "<div class=\"menusite\" align=\"center\">\n";
		echo "<form action=\"index.php\" method=\"POST\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='search' value=\"$settings->searchtype\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"$settings->validform\">\n";
		echo "<input type=\"hidden\" name ='displayall' value=\"$settings->displayall\">\n";
		echo "<input type=\"hidden\" name ='order' value=\"$settings->displayorder\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<select onchange=\"form.submit()\" size=\"1\" name=\"site\"  style=\" font-size:13px; font-weight:bold; color: #003399;
		font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
		
		asort($listsites);
		foreach ($listsites as $id => $sitename3) {
			if ($id == $settings->siteid && isset($_SESSION[$sitename3])) {
				echo "<option value=\"$id\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $sitename3 . "&nbsp;&bull;&nbsp;" . numbdisp($_SESSION[$sitename3]) . " &nbsp;" . $language['page'] . "</option>\n";
			} elseif ($id == $settings->siteid && !isset($_SESSION[$sitename3])) {
				echo "<option value=\"$id\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $sitename3 . "</option>\n";
			} elseif (isset($_SESSION[$sitename3])) {
				echo "<option value=\"$id\" style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $sitename3 . "&nbsp;&bull;&nbsp;" . numbdisp($_SESSION[$sitename3]) . " &nbsp;" . $language['page'] . "</option>\n";
			} else {
				echo "<option value=\"$id\" style=\" font-size:13px; font-weight:bold; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $sitename3 . "</option>\n";
			}
		}
		if (count($listsites) > 1) {
			echo "<option value='0' style=\" font-size:13px; font-weight:bold; font-style:italic; color: #003399;
				font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $language['summary'] . "</option>\n";
		}
		echo "</select></form></div>\n";
	}
} else {
	//mysql query
	$settings->siteid = $_SESSION['rightsite'];
	$sqlsite = "SELECT * FROM crawlt_site
		WHERE id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'";
	$requetesite = db_query($sqlsite, $db->connexion);
	$nbrresult = $requetesite->num_rows;
	
	if ($nbrresult >= 1) {
		while ($ligne = $requetesite->fetch_object()) {
			$sitename = $ligne->name;
			$siteurl = $ligne->url;
			$siteid = $ligne->id_site;
			$urlsite[$siteid] = $siteurl;
		}
		
		//to avoid problem if the url is entered in the database with http://
		if (!preg_match('#^http://#i', $urlsite[$settings->siteid])) {
			$hostsite = "http://" . $urlsite[$settings->siteid];
		} else {
			$hostsite = $urlsite[$settings->siteid];
		}
		if (!isset($_SESSION[$sitename])) {
			//query to have the total number of page for the site
			$sqlstats = "SELECT DISTINCT crawlt_pages_id_page FROM crawlt_visits
				WHERE  crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
				AND crawlt_crawler_id_crawler !='0'
				AND crawlt_crawler_id_crawler !='65500'
				AND crawlt_crawler_id_crawler !='65501'
				";
			$requetestats = db_query($sqlstats, $db->connexion);
			$nbrpagestotal = $requetestats->num_rows;
			$_SESSION[$sitename] = $nbrpagestotal;
		} else {
			$nbrpagestotal = $_SESSION[$sitename];
		}
		
		//display
		echo "<div class=\"menusite\" >\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='search' value=\"$settings->searchtype\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"$settings->validform\">\n";
		echo "<input type=\"hidden\" name ='displayall' value=\"$settings->displayall\">\n";
		echo "<input type=\"hidden\" name ='order' value=\"$settings->displayorder\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<select size=\"1\" name=\"site\"  style=\" font-size:13px; font-weight:bold; color: #003399;
		font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; width:244px;\">\n";
		if (isset($_SESSION[$sitename])) {
			echo "<option value=\"$settings->siteid\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
			font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $sitename . "&nbsp;&bull;&nbsp;" . $_SESSION[$sitename] . "</option>\n";
		} else {
			echo "<option value=\"$settings->siteid\" selected style=\" font-size:13px; font-weight:bold; color: #003399;
			font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif;\">" . $sitename . "</option>\n";
		}
		echo "</select></form></div>\n";
	}
}
?>
