<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.9
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
// file: functions.php
//----------------------------------------------------------------------
//  Last update: 09/03/2011
//----------------------------------------------------------------------

/*
 * Transparent SHA-256 Implementation for PHP 4 and PHP 5
 *
 * Author: Perry McGee (pmcgee@nanolink.ca)
 * Website: http://www.nanolink.ca/pub/sha256
 *
 */
if (!class_exists('nanoSha2'))
{
    class nanoSha2
    {
        // php 4 - 5 compatable class properties
        var     $toUpper;
        var     $platform;

        // Php 4 - 6 compatable constructor
        function nanoSha2($toUpper = false) {
            // Determine if the caller wants upper case or not.
            $this->toUpper = is_bool($toUpper)
                           ? $toUpper
                           : ((defined('_NANO_SHA2_UPPER')) ? true : false);

            // Deteremine if the system is 32 or 64 bit.
            $tmpInt = (int)4294967295;
            $this->platform = ($tmpInt > 0) ? 64 : 32;
        }

        // Do the SHA-256 Padding routine (make input a multiple of 512 bits)
        function char_pad($str)
        {
            $tmpStr = $str;

            $l = strlen($tmpStr)*8;     // # of bits from input string

            $tmpStr .= "\x80";          // append the "1" bit followed by 7 0's

            $k = (512 - (($l + 8 + 64) % 512)) / 8;   // # of 0 bytes to append
            $k += 4;    // PHP Strings will never exceed (2^31)-1, 1st 32bits of
                        // the 64-bit value representing $l can be all 0's

            for ($x = 0; $x < $k; $x++) {
                $tmpStr .= "\0";
            }

            // append the 32-bits representing # of bits from input string ($l)
            $tmpStr .= chr((($l>>24) & 0xFF));
            $tmpStr .= chr((($l>>16) & 0xFF));
            $tmpStr .= chr((($l>>8) & 0xFF));
            $tmpStr .= chr(($l & 0xFF));

            return $tmpStr;
        }

        // Here are the bitwise and functions as defined in FIPS180-2 Standard
        function addmod2n($x, $y, $n = 4294967296)      // Z = (X + Y) mod 2^32
        {
            $mask = 0x80000000;

            if ($x < 0) {
                $x &= 0x7FFFFFFF;
                $x = (float)$x + $mask;
            }

            if ($y < 0) {
                $y &= 0x7FFFFFFF;
                $y = (float)$y + $mask;
            }

            $r = $x + $y;

            if ($r >= $n) {
                while ($r >= $n) {
                    $r -= $n;
                }
            }

            return (int)$r;
        }

        // Logical bitwise right shift (PHP default is arithmetic shift)
        function SHR($x, $n)        // x >> n
        {
            if ($n >= 32) {      // impose some limits to keep it 32-bit
                return (int)0;
            }

            if ($n <= 0) {
                return (int)$x;
            }

            $mask = 0x40000000;

            if ($x < 0) {
                $x &= 0x7FFFFFFF;
                $mask = $mask >> ($n-1);
                return ($x >> $n) | $mask;
            }

            return (int)$x >> (int)$n;
        }

        function ROTR($x, $n) { return (int)(($this->SHR($x, $n) | ($x << (32-$n)) & 0xFFFFFFFF)); }
        function Ch($x, $y, $z) { return ($x & $y) ^ ((~$x) & $z); }
        function Maj($x, $y, $z) { return ($x & $y) ^ ($x & $z) ^ ($y & $z); }
        function Sigma0($x) { return (int) ($this->ROTR($x, 2)^$this->ROTR($x, 13)^$this->ROTR($x, 22)); }
        function Sigma1($x) { return (int) ($this->ROTR($x, 6)^$this->ROTR($x, 11)^$this->ROTR($x, 25)); }
        function sigma_0($x) { return (int) ($this->ROTR($x, 7)^$this->ROTR($x, 18)^$this->SHR($x, 3)); }
        function sigma_1($x) { return (int) ($this->ROTR($x, 17)^$this->ROTR($x, 19)^$this->SHR($x, 10)); }

        /*
         * Custom functions to provide PHP support
         */
        // split a byte-string into integer array values
        function int_split($input)
        {
            $l = strlen($input);

            if ($l <= 0) {
                return (int)0;
            }

            if (($l % 4) != 0) { // invalid input
                return false;
            }

            for ($i = 0; $i < $l; $i += 4)
            {
                $int_build  = (ord($input[$i]) << 24);
                $int_build += (ord($input[$i+1]) << 16);
                $int_build += (ord($input[$i+2]) << 8);
                $int_build += (ord($input[$i+3]));

                $result[] = $int_build;
            }

            return $result;
        }

        /**
         * Process and return the hash.
         *
         * @param $str Input string to hash
         * @param $ig_func Option param to ignore checking for php > 5.1.2
         * @return string Hexadecimal representation of the message digest
         */
        function hash($str, $ig_func = false)
        {
            unset($binStr);     // binary representation of input string
            unset($hexStr);     // 256-bit message digest in readable hex format

            // check for php's internal sha256 function, ignore if ig_func==true
            if ($ig_func == false) {
                if (version_compare(PHP_VERSION,'5.1.2','>=')) {
                    return hash("sha256", $str, false);
                } else if (function_exists('mhash') && defined('MHASH_SHA256')) {
                    return base64_encode(bin2hex(mhash(MHASH_SHA256, $str)));
                }
            }

            /*
             * SHA-256 Constants
             *  Sequence of sixty-four constant 32-bit words representing the
             *  first thirty-two bits of the fractional parts of the cube roots
             *  of the first sixtyfour prime numbers.
             */
            $K = array((int)0x428a2f98, (int)0x71374491, (int)0xb5c0fbcf,
                       (int)0xe9b5dba5, (int)0x3956c25b, (int)0x59f111f1,
                       (int)0x923f82a4, (int)0xab1c5ed5, (int)0xd807aa98,
                       (int)0x12835b01, (int)0x243185be, (int)0x550c7dc3,
                       (int)0x72be5d74, (int)0x80deb1fe, (int)0x9bdc06a7,
                       (int)0xc19bf174, (int)0xe49b69c1, (int)0xefbe4786,
                       (int)0x0fc19dc6, (int)0x240ca1cc, (int)0x2de92c6f,
                       (int)0x4a7484aa, (int)0x5cb0a9dc, (int)0x76f988da,
                       (int)0x983e5152, (int)0xa831c66d, (int)0xb00327c8,
                       (int)0xbf597fc7, (int)0xc6e00bf3, (int)0xd5a79147,
                       (int)0x06ca6351, (int)0x14292967, (int)0x27b70a85,
                       (int)0x2e1b2138, (int)0x4d2c6dfc, (int)0x53380d13,
                       (int)0x650a7354, (int)0x766a0abb, (int)0x81c2c92e,
                       (int)0x92722c85, (int)0xa2bfe8a1, (int)0xa81a664b,
                       (int)0xc24b8b70, (int)0xc76c51a3, (int)0xd192e819,
                       (int)0xd6990624, (int)0xf40e3585, (int)0x106aa070,
                       (int)0x19a4c116, (int)0x1e376c08, (int)0x2748774c,
                       (int)0x34b0bcb5, (int)0x391c0cb3, (int)0x4ed8aa4a,
                       (int)0x5b9cca4f, (int)0x682e6ff3, (int)0x748f82ee,
                       (int)0x78a5636f, (int)0x84c87814, (int)0x8cc70208,
                       (int)0x90befffa, (int)0xa4506ceb, (int)0xbef9a3f7,
                       (int)0xc67178f2);

            // Pre-processing: Padding the string
            $binStr = $this->char_pad($str);

            // Parsing the Padded Message (Break into N 512-bit blocks)
            $M = str_split($binStr, 64);

            // Set the initial hash values
            $h[0] = (int)0x6a09e667;
            $h[1] = (int)0xbb67ae85;
            $h[2] = (int)0x3c6ef372;
            $h[3] = (int)0xa54ff53a;
            $h[4] = (int)0x510e527f;
            $h[5] = (int)0x9b05688c;
            $h[6] = (int)0x1f83d9ab;
            $h[7] = (int)0x5be0cd19;

            // loop through message blocks and compute hash. ( For i=1 to N : )
            $N = count($M);
            for ($i = 0; $i < $N; $i++)
            {
                // Break input block into 16 32bit words (message schedule prep)
                $MI = $this->int_split($M[$i]);

                // Initialize working variables
                $_a = (int)$h[0];
                $_b = (int)$h[1];
                $_c = (int)$h[2];
                $_d = (int)$h[3];
                $_e = (int)$h[4];
                $_f = (int)$h[5];
                $_g = (int)$h[6];
                $_h = (int)$h[7];
                unset($_s0);
                unset($_s1);
                unset($_T1);
                unset($_T2);
                $W = array();

                // Compute the hash and update
                for ($t = 0; $t < 16; $t++)
                {
                    // Prepare the first 16 message schedule values as we loop
                    $W[$t] = $MI[$t];

                    // Compute hash
                    $_T1 = $this->addmod2n($this->addmod2n($this->addmod2n($this->addmod2n($_h, $this->Sigma1($_e)), $this->Ch($_e, $_f, $_g)), $K[$t]), $W[$t]);
                    $_T2 = $this->addmod2n($this->Sigma0($_a), $this->Maj($_a, $_b, $_c));

                    // Update working variables
                    $_h = $_g; $_g = $_f; $_f = $_e; $_e = $this->addmod2n($_d, $_T1);
                    $_d = $_c; $_c = $_b; $_b = $_a; $_a = $this->addmod2n($_T1, $_T2);
                }

                for (; $t < 64; $t++)
                {
                    // Continue building the message schedule as we loop
                    $_s0 = $W[($t+1)&0x0F];
                    $_s0 = $this->sigma_0($_s0);
                    $_s1 = $W[($t+14)&0x0F];
                    $_s1 = $this->sigma_1($_s1);

                    $W[$t&0xF] = $this->addmod2n($this->addmod2n($this->addmod2n($W[$t&0xF], $_s0), $_s1), $W[($t+9)&0x0F]);

                    // Compute hash
                    $_T1 = $this->addmod2n($this->addmod2n($this->addmod2n($this->addmod2n($_h, $this->Sigma1($_e)), $this->Ch($_e, $_f, $_g)), $K[$t]), $W[$t&0xF]);
                    $_T2 = $this->addmod2n($this->Sigma0($_a), $this->Maj($_a, $_b, $_c));

                    // Update working variables
                    $_h = $_g; $_g = $_f; $_f = $_e; $_e = $this->addmod2n($_d, $_T1);
                    $_d = $_c; $_c = $_b; $_b = $_a; $_a = $this->addmod2n($_T1, $_T2);
                }

                $h[0] = $this->addmod2n($h[0], $_a);
                $h[1] = $this->addmod2n($h[1], $_b);
                $h[2] = $this->addmod2n($h[2], $_c);
                $h[3] = $this->addmod2n($h[3], $_d);
                $h[4] = $this->addmod2n($h[4], $_e);
                $h[5] = $this->addmod2n($h[5], $_f);
                $h[6] = $this->addmod2n($h[6], $_g);
                $h[7] = $this->addmod2n($h[7], $_h);
            }

            // Convert the 32-bit words into human readable hexadecimal format.
            $hexStr = sprintf("%08x%08x%08x%08x%08x%08x%08x%08x", $h[0], $h[1], $h[2], $h[3], $h[4], $h[5], $h[6], $h[7]);

            return ($this->toUpper) ? strtoupper($hexStr) : $hexStr;
        }

    }
}

if (!function_exists('str_split'))
{
    /**
     * Splits a string into an array of strings with specified length.
     * Compatability with older verions of PHP
     */
    function str_split($string, $split_length = 1)
    {
        $sign = ($split_length < 0) ? -1 : 1;
        $strlen = strlen($string);
        $split_length = abs($split_length);

        if (($split_length == 0) || ($strlen == 0)) {
            $result = false;
        } elseif ($split_length >= $strlen) {
            $result[] = $string;
        } else {
            $length = $split_length;

            for ($i = 0; $i < $strlen; $i++)
            {
                $i = (($sign < 0) ? $i + $length : $i);
                $result[] = substr($string, $sign*$i, $length);
                $i--;
                $i = (($sign < 0) ? $i : $i + $length);

                $length = (($i + $split_length) > $strlen)
                          ? ($strlen - ($i + 1))
                          : $split_length;
            }
        }

        return $result;
    }
}

/**
 * Main routine called from an application using this include.
 *
 * General usage:
 *   require_once('sha256.inc.php');
 *   $hashstr = sha256('abc');
 *
 * Note:
 * PHP Strings are limitd to (2^31)-1, so it is not worth it to
 * check for input strings > 2^64 as the FIPS180-2 defines.
 */
// 2009-07-23: Added check for function as the Suhosin plugin adds this routine.
if (!function_exists('sha256')) {
    function sha256($str, $ig_func = false) {
        $obj = new nanoSha2((defined('_NANO_SHA2_UPPER')) ? true : false);
        return $obj->hash($str, $ig_func);
    }
} else {
    function _nano_sha256($str, $ig_func = false) {
        $obj = new nanoSha2((defined('_NANO_SHA2_UPPER')) ? true : false);
        return $obj->hash($str, $ig_func);
    }
}

// support to give php4 the hash() routine which abstracts this code.
if (!function_exists('hash'))
{
    function hash($algo, $data)
    {
        if (empty($algo) || !is_string($algo) || !is_string($data)) {
            return false;
        }

        if (function_exists($algo)) {
            return $algo($data);
        }
    }
}

//create a unique and random string, thanks to phpsources(http://www.phpsources.org/scripts87-PHP.htm)
function random($car) {
	$string = "";
	$chaine = "abcdefghijklmnpqrstuvwxy";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
	$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
}
//function to format the numbers with specified decimals for display
function numbdisp($value, $decimals = 0) {
	global $crawltlang;
	// Use a default value if needed
	if($decimals > 2 || $decimals < 0 || is_null($decimals))
		$decimals = 0;
	if ($crawltlang == 'french') {
		$value = number_format($value,  $decimals, ",", " ");
	} else {
		$value = number_format($value,  $decimals, ".", ",");
	}
	return $value;
}

//function to check if there is a link to the site on the page
function islinking($url, $website) {
	global $timestart, $maxtime, $stoptest;
	
	// Try to temporarly allow url_fopen
	if (ini_get('allow_url_fopen') != 1) {
		@ini_set('allow_url_fopen', '1');
	}
	
	$timenow = time();
	if (($timenow - $timestart) > $maxtime) {
		$nofound = false;
		$stoptest = 1;
	} elseif (ini_get('allow_url_fopen') == 1) {
		$stoptest = 0;
		$fp = @fopen($url, "r");
		if ($fp) {
			if (strncmp($website, 'http://', 7) == 0) {
				$website = rtrim(substr($website, 7), '/');
			} else {
				$website = rtrim($website, '/');
			}
			$content = '';
			$nofound = false;
			$iteration = 0;
			while (!feof($fp) && $iteration < 300) {
				$content.= fgets($fp, 1024);
				$iteration++;
			}
			fclose($fp);
			if (strpos($content, $website) !== false) {
				$nofound = true;
			}
		} else {
			$nofound = false;
		}
	} else {
		$nofound = false;
		$stoptest = 0;
	}
	if ($nofound) {
		return true;
	} else {
		return false;
	}
}

//function to give the link for mapgraph
function linkmapgraph($monthdate, $actualmonth, $yeardate, $actualyear) {
	if ($monthdate >= $actualmonth && $yeardate == $actualyear) {
		$value = 2;
	} else {
		$value = 99 + ($actualmonth - $monthdate) + (12 * ($actualyear - $yeardate));
	}
	return $value;
}

//function to put the page in cache (http://spellbook.infinitiv.it/2006/07/03/caching-your-queries-with-php.htm)
function cache($cachename) {
	global $nocachetest, $crawlthost, $crawltuser, $crawltpassword, $crawltdb, $caching, $numbquery, $language;
	$caching = false;
	if (file_exists("./cachecloseperiod/$cachename.gz")) {
			//Grab the cache:
			$fgz = @fopen("./cachecloseperiod/$cachename.gz", "r");
			$data = fread($fgz, filesize("./cachecloseperiod/$cachename.gz"));
			fclose($fgz);
			$data = gzuncompress($data);
			echo $data;
			echo "<div class='smalltextgrey'>--" . $numbquery . " mysql query             " . getTime() . " s--</div>";
			echo "</body>\n";
			echo "</html>\n";
			exit();
		}
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword);
	$selection = mysql_select_db($crawltdb);
	$sqlcache = "SELECT time FROM crawlt_cache WHERE cachename='$cachename'";
	$requetecache = mysql_query($sqlcache, $connexion);
	$nbrresult = mysql_num_rows($requetecache);
	if ($nbrresult >= 1) {
		$ligne = mysql_fetch_row($requetecache);
		$time = $ligne[0];
	} else {
		$time = 0;
	}

	if (file_exists("./cache/$cachename.txt") && ($time + 3600) > time() && $nocachetest != 1) {
		//Grab the cache:
		include ("./cache/$cachename.txt");
		echo "<div class='smalltextgrey'>" . $numbquery . " mysql query             " . getTime() . " s</div>";
		echo "</body>\n";
		echo "</html>\n";
		exit();
	} else {
		//create cache :
		if ($time == 0) {
			$timecache = time();
			$sqlcache2 = "INSERT INTO crawlt_cache (cachename, time) VALUES ('$cachename','$timecache')";
			$requetecache2 = mysql_query($sqlcache2, $connexion);
		} else {
			$timecache = time();
			$sqlcache3 = "UPDATE crawlt_cache SET time='$timecache' where cachename='$cachename'";
			$requetecache3 = mysql_query($sqlcache3, $connexion);
		}
		$caching = 'true';
		ob_start();
	}
mysql_close($connexion);
}

function close() {
	global $caching, $cachename, $numbquery, $period, $navig, $language;
	//You should have this at the end of each page
	if ($caching == 'true') {
		//You were caching the contents so display them, and write the cache file
		$data = ob_get_contents();
		@ob_end_flush();
		if (($period >= 1000 || ($period >= 100 && $period < 200) || ($period >= 200 && $period < 300))&& $navig!=0 && $navig!=20 && $navig!=21  && $navig!=23 && $navig!=12 && $navig!=13 && $navig!=14 && $navig!=16) {
			$fp = fopen("./cachecloseperiod/$cachename.gz", 'w');
			fwrite($fp, gzcompress($data));
		} else {
			$fp = fopen("./cache/$cachename.txt", 'w');
			fwrite($fp, $data);
		}
		fclose($fp);
		echo "<div class='smalltextgrey'>" . $numbquery . " mysql query             " . getTime() . " s</div>";
		echo "</body>\n";
		echo "</html>\n";
	}
}

//function to escape query string
function sql_quote($value) {
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
	//check if this function exists
	if (function_exists("mysql_real_escape_string")) {
		$value = mysql_real_escape_string($value);
	}
	//for PHP version < 4.3.0 use addslashes
	else {
		$value = addslashes($value);
	}
	return $value;
}

//function to escape query string
function crawlt_sql_quote($value) {
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
	//check if this function exists
	if (function_exists("mysql_real_escape_string")) {
		$value = mysql_real_escape_string($value);
	}
	//for PHP version < 4.3.0 use addslashes
	else {
		$value = addslashes($value);
	}
	return $value;
}

//function to know if the string is encode in utf8
function isutf8($string) {
	return (utf8_encode(utf8_decode($string)) == $string);
}

//function to cut and wrap the url to avoid oversize display
function crawltcuturl($url, $length) {
	global $crawltcharset;
	if ($crawltcharset == 1) {
		if (!isutf8($url)) {
			if (function_exists("mb_convert_encoding")) {
				$url = @mb_convert_encoding($url, "UTF-8", "auto");
			}
		}
	} else {
		if (function_exists("mb_convert_encoding")) {
			$url = mb_convert_encoding($url, "ISO-8859-1", "auto");
		}
	}
	$urldisplaylength = strlen("$url");
	$cutvalue = 0;
	$urldisplay = '';
	while ($cutvalue <= $urldisplaylength) {
		$cutvalue2 = $cutvalue + $length;
		$urldisplay = $urldisplay . htmlspecialchars(substr($url, $cutvalue, $length));
		if ($cutvalue2 <= $urldisplaylength) {
			$urldisplay = $urldisplay . '<br>&nbsp;&nbsp;';
			$urlcut = 1;
		}
		$cutvalue = $cutvalue2;
	}
	return $urldisplay;
}

//function to cut and wrap the keyword to avoid oversize display
function crawltcutkeyword($keyword, $length) {
	global $keywordcut, $keywordtoolong, $crawltcharset;
	if ($crawltcharset == 1) {
		if (!isutf8($keyword)) {
			if (function_exists("mb_convert_encoding")) {
				$keyword = @mb_convert_encoding($keyword, "UTF-8", "auto");
			}
		}
	} else {
		if (function_exists("mb_convert_encoding")) {
			$keyword = @mb_convert_encoding($keyword, "ISO-8859-1", "auto");
		}
	}
	if (preg_match_all("/%/i", $keyword, $out) > 3) {
		$length = 0.6 * $length;
	}
	if (strlen("$keyword") > $length) {
		$keyworddisplay = substr("$keyword", 0, $length) . "...";
		$keywordcut = 1;
	} else {
		$keyworddisplay = $keyword;
		$keywordcut = 0;
	}
	if (strlen("$keyword") > 50) {
		$keywordtoolong = 1;
	} else {
		$keywordtoolong = 0;
	}
	return htmlspecialchars($keyworddisplay);
}

//function to set up the keyword position window
function crawltkeywordwindow($keyword) {
	$value = "onclick=\"return window.open('php/keywordposition.php?keyword=" . $keyword . "','CrawlTrack','top=0,left=0,height=700,width=1020, scrollbars=yes')\"";
	return $value;
}

//function to treat xss attacks url
function crawltattackxss($page) {
	global $listattack, $tableurldisplay, $totallistattack, $listbadsite, $crawltcssaattack;
	if (strncmp($page, 'http://', 7) == 0) {
		$page = substr($page, 7);
	}
	$parseurl = parse_url('http://site.com/' . ltrim($page, "/"));
	if (isset($parseurl['query'])) {
		$chaine = $parseurl['query'];
		if (strpos($chaine, '&amp;')) {
			$queryEx = explode('&amp;', $chaine);
		} elseif (strpos($chaine, '&')) {
			$queryEx = explode('&', $chaine);
		} else {
			$queryEx[] = $chaine;
		}
		foreach ($queryEx as $value) {
			$varAndValue = explode('=', $value);
			$badsite = "";
			if (sizeof($varAndValue) >= 2) {
				for ($i = 1;$i < sizeof($varAndValue);$i++) {
					$crawlturl = str_replace($crawltcssaattack, 'http:', $varAndValue[$i]);
					if (preg_match("/http\:/i", $crawlturl)) {
						$badsite.= $varAndValue[$i] . "=";
					}
				}
			}
			// include only parameters
			$testattacktype = 0;
			foreach ($crawltcssaattack as $attacktype) {
				if (strpos($badsite, $attacktype) !== false) {
					$testattacktype = 1;
				}
			}
			if (sizeof($varAndValue) >= 2 && $testattacktype == 1) {
				$listattack[urldecode($varAndValue[0]) . "="] = urldecode($varAndValue[0]) . "=";
				$totallistattack[urldecode($varAndValue[0]) . "="] = urldecode($varAndValue[0]) . "=";
				$listbadsite[urldecode(rtrim($badsite, "=")) ] = urldecode(rtrim($badsite, "="));
			} else {
				$listattack[' '] = "";
				$totallistattack[' '] = "";
				$listbadsite[' '] = "";
			}
		}
	} else {
		$listattack[' '] = "";
		$totallistattack[' '] = "";
		$listbadsite[' '] = "";
	}
	$tableurldisplay[crawltcuturl($page, '80') ] = crawltcuturl($page, '80');
}

//function to treat sql attacks url
function crawltattacksql($page) {
	global $listattack, $tableurldisplay, $totallistattack, $listbadsite;
	if (strncmp($page, 'http://', 7) == 0) {
		$page = substr($page, 7);
	}
	$parseurl = parse_url('http://site.com/' . ltrim($page, "/"));
	if (isset($parseurl['query'])) {
		$chaine = $parseurl['query'];
		if (strpos($chaine, '&amp;')) {
			$queryEx = explode('&amp;', $chaine);
		} elseif (strpos($chaine, '&')) {
			$queryEx = explode('&', $chaine);
		} else {
			$queryEx[] = $chaine;
		}
		foreach ($queryEx as $value) {
			$varAndValue = explode('=', $value);
			if (sizeof($varAndValue) >= 2) {
				$badsite = "";
				for ($i = 1;$i < sizeof($varAndValue);$i++) {
					$badsite.= $varAndValue[$i] . "=";
				}
			}
			// include only parameters
			if (sizeof($varAndValue) >= 2 && (strpos(strtolower($badsite), '%20select%20') !== false || strpos(strtolower($badsite), '%20or%20') !== false) || (strpos(strtolower($badsite), '%20like%20') !== false || strpos(strtolower($badsite), '%20where%20') !== false)) {
				$listattack[] = urldecode($varAndValue[0]) . "=";
				$totallistattack[] = urldecode($varAndValue[0]) . "=";
				$listbadsite[] = urldecode(rtrim($badsite, "="));
			} else {
				$listattack[] = "";
				$totallistattack[] = "";
				$listbadsite[] = "";
			}
		}
	} else {
		$listattack[] = "";
		$totallistattack[] = "";
		$listbadsite[] = "";
	}
	$tableurldisplay[] = crawltcuturl($page, '80');
}

//function to check if the email address is valid from Christian Kruse
function check_email($email) {
	// RegEx begin
	$nonascii = "\x80-\xff"; # Les caractères Non-ASCII ne sont pas permis
	$nqtext = "[^\\\\$nonascii\015\012\"]";
	$qchar = "\\\\[^$nonascii]";
	$protocol = '(?:mailto:)';
	$normuser = '[a-zA-Z0-9][a-zA-Z0-9_.-]*';
	$quotedstring = "\"(?:$nqtext|$qchar)+\"";
	$user_part = "(?:$normuser|$quotedstring)";
	$dom_mainpart = '[a-zA-Z0-9][a-zA-Z0-9._-]*\\.';
	$dom_subpart = '(?:[a-zA-Z0-9][a-zA-Z0-9._-]*\\.)*';
	$dom_tldpart = '[a-zA-Z]{2,5}';
	$domain_part = "$dom_subpart$dom_mainpart$dom_tldpart";
	$regex = "$protocol?$user_part\@$domain_part";
	// RegEx end
	return preg_match("/^$regex$/", $email);
}

//function to display title and back and forward button
function crawltbackforward($title, $period, $daytodaylocal, $monthtodaylocal, $yeartodaylocal, $daybeginlocal, $monthbeginlocal, $yearbeginlocal, $dayendweek, $monthendweek, $yearendweek, $crawler, $navig, $site, $graphpos) {
	global $language, $testdate, $urlsite, $logodisplay, $hostsite;
	$crawlencode = urlencode($crawler);
	if ($navig == 0 || $navig == 6) {
		$titledisplay = '';
	} elseif ($navig == 23) {
		$titledisplay = $language['summary'];
		$logodisplay = "application_cascade.png";
	} elseif ($navig == 2 || $navig == 4) {
		$titledisplay = $title;
	} elseif ($navig == 16) {
		$titledisplay = $language['keyword'] . ":<span class=\"browntitle\"> " . $title . "</span>";
	} elseif ($navig == 14) {
		$titledisplay = $language['entry-page'] . ":<span class=\"browntitle\"> " . $title . "</span>&nbsp;&nbsp;<a href='" . $hostsite . $crawler . "'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $language['entry-page'] . "\"></a>";
	} else {
		$titledisplay = $language[$title];
	}
	if ($navig == 0) {
		$firstline = '';
	} elseif ($navig == 4 || $navig == 14 || $navig == 16) {
		$firstline = "<br><div class='title'><img src=\"./images/" . $logodisplay . "\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $logodisplay . "\">&nbsp;" . $titledisplay . "</div>";
	} else {
		$firstline = "<div class='title'><img src=\"./images/" . $logodisplay . "\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $logodisplay . "\">&nbsp;" . $titledisplay . "</div>";
	}
	if ($period == 0 || $period >= 1000) {
		$testdate = 1;
		$dateoftheday=$yeartodaylocal."-".$monthtodaylocal."-".$daytodaylocal;
		$dayenglish=date('D', strtotime($dateoftheday));
		if($dayenglish=='Mon') {
			$jour='day0';
		} elseif($dayenglish=='Tue') {
			$jour='day1';
		} elseif($dayenglish=='Wed') {		
			$jour='day2';
		} elseif($dayenglish=='Thu') {		
			$jour='day3';
		} elseif($dayenglish=='Fri') {		
			$jour='day4';
		} elseif($dayenglish=='Sat') {	
			$jour='day5';
		} elseif($dayenglish=='Sun') {			
			$jour='day6';	}
		if ($period == 0) {
			$value = "
            <h2>" . $language['display_period'] . "&nbsp;" .$language[$jour]."&nbsp;". $daytodaylocal . "/" . $monthtodaylocal . "/" . $yeartodaylocal . "</h2>           
            <h2><a href=\"index.php?navig=$navig&amp;period=1000&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <img src=\"./images/control_play.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\">
            <img src=\"./images/control_end.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></h2>";
		} else {
			$periodback = $period + 1;
			$periodgo = $period - 1;
			if ($periodgo < 1000) {
				$periodgo = 0;
			}
			$value = "
            <h2>" . $language['display_period'] . "&nbsp;" .$language[$jour]."&nbsp;". $daytodaylocal . "/" . $monthtodaylocal . "/" . $yeartodaylocal . "</h2>                 
            <h2><a href=\"index.php?navig=$navig&amp;period=$periodback&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <a href=\"index.php?navig=$navig&amp;period=$periodgo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_play_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\"></a>
            <a href=\"index.php?navig=$navig&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_end_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></a></h2>";
		}
	} elseif ($period == 2 || ($period >= 100 && $period < 200)) {
		$testdate = 0;
		if ($period == 2) {
			$value = "         
            <h2>" . $language['display_period'] . "&nbsp;" . $language[$monthtodaylocal] . "&nbsp;" . $yeartodaylocal . "</h2>            	
            <h2><a href=\"index.php?navig=$navig&amp;period=100&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <img src=\"./images/control_play.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\">
            <img src=\"./images/control_end.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></h2>";
		} else {
			$periodback = $period + 1;
			$periodgo = $period - 1;
			if ($periodgo < 100) {
				$periodgo = 2;
			}
			$value = "           
            <h2>" . $language['display_period'] . "&nbsp;" . $language[$monthtodaylocal] . "&nbsp;" . $yeartodaylocal . "</h2>                 
            <h2><a href=\"index.php?navig=$navig&amp;period=$periodback&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <a href=\"index.php?navig=$navig&amp;period=$periodgo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_play_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\"></a>
            <a href=\"index.php?navig=$navig&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_end_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></a></h2>";
		}
	} elseif ($period == 3 || ($period >= 200 && $period < 300)) {
		$testdate = 0;
		if ($period == 3) {
			$value = "           
            <h2>" . $language['display_period'] . "&nbsp;" . $language['one_year'] . "&nbsp;" . $yeartodaylocal . "</h2>            	
            <h2><a href=\"index.php?navig=$navig&amp;period=200&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <img src=\"./images/control_play.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\">
            <img src=\"./images/control_end.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></h2>";
		} else {
			$periodback = $period + 1;
			$periodgo = $period - 1;
			if ($periodgo < 200) {
				$periodgo = 3;
			}
			$value = "           
            <h2>" . $language['display_period'] . "&nbsp;" . $language['one_year'] . "&nbsp;" . $yeartodaylocal . "</h2>                  
            <h2><a href=\"index.php?navig=$navig&amp;period=$periodback&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <a href=\"index.php?navig=$navig&amp;period=$periodgo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_play_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\"></a>
            <a href=\"index.php?navig=$navig&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_end_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></a></h2>";
		}
	} elseif ($period == 1 || ($period >= 300 && $period < 400)) {
		$testdate = 0;
		if ($period == 1) {
			$value = "            
            <h2>" . $language['display_period'] . "&nbsp;" . $language['days'] . "&nbsp;" . $language['from'] . "&nbsp;" . $daybeginlocal . "/" . $monthbeginlocal . "/" . $yearbeginlocal . "&nbsp;" . $language['to'] . "&nbsp;" . $dayendweek . "/" . $monthendweek . "/" . $yearendweek . "</h2>            	
            <h2><a href=\"index.php?navig=$navig&amp;period=300&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <img src=\"./images/control_play.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\">
            <img src=\"./images/control_end.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></h2>";
		} else {
			$periodback = $period + 1;
			$periodgo = $period - 1;
			if ($periodgo < 300) {
				$periodgo = 1;
			}
			$value = "           
            <h2>" . $language['display_period'] . "&nbsp;" . $language['days'] . "&nbsp;" . $language['from'] . "&nbsp;" . $daybeginlocal . "/" . $monthbeginlocal . "/" . $yearbeginlocal . "&nbsp;" . $language['to'] . "&nbsp;" . $dayendweek . "/" . $monthendweek . "/" . $yearendweek . "</h2>    
            <h2><a href=\"index.php?navig=$navig&amp;period=$periodback&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <a href=\"index.php?navig=$navig&amp;period=$periodgo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_play_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\"></a>
            <a href=\"index.php?navig=$navig&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_end_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></a></h2>";
		}
	} elseif ($period == 4 || $period == 5) {
		$testdate = 0;
		$value = "
        <h2>" . $language['display_period'] . "&nbsp;" . $daybeginlocal . "/" . $monthbeginlocal . "/" . $yearbeginlocal . "
        ---> " . $daytodaylocal . "/" . $monthtodaylocal . "/" . $yeartodaylocal . "</h2><br><br>";
	}
	$value = $firstline . $value;
	if ($navig == 6) {
		$value = '';
	}
	return $value;
}

//function to count the number of day from today
function nbdayfromtoday($date) {
	$today = strtotime("today");
	$daydate = strtotime($date);
	$delta = $today - $daydate;
	if ($delta <= 0) {
		$nbdayfromtoday = 0;
	} else {
		$nbdayfromtoday = $delta / 86400;
		$nbdayfromtoday = IntVal($nbdayfromtoday);
	}
	return ($nbdayfromtoday);
}

// Function to remove http(s) at beginning of URLs
function strip_protocol($url='')
{
	return preg_replace("/^https?:\/\/(.+)$/i","\\1", $url);
}

//request date calculation according period
//-------period calculation including time shift-----------------------------------------
//day server
$serverday = date("j", strtotime("today"));
$todayserver = date("Y-m-d", strtotime("today"));
$todayserver2 = explode('-', $todayserver);
$yeartodayserver = $todayserver2[0];
$monthtodayserver = $todayserver2[1];
$daytodayserver = $todayserver2[2];
//day local
$localday = date("j", (strtotime("today")) - ($times * 3600));
//test to calculate the reference time
if ($serverday == $localday) {
	$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600)));
} elseif ($serverday < $localday) {
	if ($serverday == 1 && $localday != 2) {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600) - 86400));
	} else {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600) + 86400));
	}
} elseif ($serverday > $localday) {
	if ($localday == 1 && $serverday != 2) {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600) + 86400));
	} else {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600) - 86400));
	}
}
$datelocal = date("Y-m-d H:i:s",(strtotime("today")- ($times * 3600)));
$datelocalcut = explode(' ', $datelocal);
$todaylocal = explode('-', $datelocalcut[0]);
$yeartodaylocal = $todaylocal[0];
$monthtodaylocal = $todaylocal[1];
$daytodaylocal = $todaylocal[2];
if ($period == 0) {
	//case 1 day
	$daterequest = $reftime;
	$daterequestseo = date("Y-m-d", strtotime($reftime));
	$datebeginlocal = date("Y-m-d H:i:s", strtotime($datelocal));
} elseif ($period == 1) {
	//case 1 week
	$testweekday = 0;
	do {
		$dayname = date("l", (strtotime($reftime) - ($times * 3600)));
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
} elseif ($period == 2) {
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
} elseif ($period == 3) {
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
} elseif ($period >= 1000) {
	//case 1 day (back and forward)
	$shiftday = $period - 999;
	$shiftday2 = $period - 1000;
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
} elseif ($period >= 100 && $period < 200) {
	//case 1 month (back and forward)
	$shiftmonth = $period - 99;
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
} elseif ($period >= 200 && $period < 300) {
	//case 1 year (back and forward)
	$shiftyear = $period - 199;
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
} elseif ($period >= 300 && $period < 400) {
	//case 1 week (back and forward)
	$shiftweek = $period - 299;
	$reftime = date("Y-m-d H:i:s", (strtotime($reftime) - (604800 * $shiftweek)));
	$datelocal = date("Y-m-d H:i:s", (strtotime($datelocal) - (604800 * $shiftweek)));
	//case 1 week
	$testweekday = 0;
	do {
		$dayname = date("l", (strtotime($reftime) - ($times * 3600)));
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
} elseif ($period == 4) {
	//case 8 days
	$daterequest = date("Y-m-d H:i:s", (strtotime($reftime) - 604800));
	$daterequestseo = date("Y-m-d", (strtotime($reftime) - 604800));
	$datebeginlocal = date("Y-m-d H:i:s", (strtotime($datelocal) - 604800));
} elseif ($period == 5) {
	//case since installation
	$sql = "SELECT  MIN(date) AS min_date FROM crawlt_visits
    WHERE crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'";
	$requete = db_query($sql, $connexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		$ligne = mysql_fetch_row($requete);
		$reftimestart = $ligne[0];
	} else {
		$reftimestart = $reftime;
	}
	$daterequest = date("Y-m-d H:i:s", strtotime($reftimestart));
	$daterequestseo = date("Y-m-d", strtotime($reftimestart));
	$datebeginlocal = date("Y-m-d H:i:s", (strtotime($daterequest) - ($times * 3600)));
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
