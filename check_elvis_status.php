#!/usr/bin/php
<?php
/* vim: set encoding=utf-8: */
/*
 * Nagios plugin to check Elvis DAM server-status json
 * Copyright (C) 2013 Elan Ruusamäe <glen@delfi.ee>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define('PROGNAME', basename(array_shift($argv), '.php'));
define('LABEL', strtoupper(str_replace('check_', '', PROGNAME)));

// loads from same dir as program
require_once 'utils.php';

function usage() {
	global $default_opt;
	$opt = $default_opt;
    echo "Usage: ", PROGNAME, " [OPTION]...

Check Elvis DAM server-status data
Example: ", PROGNAME ,"

Plugin action specific options:
  -u    URL to Elvis DAM /server-status. Sample: http://USERNAME:PASSWORD@HOSTNAME/dam/controller/admin/server-status
  -m    Message what you are querying
  -e    Expression what to retrieve from json data. this must be valid PHP Expression
  -i    Invert expression, critical and warning must be below the tresholds
  -w    The warning range. default '{$opt['w']}'
  -c    The critical range. default '{$opt['c']}'
  -v    Enable verbose mode.
";
	exit(STATE_UNKNOWN);
}

$default_opt = array(
	'u' => '',
	'm' => '',
	'e' => null,
	'v' => null,
	'w' => 0,
	'c' => 0,
);
$opt = array_merge($default_opt, getopt("u:e:m:w:c:vi"));
$invert = isset($opt['i']);

if (empty($opt['u']) || !isset($opt['e'])) {
	usage();
}

$data = file_get_contents($opt['u']);
if ($data === false) {
	echo "ERROR: Can't fetch '{$opt['u']}'\n";
	exit(STATE_CRITICAL);
}

$json = json_decode($data);
if ($json === null) {
	echo "ERROR: Can't parse json\n";
	exit(STATE_CRITICAL);
}

$eval = 'return $json' . $opt['e'] .';';
if (isset($opt['v'])) {
	echo "EVAL: $eval\n";
}
$res = eval($eval);

if ($res === null) {
	echo LABEL, ": ERROR: {$opt['m']}: Unexpected null\n";
	exit(STATE_UNKNOWN);
} elseif ($res === false) {
	echo LABEL, ": ERROR: {$opt['m']}: parse error: {$opt['e']}\n";
	exit(STATE_UNKNOWN);
} elseif ((!$invert && $res > $opt['c']) || ($invert && $res < $opt['c']))  {
	echo LABEL, ": CRITICAL: {$opt['m']}: $res\n";
	exit(STATE_CRITICAL);
} elseif ((!$invert && $res > $opt['w']) || ($invert && $res < $opt['w'])) {
	echo LABEL, ": WARNING: {$opt['m']}: $res\n";
	exit(STATE_WARNING);
} else {
	echo LABEL, ": OK: {$opt['m']}: $res\n";
	exit(STATE_OK);
}