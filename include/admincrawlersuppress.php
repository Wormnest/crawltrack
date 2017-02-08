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
// file: admincrawlersuppress.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

if (isset($_POST['suppresscrawler'])) {
	$suppresscrawler = (int)$_POST['suppresscrawler'];
} else {
	$suppresscrawler = 0;
}
if (isset($_POST['suppresscrawlerok'])) {
	$suppresscrawlerok = (int)$_POST['suppresscrawlerok'];
} else {
	$suppresscrawlerok = 0;
}
if ($suppresscrawler == 1) {
	if (isset($_POST['crawlertosuppress'])) {
		$crawlertosuppress = $_POST['crawlertosuppress'];
	} else {
		header("Location:../index.php");
		exit;
	}
	if (isset($_POST['idcrawlertosuppress'])) {
		$idcrawlertosuppress = (int)$_POST['idcrawlertosuppress'];
	} else {
		header("Location:../index.php");
		exit;
	}
	if ($suppresscrawlerok == 1) {
		//crawler suppression
		//database connection
		require_once("jgbdb.php");
		$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
		
		//database query to suppress the crawler
		$sqldelete = "DELETE FROM crawlt_crawler WHERE id_crawler= '" . crawlt_sql_quote($connexion, $idcrawlertosuppress) . "'";
		$requetedelete = db_query($sqldelete, $connexion);
		$sqldelete2 = "DELETE FROM crawlt_visits WHERE crawlt_crawler_id_crawler= '" . crawlt_sql_quote($connexion, $idcrawlertosuppress) . "'";
		$requetedelete2 = db_query($sqldelete2, $connexion);
		
		//database query to optimize the table
		$sqloptimize = "OPTIMIZE TABLE crawlt_visits";
		$requeteoptimize = db_query($sqloptimize, $connexion);
		
		//empty the cache table
		$sqlcache = "TRUNCATE TABLE crawlt_cache";
		$requetecache = db_query($sqlcache, $connexion);
		if ($requetedelete && $requetedelete2) {
			echo "<br><br><h1>" . $language['crawler_suppress_ok'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</form>\n";
			echo "</div><br><br>\n";
		} else {
			echo "<br><br><h1>" . $language['crawler_suppress_no_ok'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</form>\n";
			echo "</div><br><br>\n";
		}
mysqli_close($connexion);
	} else {
		//validation of suppression
		//display
		$crawlertosuppress = stripslashes($crawlertosuppress);
		$crawlertosuppressdisplay = htmlentities($crawlertosuppress);
		echo "<br><br><h1>" . $language['crawler_suppress_validation'] . "</h1>\n";
		echo "<h1>" . $language['crawler_name'] . ":&nbsp;$crawlertosuppressdisplay</h1>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"10\">";
		echo "<input type=\"hidden\" name ='suppresscrawler' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='suppresscrawlerok' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='crawlertosuppress' value=\"$crawlertosuppress\">\n";
		echo "<input type=\"hidden\" name ='idcrawlertosuppress' value=\"$idcrawlertosuppress\">\n";
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
		echo "<input type=\"hidden\" name ='validform' value=\"10\">";
		echo "<input type=\"hidden\" name ='suppresscrawler' value=\"0\">\n";
		echo "<input type=\"hidden\" name ='suppresscrawlerok' value=\"0\">\n";
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
	//database connection
	if (isset($crawlthost)) {
		require_once("jgbdb.php");
		$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
	} else {
		require_once("jgbdb.php");
		$connexion = db_connect($host, $tuser, $password, $db);
	}
	
	//database query to get crawler list
	$sqldeletecrawler = "SELECT * FROM crawlt_crawler";
	$requetedeletecrawler = db_query($sqldeletecrawler, $connexion);
	$nbrresult = $requetedeletecrawler->num_rows;
	if ($nbrresult >= 1) {
		while ($ligne = $requetedeletecrawler->fetch_object()) {
			$idcrawler = $ligne->id_crawler;
			$crawlername = $ligne->crawler_name;
			$crawlerua = $ligne->crawler_user_agent;
			$crawlerip = $ligne->crawler_ip;
			$namecrawler[$idcrawler] = $crawlername;
			if (!empty($crawlerua)) {
				$uacrawler[$idcrawler] = $crawlerua;
			} else {
				$uacrawler[$idcrawler] = $crawlerip;
			}
		}
		mysqli_close($connexion);
		asort($namecrawler);
		$current = current($namecrawler);
		do {
			$listidcrawler[] = key($namecrawler);
		} while ($current = next($namecrawler));
		//display
		echo "<br><br><h1>" . $language['crawler_suppress'] . "</h1>\n";
		echo "<div class='tableau' align='center' width='550px'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
		echo "<tr><th class='tableau2' colspan='3'>\n";
		echo "" . $language['crawler_list'] . "\n";
		echo "</th></tr>\n";
		foreach ($listidcrawler as $crawler1) {
			echo "<tr><td class='tableau32' width='15%'>\n";
			echo "" . $namecrawler[$crawler1] . "\n";
			echo "</td><td class='tableau35' width='70%'>\n";
			$ua = "$uacrawler[$crawler1]";
			$long = strlen($ua);
			if ($long > 80) {
				$ua = substr("$uacrawler[$crawler1]", 0, 80);
				$ua = $ua . "...";
			}
			$uadisplay = htmlentities($ua);
			
			echo "$uadisplay\n";
			echo "</td><td class='tableau45' width='15%'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"10\">\n";
			echo "<input type=\"hidden\" name ='suppresscrawler' value=\"1\">\n";
			echo "<input type=\"hidden\" name ='crawlertosuppress' value=\"" . $namecrawler[$crawler1] . "\">\n";
			echo "<input type=\"hidden\" name ='idcrawlertosuppress' value=\"$crawler1\">\n";
			echo "<input type='submit' class='button45' value='" . $language['suppress_crawler'] . "'>\n";
			echo "</form>\n";
			echo "</td></tr>\n";
		}
		echo "</table></div>\n";
		echo "<br><br>\n";
	} else {
		//display
		echo "<br><br><h1>" . $language['crawler_suppress'] . "</h1>\n";
		echo "<div class='tableau' align='center' width='550px'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
		echo "<tr><th class='tableau2' colspan='3'>\n";
		echo "" . $language['crawler_list'] . "\n";
		echo "</th></tr>\n";
		echo "</table></div>\n";
		echo "<br><br>\n";
	}
}
?>
