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
// file: tendance-graph.php
//----------------------------------------------------------------------
// this graph is made with artichow    website: www.artichow.org
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
error_reporting(0);
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
$graph = new Graph(480, 220);
$group = new PlotGroup();
$group->setPadding(0, 0, 20, 40);
if (function_exists('imageantialias')) {
	$graph->setAntiAliasing(TRUE);
}
$plot = new LinePlot($value, LINEPLOT_MIDDLE);

// Change line color
$plot->setColor(new Color(0, 0, 250));
$plot->setThickness(4);
$group->add($plot);
$plot = new LinePlot($value2, LINEPLOT_MIDDLE);
$plot->hideLine(TRUE);
if ($tendance7 > 0) {
	$plot->mark->setType(MARK_HAPPY);
} else {
	$plot->mark->setType(MARK_UNHAPPY);
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
