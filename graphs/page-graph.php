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
// file: page-graph.php
//----------------------------------------------------------------------
// this graph is made with artichow    website: www.artichow.org
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
error_reporting(0);
//initialize array
$listlangcrawlt = array();
//get graph values
$nbrpageview = $_GET['nbrpageview'];
$nbrpagestotal = $_GET['nbrpagestotal'];
$crawltlang = $_GET['crawltlang'];
if (($crawltlang == 'russian' && !file_exists('./artichow/font/simsun.ttf')) OR $crawltlang == 'bulgarian' OR $crawltlang == 'turkish') {
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
$graph = new Graph(500, 200);
if (function_exists('imageantialias')) {
	$graph->setAntiAliasing(TRUE);
}
$graph->border->hide(TRUE);
$graph->shadow->setSize(5);
$graph->shadow->smooth(TRUE);
$graph->shadow->setPosition('SHADOW_LEFT_BOTTOM');
$graph->shadow->setColor(new DarkBlue);
$plot = new Pie($values);
$plot->setCenter(0.35, 0.5);
$plot->setSize(0.6, 0.8);
$plot->set3D(15);
$plot->setLabelPosition(10);
$plot->label->setColor(new DarkBlue);
if ($ttf == 'ok') {
	$plot->label->setFont(new Tuffy(9));
} else {
	$plot->label->setFont(new Font(2));
}
$plot->setBorderColor(new DarkBlue);
$plot->setLegend($legend);
$plot->legend->setPosition(1.6, 1.03);
$plot->legend->shadow->setSize(0);
$plot->legend->setBackgroundColor(new White);
$plot->legend->border->hide(TRUE);
$plot->legend->setTextColor(new DarkBlue);
if ($ttf == 'ok') {
	if ($crawltlang == 'russian') {
		$plot->legend->setTextFont(new simsun(7));
	} else {
		$plot->legend->setTextFont(new Tuffy(9));
	}
} else {
	$plot->legend->setTextFont(new Font(2));
}
$graph->add($plot);
$graph->draw();
?>
