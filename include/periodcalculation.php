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
// file: periodcalculation.php
//----------------------------------------------------------------------
// Split of from functions.php
// Requirements: A valid db $connexion when $period==5.
// TODO: Change this!
//----------------------------------------------------------------------

//-------period calculation including time shift-----------------------------------------
// Set the time zone to whatever the default is to avoid errors
// Will default to UTC if it's not set properly in php.ini
date_default_timezone_set(@date_default_timezone_get());

//day server
$serverday = date("j", strtotime("today"));
$todayserver = date("Y-m-d", strtotime("today"));
$todayserver2 = explode('-', $todayserver);
$yeartodayserver = $todayserver2[0];
$monthtodayserver = $todayserver2[1];
$daytodayserver = $todayserver2[2];
//day local
$localday = date("j", (strtotime("today")) - ($settings->timediff * 3600));
//test to calculate the reference time
if ($serverday == $localday) {
	$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($settings->timediff * 3600)));
} elseif ($serverday < $localday) {
	if ($serverday == 1 && $localday != 2) {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($settings->timediff * 3600) - 86400));
	} else {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($settings->timediff * 3600) + 86400));
	}
} elseif ($serverday > $localday) {
	if ($localday == 1 && $serverday != 2) {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($settings->timediff * 3600) + 86400));
	} else {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($settings->timediff * 3600) - 86400));
	}
}
$datelocal = date("Y-m-d H:i:s",(strtotime("today")- ($settings->timediff * 3600)));
$datelocalcut = explode(' ', $datelocal);
$todaylocal = explode('-', $datelocalcut[0]);
$yeartodaylocal = $todaylocal[0];
$monthtodaylocal = $todaylocal[1];
$daytodaylocal = $todaylocal[2];
if ($settings->period == 0) {
	//case 1 day
	$daterequest = $reftime;
	$daterequestseo = date("Y-m-d", strtotime($reftime));
	$datebeginlocal = date("Y-m-d H:i:s", strtotime($datelocal));
} elseif ($settings->period == 1) {
	//case 1 week
	$testweekday = 0;
	do {
		$dayname = date("l", (strtotime($reftime) - ($settings->timediff * 3600)));
		if ($dayname == $firstdayweek) {
			$daterequest = date("Y-m-d H:i:s", strtotime($reftime));
			$daterequestseo = date("Y-m-d", strtotime($reftime));
			$testweekday = 1;
		} else {
			$reftime = date("Y-m-d H:i:s", (strtotime($reftime) - 86400));
		}
	} while ($testweekday == 0);
	$testweekday = 0;
	do {
		$dayname = date("l", strtotime($datelocal));
		if ($dayname == $firstdayweek) {
			$datebeginlocal = date("Y-m-d H:i:s", strtotime($datelocal));
			$testweekday = 1;
		} else {
			$datelocal = date("Y-m-d H:i:s", (strtotime($datelocal) - 86400));
		}
	} while ($testweekday == 0);
} elseif ($settings->period == 2) {
	//case 1 month
	$daterequestcut = explode(' ', $reftime);
	$daterequest2 = explode('-', $daterequestcut[0]);
	$yearrequest = $daterequest2[0];
	$monthrequest = $daterequest2[1];
	$dayrequest = 1;
	$daterequest = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequestseo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$datelocalcut = explode(' ', $datelocal);
	$datebeginlocal2 = explode('-', $datelocalcut[0]);
	$yearbeginlocal = $datebeginlocal2[0];
	$monthbeginlocal = $datebeginlocal2[1];
	$daybeginlocal = 1;
	$datebeginlocal = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, $daybeginlocal, $yearbeginlocal));
} elseif ($settings->period == 3) {
	//case 1 year
	$daterequestcut = explode(' ', $reftime);
	$daterequest2 = explode('-', $daterequestcut[0]);
	$yearrequest = $daterequest2[0];
	$monthrequest = 1;
	$dayrequest = 1;
	$daterequest = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequestseo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$datelocalcut = explode(' ', $datelocal);
	$datebeginlocal2 = explode('-', $datelocalcut[0]);
	$yearbeginlocal = $datebeginlocal2[0];
	$monthbeginlocal = 1;
	$daybeginlocal = 1;
	$datebeginlocal = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, $daybeginlocal, $yearbeginlocal));
} elseif ($settings->period >= 1000) {
	//case 1 day (back and forward)
	$shiftday = $settings->period - 999;
	$shiftday2 = $settings->period - 1000;
	$daterequest = date("Y-m-d H:i:s", (strtotime($reftime) - ($shiftday * 86400)));
	//case change to summer time----------------
	$explodedate1 = explode(' ', $daterequest);
	$explodedate2 = explode(':', $explodedate1[1]);
	if ($explodedate2[0] > 20) {
		$daterequest = date("Y-m-d H:i:s", (strtotime($reftime) - ($shiftday * 82800)));
		$daterequest2 = date("Y-m-d H:i:s", (strtotime($reftime) - ($shiftday2 * 82800)));
		$daterequestseo = date("Y-m-d", (strtotime($reftime) - ($shiftday * 82800)));
		$daterequest2seo = date("Y-m-d", (strtotime($reftime) - ($shiftday2 * 82800)));
		$datebeginlocal = date("Y-m-d H:i:s", (strtotime($datelocal) - ($shiftday * 82800)));
	}
	//------------------------------
	else {
		$daterequest2 = date("Y-m-d H:i:s", (strtotime($reftime) - ($shiftday2 * 86400)));
		$daterequestseo = date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
		$daterequest2seo = date("Y-m-d", (strtotime($reftime) - ($shiftday2 * 86400)));
		$datebeginlocal = date("Y-m-d H:i:s", (strtotime($datelocal) - ($shiftday * 86400)));
	}
	$datebeginlocalcut = explode(' ', $datebeginlocal);
	$todaylocal2 = explode('-', $datebeginlocalcut[0]);
	$yeartodaylocal = $todaylocal2[0];
	$monthtodaylocal = $todaylocal2[1];
	$daytodaylocal = $todaylocal2[2];
} elseif ($settings->period >= 100 && $settings->period < 200) {
	//case 1 month (back and forward)
	$shiftmonth = $settings->period - 99;
	$daterequestcut = explode(' ', $reftime);
	$daterequest2 = explode('-', $daterequestcut[0]);
	$yearrequest = $daterequest2[0];
	$monthrequest = $daterequest2[1] - $shiftmonth;
	$dayrequest = 1;
	$monthrequest2 = $daterequest2[1] - $shiftmonth + 1;
	$daterequest = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequest2 = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest2, $dayrequest, $yearrequest));
	$daterequestseo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequest2seo = date("Y-m-d", mktime(0, 0, 0, $monthrequest2, $dayrequest, $yearrequest));
	$datelocalcut = explode(' ', $datelocal);
	$datebeginlocal2 = explode('-', $datelocalcut[0]);
	$yearbeginlocal = $datebeginlocal2[0];
	$monthbeginlocal = $datebeginlocal2[1] - $shiftmonth;
	$daybeginlocal = 1;
	$datebeginlocal = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, $daybeginlocal, $yearbeginlocal));
	$datebeginlocalcut = explode(' ', $datebeginlocal);
	$todaylocal2 = explode('-', $datebeginlocalcut[0]);
	$yeartodaylocal = $todaylocal2[0];
	$monthtodaylocal = $todaylocal2[1];
	$daytodaylocal = $todaylocal2[2];
} elseif ($settings->period >= 200 && $settings->period < 300) {
	//case 1 year (back and forward)
	$shiftyear = $settings->period - 199;
	$daterequestcut = explode(' ', $reftime);
	$daterequest2 = explode('-', $daterequestcut[0]);
	$yearrequest = $daterequest2[0] - $shiftyear;
	$monthrequest = 1;
	$dayrequest = 1;
	$yearrequest2 = $daterequest2[0] - $shiftyear + 1;
	$daterequest = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequest2 = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest2));
	$daterequestseo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequest2seo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest2));
	$datelocalcut = explode(' ', $datelocal);
	$datebeginlocal2 = explode('-', $datelocalcut[0]);
	$yearbeginlocal = $datebeginlocal2[0] - $shiftyear;
	$monthbeginlocal = 1;
	$daybeginlocal = 1;
	$datebeginlocal = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, $daybeginlocal, $yearbeginlocal));
	$datebeginlocalcut = explode(' ', $datebeginlocal);
	$todaylocal2 = explode('-', $datebeginlocalcut[0]);
	$yeartodaylocal = $todaylocal2[0];
	$monthtodaylocal = $todaylocal2[1];
	$daytodaylocal = $todaylocal2[2];
} elseif ($settings->period >= 300 && $settings->period < 400) {
	//case 1 week (back and forward)
	$shiftweek = $settings->period - 299;
	$reftime = date("Y-m-d H:i:s", (strtotime($reftime) - (604800 * $shiftweek)));
	$datelocal = date("Y-m-d H:i:s", (strtotime($datelocal) - (604800 * $shiftweek)));
	//case 1 week
	$testweekday = 0;
	do {
		$dayname = date("l", (strtotime($reftime) - ($settings->timediff * 3600)));
		if ($dayname == $firstdayweek) {
			$daterequest = date("Y-m-d H:i:s", strtotime($reftime));
			$daterequestseo = date("Y-m-d", strtotime($reftime));
			$daterequest2 = date("Y-m-d H:i:s", (strtotime($reftime) + 604800));
			$daterequest2seo = date("Y-m-d", (strtotime($reftime) + 604800));
			$testweekday = 1;
		} else {
			$reftime = date("Y-m-d H:i:s", (strtotime($reftime) - 86400));
		}
	} while ($testweekday == 0);
	$testweekday = 0;
	do {
		$dayname = date("l", strtotime($datelocal));
		if ($dayname == $firstdayweek) {
			$datebeginlocal = date("Y-m-d H:i:s", strtotime($datelocal));
			$testweekday = 1;
		} else {
			$datelocal = date("Y-m-d H:i:s", (strtotime($datelocal) - 86400));
		}
	} while ($testweekday == 0);
} elseif ($settings->period == 4) {
	//case 8 days
	$daterequest = date("Y-m-d H:i:s", (strtotime($reftime) - 604800));
	$daterequestseo = date("Y-m-d", (strtotime($reftime) - 604800));
	$datebeginlocal = date("Y-m-d H:i:s", (strtotime($datelocal) - 604800));
} elseif ($settings->period == 5) {
	//case since installation
	$sql = "SELECT  MIN(date) AS min_date FROM crawlt_visits
    WHERE crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $site) . "'";
	$requete = db_query($sql, $db->connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		$ligne = $requete->fetch_row();
		$reftimestart = $ligne[0];
	} else {
		$reftimestart = $reftime;
	}
	$daterequest = date("Y-m-d H:i:s", strtotime($reftimestart));
	$daterequestseo = date("Y-m-d", strtotime($reftimestart));
	$datebeginlocal = date("Y-m-d H:i:s", (strtotime($daterequest) - ($settings->timediff * 3600)));
}
$daterequestcut = explode(' ', $daterequest);
$beginserver = explode('-', $daterequestcut[0]);
$yearbeginserver = $beginserver[0];
$monthbeginserver = $beginserver[1];
$daybeginserver = $beginserver[2];
$datebeginlocalcut = explode(' ', $datebeginlocal);
$beginlocal = explode('-', $datebeginlocalcut[0]);
$yearbeginlocal = $beginlocal[0];
$monthbeginlocal = $beginlocal[1];
$daybeginlocal = $beginlocal[2];
$oneweeklater = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, ($daybeginlocal + 6), $yearbeginlocal));
$endweek = explode(' ', $oneweeklater);
$endweek2 = explode('-', $endweek[0]);
$yearendweek = $endweek2[0];
$monthendweek = $endweek2[1];
$dayendweek = $endweek2[2];
//-------end of period calculation including time shift-----------------------------------------
?>