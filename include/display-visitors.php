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
// file: display-visitors.php
//----------------------------------------------------------------------

//----Technicals parameters-------------------------------------------------------------------------------
$maxtime = 20; //maximum total time in seconds allowed for the script to search a link
//--------------------------------------------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array and variable
$listip = array();
$nbrcountry = array();
$nbrcountry2 = array();
$values = array();
$goodrefererlist = array();
$linkstatut = array();
$timestart = time();
$nbrnooksite = 0;
$nbrunknownsite = 0;
$refererlist = array();
$nbrvisitorbrowser = array();
$crawltbrowserlist3 = array();

$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->displayorder . $settings->displayall . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language;

//start the caching
cache($cachename);

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");
//clean table from crawler entry
include ("include/cleaning-crawler-entry.php");
//include visitors calculation file
include ("include/visitors-calculation.php");

//query to get the good referer site list
if (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1) {
	$sql = "SELECT referer 
  FROM crawlt_goodreferer 
  WHERE id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'";
	$requete = db_query($sql, $db->connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		while ($ligne = $requete->fetch_row()) {
			$linkstatut[$ligne[0]] = 'ok';
		}
	}
}
//query to get the referer list
if ($settings->period >= 10) {
	$sql = "SELECT  referer
    FROM crawlt_visits_human
    INNER JOIN crawlt_referer    
    ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
    WHERE  date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "' 
    AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
    AND crawlt_id_crawler= '0' 
    $notinternalreferercondition
    AND referer !=''
";
} else {
	$sql = "SELECT  referer
    FROM crawlt_visits_human
    INNER JOIN crawlt_referer    
    ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
    WHERE  date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "' 
    AND crawlt_id_crawler= '0'     
    $notinternalreferercondition
    AND referer !=''";
}
$requete = db_query($sql, $db->connexion);
$nbrresult = $requete->num_rows;
if ($nbrresult >= 1) {
	if ($settings->period == 0 || $settings->period >= 1000 || $settings->period == 1 || ($settings->period >= 300 && $settings->period < 400)) {
		// we search for referer details only for 1 day or 1 week period
		while ($ligne = $requete->fetch_row()) {
			$parseurl = parse_url(htmlspecialchars($ligne[0]));
			if (isset($parseurl['host'])) {
				@$refererlist[$parseurl['host']]++;
				${'detailreferer' . $parseurl['host']}[] = urldecode($ligne[0]);
				if (!isset($linkstatut[$parseurl['host']])) {
					if ($settings->checklink == 1 && (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1)) {
						if (!isset($_SESSION[$parseurl['host']])) {
							if (islinking($ligne[0], $hostsite)) {
								$linkstatut[$parseurl['host']] = 'ok';
								$goodrefererlist[] = $parseurl['host'];
							} else {
								if ($stoptest == 1) {
									$linkstatut[$parseurl['host']] = 'not-check';
									$nbrunknownsite++;
								} else {
									$_SESSION[$parseurl['host']] = 'no-ok';
									$linkstatut[$parseurl['host']] = 'no-ok';
									$nbrnooksite++;
								}
							}
						} else {
							$linkstatut[$parseurl['host']] = $_SESSION[$parseurl['host']];
							if ($linkstatut[$parseurl['host']] == 'no-ok') {
								$nbrnooksite++;
							} elseif ($linkstatut[$parseurl['host']] == 'not-check') {
								$nbrunknownsite++;
							}
						}
					} else {
						$linkstatut[$parseurl['host']] = '?';
						$nbrunknownsite++;
					}
				}
			}
		}
	} else {
		while ($ligne = $requete->fetch_row()) {
			@$parseurl = parse_url(htmlspecialchars($ligne[0]));
			if (isset($parseurl['host'])) {
				@$refererlist[$parseurl['host']]++;
			}
		}
	}
	arsort($refererlist);
} else {
	$refererlist = array();
}
//query to update the goodreferer table
if ($settings->checklink == 1 && (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1)) {
	if (count($goodrefererlist) >= 1) {
		$goodreferervalues = '';
		foreach ($goodrefererlist as $goodreferer) {
			$goodreferervalues.= "('" . $settings->siteid . "','" . $goodreferer . "'),";
		}
		$goodreferervalues = rtrim($goodreferervalues, ',');
		$sql = "INSERT INTO crawlt_goodreferer (id_site, referer) VALUES $goodreferervalues";
		$requete = db_query($sql, $db->connexion);
	}
}
//-------------------------------------------------------------------------------------------------
//origin graph preparation
//query to get the country code
if (function_exists('geoip_country_code_by_name')) {
	// The server is running a (faster) standalone version of GeoIP
	foreach ($listip as $ip) {
		$codeip = "code-" . $ip;
		if (isset($_SESSION[$codeip])) {
			$countrycode[$ip] = $_SESSION[$codeip];
			@$nbrcountry[$_SESSION[$codeip]]++;
		} else {
			$code = geoip_country_code_by_name($ip);
			if ($code !== false) {
				$code = strtolower($code);
			} else {
				$code = "xx";
			}
			$countrycode[$ip] = $code;
			@$nbrcountry[$code]++;
			$_SESSION[$codeip] = $code;
		}
	}
} else {
	// Use bundled GeoIP
	include ("geoipdatabase/geoip.inc");
	$gi = geoip_open("geoipdatabase/GeoIP.dat", GEOIP_STANDARD);
	foreach ($listip as $ip) {
		$codeip = "code-" . $ip;
		if (isset($_SESSION[$codeip])) {
			$countrycode[$ip] = $_SESSION[$codeip];
			@$nbrcountry[$_SESSION[$codeip]]++;
		} else {
			$code = strtolower(geoip_country_code_by_addr($gi, $ip));
			if ($code == "" || $code == "a1") {
				$code = "xx";
			}
			$countrycode[$ip] = $code;
			@$nbrcountry[$code]++;
			$_SESSION[$codeip] = $code;
		}
	}
	geoip_close($gi);
}
//treatment to prepare the datas for the graph and to display the 5 top and group the other in the 'Other' category
arsort($nbrcountry);
foreach ($nbrcountry as $key => $value) {
	$name[] = $key;
}
$nbrtotcountry = count($nbrcountry);
$i = 0;
foreach ($nbrcountry as $nbr) {
	if ($i > 4 && $nbrtotcountry > 6) {
		$crawler = $name[$i];
		$crawler3 = 'other';
		@$nbrcountry2[$crawler3] = @$nbrcountry2[$crawler3] + $nbrcountry[$crawler];
	} else {
		$crawler = $name[$i];
		@$nbrcountry2[$crawler] = $nbrcountry[$crawler];
	}
	$i++;
}
foreach ($nbrcountry2 as $key => $value) {
	$name2[] = $key;
}
$i = 0;
foreach ($nbrcountry2 as $nbr2) {
	if ($name2[$i] == 'other') {
		$values['other'] = $nbr2;
	} else {
		$values[$name2[$i]] = $nbr2;
	}
	$i++;
}
//prepare data to be transferred to graph file
$datatransferttograph = addslashes(urlencode(serialize($values)));
//insert the values in the graph table
$piegraphname2 = "origin2-" . $cachename;
//check if this graph already exists in the table
$sql = "SELECT name  FROM crawlt_graph
          WHERE name= '" . crawlt_sql_quote($db->connexion, $piegraphname2) . "'";
$requete = db_query($sql, $db->connexion);
$nbrresult = $requete->num_rows;
if ($nbrresult >= 1) {
	$sql2 = "UPDATE crawlt_graph SET graph_values='" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "'
            WHERE name= '" . crawlt_sql_quote($db->connexion, $piegraphname2) . "'";
} else {
	$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . crawlt_sql_quote($db->connexion, $piegraphname2) . "','" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "')";
}
$requete2 = db_query($sql2, $db->connexion);
//browser calculation===================================================================================
arsort($nbrvisitorbrowser);
require_once ("include/searchenginelist.php");
//prepare data to be transferred to graph file
$nbrtotbrowser = count($nbrvisitorbrowser);
$i = 0;
foreach ($nbrvisitorbrowser as $key => $value) {
	if ($i > 3 && $nbrtotbrowser > 5) {
		$crawltbrowserlist3['other'] = @$crawltbrowserlist3['other'] + $value;
	} else {
		$crawltbrowserlist3[str_replace("Internet Explorer", "IE", $crawltbrowserlist2[$key]) ] = $value;
	}
	$i++;
}
$datatransferttograph = addslashes(urlencode(serialize($crawltbrowserlist3)));
//insert the values in the graph table
$piegraphname3 = "browser-" . $cachename;
//check if this graph already exists in the table
$sql = "SELECT name  FROM crawlt_graph
          WHERE name= '" . crawlt_sql_quote($db->connexion, $piegraphname3) . "'";
$requete = db_query($sql, $db->connexion);
$nbrresult = $requete->num_rows;
if ($nbrresult >= 1) {
	$sql2 = "UPDATE crawlt_graph SET graph_values='" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "'
            WHERE name= '" . crawlt_sql_quote($db->connexion, $piegraphname3) . "'";
} else {
	$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . crawlt_sql_quote($db->connexion, $piegraphname3) . "','" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "')";
}
$requete2 = db_query($sql2, $db->connexion);

//visits per hour graph calculation========================================================================
if ($settings->period == 0 || $settings->period >= 1000) {
	$nbvisitsgraph = array("0" => "0", "1" => "0", "2" => "0", "3" => "0", "4" => "0", "5" => "0", "6" => "0", "7" => "0", "8" => "0", "9" => "0", "10" => "0", "11" => "0", "12" => "0", "13" => "0", "14" => "0", "15" => "0", "16" => "0", "17" => "0", "18" => "0", "19" => "0", "20" => "0", "21" => "0", "22" => "0", "23" => "0","100" => "0", "101" => "0", "102" => "0", "103" => "0", "104" => "0", "105" => "0", "106" => "0", "107" => "0", "108" => "0", "109" => "0", "110" => "0", "111" => "0", "112" => "0", "113" => "0", "114" => "0", "115" => "0", "116" => "0", "117" => "0", "118" => "0", "119" => "0", "120" => "0", "121" => "0", "122" => "0", "123" => "0");
	//actual period
if ($settings->period == 0) {
		//query to count the number of  visits
		$sqlstats = "SELECT  HOUR(date), COUNT(id_visit) FROM crawlt_visits_human
		WHERE  date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
		AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
		GROUP BY HOUR(date)";
	} elseif ($settings->period >= 1000) {
		//query to count the number of  visits
		$sqlstats = "SELECT  HOUR(date), COUNT(id_visit) FROM crawlt_visits_human
		WHERE  date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    		AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "' 
		AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "' 
		GROUP BY HOUR(date)";
	}
	$requetestats = db_query($sqlstats, $db->connexion);
	while ($ligne = $requetestats->fetch_row()) {
		$hour = $ligne[0] - $settings->timediff;
		if ($hour < 0) {
			$hour = 24 + $hour;
		}
		if ($hour >= 24) {
			$hour = $hour - 24;
		}
		$nbvisitsgraph[$hour] = $ligne[1];
	}
	//average 7 previous days
	$daterequest3 = date("Y-m-d H:i:s", (strtotime($daterequest)) - 604800);
		//query to count the number of  visits
		$sqlstats = "SELECT  HOUR(date), COUNT(id_visit), COUNT(DISTINCT DATE(date)) FROM crawlt_visits_human
		WHERE  date >'" . crawlt_sql_quote($db->connexion, $daterequest3) . "' 
    		AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    		AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
		GROUP BY HOUR(date)";
		
	$requetestats = db_query($sqlstats, $db->connexion);
	while ($ligne = $requetestats->fetch_row()) {
		$hour = $ligne[0] - $settings->timediff;
		if ($hour < 0) {
			$hour = 24 + $hour;
		}
		if ($hour >= 24) {
			$hour = $hour - 24;
		}
		$hour= $hour + 100;
		$nbvisitsgraph[$hour] = round($ligne[1]/$ligne[2]);
	}
	
	//prepare data to be transferred to graph file
	$datatransferttograph = addslashes(urlencode(serialize($nbvisitsgraph)));
	//insert the values in the graph table
	$graphname2 = "visitshours-" . $cachename;
	//check if this graph already exists in the table
	$sql = "SELECT name  FROM crawlt_graph
		WHERE name= '" . crawlt_sql_quote($db->connexion, $graphname2) . "'";
	$requete = db_query($sql, $db->connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		$sql2 = "UPDATE crawlt_graph SET graph_values='" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "'
			WHERE name= '" . crawlt_sql_quote($db->connexion, $graphname2) . "'";
	} else {
		$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . crawlt_sql_quote($db->connexion, $graphname2) . "','" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "')";
	}
	$requete2 = db_query($sql2, $db->connexion);
}
//display=======================================================================================================
if ($totalvisitor > 0) {
	echo "<div class=\"content2\"><br><hr>\n";
	echo "</div>\n";
	//summary table display
	echo "<div class='tableau' align='center' onmouseout=\"javascript:montre();\">\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr><th class='tableau1' >\n";
	echo "" . $language['visits'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1'>\n";
	echo "" . $language['unique_visitors'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1'>\n";
	echo "" . $language['nbr_pages'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1'>\n";
	echo "" . $language['average_pages'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['bounce_rate'] . "\n";
	echo "</th></tr>\n";
	echo "<tr><td class='tableau3'>" . numbdisp($totalvisitor) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp($nbrvisitor) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp($nbrpage) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp($nbrpage / $totalvisitor, 1) . "</td>\n";
	echo "<td class='tableau5'>" . numbdisp(($onepage / $nbrvisitor) * 100, 1) . " %</td></tr>\n";
	echo "</table></div><br>\n";
	//graph
	if ($visitsendgoogle > 0) {
		$values2['google'] = $visitsendgoogle;
	}
	if ($visitsendgoogleimage > 0) {
		$values2['googleimage'] = $visitsendgoogleimage;
	}	
	if ($visitsendmsn > 0) {
		$values2['msn'] = $visitsendmsn;
	}
	if ($visitsendyahoo > 0) {
		$values2['yahoo'] = $visitsendyahoo;
	}
	if ($visitsendask > 0) {
		$values2['ask'] = $visitsendask;
	}
	if ($visitsendexalead > 0) {
		$values2['baidu'] = $visitsendexalead;
	}
	if ($visitsendyandex > 0) {
		$values2['yandex'] = $visitsendyandex;
	}
	if ($visitsendaol > 0) {
		$values2['aol'] = $visitsendaol;
	}		
	if ($visitsendother > 0) {
		$values2['website3'] = $visitsendother;
	}
	if ($visitdirect > 0) {
		$values2['direct'] = $visitdirect;
	}
	arsort($values2);
	//prepare data to be transferred to graph file
	$datatransferttograph = addslashes(urlencode(serialize($values2)));
	//insert the values in the graph table
	$piegraphname = "searchengine-" . $cachename;
	//check if this graph already exists in the table
	$sql = "SELECT name  FROM crawlt_graph
                  WHERE name= '" . crawlt_sql_quote($db->connexion, $piegraphname) . "'";
	$requete = db_query($sql, $db->connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		$sql2 = "UPDATE crawlt_graph SET graph_values='" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "'
                    WHERE name= '" . crawlt_sql_quote($db->connexion, $piegraphname) . "'";
	} else {
		$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . crawlt_sql_quote($db->connexion, $piegraphname) . "','" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "')";
	}
	$requete2 = db_query($sql2, $db->connexion);
	// Don't close database here since mapgraph3 also needs the connection!

	echo "<div class='tableaularge' align='center' onmouseout=\"javascript:montre();\">\n";
	if ($settings->period != 5) {
		//graph
		echo "<div class='graphvisits'>\n";
		//mapgraph
		$typegraph = 'entry';
		include "include/mapgraph3.php";
		echo "<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&amp;crawltlang=$settings->language&amp;period=$settings->period&amp;graphname=$graphname\" USEMAP=\"#seoentry\" border=\"0\" alt=\"graph\" >\n";
		echo "&nbsp;<br><br>\n";
		echo "&nbsp;</div><br>\n";
		echo "<div class='imprimgraph'>\n";
		echo "&nbsp;<br><br><br><br></div>\n";
	}
	//graph hits per hour
	if ($settings->period == 0 || $settings->period >= 1000) {
		echo "<hr><h2>" . $language['hits-per-hour'] . "</h2><br>";
		//graph
		echo "<div class='graphvisits'>\n";
		echo "<img src=\"./graphs/visit-graph.php?crawltlang=$settings->language&period=$settings->period&navig=$settings->navig&graphname=$graphname2\"  alt=\"graph\" width=\"700\" height=\"300\"  border=\"0\"/>\n";
		echo "</div>\n";
		echo "<div class='imprimgraph'>\n";
		echo "&nbsp;<br><br><br><br><br><br></div><br>\n";
	}
	echo "<hr><h2>" . $language['visitor-browser'] . "</h2><br>";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\"><td width='48%' valign='top'>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\">\n";
	echo "<th class='tableau1' >\n";
	echo "" . $language['browser'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2' >\n";
	echo "" . $language['unique_visitors'] . "\n";
	echo "</th></tr>\n";
	//counter for alternate color lane
	$comptligne = 2;
	foreach ($nbrvisitorbrowser as $key => $value) {
		if ($comptligne % 2 == 0) {
			echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . $crawltbrowserlist2[$key] . "</td>\n";
			echo "<td class='tableau5'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp(($value / $nbrvisitor) * 100, 1) . "%)</td></tr>\n";
		} else {
			echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . $crawltbrowserlist2[$key] . "</td>\n";
			echo "<td class='tableau50'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp(($value / $nbrvisitor) * 100, 1) . "%)</td></tr>\n";
		}
		$comptligne++;
	}
	echo "</table><br>\n";
	echo "</td><td>&nbsp;</td><td valign='top' width='48%'>";
	echo "<img src=\"./graphs/origine-graph.php?graphname=$piegraphname3&amp;crawltlang=$settings->language\" alt=\"graph\" style=\"border:0; width:450px; height:200px\" >\n";
	echo "</td></tr></table>";
	//graph
	echo "<hr><div align=\"center\">\n";
	echo "<h2>" . $language['nbr_tot_visit_seo'] . "<br>";
	echo "<img src=\"./graphs/crawler-graph.php?graphname=$piegraphname&amp;crawltlang=$settings->language\" alt=\"graph\"   style=\"border:0; width:450px; height:200px\">\n";
	echo "<img src=\"./graphs/origine-graph.php?graphname=$piegraphname2&amp;crawltlang=$settings->language\" alt=\"graph\" style=\"border:0; width:450px; height:200px\" >\n";
	echo "</h2></div>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\"><td width='48%' valign='top'>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\">\n";
	echo "<th class='tableau1' >\n";
	echo "" . $language['referer'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2' >\n";
	echo "" . $language['visits'] . "\n";
	echo "</th></tr>\n";
	//counter for alternate color lane
	$comptligne = 2;
	foreach ($values2 as $key => $value) {
		if ($comptligne % 2 == 0) {
			echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . $language[$key] . "</td>\n";
			echo "<td class='tableau5'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp(($value / $totalvisitor) * 100, 1) . "%)</td></tr>\n";
		} else {
			echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . $language[$key] . "</td>\n";
			echo "<td class='tableau50'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp(($value / $totalvisitor) * 100, 1) . "%)</td></tr>\n";
		}
		$comptligne++;
	}
	echo "</table><br>\n";
	echo "<table cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\">\n";
	echo "<th class='tableau1' >\n";
	echo "" . $language['crawler_country'] . "\n";
	echo "&nbsp;(" . count($nbrcountry) . " " . $language['country'] . ")</th>\n";
	echo "<th class='tableau2' >\n";
	echo "" . $language['unique_visitors'] . "\n";
	echo "</th></tr>\n";
	//counter for alternate color lane
	$comptligne = 2;
	foreach ($nbrcountry as $key => $value) {
		if ($comptligne % 2 == 0) {
			echo "<tr><td class='tableau3g'>&nbsp;&nbsp;&nbsp;<img src=\"./images/flags/$key.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$key]\">&nbsp;" . $country[$key] . "</td>\n";
			echo "<td class='tableau5'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp(($value / $nbrvisitor) * 100, 1) . "%)</td></tr>\n";
		} else {
			echo "<tr><td class='tableau30g'>&nbsp;&nbsp;&nbsp;<img src=\"./images/flags/$key.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$key]\">&nbsp;" . $country[$key] . "</td>\n";
			echo "<td class='tableau50'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp(($value / $nbrvisitor) * 100, 1) . "%)</td></tr>\n";
		}
		$comptligne++;
	}
	echo "</table><br>\n";
	echo "</td><td>&nbsp;</td><td valign='top' width='48%'>";
	echo "<a name=\"top\"></a>";
	//-----------------------------
	if ($settings->period == 0 || $settings->period >= 1000 || $settings->period == 1 || ($settings->period >= 300 && $settings->period < 400)) {
		// we display referer details only for 1 day or 1 week period
		echo "<table cellpadding='0px' cellspacing='0' width='100%'>\n";
		echo "<tr onmouseover=\"javascript:montre();\">\n";
		echo "<th class='tableau1' colspan='2'>\n";
		echo "" . $language['website'] . "\n";
		$nbrreferer = count($refererlist);
		echo "&nbsp;(" . $nbrreferer . " " . $language['website2'] . ")<br>\n";
		if (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1 && (in_array('?', $linkstatut) || in_array('not-check', $linkstatut))) {
			echo ($nbrreferer - $nbrnooksite - $nbrunknownsite) . " " . $language['website2'] . "<img src=\"./images/tick.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['linkok'] . "\" alt=\"" . $language['linkok'] . "\">\n";
			echo " -- " . $nbrunknownsite . " " . $language['website2'] . "<img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['notcheck'] . "\" alt=\"" . $language['notcheck'] . "\">\n";
			echo " -- " . $nbrnooksite . " " . $language['website2'] . "<img src=\"./images/cancel.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['spamreferer'] . "\" alt=\"" . $language['spamreferer'] . "\"><br>\n";
			echo "<a href=\"./php/refresh.php?navig=$settings->navig&period=$settings->period&site=$settings->siteid&crawler=$crawlencode&graphpos=$settings->graphpos&checklink=1\" rel='nofollow'>" . $language['checklink'] . "</a></th>\n";
		} elseif (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1 && (in_array('?', $linkstatut) || in_array('no-ok', $linkstatut))) {
			echo ($nbrreferer - $nbrnooksite - $nbrunknownsite) . " " . $language['website2'] . "<img src=\"./images/tick.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['linkok'] . "\" alt=\"" . $language['linkok'] . "\">\n";
			echo " -- " . $nbrunknownsite . " " . $language['website2'] . "<img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['notcheck'] . "\" alt=\"" . $language['notcheck'] . "\">\n";
			echo " -- " . $nbrnooksite . " " . $language['website2'] . "<img src=\"./images/cancel.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['spamreferer'] . "\" alt=\"" . $language['spamreferer'] . "\"><br>\n";
		}
		echo "<th class='tableau2' >\n";
		echo "&nbsp;&nbsp;&nbsp;" . $language['nbr_visits'] . "&nbsp;&nbsp;&nbsp;\n";
		echo "</th></tr>\n";
		//counter for alternate color lane
		$comptligne = 2;
		foreach ($refererlist as $key => $value) {
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . @crawltcuturl($key, 50, $settings->useutf8) . "<br>\n";
				${'detailreferer' . $key} = array_unique(${'detailreferer' . $key});
				foreach (${'detailreferer' . $key} as $value2) {
					$value2 = str_replace("&", "&amp;", $value2);
					$value2 = str_replace("\"", "'", $value2);				
					echo "<a href=\"" . $value2 . "\" rel='nofollow'>\n";
					echo "<img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $value2 . "\" alt=\"" . $value2 . "\" >\n";
					echo "</a>\n";
				}
				echo "</td><td class='tableau6'>\n";
				if ($linkstatut[$key] == 'ok' && (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1)) {
					echo "<img src=\"./images/tick.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['linkok'] . "\" alt=\"" . $language['linkok'] . "\">\n";
				}
				if ($linkstatut[$key] == '?' && (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1)) {
					echo "<img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['notcheck'] . "\" alt=\"" . $language['notcheck'] . "\">\n";
				}
				if ($linkstatut[$key] == 'not-check') {
					echo "<img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['notcheck'] . "\" alt=\"" . $language['notcheck'] . "\">\n";
				}
				if ($linkstatut[$key] == 'no-ok' || $linkstatut[$key] == 'not-check') {
					echo "<a href=\"#\" onclick=\"if(confirm('" . $language['goodreferer'] . "')) document.location.href='./php/goodreferer.php?navig=$settings->navig&amp;period=$settings->period&amp;site=$settings->siteid&amp;crawler=$crawlencode&amp;graphpos=$settings->graphpos&amp;referer=$key'\"><img src=\"./images/accept.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['goodreferer2'] . "\" alt=\"" . $language['goodreferer2'] . "\"></a>&nbsp;<a href=\"#\" onclick=\"if(confirm('" . $language['badreferer'] . "')) document.location.href='./php/badreferer.php?navig=$settings->navig&amp;period=$settings->period&amp;site=$settings->siteid&amp;crawler=$crawlencode&amp;graphpos=$settings->graphpos&amp;referer=$key'\"><img src=\"./images/cancel.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['spamreferer'] . "\" alt=\"" . $language['spamreferer'] . "\"></a>\n";
				}
				echo "</td><td class='tableau5'>&nbsp;" . numbdisp($value) . "&nbsp;</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . @crawltcuturl($key, 50, $settings->useutf8) . "<br>\n";
				${'detailreferer' . $key} = array_unique(${'detailreferer' . $key});
				foreach (${'detailreferer' . $key} as $value2) {
					$value2 = str_replace("&", "&amp;", $value2);
					$value2 = str_replace("\"", "'", $value2);
					echo "<a href=\"" . $value2 . "\" rel='nofollow'>\n";
					echo "<img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $value2 . "\" alt=\"" . $value2 . "\">\n";
					echo "</a>\n";
				}
				echo "</td><td class='tableau60'>\n";
				if ($linkstatut[$key] == 'ok' && (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1)) {
					echo "<img src=\"./images/tick.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['linkok'] . "\" alt=\"" . $language['linkok'] . "\">\n";
				}
				if ($linkstatut[$key] == '?' && (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1)) {
					echo "<img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['notcheck'] . "\" alt=\"" . $language['notcheck'] . "\">\n";
				}
				if ($linkstatut[$key] == 'not-check') {
					echo "<img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['notcheck'] . "\" alt=\"" . $language['notcheck'] . "\">\n";
				}
				if ($linkstatut[$key] == 'no-ok' || $linkstatut[$key] == 'not-check') {
					echo "<a href=\"#\" onclick=\"if(confirm('" . $language['goodreferer'] . "')) document.location.href='./php/goodreferer.php?navig=$settings->navig&amp;period=$settings->period&amp;site=$settings->siteid&amp;crawler=$crawlencode&amp;graphpos=$settings->graphpos&amp;referer=$key'\"><img src=\"./images/accept.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['goodreferer2'] . "\" alt=\"" . $language['goodreferer2'] . "\"></a>&nbsp;<a href=\"#\" onclick=\"if(confirm('" . $language['badreferer'] . "')) document.location.href='./php/badreferer.php?navig=$settings->navig&amp;period=$settings->period&amp;site=$settings->siteid&amp;crawler=$crawlencode&amp;graphpos=$settings->graphpos&amp;referer=$key'\"><img src=\"./images/cancel.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['spamreferer'] . "\" alt=\"" . $language['spamreferer'] . "\"></a>\n";
				}
				echo "</td><td class='tableau50'>&nbsp;" . numbdisp($value) . "&nbsp;</td></tr>\n";
			}
			$comptligne++;
		}
		echo "</table><br>\n";
	} else {
		echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
		echo "<tr onmouseover=\"javascript:montre();\">\n";
		echo "<th class='tableau1'>\n";
		echo "" . $language['website'] . "\n";
		$nbrreferer = count($refererlist);
		echo "&nbsp;(" . $nbrreferer . " " . $language['website2'] . ")<br>\n";
		echo "<th class='tableau2' >\n";
		echo "&nbsp;&nbsp;&nbsp;" . $language['nbr_visits'] . "&nbsp;&nbsp;&nbsp;\n";
		echo "</th></tr>\n";
		//counter for alternate color lane
		$comptligne = 2;
		foreach ($refererlist as $key => $value) {
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3g'>&nbsp;&nbsp;<a href=\"http://" . $key . "\" rel='nofollow'>" . @crawltcuturl($key, 50, $settings->useutf8) . "</a>\n";
				echo "</td><td class='tableau5'>&nbsp;" . numbdisp($value) . "&nbsp;</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30g'>&nbsp;&nbsp;<a href=\"http://\"" . $key . "\" rel='nofollow'>" . @crawltcuturl($key, 50, $settings->useutf8) . "</a>\n";
				echo "</td><td class='tableau50'>&nbsp;" . numbdisp($value) . "&nbsp;</td></tr>\n";
			}
			$comptligne++;
		}
		echo "</table><br>\n";
	}
	//----------------------
	echo "&nbsp;";
	echo "</td></tr></table>";
	echo "<p align='center'><span class='smalltext'>" . $language['maxmind'] . " <a href='http://maxmind.com'>http://maxmind.com</a></span></p>\n";
} else {
	echo "<div align='center'>\n";
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
$db->close(); // Close database
?>
