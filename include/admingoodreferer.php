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
// file: admingoodreferer.php
//----------------------------------------------------------------------
//  Last update: 12/02/2011
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
$siteurldisplay = htmlentities($urlreferer);
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
		//good site suppression
		//database connection
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		
		//database query to suppress the site
		$sqldelete = "DELETE FROM crawlt_goodreferer WHERE referer= '" . sql_quote($crawlertosuppress) . "' AND id_site='" . sql_quote($site) . "'";
		$requetedelete = db_query($sqldelete, $connexion);
		
		//empty the cache table
		$sqlcache = "TRUNCATE TABLE crawlt_cache";
		$requetecache = db_query($sqlcache, $connexion);
		if ($requetedelete) {
			echo "<br><br><h1>" . $language['goodsite_suppress_ok'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"33\">";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</form>\n";
			echo "</div><br><br>\n";
		} else {
			echo "<br><br><h1>" . $language['goodsite_suppress_no_ok'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"33\">";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</form>\n";
			echo "</div><br><br>\n";
		}
mysql_close($connexion);
	} else {
		//validation of suppression
		//display
		$crawlertosuppress = stripslashes($crawlertosuppress);
		$crawlertosuppressdisplay = htmlentities($crawlertosuppress);
		echo "<br><br><h1>" . $language['goodsite_suppress_validation'] . "</h1>\n";
		echo "<h1>$crawlertosuppressdisplay</h1>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"33\">";
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
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"33\">";
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
} elseif ($validsite == 1 && empty($urlreferer)) {
	echo "<br><br><p>" . $language['goodsite_no_ok'] . "</p>";
	$validsite = 0;
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value='33'>\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
	echo "<input type=\"hidden\" name ='siteurl' value='$siteurldisplay'>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
	echo "</form>\n";
	echo "</div><br><br>\n";
} elseif ($validsite == 1 && !empty($urlreferer)) {
	//add the site in the database
	
	//treat the url to have only the host
	$urlreferer = ltrim($urlreferer, "http://");
	$parseurl = parse_url('http://' . $urlreferer);
	$urlreferer = $parseurl['host'];
	
	//database connection
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
	$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
	//check if site already exist
	$sqlexist = "SELECT * FROM crawlt_goodreferer
		WHERE referer='" . sql_quote($urlreferer) . "' AND id_site='" . sql_quote($site) . "'";
	$requeteexist = db_query($sqlexist, $connexion);
	$nbrresult = mysql_num_rows($requeteexist);
	if ($nbrresult >= 1) {
		//site already exist
		echo "<br><br><h1>" . $language['exist_site'] . "</h1>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"33\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form><br><br>\n";
	} else {
		//the site didn't exist, we can add it in the database
		$sqlsite2 = "INSERT INTO crawlt_goodreferer (id_site,referer) VALUES ('" . sql_quote($site) . "','" . sql_quote($urlreferer) . "')";
		$requetesite2 = db_query($sqlsite2, $connexion);
		
		//check is requete is successfull
		if ($requetesite2 == 1) {
			echo "<br><br><p>" . $language['site_ok'] . "</p>\n";
			
			//continue
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"33\">";
			echo "<table class=\"centrer\">\n";
			echo "<tr>\n";
			echo "<td colspan=\"2\">\n";
			echo "<input name='ok' type='submit'  value='OK ' size='20'>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</form><br><br>\n";
		}
	}
mysql_close($connexion);
} else {
	//database connection
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
	$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
	//database query to get good sites list
	$sql = "SELECT referer FROM crawlt_goodreferer WHERE id_site='" . sql_quote($site) . "' ";
	$requete = db_query($sql, $connexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		while ($ligne = mysql_fetch_row($requete)) {
			$listgoodsite[] = $ligne[0];
		}
		asort($listgoodsite);		//display
		echo "<br><br><h1>" . $language['goodreferer_list'] . "<br><span class='smalltext'>" . $language['goodreferer_list2'] . "</span></h1>\n";
		echo "<div class='tableau' align='center' width='550px'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
		echo "<tr><th class='tableau2' colspan='2'>\n";
		echo "" . $language['goodreferer_list3'] . "\n";
		echo "</th></tr>\n";
		foreach ($listgoodsite as $id => $goodsite) {
			echo "<tr><td class='tableau3'>\n";
			echo "" . $goodsite . "\n";
			echo "</td><td class='tableau45' width='15%'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"33\">\n";
			echo "<input type=\"hidden\" name ='suppresscrawler' value=\"1\">\n";
			echo "<input type=\"hidden\" name ='crawlertosuppress' value=\"" . $goodsite . "\">\n";
			echo "<input type=\"hidden\" name ='idcrawlertosuppress' value=\"$id\">\n";
			echo "<input type='submit' class='button45' value='" . $language['suppress_goodsite'] . "'>\n";
			echo "</form>\n";
			echo "</td></tr>\n";
		}
		echo "</table></div>\n";
		echo "<br><br>\n";
		echo "<h2>" . $language['add_goodreferer'] . "</h2>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type=\"hidden\" name ='validform' value=\"33\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"1\">";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>" . $language['site_url'] . "</td>\n";
		echo "<td><input name='urlreferer'  value='$siteurldisplay' type='text' maxlength='250' size='50'/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td >\n";
		echo "<br>\n";
		echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form></div><br><br>\n";
	} else {
		//display
		echo "<br><br><h1>" . $language['goodreferer_list'] . "<br><span class='smalltext'>" . $language['goodreferer_list2'] . "</span></h1>\n";
		echo "<div class='tableau' align='center' width='550px'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
		echo "<tr><th class='tableau2'>\n";
		echo "" . $language['goodreferer_list3'] . "\n";
		echo "</th></tr>\n";
		echo "<tr><td class='tableau5'>\n";
		echo "" . $language['listgoodreferer_empty'] . "\n";
		echo "</td></tr>\n";
		echo "</table></div>\n";
		echo "<br><br>\n";
		echo "<h2>" . $language['add_goodreferer'] . "</h2>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"33\">";
		echo "<input type=\"hidden\" name ='validsite' value=\"1\">";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td>" . $language['site_url'] . "</td>\n";
		echo "<td><input name='urlreferer'  value='$siteurldisplay' type='text' maxlength='250' size='50'/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td >\n";
		echo "<br>\n";
		echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form></div><br><br>\n";
	}
mysql_close($connexion);
}
?>
