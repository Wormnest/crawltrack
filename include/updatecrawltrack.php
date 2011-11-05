<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.1
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
// file: updatecrawltrack.php
//----------------------------------------------------------------------
//  Last update: 05/11/2011
//----------------------------------------------------------------------
//this file is needed to update from a previous release
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//connexion to database
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or exit("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or exit("MySQL database selection problem");
$process_ok = true;

//----------------------------------------------------------------------------------------------------
// Call the maintenance script which will do the job
$maintenance_mode = 'update';
$tables_to_touch = 'all';
include 'maintenance.php';

// Special case for 'crawlt_config' table: set some default values
if (!$existing_crawlt_config_table) {
	//give a value to $time for release before 1.4.0
	if (!isset($times)) {
		$times = 0;
	}
	//give a value to $mail for release before 1.5.0
	if (!isset($mail)) {
		$mail = 0;
	}
	//give a value to $dest for release before 1.5.0
	if (!isset($dest)) {
		$dest = '';
	}
	//give a value to $public for release before 1.5.0
	if (!isset($public)) {
		$public = 0;
	}
}

//----------------------------------------------------------------------------------------------------
//update configconnect.php file if version <328
if ($version < 328) {
	//update the configconnect file
	
	//determine the path to the file
	if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME'])) {
		$path = dirname($_SERVER['SCRIPT_FILENAME']);
	} elseif (isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT']) && isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF'])) {
		$path = dirname($_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF']);
	} else {
		$path = '..';
	}
	$filename = $path . '/include/configconnect.php';
	$filedir = $path . '/include';
	//suppress existing file
	@chmod($filedir, 0755);
	@unlink($filename);
	//create the new configconnect file
	
	// Get the reference file and replace the needed values
	$ref_file_content = file_get_contents(dirname(__FILE__) . '/data/configconnect.base.php');
	// Replace the values
	$final_file_content = preg_replace('/USER/', $crawltuser, $ref_file_content);
	$final_file_content = preg_replace('/PASSWORD/', $crawltpassword, $final_file_content);
	$final_file_content = preg_replace('/DATABASE/', $crawltdb, $final_file_content);
	$final_file_content = preg_replace('/HOST/', $crawlthost, $final_file_content);
	$final_file_content = preg_replace('/SECRETSENTENCE/', random(50), $final_file_content);
	if ($file = fopen($filename, "w")) {
		fwrite($file, $final_file_content);
		fclose($file);
	} else {
		$process_ok = false;
	}
}
//----------------------------------------------------------------------------------------------------
//update crawltrack.php file if version <331
$crawltrack_php_updated = false;
if ($version < 331) {
	//update the crawltrack file
	
	//determine the path to the file
	if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME'])) {
		$path = dirname($_SERVER['SCRIPT_FILENAME']);
	} elseif (isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT']) && isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF'])) {
		$path = dirname($_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF']);
	} else {
		$path = '..';
	}
	$filename = $path . '/crawltrack.php';
	$filedir = $path;
	//suppress existing file
	@chmod($filedir, 0755);
	if (@unlink($filename)) {
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
		$filename2 = $path . '/crawltrack.php';
		$filedir = $path;
		if ($file2 = fopen($filename2, "w")) {
			fwrite($file2, $final_file_content);
			fclose($file2);
			$crawltrack_php_updated = true;
		}
		$delete_old_ok = true;
	} else {
		echo "<h1>" . $language['chmod_no_ok'] . "</h1>";
		$process_ok = false;
	}
} else {
	$crawltrack_php_updated = true;
}

//----------------------------------------------------------------------------------------------------
//set the correct chmod level to all folder
//determine the path to the file
if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME'])) {
	$path = dirname($_SERVER['SCRIPT_FILENAME']);
} else {
	$path = '.';
}
@chmod($path, 0755);
@chmod($path . '/cache', 0755);
@chmod($path . '/graphs', 0755);
@chmod($path . '/html', 0755);
@chmod($path . '/images', 0755);
@chmod($path . '/include', 0755);
@chmod($path . '/language', 0755);
@chmod($path . '/php', 0755);
@chmod($path . '/phpmailer', 0755);
@chmod($path . '/styles', 0755);

//----------------------------------------------------------------------------------------------------


//empty the cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = mysql_query($sqlcache, $connexion) or exit("MySQL query error");

// Just check if the main errors mesages array are empty
if (empty($tables_actions_error_messages) && empty($fields_actions_error_messages) && $process_ok) {
	$sqlupdateversion = "UPDATE crawlt_config SET version='331'";
	$requeteupdateversion = mysql_query($sqlupdateversion, $connexion);
	$a = substr($versionid, 0, 1);
	$b = substr($versionid, 1, 1);
	$c = substr($versionid, 2, 1);
?>
    <div class="content">

    <h1><?php echo $language['update_crawltrack_ok'] ?>&nbsp;<?php echo $a . $b . $c; ?></h1>
    
    <?php if (!$existing_crawlt_site_url_field): //we need to add the site url in the table
		 ?> 
        <form action="index.php" method="POST" >
        <input type="hidden" name ='navig' value='10'>
        <table width="100%" align="center">
        <tr>
        <td width="100%" align="center">
        <input name='ok' type='submit'  value=' <?php echo $language['url_update'] ?> ' size='20'>
        </td>
        </tr>
        </table>
        </form><br><br><br>
    <?php
	else: //continue
		 ?>
        <div class="form">
        <form action="index.php" method="POST" >
        <input type="hidden" name ='navig' value='0'>
        <table width="100%" align="center">
        <tr>
        <td width="100%" align="center">
        <input name='ok' type='submit'  value=' OK ' size='20'>
        </td>
        </tr>
        </table>
        </form>
        </div><br><br><br>
    <?php
	endif ?>
<?php
} else {
	// Update failed, show all error messages
	
?>
    <h1><?php echo $language['update_crawltrack_no_ok'] ?></h1>

	<?php foreach ($tables_actions_error_messages as $table_error_message): ?>
		<?php echo $table_error_message ?><br>
	<?php
	endforeach ?>
	<?php foreach ($fields_actions_error_messages as $field_error_message): ?>
		<?php echo $field_error_message ?><br>
	<?php
	endforeach ?>
	<?php foreach ($index_actions_error_messages as $index_error_message): ?>
		<?php echo $index_error_message ?><br>
	<?php
	endforeach ?>

    <div class="form">
    <form action="index.php" method="POST" >
	<input type="hidden" name="navig" value="1">
    <?php if (isset($crawltlang)): ?>
		<input type="hidden" name="lang" value="<?php echo $crawltlang ?>">
	<?php
	else: ?>
		<input type="hidden" name="lang" value="<?php echo $lang ?>">
	<?php
	endif ?>
    <table width="100%" align="center">
    <tr>
    <td width="100%" align="center">
    <input name="ok" type="submit"  value=" OK " size="20">
    </td>
    </tr>
    </table>
    </form>
    <br><br><br>
<?php
}
mysql_close($connexion);
?>
