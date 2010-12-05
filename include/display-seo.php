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
// file: display-seo.php
//----------------------------------------------------------------------
//  Last update: 05/12/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array and variable
$nbrtag = array();
$tablinkyahoo = array();
$tabpageyahoo = array();
$tablinkmsn = array();
$tabpagemsn = array();
$tablinkdelicious = array();
$tagdelicious = "";
$tablinkexalead = array();
$tabpageexalead = array();
$tablinkgoogle = array();
$tabpagegoogle = array();
if ($period >= 1000) {
	$cachename = "permanent-" . $navig . "-" . $site . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($period >= 100 && $period < 200) //previous month
{
	$cachename = "permanent-month" . $navig . "-" . $site . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($period >= 200 && $period < 300) //previous year
{
	$cachename = "permanent-year" . $navig . "-" . $site . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $navig . $period . $site . $firstdayweek . $localday . $graphpos . $crawltlang;
}
//start the caching
cache($cachename);
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");
//request to get the msn and yahoo positions data and the number of Delicious bookmarks and  Delicious keywords
if ($period >= 10) {
	$sqlseo = "SELECT   linkyahoo, pageyahoo, pagemsn, nbrdelicious,tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle FROM crawlt_seo_position
    WHERE  id_site='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "' 
    AND  date <'" . sql_quote($daterequest2seo) . "'        
    ORDER BY date";
} else {
	$sqlseo = "SELECT  linkyahoo, pageyahoo, pagemsn, nbrdelicious,tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle FROM crawlt_seo_position
    WHERE  id_site='" . sql_quote($site) . "' 
    AND  date >='" . sql_quote($daterequestseo) . "'        
    ORDER BY date";
}
$requeteseo = db_query($sqlseo, $connexion);
$nbrresult = mysql_num_rows($requeteseo);
if ($nbrresult >= 1) {
	$i = 1;
	while ($ligneseo = mysql_fetch_row($requeteseo)) {
		$tablinkyahoo[] = $ligneseo[0];
		$tabpageyahoo[] = $ligneseo[1];
		$tabpagemsn[] = $ligneseo[2];
		$tablinkdelicious[] = $ligneseo[3];
		$tablinkexalead[] = $ligneseo[5];
		$tabpageexalead[] = $ligneseo[6];
		$tablinkgoogle[] = $ligneseo[7];
		$tabpagegoogle[] = $ligneseo[8];
		$tabtag = @unserialize($ligneseo[4]);
		if (is_array($tabtag)) {
			foreach ($tabtag as $key => $value) {
				$nbrtag[$key] = $tabtag[$key];
			}
			$checktagdelicious = 1;
		} else {
			$checktagdelicious = 0;
		}
	}
	if (array_sum($tablinkdelicious) != 0 && $checktagdelicious == 1) {
		arsort($nbrtag);
		foreach ($nbrtag as $tag => $value) {
			if ($crawltcharset == 1) {
				if (!isutf8($tag)) {
					$tag2 = mb_convert_encoding($tag, "UTF-8", "auto");
				} else {
					$tag2 = $tag;
				}
			} else {
				$tag2 = mb_convert_encoding($tag, "ISO-8859-1", "auto");
			}
			if ($tag2 != "") {
				$tagdelicious = $tagdelicious . $tag2 . "(" . $nbrtag[$tag] . "), ";
				if (strlen($tagdelicious) > (55 * $i + (4 * ($i - 1)))) {
					$tagdelicious = $tagdelicious . "<br>";
					$i++;
				}
			}
		}
		$tagdelicious = rtrim($tagdelicious, "<br>");
		$tagdelicious = rtrim($tagdelicious, " ");
		$tagdelicious = rtrim($tagdelicious, ",");
		if (empty($tagdelicious)) {
			$tagdelicious = '-';
		}
	} else {
		$tagdelicious = '-';
	}
	//preparation of values for display
	if ($period == 0 || $period >= 1000) {
		$linkyahoo = numbdisp($tablinkyahoo[0]);
		$pageyahoo = numbdisp($tabpageyahoo[0]);
		$pagemsn = numbdisp($tabpagemsn[0]);
		$linkdelicious = numbdisp($tablinkdelicious[0]);
		$linkexalead = numbdisp($tablinkexalead[0]);
		$pageexalead = numbdisp($tabpageexalead[0]);
		$linkgoogle = numbdisp($tablinkgoogle[0]);
		$pagegoogle = numbdisp($tabpagegoogle[0]);
	} else {
		$linkyahoo = numbdisp($tablinkyahoo[0]) . " --> " . numbdisp($tablinkyahoo[($nbrresult - 1) ]);
		$pageyahoo = numbdisp($tabpageyahoo[0]) . " --> " . numbdisp($tabpageyahoo[($nbrresult - 1) ]);
		$pagemsn = numbdisp($tabpagemsn[0]) . " --> " . numbdisp($tabpagemsn[($nbrresult - 1) ]);
		$linkdelicious = numbdisp($tablinkdelicious[0]) . "-->" . numbdisp($tablinkdelicious[($nbrresult - 1) ]);
		$linkexalead = numbdisp($tablinkexalead[0]) . " --> " . numbdisp($tablinkexalead[($nbrresult - 1) ]);
		$pageexalead = numbdisp($tabpageexalead[0]) . " --> " . numbdisp($tabpageexalead[($nbrresult - 1) ]);
		$linkgoogle = numbdisp($tablinkgoogle[0]) . " --> " . numbdisp($tablinkgoogle[($nbrresult - 1) ]);
		$pagegoogle = numbdisp($tabpagegoogle[0]) . " --> " . numbdisp($tabpagegoogle[($nbrresult - 1) ]);
	}
}
//mysql connexion close
mysql_close($connexion);
//display
echo "<div class=\"content2\"><br><hr>\n";
echo "</div>\n";
//backling and index page table
echo "<div class='tableaularge' align='center'>\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr onmouseover=\"javascript:montre();\">\n";
echo "<th class='tableau10' colspan=\"3\">\n";
echo "" . $language['searchengine'] . "\n";
echo "</th></tr><tr>\n";
echo "<th class='tableau1' width=\"20%\" >\n";
echo "&nbsp;\n";
echo "</th>\n";
echo "<th class='tableau1'  width=\"40%\">\n";
echo "" . $language['nbr_tot_link'] . "\n";
echo "</th>\n";
echo "<th class='tableau2' width=\"40%\">\n";
echo "" . $language['nbr_tot_pages_index'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3g'>&nbsp;&nbsp;&nbsp;<a href=\"http://www.google.com\">" . $language['google'] . "</a>\n";
if ($period == 0 && ($linkgoogle == 0 || $pagegoogle == 0)) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=google&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
if (($tablinkgoogle[0] == $tablinkgoogle[($nbrresult - 1) ]) && $tablinkgoogle[0] == 0) {
	echo "<td class='tableau3' >-</td>\n";
} else {
	echo "<td class='tableau3'>" . $linkgoogle . "</td>\n";
}
if (($tabpagegoogle[0] == $tabpagegoogle[($nbrresult - 1) ]) && $tabpagegoogle[0] == 0) {
	echo "<td class='tableau5'>-</td></tr>\n";
} else {
	echo "<td class='tableau5'>" . $pagegoogle . "</td></tr>\n";
}
echo "<tr><td class='tableau30g'>&nbsp;&nbsp;&nbsp;<a href=\"http://www.exalead.com\">" . $language['exalead'] . "</a>\n";
if ($period == 0 && ($linkexalead == 0 || $pageexalead == 0)) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=exalead&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
if (($tablinkexalead[0] == $tablinkexalead[($nbrresult - 1) ]) && $tablinkexalead[0] == 0) {
	echo "<td class='tableau30' >-</td>\n";
} else {
	echo "<td class='tableau30'>" . $linkexalead . "</td>\n";
}
if (($tabpageexalead[0] == $tabpageexalead[($nbrresult - 1) ]) && $tabpageexalead[0] == 0) {
	echo "<td class='tableau50'>-</td></tr>\n";
} else {
	echo "<td class='tableau50'>" . $pageexalead . "</td></tr>\n";
}
echo "<tr><td class='tableau3g' >&nbsp;&nbsp;&nbsp;<a href=\"http://msdn.microsoft.com/live/search/\">" . $language['msn'] . "</a>\n";
if ($period == 0 && ($pagemsn == 0)) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=msn&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
echo "<td class='tableau3' >-</td>\n";
if (($tabpagemsn[0] == $tabpagemsn[($nbrresult - 1) ]) && $tabpagemsn[0] == 0) {
	echo "<td class='tableau5'>-</td></tr>\n";
} else {
	echo "<td class='tableau5'>" . $pagemsn . "</td></tr>\n";
}
echo "<tr><td class='tableau30g'>&nbsp;&nbsp;&nbsp;<a href=\"http://developer.yahoo.net/about\">" . $language['yahoo'] . "</a>\n";
if ($period == 0 && ($linkyahoo == 0 || $pageyahoo == 0)) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=yahoo&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
if (($tablinkyahoo[0] == $tablinkyahoo[($nbrresult - 1) ]) && $tablinkyahoo[0] == 0) {
	echo "<td class='tableau30' >-</td>\n";
} else {
	echo "<td class='tableau30'>" . $linkyahoo . "</td>\n";
}
if (($tabpageyahoo[0] == $tabpageyahoo[($nbrresult - 1) ]) && $tabpageyahoo[0] == 0) {
	echo "<td class='tableau50'>-</td></tr>\n";
} else {
	echo "<td class='tableau50'>" . $pageyahoo . "</td></tr>\n";
}
echo "</table><br>\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr onmouseover=\"javascript:montre();\">\n";
echo "<th class='tableau10' colspan=\"3\">\n";
echo "" . $language['social-bookmark'] . "\n";
echo "</th></tr><tr>\n";
echo "<th class='tableau1' width=\"24%\">\n";
echo "&nbsp;\n";
echo "</th>\n";
echo "<th class='tableau1' width=\"20%\">\n";
echo "" . $language['nbr_tot_bookmark'] . "\n";
echo "</th>\n";
echo "<th class='tableau2'width=\"56%\">\n";
echo "" . $language['tag'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3g' >&nbsp;&nbsp;&nbsp;<a href=\"http://del.icio.us/help/api/\">" . $language['delicious'] . "</a>\n";
if ($period == 0 && $linkdelicious == 0) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=delicious&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
if ($linkdelicious == 0) {
	echo "<td class='tableau3' >-</td>\n";
} else {
	echo "<td class='tableau3'>" . $linkdelicious . "</td>\n";
}
if ($tagdelicious == ' ') {
	echo "<td class='tableau5'>-</td></tr>\n";
} else {
	echo "<td class='tableau5'>" . $tagdelicious . "</td></tr>\n";
}
echo "</table><br>\n";
echo "</div><br>\n";
if ($period != 5) {
	//graph
	echo "<div class='graphvisits'>\n";
	//mapgraph
	$typegraph = 'link';
	include "include/mapgraph2.php";
	echo "<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&amp;crawltlang=$crawltlang&amp;period=$period&amp;graphname=$graphname\" usemap=\"#seolink\" border=\"0\" alt=\"graph\" >\n";
	echo "&nbsp;</div><br>\n";
	echo "<div class='imprimgraph'>\n";
	echo "&nbsp;<br><br><br><br><br><br><br><br><br><br><br><br><br><br></div>\n";
	//graph
	echo "<div class='graphvisits'>\n";
	//mapgraph
	$typegraph = 'page';
	include "include/mapgraph2.php";
	echo "<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&amp;crawltlang=$crawltlang&amp;period=$period&amp;graphname=$graphname\" usemap=\"#seopage\" border=\"0\" alt=\"graph\" >\n";
	echo "&nbsp;</div><br>\n";
	echo "<div class='imprimgraph'>\n";
	echo "&nbsp;<br><br><br><br></div>\n";
	//graph
	echo "<div class='graphvisits'>\n";
	//mapgraph
	$typegraph = 'bookmark';
	include "include/mapgraph2.php";
	echo "<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&amp;crawltlang=$crawltlang&amp;period=$period&amp;graphname=$graphname\" usemap=\"#bookmark\" border=\"0\" alt=\"graph\" >\n";
	echo "&nbsp;</div><br>\n";
	echo "<div class='imprimgraph'>\n";
	echo "&nbsp;<br><br><br><br>\n";
} else {
	echo "<div>\n";
}
?>
