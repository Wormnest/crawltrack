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
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// This script is distributed under GNU GPL license
//----------------------------------------------------------------------
// Updating: Jacob Boerema
//----------------------------------------------------------------------
// file: page-graph.php
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

//initialize array
$listlangcrawlt = array();
//get graph values
$nbrpageview = $_GET['nbrpageview'];
$nbrpagestotal = $_GET['nbrpagestotal'];
$crawltlang = $_GET['crawltlang'];
if (($crawltlang == 'russian' && !file_exists('./artichow/font/simsun.ttf')) || $crawltlang == 'bulgarian' || $crawltlang == 'turkish') {
	$crawltlang = 'english';
}
if (isset($_GET['navig'])) {
	$navig = (int)$_GET['navig'];
} else {
	$navig = 1;
}

//get the listlang files
include ("../include/listlang.php");

//language file include
if (file_exists("../language/" . $crawltlang . ".php") && in_array($crawltlang, $listlangcrawlt)) {
	include ("../language/" . $crawltlang . ".php");
} else {
	exit('<h1>No language files available !!!!</h1>');
}

$values[0] = $nbrpageview;
$values[1] = ($nbrpagestotal - $nbrpageview);
if ($navig == 17) {
	$values[0] = $nbrpageview;
	$values[1] = $nbrpagestotal;
	$legend[0] = $language['hacking3'];
	$legend[1] = $language['hacking4'];
} else {
	$values[0] = $nbrpageview;
	$values[1] = ($nbrpagestotal - $nbrpageview);
	$legend[0] = $language['pc-page-view'];
	$legend[1] = $language['pc-page-noview'];
}

//to avoid Artichow bug with php 5.2
if ($values[0] == 0) {
	$values[0] = 0.001;
}
if ($values[1] == 0) {
	$values[1] = 0.001;
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

$graph = new awGraph(500, 200);
$graph->setAntiAliasing(TRUE);
$graph->border->hide(TRUE);
$graph->shadow->setSize(5);
$graph->shadow->smooth(TRUE);
//$graph->shadow->setPosition(awShadow::LEFT_BOTTOM); -- we don't want a border for pies
$graph->shadow->hide(TRUE);
$graph->shadow->setColor(new awDarkBlue);
$plot = new awPie($values);
$plot->setCenter(0.35, 0.5);
$plot->setSize(0.6, 0.8);
$plot->set3D(15);
$plot->setLabelPosition(10);
$plot->label->setColor(new awDarkBlue);

if ($ttf == 'ok') {
	$plot->label->setFont(new awTuffy(9));
} else {
	$plot->label->setFont(new awFont(2));
}
$plot->setBorderColor(new awDarkBlue);
$plot->setLegend($legend);
$plot->legend->setPosition(1.6, 1.03);
$plot->legend->shadow->setSize(0);
$plot->legend->setBackgroundColor(new awWhite);
$plot->legend->border->hide(TRUE);
$plot->legend->setTextColor(new awDarkBlue);

if ($ttf == 'ok') {
	if ($crawltlang == 'russian') {
		$plot->legend->setTextFont(new awsimsun(7));
	} else {
		$plot->legend->setTextFont(new awTuffy(9));
	}
} else {
	$plot->legend->setTextFont(new awFont(2));
}

$graph->add($plot);
$graph->draw();
?>
