<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.7
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
// file: createtable.php
//----------------------------------------------------------------------
//  Last update: 29/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_INSTALL')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//determine the path to the file
if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME'])) {
	$path = dirname($_SERVER['SCRIPT_FILENAME']);
} elseif (isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT']) && isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF'])) {
	$path = dirname($_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF']);
} else {
	$path = '..';
}
//valid form
if (empty($idmysql) || empty($passwordmysql) || empty($hostmysql) || empty($basemysql)) {
	echo "<p>" . $language['step1_install_no_ok'] . "</p>";
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value='2'>\n";
	echo "<input type=\"hidden\" name ='navig' value='15'>\n";
	echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
	echo "<input type=\"hidden\" name ='idmysql' value='$idmysql'>\n";
	echo "<input type=\"hidden\" name ='passwordmysql' value='$passwordmysql'>\n";
	echo "<input type=\"hidden\" name ='hostmysql' value='$hostmysql'>\n";
	echo "<input type=\"hidden\" name ='basemysql' value='$basemysql'>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
	echo "</form>\n";
	echo "<br></div>\n";
}
//configconnect file creation
else {
	//check if file already exist
	if (file_exists('include/configconnect.php')) {
		$config_filepath = $path . '/include/configconnect.php';
	} else {
		//file didn't exist, we can create it
		
		// Get the reference file and replace the needed values
		$ref_file_content = file_get_contents(dirname(__FILE__) . '/data/configconnect.base.php');
		// Replace the values
		$final_file_content = preg_replace('/USER/', $idmysql, $ref_file_content);
		$final_file_content = preg_replace('/PASSWORD/', $passwordmysql, $final_file_content);
		$final_file_content = preg_replace('/DATABASE/', $basemysql, $final_file_content);
		$final_file_content = preg_replace('/HOST/', $hostmysql, $final_file_content);
		$final_file_content = preg_replace('/SECRETSENTENCE/', random(50), $final_file_content);
		$config_filepath = $path . '/include/configconnect.php';
		$filedir = $path . '/include';
		
		//chmod the directory
		@chmod($filedir, 0755);
		if ($file = fopen($config_filepath, "w")) {
			fwrite($file, $final_file_content);
			fclose($file);
		}
	}
	//crawltrack file creation
	//check if file already exist
	if (file_exists('crawltrack.php')) {
		$crawltrack_filepath = $path . '/crawltrack.php';
	} else {
		//file didn't exist, we can create it
		
		//url calculation
		$dom = $_SERVER["HTTP_HOST"];
		$file1 = $_SERVER["PHP_SELF"];
		$size = strlen($file1);
		$file1 = substr($file1, -$size, -9);
		$url_crawlt = "http://" . $dom . $file1;
		
		// Get the reference file and replace the needed values
		$ref_file_content = file_get_contents(dirname(__FILE__) . '/data/crawltrack.base.php');
		// Replace the values
		$final_file_content = preg_replace('/FILE_PATH/', $path, $ref_file_content);
		$final_file_content = preg_replace('/URL_CRAWLTRACK/', $url_crawlt, $final_file_content);
		$crawltrack_filepath = $path . '/crawltrack.php';
		$filedir = $path;
		
		//chmod the directory
		@chmod($filedir, 0755);
		if ($file2 = fopen($crawltrack_filepath, "w")) {
			fwrite($file2, $final_file_content);
			fclose($file2);
		}
	}
	//set the correct chmod level to all folder
	@chmod($path, 0755);
	@chmod($path . '/cache', 0755);
	@chmod($path . '/cachecloseperiod', 0755);
	@chmod($path . '/geoipdatabase', 0755);
	@chmod($path . '/graphs', 0755);
	@chmod($path . '/html', 0755);
	@chmod($path . '/images', 0755);
	@chmod($path . '/include', 0755);
	@chmod($path . '/language', 0755);
	@chmod($path . '/nusoap', 0755);
	@chmod($path . '/php', 0755);
	@chmod($path . '/phpmailer', 0755);
	@chmod($path . '/styles', 0755);
	//check if file correctly created
	if (file_exists('include/configconnect.php') && file_exists('crawltrack.php')) {
		//case file ok
		echo "<p>" . $language['step1_install_ok'] . "</p>\n";
		
		// tables creation
		include ("./include/configconnect.php");
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword);
		
		// check if connection is ok
		if (!$connexion) {
			//suppress the files
			@chmod($path, 0755);
			@chmod($path . '/include', 0755);
			unlink($config_filepath);
			unlink($crawltrack_filepath);
			echo "<p>" . $language['step2_install_no_ok'] . "</p>";
			echo "<div class=\"form\">\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='validform' value='2'>\n";
			echo "<input type=\"hidden\" name ='navig' value='15'>\n";
			echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
			echo "<input type=\"hidden\" name ='idmysql' value='$idmysql'>\n";
			echo "<input type=\"hidden\" name ='passwordmysql' value='$passwordmysql'>\n";
			echo "<input type=\"hidden\" name ='hostmysql' value='$hostmysql'>\n";
			echo "<input type=\"hidden\" name ='basemysql' value='$basemysql'>\n";
			echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
			echo "</form>\n";
			echo "</div>\n";
		} else {
			//check is base selection is ok
			$selection = mysql_select_db($crawltdb);
			
			if (!$selection) {
				//suppress the files
				@chmod($path, 0755);
				@chmod($path . '/include', 0755);
				unlink($config_filepath);
				unlink($crawltrack_filepath);
				echo "<p>" . $language['step3_install_no_ok'] . "</p>";
				echo "<div class=\"form\">\n";
				echo "<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='validform' value='2'>\n";
				echo "<input type=\"hidden\" name ='navig' value='15'>\n";
				echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
				echo "<input type=\"hidden\" name ='idmysql' value='$idmysql'>\n";
				echo "<input type=\"hidden\" name ='passwordmysql' value='$passwordmysql'>\n";
				echo "<input type=\"hidden\" name ='hostmysql' value='$hostmysql'>\n";
				echo "<input type=\"hidden\" name ='basemysql' value='$basemysql'>\n";
				echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='20'>\n";
				echo "</form>\n";
				echo "</div>\n";
			} else {
				// Call the maintenance script which will do the job
				$maintenance_mode = 'install';
				$tables_to_touch = 'all';
				include 'maintenance.php';
				mysql_close($connexion);
				if (empty($tables_actions_error_messages)) {
					//case table creation ok
					echo "<p>" . $language['step1_install_ok2'] . "</p>\n";
					echo "<div class=\"form\">\n";
					echo "<form action=\"index.php\" method=\"POST\" >\n";
					echo "<input type=\"hidden\" name ='navig' value='15'>\n";
					echo "<input type=\"hidden\" name ='validform' value='4'>\n";
					echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
					echo "<input name='ok' type='submit'  value=' " . $language['step4_install'] . " ' size='60'>\n";
					echo "</form>\n";
					echo "<br></div>\n";
				} else {
					//case table creation no ok
					echo "<p>" . $language['step1_install_no_ok3'] . "</p>\n";
					echo "<div class=\"form\">\n";
					echo "<form action=\"index.php\" method=\"POST\" >\n";
					echo "<input type=\"hidden\" name ='validform' value='3'>\n";
					echo "<input type=\"hidden\" name ='navig' value='15'>\n";
					echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
					echo "<input type=\"hidden\" name ='idmysql' value='$idmysql'>\n";
					echo "<input type=\"hidden\" name ='passwordmysql' value='$passwordmysql'>\n";
					echo "<input type=\"hidden\" name ='hostmysql' value='$hostmysql'>\n";
					echo "<input type=\"hidden\" name ='basemysql' value='$basemysql'>\n";
					echo "<input name='ok' type='submit'  value=' " . $language['retry'] . " ' size='60'>\n";
					echo "</form>\n";
					echo "<br></div>\n";
				}
			}
		}
	} else {
		//case file no ok
		echo "<p>" . $language['step1_install_no_ok2'] . "</p>";
		echo "<div class=\"form\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='validform' value='2'>\n";
		echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
		echo "<input type=\"hidden\" name ='idmysql' value='$idmysql'>\n";
		echo "<input type=\"hidden\" name ='passwordmysql' value='$passwordmysql'>\n";
		echo "<input type=\"hidden\" name ='hostmysql' value='$hostmysql'>\n";
		echo "<input type=\"hidden\" name ='basemysql' value='$basemysql'>\n";
		echo "<input name='ok' type='submit'  value=' " . $language['back_to_form'] . " ' size='60'>\n";
		echo "</form>\n";
		echo "<br></div>\n";
	}
}
?>
