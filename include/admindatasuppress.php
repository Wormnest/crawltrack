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
// file: admindatasuppress.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_POST['suppressdata'])) {
	$suppressdata = (int)$_POST['suppressdata'];
} else {
	$suppressdata = 0;
}
if (isset($_POST['suppressdataok'])) {
	$suppressdataok = (int)$_POST['suppressdataok'];
} else {
	$suppressdataok = 0;
}
if ($suppressdata == 1) {
	if (isset($_POST['datatosuppress'])) {
		$datatosuppress = (int)$_POST['datatosuppress'];
	} else {
		header("Location:../index.php");
		exit;
	}
	//initialize array
	$nbvisits = array();
	$nbrpagesview = array();
	$listmonth = array();
	$crawlttablepage = array();
	$crawlttablereferer = array();
	if ($suppressdataok == 1) {
		//data suppression
		//database connection
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		
		//period calculation
		if($datatosuppress == 1) {
			//suppress all visits from bots other than Ask Jeeves/Teoma, Exabot, Googlebot, MSN Bot, Bingbot and Slurp Inktomi (Yahoo)
			$sqldelete = "DELETE crawlt_visits
				FROM crawlt_visits
				INNER JOIN crawlt_crawler
				ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
				WHERE crawler_name NOT IN ('GoogleBot','MSN Bot','Slurp Inktomi (Yahoo)','Ask Jeeves/Teoma','Exabot','Bingbot')";
		} elseif ($datatosuppress == 16) {
			//suppress all attack data
			$sqldelete = "DELETE
				FROM crawlt_visits
				WHERE crawlt_crawler_id_crawler IN ('65500','65501')";
		} else {
			switch($datatosuppress) {
				case 2: //suppress all bots visits more than 1 year old
					$ts = strtotime("-1 year");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits';
				break;
				case 3: //suppress all bots visits more than 6 months old
					$ts = strtotime("-6 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits';
				break;
				case 4: //suppress all bots visits more than 5 months old
					$ts = strtotime("-5 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits';
				break;
				case 5: //suppress all bots visits more than 4 months old
					$ts = strtotime("-4 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits';
				break;
				case 6: //suppress all bots visits more than 3 months old
					$ts = strtotime("-3 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits';
				break;
				case 7: //suppress all bots visits more than 2 months old
					$ts = strtotime("-2 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits';
				break;
				case 8: //suppress all bots visits more than 1 month old
					$ts = strtotime("-1 month");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits';
				break;
				case 9: //suppress all human visits more than 1 year old
					$ts = strtotime("-1 year");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits_human';
				break;
				case 10: //suppress all human visits more than 6 months old
					$ts = strtotime("-6 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits_human';
				break;
				case 11: //suppress all human visits more than 5 months old
					$ts = strtotime("-5 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits_human';
				break;
				case 12: //suppress all human visits more than 4 months old
					$ts = strtotime("-4 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits_human';
				break;
				case 13: //suppress all human visits more than 3 months old
					$ts = strtotime("-3 month");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits_human';
				break;
				case 14: //suppress all human visits more than 2 months old
					$ts = strtotime("-2 months");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits_human';
				break;
				case 15: //suppress all human visits more than 1 month old
					$ts = strtotime("-1 month");
					$datetosuppress = date("Y", $ts) . "-" . date("m", $ts) . "-01";
					$table_name = 'crawlt_visits_human';
				break;
				default:
					exit('<h1>Hacking attempt !!!!</h1>');
			}
			$sqldelete = "DELETE FROM $table_name WHERE `date` < '" . sql_quote($datetosuppress) . "'";
		}
		//suppress data
		$requetedelete = db_query($sqldelete, $connexion);
		//database query to optimize the table
		if ($datatosuppress < 9 || $datatosuppress == 16) {
			$sqloptimize = "OPTIMIZE TABLE crawlt_visits";
		} else {
			$sqloptimize = "OPTIMIZE TABLE crawlt_visits_human";
		}
		$requeteoptimize = db_query($sqloptimize, $connexion);
		//database query to list the pages no more used in crawlt_visits and crawlt_visits_human  table
		$sql = "SELECT id_page 
			FROM  crawlt_pages
			LEFT OUTER JOIN crawlt_visits
			ON crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page
			LEFT OUTER JOIN crawlt_visits_human
			ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page       
			WHERE crawlt_visits.crawlt_pages_id_page IS NULL
			AND crawlt_visits_human.crawlt_id_page IS NULL";
		$requete = db_query($sql, $connexion);
		$nbrresult = mysql_num_rows($requete);
		if ($nbrresult >= 1) {
			while ($ligne = mysql_fetch_row($requete)) {
				$crawlttablepage[] = $ligne[0];
			}
			$crawltlistpage = implode("','", $crawlttablepage);
			//database query to suppress the data in page table
			$sqldelete2 = "DELETE FROM crawlt_pages WHERE id_page IN ('$crawltlistpage')";
			$requetedelete2 = db_query($sqldelete2, $connexion);
			//database query to optimize the table
			$sqloptimize2 = "OPTIMIZE TABLE crawlt_pages";
			$requeteoptimize2 = db_query($sqloptimize2, $connexion);
		}
		if ($datatosuppress > 8 && $datatosuppress != 16) {
			//database query to list the referer no more used in crawlt_visits_human  table
			$sql = "SELECT id_referer 
				FROM  crawlt_referer
				LEFT OUTER JOIN crawlt_visits_human
				ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer       
				WHERE crawlt_visits_human.crawlt_id_referer IS NULL LIMIT 100000";
			$requete = db_query($sql, $connexion);
			$nbrresult = mysql_num_rows($requete);
			
			if ($nbrresult >= 1) {
				while ($ligne = mysql_fetch_row($requete)) {
					$crawlttablereferer[] = $ligne[0];
				}
				$crawltlistreferer = implode("','", $crawlttablereferer);
				
				//database query to suppress the data in referer table
				$sqldelete2 = "DELETE FROM crawlt_referer WHERE id_referer IN ('$crawltlistreferer')";
				$requetedelete2 = db_query($sqldelete2, $connexion);
				
				//database query to optimize the table
				$sqloptimize2 = "OPTIMIZE TABLE crawlt_referer";
				$requeteoptimize2 = db_query($sqloptimize2, $connexion);
			}
			//database query to list the keyword no more used in crawlt_visits_human  table
			$sql = "SELECT id_keyword 
				FROM  crawlt_keyword
				LEFT OUTER JOIN crawlt_visits_human
				ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword       
				WHERE crawlt_visits_human.crawlt_keyword_id_keyword IS NULL LIMIT 100000";
			$requete = db_query($sql, $connexion);
			$nbrresult = mysql_num_rows($requete);
			if ($nbrresult >= 1) {
				while ($ligne = mysql_fetch_row($requete)) {
					$crawlttablekeyword[] = $ligne[0];
				}
				$crawltlistkeyword = implode("','", $crawlttablekeyword);
				
				//database query to suppress the data in referer table
				$sqldelete2 = "DELETE FROM crawlt_keyword WHERE id_keyword IN ('$crawltlistkeyword')";
				$requetedelete2 = db_query($sqldelete2, $connexion);
				
				//database query to optimize the table
				$sqloptimize2 = "OPTIMIZE TABLE crawlt_keyword";
				$requeteoptimize2 = db_query($sqloptimize2, $connexion);
			}
		}
		if ($datatosuppress == 16) {
			//clear crawlt_pages_attack table
			$sql = "TRUNCATE TABLE crawlt_pages_attack";
			$requete = db_query($sql, $connexion);
		}
		//empty the cache table
		$sqlcache = "TRUNCATE TABLE crawlt_cache";
		$requetecache = mysql_query($sqlcache, $connexion) or die("MySQL query error");
		if ($requetedelete) {
			echo "<br><br><h1>" . $language['data_suppress_ok'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</form>\n";
			echo "</div>\n";
		} else {
			echo "<br><br><h1>" . $language['data_suppress_no_ok'] . "</h1>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='6'>\n";
			echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
			echo "</form>\n";
			echo "</div>\n";
		}
mysql_close($connexion);
	} else {
		//validation of suppression
		//display
		if ($datatosuppress == 1) {
			$datatosuppressdisplay = $language['other_bot'];
		} elseif ($datatosuppress == 2) {
			$datatosuppressdisplay = $language['one_year_data'];
		} elseif ($datatosuppress == 3) {
			$datatosuppressdisplay = $language['six_months_data'];
		} elseif ($datatosuppress == 4) {
			$datatosuppressdisplay = $language['five_months_data'];
		} elseif ($datatosuppress == 5) {
			$datatosuppressdisplay = $language['four_months_data'];
		} elseif ($datatosuppress == 6) {
			$datatosuppressdisplay = $language['three_months_data'];
		} elseif ($datatosuppress == 7) {
			$datatosuppressdisplay = $language['two_months_data'];
		} elseif ($datatosuppress == 8) {
			$datatosuppressdisplay = $language['one_month_data'];
		} elseif ($datatosuppress == 9) {
			$datatosuppressdisplay = $language['one_year_data_human'];
		} elseif ($datatosuppress == 10) {
			$datatosuppressdisplay = $language['six_months_data_human'];
		} elseif ($datatosuppress == 11) {
			$datatosuppressdisplay = $language['five_months_data_human'];
		} elseif ($datatosuppress == 12) {
			$datatosuppressdisplay = $language['four_months_data_human'];
		} elseif ($datatosuppress == 13) {
			$datatosuppressdisplay = $language['three_months_data_human'];
		} elseif ($datatosuppress == 14) {
			$datatosuppressdisplay = $language['two_months_data_human'];
		} elseif ($datatosuppress == 15) {
			$datatosuppressdisplay = $language['one_month_data_human'];
		} elseif ($datatosuppress == 16) {
			$datatosuppressdisplay = $language['attack_data'];
		} else {
			exit('<h1>Hacking attempt !!!!</h1>');
		}
		echo "<br><br><h1>" . $language['data_suppress_validation'] . "$datatosuppressdisplay &nbsp;?</h1>\n";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='navig' value='6'>\n";
		echo "<input type=\"hidden\" name ='validform' value=\"17\">";
		echo "<input type=\"hidden\" name ='suppressdata' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='suppressdataok' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='datatosuppress' value=\"$datatosuppress\">\n";
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
		echo "<input type=\"hidden\" name ='suppressdata' value=\"0\">\n";
		echo "<input type=\"hidden\" name ='suppressdataok' value=\"0\">\n";
		echo "<table class=\"centrer\">\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\">\n";
		echo "<input name='ok' type='submit'  value=' " . $language['no'] . " ' size='20'>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</div>";
	}
} else {
	echo "<br><br><h1>" . $language['data_suppress'] . "</h1>\n";
	echo "<br><div class=\"alert2\"><b>" . $language['data_suppress3'] . "</b></div>\n";
	echo "<br><table>\n";
	echo "<tr><td valign='top'>\n";
	echo "<h1>" . $language['data_suppress2'] . "</h1>\n";
	echo "</td><td></td></tr><td></td><td>\n";
	echo "<div class=\"form3\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"16\" >" . $language['attack_data'] . "<br><hr>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"1\" >" . $language['other_bot'] . "<br><hr>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"2\" >" . $language['one_year_data'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"3\">" . $language['six_months_data'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"4\">" . $language['five_months_data'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"5\" >" . $language['four_months_data'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"6\">" . $language['three_months_data'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"7\">" . $language['two_months_data'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"8\">" . $language['one_month_data'] . "<br><hr>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"9\" >" . $language['one_year_data_human'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"10\">" . $language['six_months_data_human'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"11\">" . $language['five_months_data_human'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"12\">" . $language['four_months_data_human'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"13\">" . $language['three_months_data_human'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"14\">" . $language['two_months_data_human'] . "<br><br>\n";
	echo "<input type=\"radio\" name=\"datatosuppress\" value=\"15\">" . $language['one_month_data_human'] . "<br><br>\n";
	echo "<input type=\"hidden\" name =\"suppressdata\" value=\"1\">\n";
	echo "<input type=\"hidden\" name =\"navig\" value=\"6\">\n";
	echo "<input type=\"hidden\" name =\"validform\" value=\"17\">\n";
	echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
	echo "</form>\n";
	echo "</div>";
	echo "</td></tr></table>\n";
	echo "<br><div class=\"alert2\"><b>" . $language['data_suppress3'] . "</b></div><br><br>\n";
}
?>
