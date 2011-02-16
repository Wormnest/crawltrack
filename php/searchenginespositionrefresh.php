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
// file: searchenginespositionrefresh.php
//----------------------------------------------------------------------
//  Last update: 16/02/2011
//----------------------------------------------------------------------
error_reporting(0);
//to avoid notice error
$times = 0;
//nusoap
require_once (dirname(__FILE__).'/../nusoap/class.nusoap_base.php');
require_once (dirname(__FILE__).'/../nusoap/class.soap_transport_http.php');
require_once (dirname(__FILE__).'/../nusoap/class.soap_val.php');
require_once (dirname(__FILE__).'/../nusoap/class.soap_parser.php');
require_once (dirname(__FILE__).'/../nusoap/class.soap_fault.php');
require_once (dirname(__FILE__).'/../nusoap/class.soapclient.php');

//sortyahooXMLTree
function sortyahooXMLTree($data) {
	// create parser
	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $data, $values, $tags);
	xml_parser_free($parser);
	$result = $values[0]['attributes']['totalResultsAvailable'];
	return $result;
}
function &composeArray($array, $elements, $value = array()) {
	// get current element
	$element = array_shift($elements);
	// does the current element refer to a list
	if ($element == "Result") {
		// more elements?
		if (sizeof($elements) > 0) {
			$array[$element][sizeof($array[$element]) - 1] = & composeArray($array[$element][sizeof($array[$element]) - 1], $elements, $value);
		} else
		// if (is_array($value))
		{
			$array[$element][sizeof($array[$element]) ] = $value;
		}
	} else {
		// more elements?
		if (sizeof($elements) > 0) {
			$array[$element] = & composeArray($array[$element], $elements, $value);
		} else {
			$array[$element] = $value;
		}
	}
	return $array;
}
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//get url data
if (isset($_GET['navig'])) {
	$navig = (int)$_GET['navig'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['period'])) {
	$period = (int)$_GET['period'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['site'])) {
	$site = (int)$_GET['site'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['crawler'])) {
	$crawler = $_GET['crawler'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['graphpos'])) {
	$graphpos = $_GET['graphpos'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['retry'])) {
	$retry = $_GET['retry'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
include ("../include/functions.php");
//database connection
include ("../include/configconnect.php");
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//mysql query for timeshift
$sqlcrawltconfig = "SELECT timeshift FROM crawlt_config";
$requetecrawltconfig = mysql_query($sqlcrawltconfig, $connexion);
$nbrresultcrawlt = mysql_num_rows($requetecrawltconfig);
if ($nbrresultcrawlt >= 1) {
	$lignecrawlt = mysql_fetch_row($requetecrawltconfig);
	$crawlttime = $lignecrawlt[0];
}

//take in account timeshift
$crawltts = time() - ($crawlttime * 3600);
$crawltdatetoday2 = date("Y-m-d", $crawltts);

//mysql query for site id and url
$crawltsql = "SELECT id_site, url  FROM crawlt_site";
$crawltrequete = mysql_query($crawltsql, $connexion);
$crawltnbrresult = mysql_num_rows($crawltrequete);
$crawltnbrresult2 = ($crawltnbrresult * 2);
$crawltnbrresult3 = ($crawltnbrresult * 3);

//initialize array
$listsitecrawlt = array();
$crawltsiteurl = array();
if ($crawltnbrresult >= 1) {
	while ($crawltligne = mysql_fetch_row($crawltrequete)) {
		$listsitecrawlt[] = $crawltligne[0];
		$crawltsiteurl[$crawltligne[0]] = $crawltligne[1];
	}
}

//looking for position in search engines database using api
//test which one to retry
if ($retry == 'yahoo') {
	//yahoo
	foreach ($listsitecrawlt as $crawltidsite) {
		$crawlturlsite = $crawltsiteurl[$crawltidsite];
		//to avoid problem if the url is entered in the database with http://
		$crawlturlsite = strip_protocol($crawlturlsite);
		// yahoo links
		$crawltrequete1 = "http://$crawlturlsite";
		$crawltquery1 = "http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=crawltrack&omit_inlinks=domain&query=" . rawurlencode($crawltrequete1);
		// Try to temporarly allow url_fopen
		if (ini_get('allow_url_fopen') != 1) {
			@ini_set('allow_url_fopen', '1');
		}
		$crawltnbryahoo1 = 0;
		if (ini_get('allow_url_fopen') == 1) {
				$crawltxml1 = file_get_contents($crawltquery1);
			$crawltnbryahoo1 = sortyahooXMLTree($crawltxml1);
			if (empty($crawltnbryahoo1)) {
				$crawltnbryahoo1 = 0;
			}
		}

		
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, linkyahoo  FROM crawlt_seo_position
			WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
			AND id_site='" . sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$crawltnbrresultcheck = mysql_num_rows($crawltrequetecheck);
		if ($crawltnbrresultcheck >= 1) {
				$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
				$crawltexitingvalue = $crawltligne['linkyahoo'];
				if($crawltexitingvalue == 0) {
			$crawltsqlseo = "UPDATE crawlt_seo_position SET linkyahoo='" . sql_quote($crawltnbryahoo1) . "'
				WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
				AND id_site='" . sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);	
			}			
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`,id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . sql_quote($crawltdatetoday2) . "','" . sql_quote($crawltidsite) . "','" . sql_quote($crawltnbryahoo1) . "','0','0','0','0',' ','0','0','0','0')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
		}
		//yahoo pages
		$crawltrequete2 = "http://$crawlturlsite";
		$crawltquery2 = "http://search.yahooapis.com/SiteExplorerService/V1/pageData?appid=crawltrack&query=" . rawurlencode($crawltrequete2);
		// Try to temporarly allow url_fopen
		if (ini_get('allow_url_fopen') != 1) {
			@ini_set('allow_url_fopen', '1');
		}
		$crawltnbryahoo2 = 0;
		if (ini_get('allow_url_fopen') == 1) {
			$crawltxml2 = file_get_contents($crawltquery2);
			$crawltnbryahoo2 = sortyahooXMLTree($crawltxml2);
			if (empty($crawltnbryahoo2)) {
				$crawltnbryahoo2 = 0;
			}
		}
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, pageyahoo  FROM crawlt_seo_position
                        WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
                        AND id_site='" . sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$crawltnbrresultcheck = mysql_num_rows($crawltrequetecheck);
		if ($crawltnbrresultcheck >= 1) {
				$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
				$crawltexitingvalue = $crawltligne['pageyahoo'];
				if($crawltexitingvalue == 0) {			
			$crawltsqlseo = "UPDATE crawlt_seo_position SET pageyahoo='" . sql_quote($crawltnbryahoo2) . "'
				WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
				AND id_site='" . sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);		
			}		
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`,id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . sql_quote($crawltdatetoday2) . "','" . sql_quote($crawltidsite) . "','0','" . sql_quote($crawltnbryahoo2) . "','0','0','0',' ','0','0','0','0')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
		}
	}
} elseif ($retry == 'msn') {
	//msn
	foreach ($listsitecrawlt as $crawltidsite) {
		$crawlturlsite = $crawltsiteurl[$crawltidsite];
		
		//to avoid problem if the url is entered in the database with http://
		$crawlturlsite = strip_protocol($crawlturlsite);
		
		//msn links
		$crawltrequete1 = "linkdomain:$crawlturlsite";
		$soapclient = new nusoap_client("http://soap.search.msn.com/webservices.asmx");
		$crawltparam1 = array('AppID' => '5E4A1FC1F7B268DD7BCE62F39BFF8A0D81CB900B', 'Query' => $crawltrequete1, 'CultureInfo' => 'en-US', 'SafeSearch' => 'Off', 'Requests' => array('SourceRequest' => array('Source' => 'Web', 'Offset' => 0, 'Count' => 50, 'ResultFields' => 'All'),),);
		$crawltsearchresults1 = $soapclient->call("Search", array("Request" => $crawltparam1));
		if (!empty($crawltsearchresults1)) {
			$crawltnbrmsn1 = $crawltsearchresults1['Responses']['SourceResponse']['Total'];
		} else {
			$crawltnbrmsn1 = 0;
		}
		if (empty($crawltnbrmsn1)) {
			$crawltnbrmsn1 = 0;
		}
		
		//insert values in the crawlt_seo_position table
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, linkmsn  FROM crawlt_seo_position
			WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
			AND id_site='" . sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$crawltnbrresultcheck = mysql_num_rows($crawltrequetecheck);
		if ($crawltnbrresultcheck >= 1) {
			$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
			$crawltexitingvalue = $crawltligne['linkmsn'];
			if($crawltexitingvalue == 0) {			
			$crawltsqlseo = "UPDATE crawlt_seo_position SET linkmsn='" . sql_quote($crawltnbrmsn1) . "'
				WHERE date= '" . sql_quote($crawltdatetoday2) . "'
				AND id_site='" . sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion) or die("MySQL query error");
			}
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . sql_quote($crawltdatetoday2) . "','" . sql_quote($crawltidsite) . "','0','0','" . sql_quote($crawltnbrmsn1) . "','0','0',' ','0','0','0','0')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
		}
		
		//msn pages
		$crawltrequete2 = "site:$crawlturlsite";
		$soapclient = new nusoap_client("http://soap.search.msn.com/webservices.asmx");
		$crawltparam2 = array('AppID' => '5E4A1FC1F7B268DD7BCE62F39BFF8A0D81CB900B', 'Query' => $crawltrequete2, 'CultureInfo' => 'en-US', 'SafeSearch' => 'Off', 'Requests' => array('SourceRequest' => array('Source' => 'Web', 'Offset' => 0, 'Count' => 50, 'ResultFields' => 'All'),),);
		$crawltsearchresults2 = $soapclient->call("Search", array("Request" => $crawltparam2));
		if (!empty($crawltsearchresults2)) {
			$crawltnbrmsn2 = $crawltsearchresults2['Responses']['SourceResponse']['Total'];
		} else {
			$crawltnbrmsn2 = 0;
		}
		if (empty($crawltnbrmsn2)) {
			$crawltnbrmsn2 = 0;
		}
		
		//insert values in the crawlt_seo_position table
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, pagemsn  FROM crawlt_seo_position
			WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
			AND id_site='" . sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$crawltnbrresultcheck = mysql_num_rows($crawltrequetecheck);
		
		if ($crawltnbrresultcheck >= 1) {
				$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
				$crawltexitingvalue = $crawltligne['pagemsn'];
				if($crawltexitingvalue == 0) {
			$crawltsqlseo = "UPDATE crawlt_seo_position SET pagemsn='" . sql_quote($crawltnbrmsn2) . "'
				WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
				AND id_site='" . sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);	
			}			
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . sql_quote($crawltdatetoday2) . "','" . sql_quote($crawltidsite) . "','0','0','0','" . sql_quote($crawltnbrmsn2) . "','0',' ','0','0','0','0')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
		}
	}
} elseif ($retry == 'exalead') {
	//exalead
	foreach ($listsitecrawlt as $crawltidsite) {
		$crawlturlsite = $crawltsiteurl[$crawltidsite];
		//to avoid problem if the url is entered in the database with http://
		$crawlturlsite = strip_protocol($crawlturlsite);
		
		//exalead links
		$crawltrequete1 = "http://www.exalead.com/search/web/results?q=link:$crawlturlsite";
		// Try to temporarly allow url_fopen
		if (ini_get('allow_url_fopen') != 1) {
			@ini_set('allow_url_fopen', '1');
		}
		$crawltnbrexalead1 = 0;
		if (ini_get('allow_url_fopen') == 1) {
			$result = file_get_contents($crawltrequete1);
			if(preg_match('#<span class="orange">([^<]+)</span>#iUs', $result, $matches))
				$crawltnbrexalead1 = str_replace(",", "", $matches[1]);
		}

		//insert values in the crawlt_seo_position table
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, linkexalead  FROM crawlt_seo_position
			WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
			AND id_site='" . sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$crawltnbrresultcheck = mysql_num_rows($crawltrequetecheck);
		if ($crawltnbrresultcheck >= 1) {
				$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
				$crawltexitingvalue = $crawltligne['linkexalead'];
				if($crawltexitingvalue == 0) {
			$crawltsqlseo = "UPDATE crawlt_seo_position SET linkexalead='" . sql_quote($crawltnbrexalead1) . "'
				WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
				AND id_site='" . sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
			}
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . sql_quote($crawltdatetoday2) . "','" . sql_quote($crawltidsite) . "','0','0','0','0','0',' '," . sql_quote($crawltnbrexalead1) . ",'0','0','0')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
		}
		//exalead pages
		$crawltrequete2 = "http://www.exalead.com/search/web/results?q=site:$crawlturlsite";
		// Try to temporarly allow url_fopen
		if (ini_get('allow_url_fopen') != 1) {
			@ini_set('allow_url_fopen', '1');
		}
		$crawltnbrexalead2 = 0;
		if (ini_get('allow_url_fopen') == 1) {
			$result = file_get_contents($crawltrequete2);
			if(preg_match('#<span class="orange">([^<]+)</span>#iUs', $result, $matches))
				$crawltnbrexalead2 = str_replace(",", "", $matches[1]);
		}
		
		//insert values in the crawlt_seo_position table
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, pageexalead  FROM crawlt_seo_position
			WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
			AND id_site='" . sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$crawltnbrresultcheck = mysql_num_rows($crawltrequetecheck);
		if ($crawltnbrresultcheck >= 1) {
				$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
				$crawltexitingvalue = $crawltligne['pageexalead'];
				if($crawltexitingvalue == 0) {
			$crawltsqlseo = "UPDATE crawlt_seo_position SET pageexalead='" . sql_quote($crawltnbrexalead2) . "'
				WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
				AND id_site='" . sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);	
			}			
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . sql_quote($crawltdatetoday2) . "','" . sql_quote($crawltidsite) . "','0','0','0','0','0',' ','0','" . sql_quote($crawltnbrexalead2) . "','0','0')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);		
		}

	}
} elseif ($retry == 'delicious') {
	//del.icio.us
	foreach ($listsitecrawlt as $crawltidsite) {
		$crawlturlsite = $crawltsiteurl[$crawltidsite];
		$crawltresultdelicious = '';
		$crawltnbrpertag = array();
		//to avoid problem if the url is entered in the database with http://
		$crawlturlsite = strip_protocol($crawlturlsite);
		$crawlturlsite2 = "http://" . $crawlturlsite . "/";
		$crawltquery1 = "http://badges.del.icio.us/feeds/json/url/blogbadge?hash=" . md5($crawlturlsite2);
		// Try to temporarly allow url_fopen
		if (ini_get('allow_url_fopen') != 1) {
			@ini_set('allow_url_fopen', '1');
		}
		$crawltnbrtagdelicious = 0;
		$crawlttagtab = " ";
		if (ini_get('allow_url_fopen') == 1) {
			$crawltresultdelicious = file_get_contents($crawltquery1);
			$crawltnbrtag = explode("\"total_posts\":", $crawltresultdelicious);
			if (count($crawltnbrtag) == 1) {
				$crawltnbrtagdelicious = 0;
				$crawlttagtab = " ";
			} else {
				//delicious tags
				$crawltnbrtag2 = explode("}", $crawltnbrtag[1]);
				$crawltnbrtagdelicious = $crawltnbrtag2[0];
				$crawlttag = explode("\"top_tags\":{\"", $crawltresultdelicious);
				$crawlttag2 = explode("}", $crawlttag[1]);
				$crawlttag3 = str_replace(":", "", $crawlttag2[0]);
				$crawlttag3 = str_replace(",", "", $crawlttag3);
				$crawlttag4 = explode("\"", $crawlttag3);
				$n = count($crawlttag4);
				for ($i = 0;$i < $n;$i++) {
					if ($i % 2 == 0) {
						$crawltnbrpertag[$crawlttag4[$i]] = $crawlttag4[$i + 1];
					}
				}
				$crawlttagtab = serialize($crawltnbrpertag);
			}
		}
		
		//insert values in the crawlt_seo_position table
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, nbrdelicious  FROM crawlt_seo_position
			WHERE date= '" . sql_quote($crawltdatetoday2) . "'
			AND id_site='" . sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$nbrresultcheck = mysql_num_rows($crawltrequetecheck);
		if ($nbrresultcheck >= 1) {
				$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
				$crawltexitingvalue = $crawltligne['nbrdelicious'];
				if($crawltexitingvalue == 0) {
			$crawltsqlseo = "UPDATE crawlt_seo_position SET nbrdelicious='" . sql_quote($crawltnbrtagdelicious) . "',tagdelicious='" . sql_quote($crawlttagtab) . "'
				WHERE `date`= '" . sql_quote($crawltdatetoday2) . "'
				AND id_site='" . sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);	
			}			
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious,linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . sql_quote($crawltdatetoday2) . "','" . sql_quote($crawltidsite) . "','0','0','0','0','" . sql_quote($crawltnbrtagdelicious) . "','" . sql_quote($crawlttagtab) . "','0','0','0','0')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
		}

	}
}
if ($retry == 'google') {
	//google
	foreach ($listsitecrawlt as $crawltidsite) {
		$crawlturlsite = $crawltsiteurl[$crawltidsite];
		//to avoid problem if the url is entered in the database with http://
		$crawlturlsite = strip_protocol($crawlturlsite);
		
		//google link
		$crawltrequete1 = "link:$crawlturlsite";
		$crawltquery1 = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=" . rawurlencode($crawltrequete1);
		
		// Try to temporarly allow url_fopen
		if (ini_get('allow_url_fopen') != 1) {
			@ini_set('allow_url_fopen', '1');
		}
		$crawltnbrgoogle1 = 0;
		if (ini_get('allow_url_fopen') == 1) {
		$crawltxml1 = file_get_contents($crawltquery1);
			if(stristr($crawltxml1, 'estimatedResultCount":"') !== false) {
				$crawltsearch = explode('estimatedResultCount":"', $crawltxml1);
				$crawltsearch2 = explode('","currentPageIndex', $crawltsearch[1]);
				$crawltnbrgoogle1 = $crawltsearch2[0];
				if (empty($crawltnbrgoogle1)) {
					$crawltnbrgoogle1 = 0;
				}
			}
		}
		
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, linkgoogle  FROM crawlt_seo_position
			WHERE `date`= '" . crawlt_sql_quote($crawltdatetoday2) . "'
			AND id_site='" . crawlt_sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$crawltnbrresultcheck = mysql_num_rows($crawltrequetecheck);		
		if ($crawltnbrresultcheck >= 1) {
				$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
				$crawltexitingvalue = $crawltligne['linkgoogle'];
				if($crawltexitingvalue == 0) {			
			$crawltsqlseo = "UPDATE crawlt_seo_position SET linkgoogle='" . crawlt_sql_quote($crawltnbrgoogle1) . "'
				WHERE `date`= '" . crawlt_sql_quote($crawltdatetoday2) . "'
				AND id_site='" . crawlt_sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);	
			}			
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . crawlt_sql_quote($crawltdatetoday2) . "','" . crawlt_sql_quote($crawltidsite) . "','0','0','0','0','0',' ','0','0','" . crawlt_sql_quote($crawltnbrgoogle1) . "','0')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
		}

		
		//google pages
		$crawltrequete2 = "site:$crawlturlsite";
		$crawltquery2 = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=" . rawurlencode($crawltrequete2);
		
		// Try to temporarly allow url_fopen
		if (ini_get('allow_url_fopen') != 1) {
			@ini_set('allow_url_fopen', '1');
		}
		
		$crawltnbrgoogle2 = 0;
		if (ini_get('allow_url_fopen') == 1) {
		$crawltxml2 = file_get_contents($crawltquery2);
			if(stristr($crawltxml2, 'estimatedResultCount":"') !== false) {
				$crawltsearch = explode('estimatedResultCount":"', $crawltxml2);
				$crawltsearch2 = explode('","currentPageIndex', $crawltsearch[1]);
				$crawltnbrgoogle2 = $crawltsearch2[0];
				if (empty($crawltnbrgoogle2)) {
					$crawltnbrgoogle2 = 0;
				}
			}
		}
		
		//check if the date exists already in the table
		$crawltsqlcheck = "SELECT `date`, id_site, pagegoogle  FROM crawlt_seo_position
			WHERE `date`= '" . crawlt_sql_quote($crawltdatetoday2) . "'
			AND id_site='" . crawlt_sql_quote($crawltidsite) . "'";
		$crawltrequetecheck = mysql_query($crawltsqlcheck, $connexion);
		$crawltnbrresultcheck = mysql_num_rows($crawltrequetecheck);
		
		if ($crawltnbrresultcheck >= 1) {
				$crawltligne = mysql_fetch_assoc($crawltrequetecheck);
				$crawltexitingvalue = $crawltligne['pagegoogle'];
				if($crawltexitingvalue == 0) {			
			$crawltsqlseo = "UPDATE crawlt_seo_position SET pagegoogle='" . crawlt_sql_quote($crawltnbrgoogle2) . "'
				WHERE `date`= '" . crawlt_sql_quote($crawltdatetoday2) . "'
				AND id_site='" . crawlt_sql_quote($crawltidsite) . "'";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
			}				
		} else {
			$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . crawlt_sql_quote($crawltdatetoday2) . "','" . crawlt_sql_quote($crawltidsite) . "','0','0','0','0','0',' ','0','0','0','" . crawlt_sql_quote($crawltnbrgoogle2) . "')";
			$crawltrequeteseo = mysql_query($crawltsqlseo, $connexion);
		}

	}
}
//clear the cache and call back the indexation page

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = mysql_query($sqlcache, $connexion);

//clear graph table
$sqlgraph = "TRUNCATE TABLE crawlt_graph";
$requetegraph = mysql_query($sqlgraph, $connexion);

//mysql connexion close
mysql_close($connexion);
//clear the cache folder
$dir = dir('../cache/');
while (false !== $entry = $dir->read()) {
	// Skip pointers
	if ($entry == '.' || $entry == '..') {
		continue;
	}
	unlink("../cache/$entry");
}
// Clean up
$dir->close();

//call back the page
$urlrefresh = "../index.php?navig=$navig&period=$period&site=$site&graphpos=$graphpos";
header("Location: $urlrefresh");
exit;
?>
