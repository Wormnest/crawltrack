<?php
//----------------------------------------------------------------------
//  CrawlTrack
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Updating: Jacob Boerema
//----------------------------------------------------------------------
// This script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: origine-graph.php
//----------------------------------------------------------------------
// this graph is made with artichow    website: www.artichow.org
//----------------------------------------------------------------------

// Set debugging to non zero to turn it on.
// DON'T FORGET TO TURN IT OFF AFTER YOU FINISH DEBUGGING OR WHEN COMMITTING CHANGES!
$DEBUG = 0;

if ($DEBUG == 0) {
	// Normal: don't show any errors, warnings, notices.
	error_reporting(0);
} else {
	// DURING DEBUGGING ONLY
	error_reporting(E_ALL);
}

//get graph infos
$graphname = $_GET['graphname'];

//database connection
include ("../include/configconnect.php");
require_once("../include/jgbdb.php");
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

//get the functions files
$times = 0; //give value just to avoid error in functions.php
$firstdayweek = 'Monday'; //give value just to avoid error in functions.php
$period = 0; //give value just to avoid error in functions.php
include ("../include/functions.php");

//get graph values
if (file_exists("../cachecloseperiod/$graphname.gz")) {
	$fgz = @fopen("../cachecloseperiod/$graphname.gz", "r");
	$data = fread($fgz, filesize("../cachecloseperiod/$graphname.gz"));
	fclose($fgz);
	$data = gzuncompress($data);
	$datatransfert = unserialize(urldecode(stripslashes($data)));
} else {
	$sql = "SELECT   graph_values FROM crawlt_graph
		WHERE  name='" . crawlt_sql_quote($connexion, $graphname) . "'";
	$requete = db_mysql_query($sql, $connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		$ligne = $requete->fetch_assoc();
		$data = $ligne['graph_values'];
	} else {
		exit('<h1>No Graph values available !!!!</h1>');
	}
	$datatransfert = unserialize(urldecode(stripslashes($data)));
}
mysqli_close($connexion);
$totvalues = array_sum($datatransfert);

//get the listlang files
include ("../include/listlang.php");
$crawltlang = $_GET['crawltlang'];
if (($crawltlang == 'russian' && !file_exists('./artichow/font/simsun.ttf')) || $crawltlang == 'bulgarian' || $crawltlang == 'turkish') {
	$crawltlang = 'english';
}

//language file include
if (file_exists("../language/" . $crawltlang . ".php") && in_array($crawltlang, $listlangcrawlt)) {
	include ("../language/" . $crawltlang . ".php");
} else {
	exit('<h1>No language files available !!!!</h1>');
}
foreach ($datatransfert as $key => $value) {
	if (($value / $totvalues) > 0.009) //to avoid Artichow bug with php 5.2
	{
		if (isset($country[$key])) {
			$legend[] = $country[$key];
		} elseif (isset($language[$key])) {
			$legend[] = $language[$key];
		} else {
			$legend[] = $key;
		}
		$values[] = $value;
	}
}
$graphnameexplode = explode('-', $graphname);
$graphtitle = $graphnameexplode[0];
if ($graphnameexplode[1] == 'permanent') {
	$fp = fopen("../cachecloseperiod/$graphname.gz", 'w');
	fwrite($fp, gzcompress($data));
	fclose($fp);
}

//build the graph
//test to see if ttf font is available
$fontttf = gd_info();
if (@$fontttf['FreeType Linkage'] == 'with freetype') {
	$ttf = 'ok';
} else {
	$ttf = 'no-ok';
}

require_once ("artichow/Pie.class.php");
require_once ("artichow/Graph.class.php");

$graph = new awGraph(450, 200);
if (function_exists('imageantialias')) {
	$graph->setAntiAliasing(TRUE);
}
$graph->border->hide(TRUE);
$graph->shadow->setSize(5);
$graph->shadow->smooth(TRUE);
//$graph->shadow->setPosition(awShadow::LEFT_BOTTOM); -- we don't want a border for pies
$graph->shadow->hide(TRUE);
$graph->shadow->setColor(new awDarkBlue);
$plot = new awPie($values);
$plot->setCenter(0.4, 0.5);
$plot->setSize(0.6, 0.8);
$plot->set3D(15);
$plot->setLabelPosition(10);
$plot->label->setColor(new awDarkBlue);

if ($ttf == 'ok') {
	$plot->label->setFont(new awTuffy(10));
} else {
	$plot->label->setFont(new awFont(2));
}
$plot->setBorderColor(new awDarkBlue);
$plot->setLegend($legend);
$plot->legend->setPosition(1.5);
$plot->legend->shadow->setSize(0);
$plot->legend->setBackgroundColor(new awWhite);
$plot->legend->border->hide(TRUE);
$plot->legend->setTextColor(new awDarkBlue);

if ($ttf == 'ok') {
	if ($crawltlang == 'russian') {
		$plot->legend->setTextFont(new awsimsun(10));
	} else {
		$plot->legend->setTextFont(new awTuffy(10));
	}
} else {
	$plot->legend->setTextFont(new awFont(2));
}

$graph->add($plot);
$graph->draw();
?>
