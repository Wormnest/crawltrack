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
// file: regression.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
//Librement inspir� de la classe RegLin de Abel MILCENT
//----------------------------------------------------------------------
function GetEvol($tableau) {
	//initialisation valeur
	$sumX = 0; //somme des X
	$sumY = 0; //somme des Y
	$sumX2 = 0; //somme des X�
	$sumY2 = 0; //somme des y�
	$sumXY = 0; //somme des x*y
	$n = count($tableau);
	
	//Mise en place des abscisses
	for ($i = 0;$i < $n;$i++) {
		$tAbscisse[$i] = $i;
	}
	//parcours des donnees pour r�cup�ration formule n�cessaire au calcul des �l�ments
	for ($i = 0;$i < $n;$i++) {
		$xVal = $tAbscisse[$i];
		$yVal = $tableau[$i];
		$sumX+= $xVal;
		$sumY+= $yVal;
		$sumX2+= $xVal * $xVal;
		$sumY2+= $yVal * $yVal;
		$sumXY+= $xVal * $yVal;
	}
	$vMoyX = $sumX / $n; //moyenne X
	$vMoyY = $sumY / $n; //moyenne Y
	
	//La variance, c'est la moyenne des carr�s soustrait du carr� de la moyenne: Rappel
	$vVarianceX = ($sumX2 / $n) - ($vMoyX * $vMoyX);
	//La covariance, la moyenne des produits moins le produit des moyennes
	$vCovariance = ($sumXY / $n) - ($vMoyX * $vMoyY);
	
	//Calcul de coef directeur de la r�gression
	$vA = $vCovariance / $vVarianceX;
	$vB = $vMoyY - ($vA * $vMoyX);
	
	//renvoie le % d'�volution par jour
	$tOpt = array();
	foreach ($tAbscisse as $i) {
		$tOpt[$i] = $vA * $i + $vB;
	}
	$evolution = round((($tOpt[1] - $tOpt[0]) / abs($tOpt[0])) * 100, 2);
	return $evolution;
}
?>
