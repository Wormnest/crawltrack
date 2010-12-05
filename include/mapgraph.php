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
// file: mapgraph.php
//----------------------------------------------------------------------
//  Last update: 05/12/2010
//----------------------------------------------------------------------
//initialize array
$axexlabel=array();
$axex=array();
$nbvisitsgraph=array();
$nbpagesgraph=array();
$nbcrawlergraph=array();
$datatransfert=array();

//number of days (or months) for the period
$nbday2=0;
if($period == 0 || $period >= 1000)
    {
    $nbday=24;
    }
elseif($period==1 || ($period>=300 && $period<400))
    {
    $nbday=7;
    }     
elseif($period==2 || ($period>=100 && $period<200))
    {    
    $nbday=date("t",mktime(0,0,0,$monthrequest,$dayrequest,$yearrequest));
    }
elseif($period==3 || ($period >= 200 && $period<300))
    {
    $nbday=12;
    }    
elseif($period==4)
    {
    $nbday=8;
    }


//prepare X axis label

if($period == 0 || $period >= 1000)
{
    $axex = array(
    "0" => "0",
    "1" => "1",
    "2" => "2",
    "3" => "3",
    "4" => "4",
    "5" => "5",
    "6" => "6",
    "7" => "7",
    "8" => "8",
    "9" => "9",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19",
    "20" => "20",
    "21" => "21",
    "22" => "22",
    "23" => "23"
    );
	
    $nbvisitsgraph = array(
    "0" => "0",
    "1" => "0",
    "2" => "0",
    "3" => "0",
    "4" => "0",
    "5" => "0",
    "6" => "0",
    "7" => "0",
    "8" => "0",
    "9" => "0",
    "10" => "0",
    "11" => "0",
    "12" => "0",
    "13" => "0",
    "14" => "0",
    "15" => "0",
    "16" => "0",
    "17" => "0",
    "18" => "0",
    "19" => "0",
    "20" => "0",
    "21" => "0",
    "22" => "0",
    "23" => "0"
    );
	
    $nbpagesgraph = array(
    "0" => "0",
    "1" => "0",
    "2" => "0",
    "3" => "0",
    "4" => "0",
    "5" => "0",
    "6" => "0",
    "7" => "0",
    "8" => "0",
    "9" => "0",
    "10" => "0",
    "11" => "0",
    "12" => "0",
    "13" => "0",
    "14" => "0",
    "15" => "0",
    "16" => "0",
    "17" => "0",
    "18" => "0",
    "19" => "0",
    "20" => "0",
    "21" => "0",
    "22" => "0",
    "23" => "0"
    );
	
    $nbcrawlergraph = array(
    "0" => "0",
    "1" => "0",
    "2" => "0",
    "3" => "0",
    "4" => "0",
    "5" => "0",
    "6" => "0",
    "7" => "0",
    "8" => "0",
    "9" => "0",
    "10" => "0",
    "11" => "0",
    "12" => "0",
    "13" => "0",
    "14" => "0",
    "15" => "0",
    "16" => "0",
    "17" => "0",
    "18" => "0",
    "19" => "0",
    "20" => "0",
    "21" => "0",
    "22" => "0",
    "23" => "0"
    );
	
    $datatransfert = array(
    "0" => "0-0-0",
    "1" => "0-0-0",
    "2" => "0-0-0",
    "3" => "0-0-0",
    "4" => "0-0-0",
    "5" => "0-0-0",
    "6" => "0-0-0",
    "7" => "0-0-0",
    "8" => "0-0-0",
    "9" => "0-0-0",
    "10" => "0-0-0",
    "11" => "0-0-0",
    "12" => "0-0-0",
    "13" => "0-0-0",
    "14" => "0-0-0",
    "15" => "0-0-0",
    "16" => "0-0-0",
    "17" => "0-0-0",
    "18" => "0-0-0",
    "19" => "0-0-0",
    "20" => "0-0-0",
    "21" => "0-0-0",
    "22" => "0-0-0",
    "23" => "0-0-0"
    );
}
elseif($period==3 || ($period >= 200 && $period<300))
{
	$date=$datebeginlocalcut[0];
    $date20 = explode('-', $date);
    $yeardate = $date20[0];
	$actualmonth=date("m");
	$actualyear=date("Y");
	
    $axex = array(
    "0" => "01/".$yeardate,
    "1" => "02/".$yeardate,
    "2" => "03/".$yeardate,
    "3" => "04/".$yeardate,
    "4" => "05/".$yeardate,
    "5" => "06/".$yeardate,
    "6" => "07/".$yeardate,
    "7" => "08/".$yeardate,
    "8" => "09/".$yeardate,
    "9" => "10/".$yeardate,
    "10" => "11/".$yeardate,
    "11" => "12/".$yeardate
    );
	
    $nbvisitsgraph = array(
    "01/".$yeardate => "0",
    "02/".$yeardate => "0",
    "03/".$yeardate => "0",
    "04/".$yeardate => "0",
    "05/".$yeardate => "0",
    "06/".$yeardate => "0",
    "07/".$yeardate => "0",
    "08/".$yeardate => "0",
    "09/".$yeardate => "0",
    "10/".$yeardate => "0",
    "11/".$yeardate => "0",
    "12/".$yeardate => "0"
    );
	


    $nbpagesgraph = array(
    "01/".$yeardate => "0",
    "02/".$yeardate => "0",
    "03/".$yeardate => "0",
    "04/".$yeardate => "0",
    "05/".$yeardate => "0",
    "06/".$yeardate => "0",
    "07/".$yeardate => "0",
    "08/".$yeardate => "0",
    "09/".$yeardate => "0",
    "10/".$yeardate => "0",
    "11/".$yeardate => "0",
    "12/".$yeardate => "0"
    );
	
    $nbcrawlergraph = array(
    "01/".$yeardate => "0",
    "02/".$yeardate => "0",
    "03/".$yeardate => "0",
    "04/".$yeardate => "0",
    "05/".$yeardate => "0",
    "06/".$yeardate => "0",
    "07/".$yeardate => "0",
    "08/".$yeardate => "0",
    "09/".$yeardate => "0",
    "10/".$yeardate => "0",
    "11/".$yeardate => "0",
    "12/".$yeardate => "0"
    );

    $datatransfert = array(
    "01/".$yeardate => "0-0-0",
    "02/".$yeardate => "0-0-0",
    "03/".$yeardate => "0-0-0",
    "04/".$yeardate => "0-0-0",
    "05/".$yeardate => "0-0-0",
    "06/".$yeardate => "0-0-0",
    "07/".$yeardate => "0-0-0",
    "08/".$yeardate => "0-0-0",
    "09/".$yeardate => "0-0-0",
    "10/".$yeardate => "0-0-0",
    "11/".$yeardate => "0-0-0",
    "12/".$yeardate => "0-0-0"
    );

    $totperiod = array(
    "01/".$yeardate => linkmapgraph('1',$actualmonth,$yeardate,$actualyear),
    "02/".$yeardate => linkmapgraph('2',$actualmonth,$yeardate,$actualyear),
    "03/".$yeardate => linkmapgraph('3',$actualmonth,$yeardate,$actualyear),
    "04/".$yeardate => linkmapgraph('4',$actualmonth,$yeardate,$actualyear),
    "05/".$yeardate => linkmapgraph('5',$actualmonth,$yeardate,$actualyear),
    "06/".$yeardate => linkmapgraph('6',$actualmonth,$yeardate,$actualyear),
    "07/".$yeardate => linkmapgraph('7',$actualmonth,$yeardate,$actualyear),
    "08/".$yeardate => linkmapgraph('8',$actualmonth,$yeardate,$actualyear),
    "09/".$yeardate => linkmapgraph('9',$actualmonth,$yeardate,$actualyear),
    "10/".$yeardate => linkmapgraph('10',$actualmonth,$yeardate,$actualyear),
    "11/".$yeardate => linkmapgraph('11',$actualmonth,$yeardate,$actualyear),
    "12/".$yeardate => linkmapgraph('12',$actualmonth,$yeardate,$actualyear),
    );
}
else
{
	$date=$datebeginlocalcut[0];
	$nbday2=0;
			
	do 	{
		
		$date2=$date;
		$date20 = explode('-', $date);
		$yeardate = $date20[0];
		$monthdate = $date20[1];
		$daydate = $date20[2];
		$yeardate2 = substr($yeardate, 2, 2);
		
		if ($nbday == 7) {
			if ($firstdayweek == 'Monday') {
				$day = "day" . $nbday2;
			} else {
				//case first week day is sunday
				$nbday3 = $nbday2 + 6;
				if ($nbday3 > 6) {
					$nbday3 = $nbday3 - 7;
				}
				$day = "day" . $nbday3;
			}
			$axexlabel[$daydate . "/" . $monthdate . "/" . $yeardate] = $language[$day] . " " . $daydate;
			$axex[] = $language[$day] . " " . $daydate;
			$nbvisitsgraph[$language[$day] . " " . $daydate] = 0;
			$nbpagesgraph[$language[$day] . " " . $daydate] = 0;
			$nbcrawlergraph[$language[$day] . " " . $daydate] = 0;
			$datatransfert[$language[$day] . " " . $daydate] = '0-0-0';
			if (nbdayfromtoday($date) == 0) {
				$totperiod[$language[$day] . " " . $daydate] = 0;
			} else {
				$totperiod[$language[$day] . " " . $daydate] = 999 + nbdayfromtoday($date);
			}
		} else {
			$axex[] = $daydate . "/" . $monthdate . "/" . $yeardate;
			$nbvisitsgraph[$daydate . "/" . $monthdate . "/" . $yeardate] = 0;
			$nbpagesgraph[$daydate . "/" . $monthdate . "/" . $yeardate] = 0;
			$nbcrawlergraph[$daydate . "/" . $monthdate . "/" . $yeardate] = 0;
			$datatransfert[$daydate . "/" . $monthdate . "/" . $yeardate] = '0-0-0';
			if (nbdayfromtoday($date) == 0) {
				$totperiod[$daydate . "/" . $monthdate . "/" . $yeardate] = 0;
			} else {
				$totperiod[$daydate . "/" . $monthdate . "/" . $yeardate] = 999 + nbdayfromtoday($date);
			}
		}
		$ts = mktime(0, 0, 0, $monthdate, $daydate, $yeardate) + 86400;
		$date = date("Y-m-d", $ts);
		
		//case change summer time to winter time
		if ($date == $date2) {
			$date = date("Y-m-d", ($ts + 7200));
		}
		$nbday2++;
	} while ($nbday2 < $nbday);
}

//mysql query
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
if ($navig == 2) //case one specific crawler
{
	$testcrawler = $crawlertolookfor;
	$tables = "crawlt_crawler";
	$union = "AND  crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler";
	$crawlercount = "COUNT(DISTINCT crawler_name)";
} elseif ($navig == 4) //case one specific page
{
	$testcrawler = "AND crawlt_pages.url_page='" . sql_quote($crawlerd) . "'";
	$tables = "crawlt_pages, crawlt_crawler";
	$union = "AND  crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
		AND crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page";
	$crawlercount = "COUNT(DISTINCT crawler_name)";
} elseif ($navig == 17) //case hacking tentatives
{
	$tables = "crawlt_pages";
	$crawlercount = "COUNT(DISTINCT crawlt_ip_used)";
} elseif ($navig == 18 || $navig == 19) //case hacking tentatives
{
	$crawlercount = "COUNT(DISTINCT crawlt_ip_used)";
} else
//case all crawler or all pages
{
	$testcrawler = "";
	$tables = "crawlt_crawler";
	$union = "AND  crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler";
	$crawlercount = "COUNT(DISTINCT crawler_name)";
}
if ($period == 0 || $period >= 1000) {
	$datequery = "HOUR(date)";
} elseif ($period == 3 || ($period >= 200 && $period < 300)) {
	$datequery = "FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
} else {
	$datequery = "FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d/%m/%Y')";
}
if ($navig == 17) {
	$sqlstats = "SELECT   $datequery, COUNT(DISTINCT crawlt_pages_id_page), COUNT(DISTINCT id_visit),$crawlercount FROM crawlt_visits
  WHERE  ( crawlt_crawler_id_crawler='65501'  
  AND $datetolookfor       
  AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "')
  OR ( crawlt_crawler_id_crawler='65500'  
  AND $datetolookfor 
  AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "')
  GROUP BY $datequery";
} elseif ($navig == 18) {
	$sqlstats = "SELECT   $datequery, COUNT(DISTINCT crawlt_pages_id_page), COUNT(id_visit),$crawlercount FROM crawlt_visits
  WHERE   $datetolookfor       
  AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'
  AND crawlt_crawler_id_crawler='65500'
  GROUP BY $datequery";
} elseif ($navig == 19) {
	$sqlstats = "SELECT   $datequery, COUNT(DISTINCT crawlt_pages_id_page), COUNT(id_visit),$crawlercount FROM crawlt_visits
  WHERE   $datetolookfor       
  AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'
  AND crawlt_crawler_id_crawler='65501'
  GROUP BY $datequery";
} elseif ($navig == 21) {
	//query to count the number of page viewed by human visitors
	if ($nottoomuchip == 1) {
		$sqlstats = "SELECT  $datequery, COUNT(crawlt_id_page), COUNT(id_visit),COUNT(id_visit) FROM crawlt_visits_human
    WHERE  $datetolookfor
    AND crawlt_site_id_site='" . sql_quote($site) . "' 
    AND crawlt_ip IN ('$crawltlistip')        
    GROUP BY $datequery";
	} else {
		$sqlstats = "SELECT  $datequery, COUNT(crawlt_id_page), COUNT(id_visit),COUNT(id_visit) FROM crawlt_visits_human
    WHERE  $datetolookfor
    AND crawlt_site_id_site='" . sql_quote($site) . "'        
    GROUP BY $datequery";
	}
} else {
	//query to count the number of page viewed, visits and crawler
	$sqlstats = "SELECT  $datequery, COUNT(DISTINCT crawlt_pages_id_page), COUNT(id_visit),$crawlercount FROM crawlt_visits, $tables
		WHERE  $datetolookfor
		$union 
		AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "' 
		$testcrawler          
		GROUP BY $datequery";
}
$requetestats = db_query($sqlstats, $connexion);

if ($period == 0 || $period >= 1000) {
	while ($ligne = mysql_fetch_row($requetestats)) {
		$hour = $ligne[0] - $times;
		if ($hour < 0) {
			$hour = 24 + $hour;
		}
		if ($hour >= 24) {
			$hour = $hour - 24;
		}
		
		$nbpagesgraph[$hour] = $ligne[1];
		$nbvisitsgraph[$hour] = $ligne[2];
		$nbcrawlergraph[$hour] = $ligne[3];
		$datatransfert[$hour] = $ligne[1] . "-" . $ligne[2] . "-" . $ligne[3];
	}
} elseif ($period == 3 || ($period >= 200 && $period < 300)) {
	while ($ligne = mysql_fetch_row($requetestats)) {
		$year = substr($ligne[0], 0, 4);
		$month = substr($ligne[0], 4, 2);
		$yearmonth = $month . "/" . $year;
		$nbpagesgraph[$yearmonth] = $ligne[1];
		$nbvisitsgraph[$yearmonth] = $ligne[2];
		$nbcrawlergraph[$yearmonth] = $ligne[3];
		$datatransfert[$yearmonth] = $ligne[1] . "-" . $ligne[2] . "-" . $ligne[3];
	}
} elseif ($period == 1 || ($period >= 300 && $period < 400)) //case 1 week back and forward
{
	while ($ligne = mysql_fetch_row($requetestats)) {
		$nbpagesgraph[$axexlabel[$ligne[0]]] = $ligne[1];
		$nbvisitsgraph[$axexlabel[$ligne[0]]] = $ligne[2];
		$nbcrawlergraph[$axexlabel[$ligne[0]]] = $ligne[3];
		$datatransfert[$axexlabel[$ligne[0]]] = $ligne[1] . "-" . $ligne[2] . "-" . $ligne[3];
	}
} else {
	while ($ligne = mysql_fetch_row($requetestats)) {
		$nbpagesgraph[$ligne[0]] = $ligne[1];
		$nbvisitsgraph[$ligne[0]] = $ligne[2];
		$nbcrawlergraph[$ligne[0]] = $ligne[3];
		$datatransfert[$ligne[0]] = $ligne[1] . "-" . $ligne[2] . "-" . $ligne[3];
	}
}
mysql_free_result($requetestats);
//map graph
if ($period == 0 || $period >= 1000) {
	$widthzone = 22;
	$x2 = 88;
	$y = 30;
	$y2 = 280;
} elseif ($period == 1 || ($period >= 300 && $period < 400)) {
	$widthzone = 75.4;
	$x2 = 141.4;
	$y = 30;
	$y2 = 280;
} elseif ($period == 2 || ($period >= 100 && $period < 200)) {
	if ($nbday == 28) {
		$widthzone = 18.9;
		$x2 = 84.9;
		$y = 30;
		$y2 = 240;
	} elseif ($nbday == 29) {
		$widthzone = 18.2;
		$x2 = 84.2;
		$y = 30;
		$y2 = 240;
	} elseif ($nbday == 30) {
		$widthzone = 17.6;
		$x2 = 83;
		$y = 30;
		$y2 = 240;
	} else {
		$widthzone = 17;
		$x2 = 83;
		$y = 30;
		$y2 = 240;
	}
} elseif ($period == 3 || ($period >= 200 && $period < 300)) {
	$widthzone = 44;
	$x2 = 110;
	$y = 30;
	$y2 = 280;
} elseif ($period == 4) {
	$widthzone = 66;
	$x2 = 132;
	$y = 30;
	$y2 = 280;
}
echo "<map id=\"visit\" name=\"visit\">\n";
$iday = 0;
$x = 66;
do {
	echo "<area shape=\"rect\" coords=\"" . $x . "," . $y . "," . $x2 . "," . $y2 . "\" onmouseover=\"javascript:montre('smenu" . ($iday + 9) . "');\" onmouseout=\"javascript:montre();\" alt=\"go\"";
	if ($period != 0 && $period < 1000) {
		$dateday = $axex[$iday];
		$periodtogo = $totperiod[$dateday];
		echo "href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">\n";
	} else {
		echo ">\n";
	}
	$x = $x + $widthzone;
	$x2 = $x2 + $widthzone;
	$iday++;
} while ($iday < $nbday);
echo "</map>\n";
$iday = 0;

do {
	echo "<div id=\"smenu" . ($iday + 9) . "\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";
	if ($period == 0 || $period >= 1000) {
		echo "&nbsp;" . $axex[$iday] . "h-->" . ($axex[$iday] + 1) . "h&nbsp;\n";
	} else {
		echo "&nbsp;" . $axex[$iday] . "&nbsp;\n";
	}
	if ($navig != 17 && $navig != 18 && $navig != 19 && $navig != 21) {
		echo "&nbsp;" . $nbvisitsgraph[$axex[$iday]] . "&nbsp;" . $language['nbr_visits'] . "&nbsp;\n";
	}
	if ($navig != 4 && $navig != 17 && $navig != 18 && $navig != 19) {
		echo "&nbsp;" . $nbpagesgraph[$axex[$iday]] . "&nbsp;" . $language['nbr_pages'] . "&nbsp;\n";
	}
	if ($navig != 2 && $navig != 17 && $navig != 18 && $navig != 19 && $navig != 21) {
		echo "&nbsp;" . $nbcrawlergraph[$axex[$iday]] . "&nbsp;" . $language['crawler_name'] . "&nbsp;\n";
	}
	if ($navig == 17 || $navig == 18 || $navig == 19) {
		echo "&nbsp;" . $nbvisitsgraph[$axex[$iday]] . "&nbsp;" . $language['hacking'] . "&nbsp;\n";
		echo "&nbsp;" . $nbcrawlergraph[$axex[$iday]] . "&nbsp;" . $language['crawler_ip_used'] . "&nbsp;\n";
	}
	echo "</div>\n";
	$iday++;
} while ($iday < $nbday);
//prepare data to be transferred to graph file
$datatransferttograph = addslashes(urlencode(serialize($datatransfert)));
//insert the values in the graph table
$graphname = "visits-" . $cachename;

//check if this graph already exists in the table
$sql = "SELECT name  FROM crawlt_graph
            WHERE name= '" . sql_quote($graphname) . "'";
$requete = db_query($sql, $connexion);
$nbrresult = mysql_num_rows($requete);

if ($nbrresult >= 1) {
	$sql2 = "UPDATE crawlt_graph SET graph_values='" . sql_quote($datatransferttograph) . "'
              WHERE name= '" . sql_quote($graphname) . "'";
} else {
	$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . sql_quote($graphname) . "','" . sql_quote($datatransferttograph) . "')";
}
$requete2 = db_query($sql2, $connexion);

//mysql connexion close
mysql_close($connexion);
?>
