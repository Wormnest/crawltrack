<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.6
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: origine-graph.php
//----------------------------------------------------------------------
// this graph is made with artichow    website: www.artichow.org
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
error_reporting(0);
//get graph infos
$graphname = $_GET['graphname'];

//database connection
include ("../include/configconnect.php");
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

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
		WHERE  name='" . sql_quote($graphname) . "'";
	$requete = mysql_query($sql, $connexion) or die("MySQL query error");
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		$ligne = mysql_fetch_assoc($requete);
		$data = $ligne['graph_values'];
	} else {
		exit('<h1>No Graph values available !!!!</h1>');
	}
	$datatransfert = unserialize(urldecode(stripslashes($data)));
}
mysql_close($connexion);
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
$graph = new Graph(450, 200);
if (function_exists('imageantialias')) {
	$graph->setAntiAliasing(TRUE);
}
$graph->border->hide(TRUE);
$graph->shadow->setSize(5);
$graph->shadow->smooth(TRUE);
$graph->shadow->setPosition('SHADOW_LEFT_BOTTOM');
$graph->shadow->setColor(new DarkBlue);
$plot = new Pie($values);
$plot->setCenter(0.4, 0.5);
$plot->setSize(0.6, 0.8);
$plot->set3D(15);
$plot->setLabelPosition(10);
$plot->label->setColor(new DarkBlue);
if ($ttf == 'ok') {
	$plot->label->setFont(new Tuffy(10));
} else {
	$plot->label->setFont(new Font(2));
}
$plot->setBorderColor(new DarkBlue);
$plot->setLegend($legend);
$plot->legend->setPosition(1.5);
$plot->legend->shadow->setSize(0);
$plot->legend->setBackgroundColor(new White);
$plot->legend->border->hide(TRUE);
$plot->legend->setTextColor(new DarkBlue);
if ($ttf == 'ok') {
	if ($crawltlang == 'russian') {
		$plot->legend->setTextFont(new simsun(10));
	} else {
		$plot->legend->setTextFont(new Tuffy(10));
	}
} else {
	$plot->legend->setTextFont(new Font(2));
}
$graph->add($plot);
$graph->draw();
?>
