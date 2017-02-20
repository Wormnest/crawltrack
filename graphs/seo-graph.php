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
// file:seo-graph.php
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
//get graph info
$typegraph = htmlspecialchars($_GET['typegraph']);
$period = (int)$_GET['period'];
$graphname = urlencode($_GET['graphname']);
$crawltlang = htmlspecialchars($_GET['crawltlang']);
if (($crawltlang == 'russian' && !file_exists('./artichow/font/simsun.ttf')) || $crawltlang == 'bulgarian' || $crawltlang == 'turkish') {
	$crawltlang = 'english';
}

//get the listlang files
include ("../include/listlang.php");

//language file include
if (file_exists("../language/" . $crawltlang . ".php") && in_array($crawltlang, $listlangcrawlt)) {
	include ("../language/" . $crawltlang . ".php");
} else {
	exit('<h1>Language file not available!</h1>');
}

//database connection
include ("../include/configconnect.php");
require_once("../include/jgbdb.php");
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

//get the listlang files
include ("../include/listlang.php");
// Needed for crawlt_sql_quote
require_once("../include/functions.php");

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
		exit('<h1>No Graph values availabe !!!!</h1>');
	}
	$datatransfert = unserialize(urldecode(stripslashes($data)));
}
mysqli_close($connexion);

$graphnameexplode = explode('-', $graphname);
if ($graphnameexplode[1] == 'permanent') {
	$fp = fopen("../cachecloseperiod/$graphname.gz", 'w');
	fwrite($fp, gzcompress($data));
	fclose($fp);
}

//legend and title text
$legend0 = $language['ask']." ";
$legend1 = $language['google']." ";
$legend2 = $language['msn']." ";
$legend3 = $language['yahoo']." ";
$legend4 = $language['website3']." ";
$legend5 = $language['direct']." ";
$legend6 = $language['baidu']." ";
$legend7 = $language['googleimage']." ";
$legend8 = $language['yandex']." ";
$legend9 = $language['aol']." ";
foreach ($datatransfert as $key => $value) {
	$axex[] = $key;
}
if ($period == 1 or ($period >= 300 && $period < 400)) {
	$today1 = date("d");
}
if ($period == 3) {
	$today1 = date("m");
} else {
	$today1 = date("d-m-y");
}
if ($typegraph == 'link') {
	$titlegraph = $language['nbr_tot_link'];
	$i = 0;
	foreach ($axex as $data) {
		if ($period == 1) {
			$data2 = explode(' ', $data);
			$data3 = $data2[1];
			if ($today1 < 7 && $data3 > 25) {
				$data3 = $data3 - 31;
			}
			if ($data3 <= $today1) {
				$cutdata = explode("-", $datatransfert[$data]);
				$google[$i] = $cutdata[2];
			}
		} elseif ($period == 2) {
			if ($data <= $today1) {
				$cutdata = explode("-", $datatransfert[$data]);
				$google[$i] = $cutdata[2];
			}
		} elseif ($period == 3) {
			$data2 = explode('/', $data);
			$data3 = $data2[0];
			if ($data3 <= $today1) {
				$cutdata = explode("-", $datatransfert[$data]);
				$google[$i] = $cutdata[2];
			}
		} else {
			$cutdata = explode("-", $datatransfert[$data]);
			$google[$i] = $cutdata[2];
		}
		$msn[$i] = 0;
		$i++;
	}
} elseif ($typegraph == 'bookmark') {
	$titlegraph = $language['nbr_tot_bookmark'];
	$i = 0;
	foreach ($axex as $data) {
		if ($period == 1) {
			$data2 = explode(' ', $data);
			$data3 = $data2[1];
			if ($today1 < 7 && $data3 > 25) {
				$data3 = $data3 - 31;
			}
			if ($data3 <= $today1) {
				$delicious[$i] = $datatransfert[$data];
			}
		} elseif ($period == 2) {
			if ($data <= $today1) {
				$delicious[$i] = $datatransfert[$data];
			}
		} elseif ($period == 3) {
			$data2 = explode('/', $data);
			$data3 = $data2[0];
			if ($data3 <= $today1) {
				$delicious[$i] = $datatransfert[$data];
			}
		} else {
			$delicious[$i] = $datatransfert[$data];
		}
		$google[$i] = 0;
		$i++;
	}
} elseif ($typegraph == 'entry' || $typegraph == 'email') {
	$titlegraph = '';
	$nbrday=0;
	foreach ($axex as $data) {
		$cutdata = explode("-", $datatransfert[$data]);
		$google[] = $cutdata[0];
		$msn[] = $cutdata[1];
		$yahoo[] = $cutdata[2];
		$ask[] = $cutdata[3];
		$referer[] = $cutdata[4];
		$direct[] = $cutdata[5];
		$exalead[] = $cutdata[6];
		$unique2[] = $cutdata[7];
		$googleimage[] = $cutdata[8];
		$yandex[] = $cutdata[9];
		$aol[] = $cutdata[10];				
		$testvalue[] = $cutdata[0] + $cutdata[1] + $cutdata[2] + $cutdata[3] + $cutdata[4] + $cutdata[5] + $cutdata[6] + $cutdata[8] + $cutdata[9] + $cutdata[10];
		$nbrday++;
	}
	$isvalue = 0;
	$nbvalue = count($testvalue);
	$i = ($nbvalue - 1);
	while ($i >= 0) {
		if ($testvalue[$i] != 0 && $isvalue == 0) {
			$total[$i] = $testvalue[$i];
			$unique[$i] = $unique2[$i];
			$isvalue = 1;
		} elseif ($testvalue[$i] == 0 && $isvalue == 0) {
		} else {
			$total[$i] = $testvalue[$i];
			$unique[$i] = $unique2[$i];
		}
		$i = $i - 1;
	}
} else {
	$titlegraph = $language['nbr_tot_pages_index'];
	$i = 0;
	foreach ($axex as $data) {
		if ($period == 1) {
			$data2 = explode(' ', $data);
			$data3 = $data2[1];
			if ($today1 < 7 && $data3 > 25) {
				$data3 = $data3 - 31;
			}
			if ($data3 <= $today1) {
				$cutdata = explode("-", $datatransfert[$data]);
				$google[$i] = $cutdata[3];
			}
		} elseif ($period == 2) {
			if ($data <= $today1) {
				$cutdata = explode("-", $datatransfert[$data]);
				$google[$i] = $cutdata[3];
			}
		} elseif ($period == 3) {
			$data2 = explode('/', $data);
			$data3 = $data2[0];
			if ($data3 <= $today1) {
				$cutdata = explode("-", $datatransfert[$data]);
				$google[$i] = $cutdata[3];
			}
		} else {
			$cutdata = explode("-", $datatransfert[$data]);
			$google[$i] = $cutdata[3];
		}
		$i++;
	}
}

//graph creation
//test to see if ttf font is available
$fontttf = gd_info();
if (@$fontttf['FreeType Linkage'] == 'with freetype') {
	$ttf = 'ok';
} else {
	$ttf = 'no-ok';
}

require_once ("artichow/BarPlot.class.php");
require_once ("artichow/LinePlot.class.php");
require_once ("artichow/Graph.class.php");
require_once ("artichow/inc/Gradient.class.php");

// TODO: Support for simsun! For that we need to check:
// 1. Is simsun font available (if not then fall back to Tuffy)
// 2. Declare a simsun Font class

$graph = new awGraph(900, 300);
$graph->title->set($titlegraph);
if ($ttf == 'ok') {
	if ($crawltlang == 'russian') {
		$graph->title->setFont(new awsimsun(8));
	} else {
		$graph->title->setFont(new awTuffy(12));
	}
} else {
	$graph->title->setFont(new awFont(3));
}
$graph->title->setColor(new awDarkBlue);
$group = new awPlotGroup();
$group->setBackgroundColor(new awColor(173, 216, 230, 60));
$group->setSpace(2, 2, 0.1, 0);
$group->setPadding(50, 20, 30, 90);

if ($typegraph == 'link' || $typegraph == 'page') {
	$graph->setAntiAliasing(TRUE);
	$plot = new awLinePlot($google);
	
	// Change line color
	$plot->setColor(new awColor(50, 50, 50));
	// Change mark type
	$plot->mark->setType(awMark::CIRCLE);
	$plot->mark->border->show();
	$plot->setThickness(4);
	$group->add($plot);
	$group->legend->add($plot, $language['google'], awLegend::LINE);


	$group->legend->setBackgroundColor(new awColor(255, 255, 255, 0));
	$group->legend->setModel(awLegend::MODEL_BOTTOM);
	$group->legend->setPosition(NULL, 0.87);
	if ($ttf == 'ok') {
		if ($crawltlang == 'russian') {
			$group->legend->setTextFont(new awsimsun(8));
		} else {
			$group->legend->setTextFont(new awTuffy(10));
		}
	} else {
		$group->legend->setTextFont(new awFont(2));
	}
} elseif ($typegraph == 'bookmark') {
	$graph->setAntiAliasing(TRUE);
	$plot = new awLinePlot($delicious);
	
	// Change line color
	$plot->setColor(new awColor(0, 128, 0));
	// Change mark type
	$plot->mark->setType(awMark::SQUARE);
	$plot->mark->border->show();
	$plot->setThickness(4);
	$group->add($plot);
	$group->legend->add($plot, $language['delicious'], awLegend::LINE);
	$plot = new awLinePlot($google);
	$group->add($plot);
	$group->legend->setBackgroundColor(new awColor(255, 255, 255, 0));
	$group->legend->setModel(awLegend::MODEL_BOTTOM);
	$group->legend->setPosition(NULL, 0.87);
	if ($ttf == 'ok') {
		if ($crawltlang == 'russian') {
			$group->legend->setTextFont(new awsimsun(8));
		} else {
			$group->legend->setTextFont(new awTuffy(10));
		}
	} else {
		$group->legend->setTextFont(new awFont(2));
	}
} else {
	
	//aol
	$plot = new awBarPlot($aol, 1, 10);
	$debut = new awColor(255, 20, 127);
	$fin = new awColor(180, 0, 70);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(180, 0, 70, 10));
		$plot->barShadow->setColor(new awColor(255, 20, 127, 10));
		}	
	$plot->barShadow->smooth(TRUE);
	
	//legend
	$group->legend->add($plot, $legend9, awLegend::BACKGROUND);
	$group->add($plot);	
	
	
	//ask
	$plot = new awBarPlot($ask, 2, 10);
	$debut = new awColor(255, 255, 0);
	$fin = new awColor(215, 200, 0);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(215, 200, 0, 10));
		$plot->barShadow->setColor(new awColor(255, 255, 0, 10));
		}
	$plot->barShadow->smooth(TRUE);
	
	//legend
	$group->legend->add($plot, $legend0, awLegend::BACKGROUND);
	$group->add($plot);
	
	//exalead
	$plot = new awBarPlot($exalead, 3, 10);
	$debut = new awColor(90, 30, 30);
	$fin = new awColor(150, 100, 100);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(150, 100, 100, 10));
		$plot->barShadow->setColor(new awColor(90, 30, 30, 10));
		}
	$plot->barShadow->smooth(TRUE);
	
	//legend
	$group->legend->add($plot, $legend6, awLegend::BACKGROUND);
	$group->add($plot);

	//msn
	$plot = new awBarPlot($msn, 4, 10);
	$debut = new awColor(255, 0, 0);
	$fin = new awColor(255, 215, 0);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(255, 215, 0, 10));
		$plot->barShadow->setColor(new awColor(255, 0, 0, 10));
		}
	$plot->barShadow->smooth(TRUE);
	//legend
	$group->legend->add($plot, $legend2, awLegend::BACKGROUND);
	$group->add($plot);	
	
	
	//google
	$plot = new awBarPlot($google, 5, 10);
	$debut = new awColor(0, 128, 0);
	$fin = new awColor(144, 238, 144);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(144, 238, 144, 10));
		$plot->barShadow->setColor(new awColor(0, 128, 0, 10));
		}	
	$plot->barShadow->smooth(TRUE);
	//legend
	$group->legend->add($plot, $legend1, awLegend::BACKGROUND);
	$group->add($plot);

	//googleimage
	$plot = new awBarPlot($googleimage, 6, 10);
	$debut = new awColor(144, 238, 144);
	$fin = new awColor(238, 250, 238);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(238, 250, 238, 10));
		$plot->barShadow->setColor(new awColor(144, 238, 144, 10));
		}
	$plot->barShadow->smooth(TRUE);
	//legend
	$group->legend->add($plot, $legend7, awLegend::BACKGROUND);
	$group->add($plot);

	
	//yahoo
	$plot = new awBarPlot($yahoo, 7, 10);
	$debut = new awColor(0, 51, 153);
	$fin = new awColor(0, 191, 255);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(0, 191, 255, 10));
		$plot->barShadow->setColor(new awColor(0, 51, 153, 10));
		}
	$plot->barShadow->smooth(TRUE);
	//legend
	$group->legend->add($plot, $legend3, awLegend::BACKGROUND);
	$group->add($plot);
	
	//yandex
	$plot = new awBarPlot($yandex, 8, 10);
	$debut = new awColor(0, 10, 10);
	$fin = new awColor(0, 51, 153);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(0, 51, 153, 10));
		$plot->barShadow->setColor(new awColor(0, 10, 10, 10));
		}
	$plot->barShadow->smooth(TRUE);
	//legend
	$group->legend->add($plot, $legend8, awLegend::BACKGROUND);
	$group->add($plot);	
	
	
	//referer
	$plot = new awBarPlot($referer, 9, 10);
	$debut = new awColor(0, 0, 0);
	$fin = new awColor(255, 255, 255);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(255, 255, 255, 10));
		$plot->barShadow->setColor(new awColor(0, 0, 0, 10));
		}
	$plot->barShadow->smooth(TRUE);
	//legend
	$group->legend->add($plot, $legend4, awLegend::BACKGROUND);
	$group->add($plot);

	//direct
	$plot = new awBarPlot($direct, 10, 10);
	$debut = new awColor(120, 0, 0);
	$fin = new awColor(255, 0, 0);
	$plot->setBarGradient(new awLinearGradient($debut, $fin, 90));
	$plot->setXAxisZero(TRUE);
	$plot->setSpace(2, 2, 20, 0);
	$plot->barShadow->setSize(2);
	$plot->barShadow->setPosition(awShadow::RIGHT_TOP);
	if($nbrday <13)
		{		
		$plot->barShadow->setColor(new awColor(180, 180, 180, 10));
		}
	else
		{
		$plot->barBorder->setColor(new awColor(255, 0, 0, 10));
		$plot->barShadow->setColor(new awColor(120, 0, 0, 10));
		}
	$plot->barShadow->smooth(TRUE);
	//legend
	$group->legend->add($plot, $legend5, awLegend::BACKGROUND);
	$group->add($plot);

	//total
	$plot = new awLinePlot($total, awLinePlot::MIDDLE);
	// Change line color
	$plot->setColor(new awColor(0, 0, 150));
	// Change mark type
	$plot->mark->setType(awMark::SQUARE);
	$plot->mark->setFill(new awDarkBlue);
	$plot->mark->border->show();
	$plot->setThickness(3);
	$plot->label->set($total);
	$plot->label->move(0, -15);
	$group->add($plot);
	$group->legend->add($plot, $language['visits'], awLegend::LINE);

	//unique visitors
	$plot = new awLinePlot($unique, awLinePlot::MIDDLE);
	// Change line color
	$plot->setColor(new awColor(150, 0, 0));
	// Change mark type
	$plot->mark->setType(awMark::SQUARE);
	$plot->mark->setFill(new awRed);
	$plot->mark->border->show();
	$plot->setThickness(3);
	$plot->label->set($unique);
	$plot->label->move(0, 15);
	$group->add($plot);
	$group->legend->add($plot, $language['unique_visitors'], awLegend::LINE);
	$group->legend->setBackgroundColor(new awColor(255, 255, 255, 0));
	$group->legend->setModel(awLegend::MODEL_BOTTOM);
	$group->legend->setPosition(NULL, 0.87);

	if ($ttf == 'ok') {
		if ($crawltlang == 'russian') {
			$group->legend->setTextFont(new awsimsun(6));
		} else {
			$group->legend->setTextFont(new awTuffy(9));
		}
	} else {
		$group->legend->setTextFont(new awFont(2));
	}
}

//X axis label
$group->axis->bottom->setLabelText($axex);
if ($period == 2 || ($period >= 100 && $period < 200)) {
	$group->axis->bottom->label->setAngle(45);
}
if ($ttf == 'ok') {
	$group->axis->left->label->setFont(new awTuffy(8));
	$group->axis->bottom->label->setFont(new awTuffy(8));
	$group->axis->bottom->label->move(-10, 0);
} else {
	$group->axis->left->label->setFont(new awFont(2));
	$group->axis->bottom->label->setFont(new awFont(2));
	$group->axis->bottom->label->move(20, 0);
}

$graph->add($group);
$graph->draw();
?>
