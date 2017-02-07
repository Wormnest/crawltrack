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
// file: tendance-graph.php
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

//get graph values
$tendance7 = $_GET['tendance7'];
$tendance30 = $_GET['tendance30'];

//graph creation
//create table for graph
$value[0] = 10;
$value[1] = $value[0] + (6 * ($tendance30 / 100) * $value[0]);
$value[2] = $value[1] + (6 * ($tendance30 / 100) * $value[1]);
$value[3] = $value[2] + (6 * ($tendance30 / 100) * $value[2]);
$value[4] = $value[3] + (6 * ($tendance30 / 100) * $value[3]);
$value[5] = $value[4] + (6 * ($tendance7 / 100) * $value[4]);
$value2[0] = null;
$value2[1] = null;
$value2[2] = null;
$value2[3] = null;
$value2[4] = null;
$value2[5] = $value[5];

require_once ("artichow/LinePlot.class.php");
require_once ("artichow/Graph.class.php");

$graph = new awGraph(480, 220);
$group = new awPlotGroup();
$group->setPadding(0, 0, 20, 40);
$graph->setAntiAliasing(TRUE);

$plot = new awLinePlot($value, awLinePlot::MIDDLE);

// Change line color
$plot->setColor(new awColor(0, 0, 250));
$plot->setThickness(4);
$group->add($plot);
$plot = new awLinePlot($value2, awLinePlot::MIDDLE);
$plot->hideLine(TRUE);
if ($tendance7 > 0) {
	$plot->mark->setType(awMark::IMAGE);
	$plot->mark->setImage(new awFileImage("artichow/images/happy.png"));
} else {
	$plot->mark->setType(awMark::IMAGE);
	$plot->mark->setImage(new awFileImage("artichow/images/unhappy.png"));
}
$plot->mark->setSize(12);
$plot->mark->border->hide();
$plot->setThickness(4);
$group->add($plot);
$group->axis->bottom->hide(TRUE);
$group->axis->left->hide(TRUE);
$group->grid->hide(TRUE);
$graph->border->hide();
$graph->add($group);
$graph->draw();
?>
