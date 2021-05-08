#!/usr/bin/env php
<?php
/*!
*! PHP script
*! FILE NAME  : autocampars.php
*! DESCRIPTION: Save/restore/initialize camera parameters
*! AUTHOR     : Elphel, Inc.
*! Copyright (C) 2008 Elphel, Inc
*! -----------------------------------------------------------------------------**
*!
*!  This program is free software: you can redistribute it and/or modify
*!  it under the terms of the GNU General Public License as published by
*!  the Free Software Foundation, either version 3 of the License, or
*!  (at your option) any later version.
*!
*!  This program is distributed in the hope that it will be useful,
*!  but WITHOUT ANY WARRANTY; without even the implied warranty of
*!  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*!  GNU General Public License for more details.
*!
*!  You should have received a copy of the GNU General Public License
*!  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*! -----------------------------------------------------------------------------**
*!
*!
*/
  $Log="";
  $cvslog=<<<CVSLOG
  $Log: autocampars.php,v $
  Revision 1.36  2013/07/24 23:32:15  dzhimiev
  sensor phase init

  Revision 1.35  2012/07/03 20:57:40  elphel
  option for extra sensors in Eyesis4pi

  Revision 1.34  2012/04/12 00:19:37  elphel
  typo fix

  Revision 1.33  2012/04/08 04:10:36  elphel
  rev. 8.2.2 - added temperature measuring daemon, related parameters for logging SFE and system temperatures

  Revision 1.32  2012/03/14 00:05:41  elphel
  8.2.1 - GPS speed, x353.bit file tested on Eyesis

  Revision 1.31  2012/01/12 04:59:04  elphel
  added sensor_phase saving/restoring

  Revision 1.30  2011/12/22 05:42:43  elphel
  comments for IRQ_SMART

  Revision 1.29  2010/12/14 23:14:18  dzhimiev
  1. fixed waiting time for daemons to die
  2. + comments

  Revision 1.28  2010/08/18 02:16:24  elphel
  Improved startup synchronization to the master camera, autocampars.php can now wait for the network to come up.

  Revision 1.27  2010/08/16 17:28:28  elphel
  several updates - fixing usage of sensor board EEPROM, made clean processing of no-sensor case

  Revision 1.26  2010/08/13 19:07:13  elphel
  fixed autoexposure window settings in Eyesis mode

  Revision 1.25  2010/08/12 20:04:36  elphel
  restored initial autoexposure window (was accidentally wrong)

  Revision 1.24  2010/08/12 06:00:16  elphel
  bug fix: was creating temporary files identifying camera type/applications only when creating default /etc/autocampars.xml

  Revision 1.23  2010/08/10 23:16:31  dzhimiev
  1. typo in $eyesis in the end

  Revision 1.22  2010/08/10 21:14:31  elphel
  8.0.8.39 - added EEPROM support for multiplexor and sensor boards, so autocampars.php uses application-specific defaults. Exif Orientation tag support, camera Model reflects application and optional mode (i.e. camera number in Eyesis)

  Revision 1.21  2010/08/07 23:39:16  elphel
  bug fix - wrong default quality for eyesis

  Revision 1.20  2010/08/03 23:36:38  elphel
  more defaults for multisensor

  Revision 1.19  2010/08/01 19:29:24  elphel
  files updated to support coring function for noise filtering

  Revision 1.18  2010/07/04 20:56:15  elphel
  added defaults for /etc/autocampars.xml in  multisensor mode (external trigger set up, MULTI_MODE=1)

  Revision 1.17  2010/06/05 07:15:13  elphel
  Added support and descriptions to the new parameters (most related to the  multisensor operation)

  Revision 1.16  2010/06/01 08:30:36  elphel
  support for the FPGA code 03534022  with optional master time stamp over the inter-camera sync line(s)

  Revision 1.15  2010/05/13 03:39:30  elphel
  8.0.8.12 -  drivers modified for multi-sensor operation

  Revision 1.14  2009/03/14 01:06:43  elphel
  fixed bug for group "init"

  Revision 1.13  2008/12/30 08:03:21  spectr_rain
  cli --load useless to use from another CGI - so replaced with simple GET ?load=filename request

  Revision 1.12  2008/12/30 07:44:25  spectr_rain
  --load cli option for sensor_pattern_test.php - to apply sustom parameters set

  Revision 1.11  2008/12/11 23:00:22  spectr_rain
  parameters for streamer

  Revision 1.10  2008/12/05 03:34:10  elphel
  support for COMPRESSOR_SINGLE and SENSOR_SINGLE

  Revision 1.9  2008/12/04 03:01:23  elphel
  Added initialization for  AUTOEXP_EXP_MAX - maximal autoexposure time in microseconds

  Revision 1.8  2008/12/02 19:14:14  elphel
  removed saving of *SCALE - they now conflict with color gains when restored together (maybe it is just a bug in mt9x001.c)

  Revision 1.7  2008/12/02 00:31:26  elphel
  Added new parameters for white balancing P_WB_MAXWHITE it defines what to consider overexposure so white balancing is disable until the  condition is met

  Revision 1.6  2008/12/01 02:33:06  elphel
  re-enabled white balance

  Revision 1.5  2008/11/30 06:41:43  elphel
  changed default IP back to 192.168.0.9 (temporarily was 192.168.0.7)

  Revision 1.4  2008/11/30 05:34:56  elphel
  temporary disabled white balance - it need to be updated fro the new gain control

  Revision 1.3  2008/11/30 05:01:03  elphel
  Changing gains/scales behavior

  Revision 1.2  2008/11/28 08:17:09  elphel
  keeping Doxygen a little happier

  Revision 1.1.1.1  2008/11/27 20:04:01  elphel


  Revision 1.15  2008/11/27 09:26:43  elphel
  Added support for vignetting correction related parameters

  Revision 1.14  2008/11/24 18:40:21  elphel
  Now init only sets the parameters (after clearing everything) tagged with "init", setting sensor phase in wrong time caused problems

  Revision 1.13  2008/11/24 17:30:19  elphel
  Removed "?new" when submitting the form

  Revision 1.12  2008/11/24 17:04:12  elphel
  Fixed html characters when saving config files

  Revision 1.11  2008/11/22 05:53:57  elphel
  added several more parameters related no ccamftp.php

  Revision 1.10  2008/11/20 23:22:14  elphel
  Added more parameters descriptions

  Revision 1.9  2008/11/20 07:12:57  elphel
  typo

  Revision 1.8  2008/11/20 07:11:56  elphel
  minor message edit

  Revision 1.7  2008/11/20 07:03:12  elphel
  added support for config/script versions checking, parameter descriptions available in parsedit.php (only some parameters have actual description)

  Revision 1.6  2008/11/20 04:01:53  elphel
  more testing

  Revision 1.5  2008/11/20 04:00:33  elphel
  trying CVS log as PHP string

  Revision 1.4  2008/11/18 19:31:04  elphel
  implemented initialization mode (used at boot time)

  Revision 1.3  2008/11/18 07:37:10  elphel
  snapshot

  Revision 1.2  2008/11/17 23:42:59  elphel
  snapshot

  Revision 1.1  2008/11/17 06:41:20  elphel
  8.0.alpha18 - started autocampars - camera parameters save/restore/init manager
CVSLOG;
set_include_path ( get_include_path () . PATH_SEPARATOR . '/www/pages/include' );
require 'i2c_include.php'; // / to read 10359 info //TODO NC393 - update to read sensor port!
//*************** TODO: make Makefile insert revision from bitbake using sed *************
date_default_timezone_set('UTC');
//var_dump($_SERVER);
$GLOBALS['VERSION'] = '___VERSION___';
$GLOBALS['SRCREV'] =  '___SRCREV___';
$GLOBALS['LOG_MAX_ECHO'] = 100; //longest log message to be output to screen, not only logged
$GLOBALS['BOOT_RETRIES'] = 10;  //How many times to read status from two slaves until giving up
$GLOBALS['STOP_AFTER']=array(
        'REBOOT'   =>           true, // this state causes reboot of this camera, or if it is a master - all slaves too
		'BOOT'     =>           true,
		'POWERED'  =>           true,
		'PRE10359' =>           true,
		'BITSTREAM'=>           true,
		'SENSORS_DETECTED'=>    true,
		'SENSORS_SYNCHRONIZED'=>true,
		'PARAMETERS_PRESET'=>   true,
		'SEQUENCERS_ADVANCED'=> true,
		'INITIALIZED'=>         true);

//$p = strpos ( $cvslog, "Revision" );
//$GLOBALS['version'] = strtok ( substr ( $cvslog, $p + strlen ( "Revision" ), 20 ), " " );
$GLOBALS['version'] = $GLOBALS['VERSION'];// echo "<pre>";
$GLOBALS['numBackups'] = 5;
$GLOBALS['parseditPath'] = "/parsedit.php";
$GLOBALS['embedImageScale'] = 0.15;
$GLOBALS['twoColumns'] = false;
$logFilePath = "/var/log/autocampars.log";
$GLOBALS['sysfs_detect_sensors'] = '/sys/devices/soc0/elphel393-detect_sensors@0'; // /sensor00
$GLOBALS['sysfs_all_frames'] =     '/sys/devices/soc0/elphel393-framepars@0/all_frames'; // read all frames, write - reset frames
$GLOBALS['sysfs_frame_seq'] =      '/sys/devices/soc0/elphel393-framepars@0/this_frame'; //[0..3] 0 (write <16 will reset the hardware sequencer)
$GLOBALS['sysfs_chn_en'] =         '/sys/devices/soc0/elphel393-framepars@0/chn_en'; //channels enable (to blocks frame sync pulses)
$GLOBALS['sysfs_i2c_seq'] =        '/sys/devices/soc0/elphel393-sensor-i2c@0/i2c_frame';
$GLOBALS['fpga_version'] =         '/sys/devices/soc0/elphel393-framepars@0/fpga_version';
$GLOBALS['sensor_interface'] =     '/sys/devices/soc0/elphel393-framepars@0/fpga_sensor_interface';
$GLOBALS ['sensor_port'] = -1; // not specified
$GLOBALS['master_port'] = -1;  // Master port is the lowest number of existing ports

if (array_key_exists ( 'sensor_port', $_GET )) {
	$GLOBALS ['sensor_port'] = (intval($_GET ['sensor_port'])) & 3;
}
$GLOBALS['ports'] = array(); // list of enabled ports

$GLOBALS['configDir'] = "/etc/elphel393";
$GLOBALS['configLink'] = "autocampars.xml";
$GLOBALS['configPaths'] = array (
		"autocampars0.xml", // should be a single line for parsedit.php
		"autocampars1.xml",
		"autocampars2.xml",
		"autocampars3.xml"
);
$GLOBALS['backupConfigPaths'] = array (
		"autocampars0.xml.backup",
		"autocampars1.xml.backup",
		"autocampars2.xml.backup",
		"autocampars3.xml.backup"
);
// FIXME: NC393 - use sysfs to read 10359 and sensor configuration
$GLOBALS['m10359Paths'] = array (
		"/var/volatile/state/10359.0",
		"/var/volatile/state/10359.1",
		"/var/volatile/state/10359.2",
		"/var/volatile/state/10359.3"
); // / used to select multisensor defaults

$GLOBALS['camera_state_path'] = "/var/volatile/state/camera";
/*$GLOBALS['sensor_state_files'] = array (
		"/var/volatile/state/ctype.0",
		"/var/volatile/state/ctype.1",
		"/var/volatile/state/ctype.2",
		"/var/volatile/state/ctype.3"
);*/
$GLOBALS['framepars_paths'] = array (
		"/dev/frameparsall0",
		"/dev/frameparsall1",
		"/dev/frameparsall2",
		"/dev/frameparsall3"
);
$GLOBALS['configs'] = array();
$GLOBALS['init'] = false;
$GLOBALS['daemon'] = false;

$GLOBALS['driver_disabler_file'] = $GLOBALS['configDir']."/disable_driver";

log_open();


get_application_mode(); // initializes state file, sets $GLOBALS['init'] and $GLOBALS['camera_state_arr']['state']=='REBOOT' from both command line and $_GET

// Add sensor port if it is not specified but only one exists
if (($GLOBALS ['sensor_port'] <0) && (count($GLOBALS['ports'])==1)){
	$GLOBALS ['sensor_port'] = $GLOBALS['ports'][0];
}

// Processing HTTP GET parameters that do not need any other camera activity
if (isset($_GET['cancel_sync'])){
	exec('overlay_sync 0');
	respond_xml("overlay_sync 0","");
}
if (isset($_GET['log'])){
	print_log();
	exit (0);
}

if (! in_array ( $GLOBALS['camera_state_arr']['state'], array_keys($GLOBALS['STOP_AFTER']) )) {
	respond_xml($GLOBALS['camera_state_arr']['state']," Invalid state, valid are: ". implode(',',array_keys($GLOBALS['STOP_AFTER']))); // will exit with error
}

log_msg("Processing camera state: " . $GLOBALS['camera_state_arr']['state']);

get_sysfs_sensors();

log_msg("Sensors map read from the sysfs: ".str_sensors($GLOBALS['sensors'],1),1);

$GLOBALS['useDefaultPageNumber'] = 15;
$GLOBALS['protectedPage'] = 0; // / change to -1 to enable saving to page 0
///$needDetection = (elphel_get_P_value ( $GLOBALS ['sensor_port'], ELPHEL_SENSOR ) <= 0); // / we need sensor detection to be started before we can read 10359 eeprom and so select default parameters
if (($_GET ['load'] != '') && ($GLOBALS['sensor_port'] >=0) && ($GLOBALS['camera_state_arr']['state'] != 'BOOT')) {
	$load_filename = $_GET ['load'];
	if (is_file ( $load_filename )) {
		// load file
		$GLOBALS['configs'][$GLOBALS['sensor_port']] = parseConfig ( $load_filename );
		// / skip revision check
		// / and simulate loading of the page '0' with GET request
		$_GET ['ignore-revision'] = 'true';
		// and apply page '0'
		$groupMask = 0;
		for($i = 0; $i < count ( $GLOBALS['configs'][$GLOBALS['sensor_port']]['groupNames'] ); $i ++) {
			$name = $GLOBALS['configs'][$GLOBALS['sensor_port']]['groupNames'] [$i];
			$shift = - 1;
			// echo $name."\n";
			if ($name != "") {
				if ($name == 'init')
					$shift = $i;
				if ($name == 'woi')
					$shift = $i;
				if ($name == 'image')
					$shift = $i;
				if ($name == 'whiteBalance')
					$shift = $i;
				if ($name == 'autoexposure')
					$shift = $i;
				if ($name == 'vignet')
					$shift = $i;
			}
			if ($shift != - 1)
				$groupMask |= (1 << (( integer ) $shift));
		}
		log_msg("groupMask=$groupMask");
		// var_dump($GLOBALS['configs'][$GLOBALS['sensor_port']] ['groupNames']);
		$page = setParsFromPage ( $GLOBALS['sensor_port'], 0, $groupMask); // /only init parameters?
		log_close();
		exit ( 0 );
	}
}


//$GLOBALS['init'] = false;
//$GLOBALS['daemon'] = false;
$initPage = $GLOBALS['useDefaultPageNumber'];

// For new - move current configs (if any) out of the way, so the new set will be created
if ((array_key_exists ( 'new', $_GET )) || (in_array ( '--new', $_SERVER ['argv'] ))) {
	foreach ( $GLOBALS['ports'] as $port ) {
		log_msg ("Rotating configs for port $port");
		if (file_exists ($GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$port])) {
			rotateConfig ($port, $GLOBALS['numBackups'] );
		}
	}
}
// TODO: move
/*
foreach ( $_SERVER ['argv'] as $param ) {
	if (substr ( $param, 0, 6 ) == "--init") {
		$GLOBALS['init'] = true;
	}
}
*/

log_msg("get_application_mode()->".get_application_mode());

// === First initialization step that does not need parameter values - programming power, FPGA, detecting connected sensors (will reboot if requested) ===

if ($GLOBALS['init']) { // without --init will not do anything with the hardware
	if (get_application_mode () != 0) { // sets ($GLOBALS ['eyesis_mode']
		log_msg ("Detecting sensors for ".$GLOBALS['camera_state_arr']['application']);
		reset_camera (); // will try to prepare camera for reinitialization, ask to reset+exit if not possible, or just do nothing if initialization is not finished
		detect_camera (); // will reboot if requested
	} else {
		respond_xml ('', 'Should never get here');
	}
}


// === Handle parameters configuration files ===

// Create new configs if they do not exist (after sensor ports are detected)
foreach ( $GLOBALS['ports'] as $port ) {
	if (! file_exists ( $GLOBALS['configDir'].'/'.$GLOBALS['configPaths'] [$port] )) {
		get_sysfs_sensors();

		// if port is "true and not 'none'" then it's multisensor
		$multisensor = ($GLOBALS['port_mux'][$port]&&($GLOBALS['port_mux'][$port]!='none'))?1:0;
		log_msg("$port_mux on port $port = ". $GLOBALS['port_mux'][$port]. ", multisensor=$multisensor");
		$configPath = $GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$port];
		$confFile = fopen ($configPath,"w+");
		$eyesis_mode = $GLOBALS['camera_state_arr']['is_eyesis']?$GLOBALS['camera_state_arr']['mode']:0;
		fwrite ( $confFile, createDefaultConfig ( $GLOBALS['version'], $port, $multisensor, $eyesis_mode ) ); // use multisensor defaults if 10359 +sensors present
		fclose ( $confFile );
		log_msg ( "port $port: autocampars.php created a new configuration file $configPath from defaults." . ($multisensor ? " This port is multiplexed":""). ($GLOBALS['camera_state_arr']['is_eyesis']  ? (' Used Eyesis mode, camera ' . $GLOBALS['camera_state_arr']['mode']? : ' Used multisensor mode.') : '') );\
		createConfigLink($port);
		exec ( 'sync; overlay_sync 1' );
	}
	$GLOBALS ['configs'] [$port] = parseConfig($GLOBALS['configDir'].'/'.$GLOBALS['configPaths'] [$port] );
	log_msg ( "autocampars.php parsed configuration file {$GLOBALS['configDir']}/{$GLOBALS['configPaths'] [$port]}.");
}

// === Launch processing of HTTP GET and POST requests (and exit after), continue only for command-line mode (--init or --daemon) and HTTP with 'init'  ===

log_msg(__LINE__.' $GLOBALS[init]='.$GLOBALS['init'],0);
if (($_SERVER ['REQUEST_METHOD'] == "GET") && ! ($GLOBALS['init'])) { // in init mode should go through init steps and return XML
	processGet ($GLOBALS['sensor_port']);
	log_close();
	exit ( 0 );
} else if (($_SERVER ['REQUEST_METHOD'] == "POST") && !($GLOBALS['init'])) { // in init mode should go through init steps and return XML
	if ($GLOBALS['camera_state_arr']['state'] != 'INITIALIZED')	{
		respond_xml('','Camera is not initialized. Reboot or add "init" argument to HTTP GET request');
	}
	processPost ($GLOBALS['sensor_port']);
	processGet ($GLOBALS['sensor_port']);
	log_close();
	exit ( 0 );
} else {
	$old_versions=array();
	foreach ( $GLOBALS['ports'] as $port ) {
		update_minor_version($port, array_key_exists('--ignore-revision',$_SERVER ['argv'])?1:0);
		$old_versions[]=$GLOBALS['configs'][$port]['version'];
	}
	foreach ( $_SERVER ['argv'] as $param ) {
		if (substr ( $param, 0, 8 ) == "--daemon") { // should have port?
			$GLOBALS['daemon'] = true;
		} else if (substr ( $param, 0, 14 ) == "--sensor_port=") { // should have port?
			$GLOBALS['sensor_port']=intval(substr ( $param, 14, 1 ));
		} else if ($param == '--ignore-revision') {
			//get_port_index($port)
			foreach ( $GLOBALS['ports'] as $port ) {
				$GLOBALS['configs'][$port]['version'] = $GLOBALS['version'];
				log_msg(" --ignore_revision is set");
				saveRotateConfig ($port, $GLOBALS['numBackups'] );
			}
		}
	}
	if (! $GLOBALS['daemon'] && ! $GLOBALS['init']) {
		$configs = "";
		foreach($GLOBALS['configPaths'] as $k){
			$configs .= $GLOBALS['configDir']."/".$k."\n";
		}
		echo <<<USAGE

Usage: {$_SERVER['argv'][0]} [--init] [--new] [--ignore-revision]
Initialize camera using saved parameters (usually at boot),

Other functionality (parameters save/restore is provided when this script is called from the daemon,
in that case command is read from the AUTOCAMPARS_* parameter.

Configuration files:
$configs

USAGE;
	// === end of any command-line mode execution ex cept --init and --daemon
		log_close();
		exit ( 0 );
	}
}


// === Processing --init (both command line and HTTP get) and --daemon


foreach ( $GLOBALS['ports'] as $port ) {
	$old_version = $old_versions[get_port_index($port)];
	if ($GLOBALS['version'] != $old_version) { // issue warning if mismatch, but may continue
		$severity = ($GLOBALS['version'] != $GLOBALS['configs'][$port]['version'])?"ERROR":"WARNING";
		$warn = <<<WARN
$severity! Version numbers of this script and the config file for port $port mismatch:
Script ({$_SERVER['argv'][0]}):{$GLOBALS['version']}.
Config file ({$GLOBALS['configDir']}/{$GLOBALS['configPaths'][$port]}): {$old_version}
WARN;
if ($severity=="WARNING") {
	$warn .= "\nUpdating as '--ignore-revision' is set.";
} else {
	$warn .= <<<WARN
This may (or may not) cause errors. You have several options:
 1 - re-run this script with '--ignore-revision' - the file will have
     new revision number written
 2 - re-run the script with '--new' parameter - the old config file
     will be deleted and the new fresh one created
You may also provide the same parameters in the HTTP GET request, i.e.:
http://192.168.0.9/autocampars.php?new

WARN;
}
		echo $warn;
		log_msg($warn);
		if ($GLOBALS['version'] != $GLOBALS['configs'][$port]['version']){
			respond_xml('','Version mismatch'); // will exit
		}
	}
}

// === Second part of the camera initialization using configured parameters. Sets parameters, synchronizes sub-cameras

if ($GLOBALS['init']) {

	if (get_application_mode()) {
		init_cameras();
	} else {
		respond_xml('', "Can not initialize Unknown camera type.".print_r($GLOBALS['camera_state_arr'],1));
	}

	if ($GLOBALS['camera_state_arr']['state'] != 'INITIALIZED') {
		log_msg ( "Camera failed to initialize, see $logFilePath for detailes" );
		log_msg ( "Camera state = ".$GLOBALS['camera_state_arr']['state'] );
		respond_xml($GLOBALS['camera_state_arr']['state']); // will exit no error
	} else {
		$port_list_string = implode ( ", ", $GLOBALS['ports'] );
		log_msg ( "Sensors on ports: $port_list_string were successfully initialized", 1 );
	}

	if ($GLOBALS ['version'] != $GLOBALS ['configs'] [$port] ['version']) {
		respond_xml($GLOBALS['camera_state_arr']['state'],'revisions differ'); // will exit
	}

	respond_xml($GLOBALS['camera_state_arr']['state']); // will exit no error, providing current state as response (normally it is 'INITIALIZED'
}

if ($GLOBALS['daemon']) {
	processDaemon ( $GLOBALS ['sensor_port'] );
	log_close ();
	exit ( 0 );
}

log_close ();
exit ( 0 );

// ============ Functions =============
/** Update and warn if only minor revision number has changed*/
function update_minor_version($port, $silent = 0) {
	if ($GLOBALS ['configs'] [$port] ['version'] != $GLOBALS ['version']) {
		if (substr ( $GLOBALS ['configs'] [$port] ['version'], 0, strrpos ( $GLOBALS ['configs'] [$port] ['version'], '.' ) ) ==
				substr ( $GLOBALS ['version'], 0, strrpos ( $GLOBALS ['version'], '.' ) )) {
			if (!$silent) {
				log_msg ( "WARNING: updating minor mismatch version for port $port: " .
						$GLOBALS ['configs'] [$port] ['version'] . " to " . $GLOBALS ['version'], 4);
			}
			$GLOBALS ['configs'] [$port] ['version'] = $GLOBALS ['version'];
			$GLOBALS['configs'][$port]['version'] = $GLOBALS['version'];
			saveRotateConfig ($port, $GLOBALS['numBackups'] );
		} else {
			log_msg ( "ERROR: Can not auto-update version for port $port as MAJOR revision differs: " .
					$GLOBALS ['configs'] [$port] ['version'] . " to " . $GLOBALS ['version'], 2);
		}
	}
}

function get_port_index($port){
	for ($i = 0; $i < count($GLOBALS['ports']); $i++) if  ( $GLOBALS['ports'][$i] == $port ) return $i;
	return -1;
}

/** Prepare camera to be re-initialized if 'init' mode is set and stage is later than that
 * For Eyesis it should either reject or just reboot the cameras */
function reset_camera(){
	$multicamera = $GLOBALS['camera_state_arr']['has_slaves'] || $GLOBALS['camera_state_arr']['is_slave'];
	$multichannel = $GLOBALS['port_mux'] || false;
	switch ($GLOBALS['camera_state_arr']['state']){
		case 'INITIALIZED':
			if ( $multicamera || $multichannel) { // no universal way to restart so far (some things are possible), so just treat it as reboot
				respond_xml('', 'Re-initialization of mult-camera or multi-channel system is not implemented. Please use --reboot'); // will exit(1)
			}
			log_msg("Got --init command for already initialized camera, trying to re-initialize",1);
			// See if any daemons are running
			$waitDaemons = 5.0; // / Wait for daemons to stop (when disabled) before resetting frame number.
			// / They should look at thei enable bit periodically and restart if the frame is
			// / the frame is not what they were expecting to be
			if (elphel_get_frame ( $GLOBALS['master_port'] ) > 16) { // =1 after initialization
				log_msg ( "Current frame on master port =" . elphel_get_frame ( $GLOBALS ['master_port'] ) . ", sleeping to give daemons a chance");
				elphel_set_P_value ( $GLOBALS ['master_port'], ELPHEL_COMPRESSOR_RUN, 0x00, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC ); // / turn compressor off
				foreach ($GLOBALS['ports'] as $port) {
					elphel_set_P_value ( $port, ELPHEL_DAEMON_EN,      0x00, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC ); // / turn daemons off
				}
				usleep ( $waitDaemons * 1000000 );
				log_msg("Current frame on master port =" . elphel_get_frame ( $GLOBALS ['master_port'] ) . ", waking up, daemons should be dead already");
			}
			log_msg("after reset - current frame on master port =" . elphel_get_frame ( $GLOBALS ['master_port'] ));

			// Stop compressor if it was running (daemons are already stopped)
			if (elphel_get_P_value ( $port, ELPHEL_COMPRESSOR_RUN ) || elphel_get_P_value ( $port, ELPHEL_DAEMON_EN )) {
				$frame_to_set += ELPHEL_CONST_FRAME_DEAFAULT_AHEAD;
				// Should we stop sequencers?
				elphel_set_P_arr ( $port, array (
						'COMPRESSOR_RUN' => 0,
						'SENSOR_RUN' => 0,
						'DAEMON_EN' => 0
				), $frame_to_set, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC ); // was -1 ("this frame")
				// advance frames, so next settings will be ASAP (sent immediate, not limited to 64?)
				for ($i = 0; $i< ELPHEL_CONST_FRAME_DEAFAULT_AHEAD; $i++){
					// Single trigger
					elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
					usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters? 3 2 2 1 -> 4 3 3 2
				}
			}

			$GLOBALS['camera_state_arr']['state'] ='BITSTREAM';
			write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
			log_msg("Reset camera state to ". $GLOBALS['camera_state_arr']['state']);
			break;
		default:
			log_msg("Got --init command, initialization was not completed, just continue",1);
	}
}



function detect_camera(){
	log_msg("detect_camera()",0);
	$GLOBALS['camera_state_arr']['max_latency'] =    5; // frames to manually advance
	write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
	// 52 is MT9x00x, update later
	$sensor_code = 52;
	log_msg("detect_camera():\n".str_sensors($GLOBALS['sensors']),0);
	log_msg("ports:". implode(", ",$GLOBALS['ports']));
	$multicamera = $GLOBALS['camera_state_arr']['has_slaves'] || $GLOBALS['camera_state_arr']['is_slave'];
	switch ($GLOBALS['camera_state_arr']['state']){
		case 'REBOOT':
			if (!$multicamera) {
				log_msg("rebooting...");
				reboot_me(); // will also generate xml if in http mode and exit anyway
			}
			log_msg("rebooting..."); // multicamera needs to determine and reboot slaves (if it is master
		case 'BOOT':
			log_msg("boot", 0);

			// Apply sensor mask from the application mode, update sysfs
			$sensor_mask = get_application_mode(); // 20 bit - for each channel of each port and port multiplexers (typical Eyesis 0xf7777)
			log_msg(sprintf("Using sensor mask 0x%x", $sensor_mask));

			$needupdate=0;
			for($port = 0; $port < 4; $port++){
				for ($chn = 0; $chn < 4; $chn++){
					if (($sensor_mask & 1)==0){
						if ($GLOBALS['sensors'][$port][$chn] != 'none'){
							$GLOBALS['sensors'][$port][$chn] = 'none';
							$needupdate=1;
						}
					}
					$sensor_mask = $sensor_mask >> 1;
				}
			}
			if ($GLOBALS['port_mux']) { // only if defined
				for($port = 0; $port < 4; $port++) {
					if (($sensor_mask & 1)==0) {
						if ($GLOBALS['port_mux'][$port] != 'none') {
							$GLOBALS['port_mux'][$port] = 'none';
							$needupdate = 1;
						}
					}
					$sensor_mask = $sensor_mask >> 1;
				}
			}
			if ($needupdate)  {
				log_msg("Sensors not disabled by the application mode (10389 EEPROM): ".str_sensors($GLOBALS['sensors'],1),1);
				update_sysfs_sensors();
			}

			// All eyesis cameras - disable fan control to re-use for 10359 power
			if ($GLOBALS['camera_state_arr']['eyesis_power']){ // disable fan control, reuse it to control power
				$rslt=set_eyesis_power_control();
				log_msg("Setting sensors/multiplexers power");
				log_msg("set_eyesis_power_control()=>".print_r($rslt,1),0);
				log_msg("GLOBALS['camera_state_arr']=>".print_r($GLOBALS['camera_state_arr'],1),0);
			}
			// Set IPs of slave cameras (if any)
			if ($multicamera) {
				$GLOBALS['camera_state_arr'] = array_merge ($GLOBALS['camera_state_arr'], multicamera_get_IPs ());
				write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path']);
				log_msg ('Current state: ' . $GLOBALS['camera_state_arr']['state']);
			}

			// Now IPs of slave cameras are known - reboot them (will exit anyway)
			if ($GLOBALS['camera_state_arr']['state'] == 'REBOOT') {
				reboot_all_if_master(); // will exit anyway (do nothing if not master), exit anyway
			}

			// Done if it is slave
			if ($GLOBALS['camera_state_arr']['is_slave']){
				respond_xml('This is a slave camera - waiting for master to contol next steps','',4); // will exit(0)
			}

			// Sanity check
			if (!$GLOBALS['camera_state_arr']['is_master']){
				respond_xml('', 'This is a neither slave, no master camera - waiting for master to contol next steps - aborting'); // will exit(1)
			}

			log_msg("Continue with master camera");
			log_msg("Setting FPGA and sensor power");

			if ($GLOBALS['camera_state_arr']['has_slaves']) {
				$rslt = wait_slaves_boot ($GLOBALS['BOOT_RETRIES']);
				if ($rslt !== true) {
					log_msg ('wait_slaves_boot() => ' . print_r ($rslt, 1));
					log_msg ('If both states are post BOOT - system reboot is required. TODO: Add master-initiated reboot of all cameras (probably by master --init');
					break;
				}
			}

			// Camera-specific powering/loading FPGA bitstream (including 10359)
			if ($GLOBALS['camera_state_arr']['is_eyesis']){

// TODO: convert to curl_multi?
				eyesis_all_power_fpga ('hargs-power_par12');
				eyesis_all_power_fpga ('hargs-power-eyesis');
				eyesis_all_power_fpga ('hargs-post-par12');

				$ips_10359 = array ($GLOBALS['camera_state_arr']['ip_top'],$GLOBALS['camera_state_arr']['ip_middle']);
				log_msg ("Loading 10359 bitstreams");

// Try curl_multi instead
				log_msg ($cmd . 'Trying curl_multi');
				$urls = array ();
				foreach ($ips_10359 as $ip) {
					$urls[] = "http://" . $ip . "/autocampars.php?init_stage=PRE10359&exit_stage=SENSORS_SYNCHRONIZED";
				}
				$curl_data = curl_multi_start ($urls);
				log_msg ($cmd . 'Started curl_multi');
			} else if ($GLOBALS['camera_state_arr']['is_mt9p006']){
				log_msg("Initializing FPGA",3);
				unset ($output);
				exec ( 'autocampars.py localhost py393 hargs', $output, $retval );
				
				$GLOBALS['camera_state_arr']['state'] ='BITSTREAM';
				write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
				
				foreach($output as $k=>$v){
					$output[$k] = str_replace('\n', "\n", $v);
				}
				
				log_msg("COMMAND_OUTPUT for 'autocampars.py localhost py393 hargs-power_par12':\n".
						print_r($output,1)."\ncommand return value=".$retval."\n");

			} else if ($GLOBALS['camera_state_arr']['is_mt9f002']){
				// 2018/02/09: TODO: test
				log_msg("Initializing FPGA for HISPI iface",3);
				unset ($output);
				// /usr/local/verilog/hargs-hispi - does not exist yet
				exec ( 'autocampars.py localhost py393 hargs-hispi', $output, $retval );
				
				$GLOBALS['camera_state_arr']['state'] ='BITSTREAM';
				write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
				
				foreach($output as $k=>$v){
					$output[$k] = str_replace('\n', "\n", $v);
				}
				
				log_msg("COMMAND_OUTPUT for 'autocampars.py localhost py393 hargs-hispi':\n".
						print_r($output,1)."\ncommand return value=".$retval."\n");
				
				// sensor's code from c313a.h
				$sensor_code = 56;

			} else if ($GLOBALS['camera_state_arr']['is_lepton35']){
			    // 2018/02/09: TODO: test
			    log_msg("Initializing FPGA for VOSPI iface",3);
			    unset ($output);
			    // /usr/local/verilog/hargs-hispi - does not exist yet
			    exec ( 'autocampars.py localhost py393 hargs-vospi', $output, $retval );
			    
			    $GLOBALS['camera_state_arr']['state'] ='BITSTREAM';
			    write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
			    
			    foreach($output as $k=>$v){
			        $output[$k] = str_replace('\n', "\n", $v);
			    }
			    
			    log_msg("COMMAND_OUTPUT for 'autocampars.py localhost py393 hargs-vospi':\n".
			        print_r($output,1)."\ncommand return value=".$retval."\n");
			    
			    // sensor's code from c313a.h
			    $sensor_code = 68; // TODO: ************** Import as PHP constant? ***********
			} else if ($GLOBALS['camera_state_arr']['is_boson640']){
			    // 2018/02/09: TODO: test
			    log_msg("Initializing FPGA for Boson640 iface",3);
			    unset ($output);
			    // /usr/local/verilog/hargs-hispi - does not exist yet
			    exec ( 'autocampars.py localhost py393 hargs-boson', $output, $retval );
			    
			    $GLOBALS['camera_state_arr']['state'] ='BITSTREAM';
			    write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
			    
			    foreach($output as $k=>$v){
			        $output[$k] = str_replace('\n', "\n", $v);
			    }
			    
			    log_msg("COMMAND_OUTPUT for 'autocampars.py localhost py393 hargs-boson':\n".
			        print_r($output,1)."\ncommand return value=".$retval."\n");
			    
			    // sensor's code from c313a.h
			    $sensor_code = 0x48; // TODO: ************** Import as PHP constant? ***********
			}else {
				respond_xml ('', 'Do not know how to initialize master camera '
				    .print_r($GLOBALS['camera_state_arr']['is_mt9p006'],1)
				    .print_r($GLOBALS['camera_state_arr']['is_lepton35'],1)
				    );
			}
			// can not exit until joined

        case 'PRE10359': // Entry point for Eyesis slave cameras that have 10359B multiplexers on their ports
        	//log_msg('PRE10359 '.print_r($GLOBALS['port_mux'],1));
        	
        	// previous stage: just fell through - was not break;
        	// TODO: process $retval error case
        	
			if ($GLOBALS['port_mux']) {
				for($port = 0; $port < 4; $port++) {
					if ($GLOBALS['port_mux'][$port] != 'none') {
						unset ($output);
						exec ('cat /usr/local/verilog/x359.bit > /dev/sfpgaconfjtag' . $port, $output, $retval);
						log_msg ('cat /usr/local/verilog/x359.bit > /dev/sfpgaconfjtag' . $port . ' => ' . $retval);
					}
				}
			}

            $GLOBALS['camera_state_arr']['state'] ='BITSTREAM';
            write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
            if ($GLOBALS['camera_state_arr']['exit_stage'] == $GLOBALS['camera_state_arr']['state']){
            	respond_xml($GLOBALS['camera_state_arr']['state']);
            }
            if (!isset ($curl_data)){ // do not break if waiting for slaves
	            if ($GLOBALS['STOP_AFTER'][$GLOBALS['camera_state_arr']['state']]) break;
            }

		case 'BITSTREAM':
            // ***** Sensor detection stage is the same for all sensors so far, can be combined Eyesis/non-Eyesis
			$frame_nums=array(-1,-1,-1,-1);
			
			if (!is_file($GLOBALS['driver_disabler_file'])){
				// Open files for only enabled channels
				foreach ($GLOBALS['ports'] as $port) {
					$f = fopen ( $GLOBALS ['framepars_paths'] [$port], "w+");
					fseek ( $f, ELPHEL_LSEEK_FRAMEPARS_INIT, SEEK_END );
					// This is for 10359 - probably still OK for non-10359
					elphel_set_P_value ( $port, ELPHEL_MULTI_CFG, 1); // cy22393 does not work on 10359. Not enough 3.3V?
					                                                  // now it may be OK (with separate 10359 power)
					elphel_set_P_value ( $port, ELPHEL_SENSOR, 0x00, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );// / will start detection
					$frame_nums[$port]=elphel_get_frame($port);
					fclose($f);
					log_msg("Started detection for sensor port ".$port);
				}
			}else{
				// init framepars anyways - sysfs was updated earlier
				for($port=0;$port<4;$port++){
					$f = fopen($GLOBALS['framepars_paths'][$port],"w+");
					fseek ($f,ELPHEL_LSEEK_FRAMEPARS_INIT,SEEK_END);
					fclose($f);
				}
			}
			
			log_msg("System FPGA version:   ".trim(file_get_contents($GLOBALS['fpga_version'])), 3);
			log_msg("Sensor interface type: ".trim(file_get_contents($GLOBALS['sensor_interface'])), 3);
			log_msg("All frames:\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])),0);
			log_msg('Frames: '. implode(", ",$frame_nums),0);
			
			//$FORCE_SLEEP_TIME = 500000;
			//log_msg("Force sleep  ".(0.000001*$FORCE_SLEEP_TIME)." s");
			//usleep($FORCE_SLEEP_TIME);
			
		    // detection process only removes, not adds channels. So device tree and 10389 should enable maximum possible
			$needupdate=0;
			$SENSOR_NONE = 0xfc; // make a PHP constant?
			// For Boson - wait maximal boot time to get frame sync 250mm cables have sesnor detection pin grounded, so empty cable fakes sensor
			if ($GLOBALS['camera_state_arr']['is_boson640']){
			    $max_boot_time=15; // maximal Boson boot time
			    log_msg('Waiting for Boson sensors to boot for '.$max_boot_time.' seconds');
			    sleep($max_boot_time);
			    foreach ($GLOBALS['ports'] as $port) {
			        if (elphel_get_P_value ( $port, ELPHEL_SENSOR) == $sensor_code){ // unconnected will be remove later, old way
			            if (elphel_get_frame($port) < 1){
    			            log_msg("Sensor port ".$port." did not boot - disabling");
    			            for ($chn =0; $chn <4; $chn++) {
    			                $GLOBALS['sensors'][$port][$chn] = 'none';
    			            }
    			            elphel_set_P_value ( $port, ELPHEL_SENSOR, $SENSOR_NONE, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
    			            $needupdate=1;
    			        } else {
        			        log_msg("Sensor port ".$port." booted, frame = ".elphel_get_frame($port).", frames16: ".trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
    			        }
			        }
			    }
			}
			
			foreach ($GLOBALS['ports'] as $port) {
				if (elphel_get_P_value ( $port, ELPHEL_SENSOR) != $sensor_code){
					log_msg("#### Wrong/missing sensor on port ".$port.", code=".elphel_get_P_value ( $port, ELPHEL_SENSOR).
							' (expected '.$sensor_code.") . Driver reports errors until port is disabled at later stage ####",2);
					for ($chn =0; $chn <4; $chn++) {
						$GLOBALS['sensors'][$port][$chn] = 'none';
					}
					$needupdate=1;
				} else {
					$channel_mask = elphel_get_P_value ( $port, ELPHEL_SENS_AVAIL);

					//log_msg("ELPHEL_SENS_AVAIL = ".$channel_mask,2);
					
					if (!$channel_mask) {
						if ($GLOBALS['port_mux']){
							if ($GLOBALS['port_mux'][$port] != 'none'){
								$GLOBALS['port_mux'][$port] = 'none';
								$needupdate = 1;
							}
						}
						$channel_mask = 1;// no-mux sensor on
					}
					for ($chn =0; $chn <4; $chn++) {
						if (($channel_mask & 1) == 0) {
							if ($GLOBALS['sensors'][$port][$chn] != 'none') {
								$GLOBALS['sensors'][$port][$chn] = 'none';
								$needupdate=1;
							}
						}
						$channel_mask = $channel_mask >> 1;
					}
				}
			}
			
			
			
			
			if ($needupdate)  update_sysfs_sensors();
			log_msg("Detected sensors: ".str_sensors($GLOBALS['sensors'],1),3);

			// Collect results from slave channels that were running in parallel
			$nrep =0;
			
			$GLOBALS['camera_state_arr']['state'] ='SENSORS_DETECTED';
			write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
			log_msg('Reached state: '. $GLOBALS['camera_state_arr']['state']);

			if ($GLOBALS['camera_state_arr']['exit_stage'] == $GLOBALS['camera_state_arr']['state']){
				respond_xml($GLOBALS['camera_state_arr']['state']);
			}

			if (!isset ($curl_data)){ // do not break if waiting for slaves
				if ($GLOBALS['STOP_AFTER'][$GLOBALS['camera_state_arr']['state']]) break;
			}
		case 'SENSORS_DETECTED':
			// Program trigger modes (inactive), stop and reset command sequencers
		    if ($GLOBALS['camera_state_arr']['is_lepton35']){
		        log_msg('Temporary Lepton 3.5 code');
		        foreach ($GLOBALS['ports'] as $port) {
		            if ($port==$GLOBALS['master_port']){
		                elphel_set_P_value ( $port, ELPHEL_TRIG_MASTER, $GLOBALS['master_port'], ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_PERIOD,                       0, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_BITLENGTH,                    0, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_EXTERN_TIMESTAMP,                  1, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_XMIT_TIMESTAMP,                    1, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_OUT,                    0x00000, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_CONDITION,              0x00000, ELPHEL_CONST_FRAME_IMMED);
		            }
		            elphel_set_P_value     ( $port, ELPHEL_TRIG_DELAY,                        0, ELPHEL_CONST_FRAME_IMMED);
		        }
		        usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters
/*
 * Mo snapshot mode in Lepton		        
		        foreach ($GLOBALS['ports'] as $port) {
		            elphel_set_P_value ( $port, ELPHEL_TRIG, ELPHEL_CONST_TRIGMODE_SNAPSHOT, ELPHEL_CONST_FRAME_IMMED);
		        }
		        
		        elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
		        
		        usleep ($GLOBALS['camera_state_arr']['max_frame_time']);
*/		        
		        //Check that now all frame parameters are the same?
		        // reset sequencers
		        log_msg("Before reset sequencers:\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
		        /*
		        for ($port=0; $port < 4; $port++){
		            $f = fopen ( $GLOBALS['sysfs_frame_seq'].$port, 'w' ); fwrite($f,'0',1); fclose ( $f );
		            $f = fopen ( $GLOBALS['sysfs_i2c_seq'].$port, 'w' );   fwrite($f,'3',1); fclose ( $f ); // reset+run (copy frame number from frame_seq)
		            if (!in_array($port, $GLOBALS['ports'])) {
		                log_msg("Disabling sensor port  ".$port);
		                $f = fopen ( $GLOBALS['sysfs_chn_en'].$port, 'w' );    fwrite($f,'0',1); fclose ( $f ); // disable sensor channel
		            }
		        }
		        */
		        log_msg("After reset sequencers (not reset for Lepton):\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
		        // ======= First trigger, frame # = 1 ========.
/*
 * // no trigger mode in Lepton		        
		        elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
		        usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters? // 0 0 0 0 -> 1 1 1 1
		        log_msg("After single trigger:\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
		        //echo "9. frames:\n"; for ($ii=0;$ii<4;$ii++) $frame_nums[$ii]=elphel_get_frame($ii); print_r($frame_nums);
*/
		        $GLOBALS['camera_state_arr']['state'] ='SENSORS_SYNCHRONIZED';
		        write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
		        log_msg('Frames: '. implode(", ",$frame_nums));
		        log_msg('Reached state: '. $GLOBALS['camera_state_arr']['state']);
		        
		        if (isset ($curl_data)){ // wait and collect responses
		            $enable_echo = !array_key_exists('REQUEST_METHOD',$_SERVER);
		            if ($enable_echo) echo colorize("Waiting slaves to finish (number left): ",'YELLOW',0);
		            $results =  curl_multi_finish($curl_data, true, 0, $enable_echo); // Switch true -> false if errors are reported (other output damaged XML)
		            if ($enable_echo) echo colorize(" DONE\n",'GREEN',0);
		            log_msg('curl_multi returned: '.print_r($results,1),0);
		        }
		        if ($GLOBALS['camera_state_arr']['exit_stage'] == $GLOBALS['camera_state_arr']['state']){
		            respond_xml($GLOBALS['camera_state_arr']['state']);
		        }
		        if ($GLOBALS['STOP_AFTER'][$GLOBALS['camera_state_arr']['state']]) break; // will break anyway
		        
		        
		        break;
		    }
		    if ($GLOBALS['camera_state_arr']['is_boson640']){
		        log_msg('Temporary Boson640 code');
		        // Wait for maximal Boson boot time
		        foreach ($GLOBALS['ports'] as $port) { // only for detected ports
		            /*
		            if ($port==$GLOBALS['master_port']){ // no need to setup single-frame mode for Boson
		                log_msg('ELPHEL_TRIG_MASTER: '.$GLOBALS['master_port']);
		                log_msg('ELPHEL_TRIG_MASTER: '.$GLOBALS['master_port']);
		                log_msg('ELPHEL_TRIG_MASTER: '.$GLOBALS['master_port']);
		                log_msg('ELPHEL_TRIG_MASTER: '.$GLOBALS['master_port']);
		                log_msg('ELPHEL_TRIG_MASTER: '.$GLOBALS['master_port']);
		                log_msg('ELPHEL_TRIG_MASTER: '.$GLOBALS['master_port']);
		                log_msg('ELPHEL_TRIG_MASTER: '.$GLOBALS['master_port']);
		                log_msg('ELPHEL_TRIG_MASTER: '.$GLOBALS['master_port']);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_MASTER, $GLOBALS['master_port'], ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_PERIOD,                       0, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_BITLENGTH,                    0, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_EXTERN_TIMESTAMP,                  1, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_XMIT_TIMESTAMP,                    1, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_OUT,                    0x00000, ELPHEL_CONST_FRAME_IMMED);
		                elphel_set_P_value ( $port, ELPHEL_TRIG_CONDITION,              0x00000, ELPHEL_CONST_FRAME_IMMED);
		            }
		            */
		            //elphel_set_P_value     ( $port, ELPHEL_TRIG_DELAY,                        0, ELPHEL_CONST_FRAME_IMMED);
		            // Wait for fully booted of each sensor - they should be booted in parallel
		            for ($i = 0; $i < 1000; $i++){
		                elphel_skip_frames($port,1); // increase to 5-10 at a time?
		                // will just trigger pgm_limit_fps and ELPHEL_BOOTED update
		                elphel_set_P_value ( $port, ELPHEL_BAYER, 0x00, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
		                if (elphel_get_P_value ( $port, ELPHEL_BOOTED) > 0){
		                    log_msg("Break: Sensor port ".$port." booted, frame = ".elphel_get_frame($port).", frames16: "
		                        .trim(file_get_contents($GLOBALS['sysfs_all_frames'])).", i=".$i);
		                    break;
		                }
		            }
		            log_msg("Sensor port ".$port." booted, frame = ".elphel_get_frame($port).", frames16: ".trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
		            log_msg("Sensor port ".$port." ELPHEL_BOOTED = ".elphel_get_P_value ( $port, ELPHEL_BOOTED).", frame = ".elphel_get_frame($port));
		            elphel_skip_frames($port,16); // Let it initialize?
		            log_msg("Sensor port ".$port." Skipped 16 frames, frame = ".elphel_get_frame($port));
		            
		        }
		        usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters
		        log_msg("Before reset sequencers:\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
		        for ($port=0; $port < 4; $port++){
		            $f = fopen ( $GLOBALS['sysfs_frame_seq'].$port, 'w' ); fwrite($f,'0',1); fclose ( $f );
		            $f = fopen ( $GLOBALS['sysfs_i2c_seq'].$port, 'w' );   fwrite($f,'3',1); fclose ( $f ); // reset+run (copy frame number from frame_seq)
		            if (!in_array($port, $GLOBALS['ports'])) {
		                log_msg("Disabling sensor port  ".$port);
		                $f = fopen ( $GLOBALS['sysfs_chn_en'].$port, 'w' );    fwrite($f,'0',1); fclose ( $f ); // disable sensor channel
		            }
		        }
		        log_msg("After reset sequencers (Boson):\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
		        // ======= First trigger, frame # = 1 ========.
		        /*
		         * // no trigger mode in Lepton
		         elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
		         usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters? // 0 0 0 0 -> 1 1 1 1
		         log_msg("After single trigger:\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
		         //echo "9. frames:\n"; for ($ii=0;$ii<4;$ii++) $frame_nums[$ii]=elphel_get_frame($ii); print_r($frame_nums);
		         */
		        log_msg("1.Sensor port ".$port." ELPHEL_BOOTED = ".elphel_get_P_value ( $port, ELPHEL_BOOTED).", frame = ".elphel_get_frame($port));
		        $GLOBALS['camera_state_arr']['state'] ='SENSORS_SYNCHRONIZED';
		        write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
		        log_msg('Frames: '. implode(", ",$frame_nums));
		        log_msg('Reached state: '. $GLOBALS['camera_state_arr']['state']);
		        
		        if (isset ($curl_data)){ // wait and collect responses
		            $enable_echo = !array_key_exists('REQUEST_METHOD',$_SERVER);
		            if ($enable_echo) echo colorize("Waiting slaves to finish (number left): ",'YELLOW',0);
		            $results =  curl_multi_finish($curl_data, true, 0, $enable_echo); // Switch true -> false if errors are reported (other output damaged XML)
		            if ($enable_echo) echo colorize(" DONE\n",'GREEN',0);
		            log_msg('curl_multi returned: '.print_r($results,1),0);
		        }
		        if ($GLOBALS['camera_state_arr']['exit_stage'] == $GLOBALS['camera_state_arr']['state']){
		            respond_xml($GLOBALS['camera_state_arr']['state']);
		        }
		        if ($GLOBALS['STOP_AFTER'][$GLOBALS['camera_state_arr']['state']]) break; // will break anyway
		        log_msg("2.Sensor port ".$port." ELPHEL_BOOTED = ".elphel_get_P_value ( $port, ELPHEL_BOOTED).", frame = ".elphel_get_frame($port));
		        break;
		    }
		    
		    // Other than Lepton/Boson sensors
		    
			foreach ($GLOBALS['ports'] as $port) {
				if ($port==$GLOBALS['master_port']){
					elphel_set_P_value ( $port, ELPHEL_TRIG_MASTER, $GLOBALS['master_port'], ELPHEL_CONST_FRAME_IMMED);
					elphel_set_P_value ( $port, ELPHEL_TRIG_PERIOD,                       0, ELPHEL_CONST_FRAME_IMMED);
					elphel_set_P_value ( $port, ELPHEL_TRIG_BITLENGTH,                    0, ELPHEL_CONST_FRAME_IMMED);
					elphel_set_P_value ( $port, ELPHEL_EXTERN_TIMESTAMP,                  1, ELPHEL_CONST_FRAME_IMMED);
					elphel_set_P_value ( $port, ELPHEL_XMIT_TIMESTAMP,                    1, ELPHEL_CONST_FRAME_IMMED);
					elphel_set_P_value ( $port, ELPHEL_TRIG_OUT,                    0x00000, ELPHEL_CONST_FRAME_IMMED);
					elphel_set_P_value ( $port, ELPHEL_TRIG_CONDITION,              0x00000, ELPHEL_CONST_FRAME_IMMED);
				}
				elphel_set_P_value     ( $port, ELPHEL_TRIG_DELAY,                        0, ELPHEL_CONST_FRAME_IMMED);
			}

			usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters
			
			foreach ($GLOBALS['ports'] as $port) {
				elphel_set_P_value ( $port, ELPHEL_TRIG, ELPHEL_CONST_TRIGMODE_SNAPSHOT, ELPHEL_CONST_FRAME_IMMED);
			}
			elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
			
			usleep ($GLOBALS['camera_state_arr']['max_frame_time']);
			
			//Check that now all frame parameters are the same?
			// reset sequencers
			log_msg("Before reset sequencers:\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
			for ($port=0; $port < 4; $port++){
				$f = fopen ( $GLOBALS['sysfs_frame_seq'].$port, 'w' ); fwrite($f,'0',1); fclose ( $f );
				$f = fopen ( $GLOBALS['sysfs_i2c_seq'].$port, 'w' );   fwrite($f,'3',1); fclose ( $f ); // reset+run (copy frame number from frame_seq)
				if (!in_array($port, $GLOBALS['ports'])) {
					log_msg("Disabling sensor port  ".$port);
					$f = fopen ( $GLOBALS['sysfs_chn_en'].$port, 'w' );    fwrite($f,'0',1); fclose ( $f ); // disable sensor channel
				}
			}
			log_msg("After reset sequencers:\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
			// Single trigger

			// ======= First trigger, frame # = 1 ========.

			elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
			usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters? // 0 0 0 0 -> 1 1 1 1
			log_msg("After single trigger:\n"          .trim(file_get_contents($GLOBALS['sysfs_all_frames'])));
			//echo "9. frames:\n"; for ($ii=0;$ii<4;$ii++) $frame_nums[$ii]=elphel_get_frame($ii); print_r($frame_nums);
			$GLOBALS['camera_state_arr']['state'] ='SENSORS_SYNCHRONIZED';
			write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
			log_msg('Frames: '. implode(", ",$frame_nums));
			log_msg('Reached state: '. $GLOBALS['camera_state_arr']['state']);

			if (isset ($curl_data)){ // wait and collect responses
				$enable_echo = !array_key_exists('REQUEST_METHOD',$_SERVER);
				if ($enable_echo) echo colorize("Waiting slaves to finish (number left): ",'YELLOW',0);
				$results =  curl_multi_finish($curl_data, true, 0, $enable_echo); // Switch true -> false if errors are reported (other output damaged XML)
				if ($enable_echo) echo colorize(" DONE\n",'GREEN',0);
				log_msg('curl_multi returned: '.print_r($results,1),0);
			}
			if ($GLOBALS['camera_state_arr']['exit_stage'] == $GLOBALS['camera_state_arr']['state']){
				respond_xml($GLOBALS['camera_state_arr']['state']);
			}
			if ($GLOBALS['STOP_AFTER'][$GLOBALS['camera_state_arr']['state']]) break; // will break anyway
			break;
		default:
				log_msg("camera_state=".$GLOBALS['camera_state_arr']['state']);
	}
	log_msg("3.Sensor port ".$port." ELPHEL_BOOTED = ".elphel_get_P_value ( $port, ELPHEL_BOOTED).", frame = ".elphel_get_frame($port));
	log_msg("ports:". implode(", ",$GLOBALS['ports']));

}

function str_sensors($sens_arr,$inline=0){
	$sports=array();
	if ($inline) {
		foreach ($sens_arr as $port=>$subchn)	$sports[]=implode(", ",$subchn);
		return '['.implode("],[",$sports).']';
	} else {
		foreach ($sens_arr as $port=>$subchn)	$sports[]=$port.': '.implode(", ",$subchn);
		return implode("\n",$sports);

	}
}


// to re-init daemons should be stopped and other re-init actions performed before  calling
// Sensors and compressors, daemons should be stopped
// State should be reset appropriately too

/** init_cameras() is executed after loading/creating of the autocampars*.xml configuarion files during init */

function init_cameras(){ // $page) { init can only be from default page as page numbers can be different per-port
	// TODO use GSR at startup to keep withstand long exposure?

	log_msg("init_cameras() start: camera_state=".$GLOBALS['camera_state_arr']['state']);
	$mask = 1; // init parameters
	// TODO: Fix TRIG_MASTER early, before anything else
//	$trig_last_names =   array();
	$trig_par_names =    array('TRIG', 'TRIG_CONDITION', 'TRIG_MASTER','TRIG_PERIOD', 'TRIG_OUT');
	$delayed_par_names = array('COMPRESSOR_RUN', 'SENSOR_RUN', 'DAEMON_EN',
			'DAEMON_EN_AUTOEXPOSURE', 'DAEMON_EN_STREAMER',
			'DAEMON_EN_CCAMFTP','DAEMON_EN_CAMOGM', 'DAEMON_EN_TEMPERATURE');
    $broadcast = 0;
    foreach ( $GLOBALS['ports'] as $port ) {
        $broadcast |= (1 << $port);
    }
	$delayed_par_names = array_merge($delayed_par_names, $trig_par_names );

	switch ($GLOBALS['camera_state_arr']['state']){
		case 'SENSORS_SYNCHRONIZED':
			$port_page=array();
			foreach ( $GLOBALS['ports'] as $port ) {
				$port_page[$port] = $GLOBALS ['configs'] [$port] ['defaultPage'];
			}
			$all_port_pars = getMaskedPars($port_page, $mask);
	}

	switch ($GLOBALS['camera_state_arr']['state']){
		case 'SENSORS_SYNCHRONIZED':
			log_msg("start of SENSORS_SYNCHRONIZED:\n".file_get_contents($GLOBALS['sysfs_all_frames'])); // frame = 1

			// TODO: add another multi-camera systems?
			if ($GLOBALS['camera_state_arr']['is_master'] && $GLOBALS['camera_state_arr']['is_eyesis']) {
				$ips_10359 = array ($GLOBALS['camera_state_arr']['ip_top'],$GLOBALS['camera_state_arr']['ip_middle']);
				log_msg ("Launching parameter setup for slave cameras");

				// Try curl_multi instead
				log_msg ($cmd . 'Trying curl_multi',0);
				$urls = array ();
				foreach ($ips_10359 as $ip) {
					$urls[] = "http://" . $ip . "/autocampars.php?init_stage=SENSORS_SYNCHRONIZED&exit_stage=INITIALIZED";
				}
				$curl_data = curl_multi_start ($urls);
				log_msg ($cmd . 'Started curl_multi: '.print_r($urls,1),0);
			}

			// Set most parameters in immediate mode (to protect from i2c 64-command overflow),
			// then others through the sequencers while sensor is stopped (waiting for manual triggers)
			$all_parToSet =  filterParsKeepRemove($all_port_pars, $delayed_par_names, false ); // remove listed parameters
			addGammas ( $all_parToSet); // collect gamma-related parameters for all ports and program tables (common for all ports)
			$all_parLater =  filterParsKeepRemove($all_port_pars, $delayed_par_names, true ); // keep listed parameters
			foreach ( $GLOBALS['ports'] as $port ) {
				if (!isset($all_parLater[$port]['SENSOR_RUN']))     $all_parLater[$port]['SENSOR_RUN'] = 2;
				if (!isset($all_parLater[$port]['COMPRESSOR_RUN'])) $all_parLater[$port]['COMPRESSOR_RUN'] = 2;
				if (!isset($all_parLater[$port]['DAEMON_EN'])){ // set defaults here if none is set
					$all_parLater[$port]['DAEMON_EN']= // set default values
					( 1 << ELPHEL_CONST_DAEMON_BIT_AUTOEXPOSURE) |
					( 1 << ELPHEL_CONST_DAEMON_BIT_STREAMER) |
					( 0 << ELPHEL_CONST_DAEMON_BIT_CCAMFTP) |
					( 0 << ELPHEL_CONST_DAEMON_BIT_CAMOGM) |
					( 0 << ELPHEL_CONST_DAEMON_BIT_AUTOCAMPARS) |
					( 0  << ELPHEL_CONST_DAEMON_BIT_TEMPERATURE);
				}
				$daemon_en=$all_parLater[$port]['DAEMON_EN'];
				if (!isset ($all_parLater[$port]['DAEMON_EN_AUTOEXPOSURE'])) $all_parLater[$port]['DAEMON_EN_AUTOEXPOSURE'] = ($daemon_en >> ELPHEL_CONST_DAEMON_BIT_AUTOEXPOSURE) & 1;
				if (!isset ($all_parLater[$port]['DAEMON_EN_STREAMER']))     $all_parLater[$port]['DAEMON_EN_STREAMER'] =     ($daemon_en >> ELPHEL_CONST_DAEMON_BIT_STREAMER) & 1;
				if (!isset ($all_parLater[$port]['DAEMON_EN_CCAMFTP']))      $all_parLater[$port]['DAEMON_EN_CCAMFTP'] =      ($daemon_en >> ELPHEL_CONST_DAEMON_BIT_CCAMFTP) & 1;
				if (!isset ($all_parLater[$port]['DAEMON_EN_CAMOGM']))       $all_parLater[$port]['DAEMON_EN_CAMOGM'] =       ($daemon_en >> ELPHEL_CONST_DAEMON_BIT_CAMOGM) & 1;
				if (!isset ($all_parLater[$port]['DAEMON_EN_AUTOCAMPARS']))  $all_parLater[$port]['DAEMON_EN_AUTOCAMPARS'] =  ($daemon_en >> ELPHEL_CONST_DAEMON_BIT_AUTOCAMPARS) & 1;
				if (!isset ($all_parLater[$port]['DAEMON_EN_TEMPERATURE']))  $all_parLater[$port]['DAEMON_EN_TEMPERATURE'] =  ($daemon_en >> ELPHEL_CONST_DAEMON_BIT_TEMPERATURE) & 1;
				$all_parLater[$port]['DAEMON_EN']=
				(($all_parLater[$port]['DAEMON_EN_AUTOEXPOSURE'] & 1) << ELPHEL_CONST_DAEMON_BIT_AUTOEXPOSURE) |
				(($all_parLater[$port]['DAEMON_EN_STREAMER'] & 1)     << ELPHEL_CONST_DAEMON_BIT_STREAMER) |
				(($all_parLater[$port]['DAEMON_EN_CCAMFTP'] & 1)      << ELPHEL_CONST_DAEMON_BIT_CCAMFTP) |
				(($all_parLater[$port]['DAEMON_EN_CAMOGM'] & 1)       << ELPHEL_CONST_DAEMON_BIT_CAMOGM) |
				(($all_parLater[$port]['DAEMON_EN_AUTOCAMPARS'] & 1)  << ELPHEL_CONST_DAEMON_BIT_AUTOCAMPARS) |
				(($all_parLater[$port]['DAEMON_EN_TEMPERATURE'] & 1)  << ELPHEL_CONST_DAEMON_BIT_TEMPERATURE);
				unset ($all_parLater[$port]['DAEMON_EN_AUTOEXPOSURE']);
				unset ($all_parLater[$port]['DAEMON_EN_STREAMER']);
				unset ($all_parLater[$port]['DAEMON_EN_CCAMFTP']);
				unset ($all_parLater[$port]['DAEMON_EN_CAMOGM']);
				unset ($all_parLater[$port]['DAEMON_EN_AUTOCAMPARS']);
				unset ($all_parLater[$port]['DAEMON_EN_TEMPERATURE']);
			}


			$all_sensor_run =     filterParsKeepRemove($all_parLater, array('SENSOR_RUN'),     true ); // just per-port SENSOR_RUN value
			$all_compressor_run = filterParsKeepRemove($all_parLater, array('COMPRESSOR_RUN'), true ); // just per-port COMPRESSOR_RUN value
			$all_daemon_en =      filterParsKeepRemove($all_parLater, array('DAEMON_EN'),      true ); // just per-port DAEMON_EN value
			$GLOBALS['trig_pars'] =  filterParsKeepRemove($all_parLater, $trig_par_names, true )[$GLOBALS['master_port']]; // trigger

			foreach ( $GLOBALS['ports'] as $port ) {
				$frame_to_set = elphel_get_frame ( $GLOBALS ['master_port'] ) + 0; // ELPHEL_CONST_FRAME_DEAFAULT_AHEAD;
				log_msg ( "port ".$port. " setting @".$frame_to_set." (now ".elphel_get_frame ( $GLOBALS ['master_port']).
						"): " .print_r($all_parToSet[$port],1));

				//set initial exposure - the correct value will be set at PARAMETERS_PRESET
				log_msg ( "port ".$port. " setting @".$frame_to_set." init exposure: EXPOS= 1000" );
				elphel_set_P_value ( $port, ELPHEL_EXPOS, 1000, $frame_to_set, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );

				// set all in ASAP mode
				elphel_set_P_arr ( $port, $all_parToSet[$port], $frame_to_set, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );


				$frame_to_set += 2; // frame #2
				log_msg ( "port ".$port. " setting @".$frame_to_set." SENSOR_RUN= " . $all_sensor_run[$port]['SENSOR_RUN'],0);
				elphel_set_P_arr ( $port,$all_sensor_run[$port],     $frame_to_set, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );

				$frame_to_set += 1; // 2; // frame #3
				log_msg ( "port ".$port. " setting @".$frame_to_set." COMPRESSOR_RUN= " . $all_compressor_run[$port]['COMPRESSOR_RUN'],0);
				elphel_set_P_arr ( $port, $all_compressor_run[$port], $frame_to_set, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
				if ($GLOBALS['master_port'] == $port) {
				    $GLOBALS['camera_state_arr']['frames_skip'] = $frame_to_set - elphel_get_frame($GLOBALS['master_port']);
				}

				$frame_to_set += 4; // /Frame # 8 Adjust? So streamer will have at least 2 good frames in buffer?
				log_msg ( "port ".$port. " setting @".$frame_to_set." DAEMON_EN= " . $all_daemon_en[$port]['DAEMON_EN'],0);
				elphel_set_P_arr ( $port, $all_daemon_en[$port], $frame_to_set, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );

				// set exposure here
				log_msg ( "port ".$port. " setting @".$frame_to_set." restoring exposure: EXPOS= " . $all_parToSet[$port]['EXPOS'] );
				elphel_set_P_value ( $port, ELPHEL_EXPOS, $all_parToSet[$port]['EXPOS'], $frame_to_set, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );

				if ($GLOBALS['master_port'] == $port) {
					$GLOBALS['camera_state_arr']['frames_skip'] = $frame_to_set - elphel_get_frame ($GLOBALS['master_port']);
					$GLOBALS['camera_state_arr']['frames_skip_more'] = $frame_to_set - elphel_get_frame ($GLOBALS['master_port']) - $GLOBALS['camera_state_arr']['frames_skip'];
				}

			}

			$GLOBALS['camera_state_arr']['state'] ='PARAMETERS_PRESET';
			write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
			log_msg("will need to skip local ".$GLOBALS['camera_state_arr']['frames_skip'].' frames');
			log_msg("Reached state ".$GLOBALS['camera_state_arr']['state']);
			if ($GLOBALS['camera_state_arr']['exit_stage'] == $GLOBALS['camera_state_arr']['state']){
				respond_xml($GLOBALS['camera_state_arr']['state']);
			}
		case 'PARAMETERS_PRESET':
			log_msg("Skipping ".$GLOBALS['camera_state_arr']['frames_skip']." frames");
			log_msg("Frame delay = ".(0.000001*$GLOBALS['camera_state_arr']['max_frame_time'])." s");

			/// Reduce number of cycles to skip (does not need to wait to the very end, only start compression
			/// Limit exposure at startup to prevent trigger problems in the darkness (set exposure last?
			if ( $GLOBALS['camera_state_arr']['no_async']){
			    elphel_skip_frames($GLOBALS['master_port'] , $GLOBALS['camera_state_arr']['frames_skip']); // skip free-running frames
			} else { // manually advance frames
    			for ($i = 0; $i< $GLOBALS['camera_state_arr']['frames_skip']; $i++){
    				// Single trigger
    				log_msg("$i: TRIG_OUT = "      .sprintf('0x%08x',elphel_get_P_value($GLOBALS['master_port'],ELPHEL_TRIG_OUT)),0);
    				log_msg("$i: TRIG_CONDITION = ".sprintf('0x%08x',elphel_get_P_value($GLOBALS['master_port'],ELPHEL_TRIG_CONDITION)),0);
    				elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
    				usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters? 3 2 2 1 -> 4 3 3 2
    				log_msg("Trigger $i:\n".file_get_contents($GLOBALS['sysfs_all_frames']));
    			}
			}
			unset ($GLOBALS['camera_state_arr']['frames_skip']);
			// What else can be done with each camera individually
			// Camera should be left in a free running mode?

			log_msg ("Finalizing, current frames:\n".file_get_contents($GLOBALS['sysfs_all_frames']));
			log_msg ("Remaining trigger parameters: ".print_r($GLOBALS['trig_pars'],1));


			if (array_key_exists('TRIG',$GLOBALS['trig_pars']) && ($GLOBALS['trig_pars']['TRIG'] === 0)){
			    // set free running mode.
			    if ( $GLOBALS['camera_state_arr']['no_async']){
			     // do nothing?    
			    } else {
///			        elphel_set_P_arr ( $GLOBALS['master_port'],array('TRIG' => 0));
			        elphel_set_P_arr ( $GLOBALS['master_port'],array('TRIG' => 0), ELPHEL_CONST_FRAME_ASAP,0,$broadcast); // all ports (before was onlymaster?)
			        for ($i = 0; $i<= ELPHEL_CONST_FRAME_DEAFAULT_AHEAD; $i++){
    					elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
    					usleep ($GLOBALS['camera_state_arr']['max_frame_time']); // > 1 frame, so all channels will get trigger parameters? 3 2 2 1 -> 4 3 3 2
    					log_msg("2.Trigger $i:\n".file_get_contents($GLOBALS['sysfs_all_frames']));
    				}
			    }
			    elphel_set_P_arr ($GLOBALS['master_port'], $GLOBALS['trig_pars']); // set other parameters - they will not take effect immediately
			    log_msg("Started camera in free running mode",3);
				log_msg("Reached state ".$GLOBALS['camera_state_arr']['state']);

			} else if (!array_key_exists('TRIG_CONDITION',$GLOBALS['trig_pars']) || !$GLOBALS['trig_pars']['TRIG_CONDITION'] || !isset ($curl_data)){
			    // selff-triggered /single camera
			    log_msg ("array_key_exists('TRIG_CONDITION', GLOBALS['trig_pars'] = ".array_key_exists('TRIG_CONDITION',$GLOBALS['trig_pars']),0);
			    log_msg ('GLOBALS[trig_pars][TRIG_CONDITION] = '.$GLOBALS['trig_pars']['TRIG_CONDITION'],0);

			    // self-triggered mode
			    $trig_condition = (array_key_exists('TRIG_CONDITION',$GLOBALS['trig_pars']))?$GLOBALS['trig_pars']['TRIG_CONDITION']:0;
			    $trig =           (array_key_exists('TRIG',          $GLOBALS['trig_pars']))?$GLOBALS['trig_pars']['TRIG']:0; // always exists
			    unset            ($GLOBALS['trig_pars']['TRIG_CONDITION']);
			    unset            ($GLOBALS['trig_pars']['TRIG']);
			    unset($trig_period);
			    if (array_key_exists('TRIG_PERIOD',$GLOBALS['trig_pars']) && ($GLOBALS['trig_pars']['TRIG_PERIOD'] > 255)){
			        $trig_period = $GLOBALS['trig_pars']['TRIG_PERIOD'];
			        unset($GLOBALS['trig_pars']['TRIG_PERIOD']);
			    }
			    elphel_set_P_arr ($GLOBALS['master_port'], $GLOBALS['trig_pars']); // set other parameters - they will not take effect immediately
			    //usleep ($GLOBALS['camera_state_arr']['max_frame_time']);
			    if ($trig_period){
			        // The line below applies the parameter in a normal way - otherwise, if unlucky, TRIG_PERIOD can jump
			        // between 1 and $trig_period.
			        //elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  $trig_period, 0);
			        //					elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  $trig_period);
			        // Set parameter immediately
			        //				    log_msg("pre3.Trigger $i:\n".file_get_contents($GLOBALS['sysfs_all_frames']));
			        elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  $trig_period, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
			        elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_CONDITION,   $trig_condition);
			        if ( $GLOBALS['camera_state_arr']['no_async']){ // turn on triggered mode on all ports (they were off)
			            elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG, $trig, ELPHEL_CONST_FRAME_ASAP, 0, $broadcast);
			            $f = fopen ( $GLOBALS['sysfs_all_frames'].$port, 'w' ); fwrite($f,'-1'); fclose ( $f ); // skip frame and reset
			            elphel_skip_frames($GLOBALS['master_port'] , 16); // skip triggered frames 
			        }
			        log_msg("Started camera in periodic self-triggered mode, period = ".(0.00000001*$trig_period)." s, trig_condition = ".dechex($trig_condition));
			    } else { // manually advance frames
			        if (!$GLOBALS['camera_state_arr']['frames_skip_more'] >  ELPHEL_CONST_FRAME_DEAFAULT_AHEAD){
			            $GLOBALS['camera_state_arr']['frames_skip_more'] =  ELPHEL_CONST_FRAME_DEAFAULT_AHEAD + 1;
			        }
			        log_msg("Skipping  ".$GLOBALS['camera_state_arr']['frames_skip_more']." more frames");
			        if ( $GLOBALS['camera_state_arr']['no_async']){ // turn on triggered mode on all ports (they were off)
			            elphel_skip_frames($GLOBALS['master_port'] , $GLOBALS['camera_state_arr']['frames_skip_more']); // skip triggered frames
			        } else {
			            for ($i = 0; $i<= $GLOBALS['camera_state_arr']['frames_skip_more']; $i++){
    			            elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
    			            usleep ($GLOBALS['camera_state_arr']['max_frame_time']);
    			            log_msg("3.Trigger $i:\n".file_get_contents($GLOBALS['sysfs_all_frames']));
    			        }
			        }
			        elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_CONDITION,   $trig_condition, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
			        if ( $GLOBALS['camera_state_arr']['no_async']){ // turn on triggered mode on all ports (they were off)
			            elphel_skip_frames($GLOBALS['master_port'] , ELPHEL_CONST_FRAME_DEAFAULT_AHEAD); // skip triggered frames
			            // turn on external triggered mode
			            elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG, $trig, ELPHEL_CONST_FRAME_ASAP, 0, $broadcast);
			            $f = fopen ( $GLOBALS['sysfs_all_frames'].$port, 'w' ); fwrite($f,'-1'); fclose ( $f ); // skip frame and reset
			            // elphel_skip_frames($GLOBALS['master_port'] , 16); // skip triggered frames
			        }
			    }
			    log_msg("trig_condition=".$trig_condition);
			    log_msg("last3 frames:\n".file_get_contents($GLOBALS['sysfs_all_frames']));
			    log_msg("GLOBALS['trig_pars']:\n".print_r($GLOBALS['trig_pars'],1));
			    log_msg("Reached state ".$GLOBALS['camera_state_arr']['state']);
			} else {
				// Triggered input mode, not sure where input comes from
				// multicamera (now just eyesis) system, triggered by some other camera
				// not updated for Bosons. Is it needed?
				if (isset ($curl_data)) { // wait and collect responses (master camera?)
					unset($trig_out);
					unset($trig_period);
					if (array_key_exists('TRIG_OUT',$GLOBALS['trig_pars']) && $GLOBALS['trig_pars']['TRIG_OUT']){
						$trig_out = $GLOBALS['trig_pars']['TRIG_OUT'];
						unset($GLOBALS['trig_pars']['TRIG_OUT']);
					}
					if (array_key_exists('TRIG_PERIOD',$GLOBALS['trig_pars']) && ($GLOBALS['trig_pars']['TRIG_PERIOD'] > 255)){
						$trig_period = $GLOBALS['trig_pars']['TRIG_PERIOD'];
						unset($GLOBALS['trig_pars']['TRIG_PERIOD']);
					}
					log_msg("trig_out = $trig_out");
					log_msg("trig_period = $trig_period");
					log_msg("remaining  GLOBALS[trig_pars] = ".print_r($GLOBALS['trig_pars'],1));
	
					elphel_set_P_arr ($GLOBALS['master_port'], $GLOBALS['trig_pars']); // without TRIG_PERIOD and TRIG_OUT
					for ($i = 0; $i<= ELPHEL_CONST_FRAME_DEAFAULT_AHEAD; $i++){
						elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
						usleep ($GLOBALS['camera_state_arr']['max_frame_time']);
						log_msg("4.Trigger (should stop) $i:\n".file_get_contents($GLOBALS['sysfs_all_frames']));
					}

					log_msg("  trig_out = $trig_out");
					log_msg("  trig_period = $trig_period");
					log_msg("Remaining  GLOBALS[trig_pars] = ".print_r($GLOBALS['trig_pars'],1));
					$enable_echo = !array_key_exists ('REQUEST_METHOD', $_SERVER);
					if ($enable_echo) echo colorize("Waiting slaves to finish initialization (number left): ",'YELLOW',0);
					$results = curl_multi_finish ($curl_data, true, 0, $enable_echo);
					if ($enable_echo) echo colorize(" DONE\n",'GREEN',0);
					////$results = curl_multi_finish ($curl_data, false, 0, $enable_echo);
					log_msg ('curl_multi returned: ' . print_r ($results, 1));
					unset ($curl_data);
					if ($trig_out) {//	now it is OK to set it in immediate mode
						log_msg (sprintf('Setting TRIG_OUT= 0x%08x',$trig_out));
// Now elphel_set_P_arr should work with ELPHEL_CONST_FRAME_IMMED, before it was only implemented for elphel_set_P_value
//						elphel_set_P_arr ($GLOBALS['master_port'], array('TRIG_OUT' => $trig_out), ELPHEL_CONST_FRAME_IMMED);
						elphel_set_P_value ($GLOBALS['master_port'], ELPHEL_TRIG_OUT, $trig_out, ELPHEL_CONST_FRAME_IMMED);
						usleep ($GLOBALS['camera_state_arr']['max_frame_time']);
					}
					if ($trig_period){
						elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  $trig_period, ELPHEL_CONST_FRAME_IMMED);
						log_msg("Started camera in periodic mode, period = ".(0.00000001*$trig_period)." s",3);
					} else { // manually advance frames
						if (!$GLOBALS['camera_state_arr']['frames_skip_more'] >  ELPHEL_CONST_FRAME_DEAFAULT_AHEAD){
							$GLOBALS['camera_state_arr']['frames_skip_more'] =  ELPHEL_CONST_FRAME_DEAFAULT_AHEAD + 1;
						}
						log_msg("Skipping  ".$GLOBALS['camera_state_arr']['frames_skip_more']." more frames");
						for ($i = 0; $i<= $GLOBALS['camera_state_arr']['frames_skip_more']; $i++){
							elphel_set_P_value ( $GLOBALS['master_port'], ELPHEL_TRIG_PERIOD,  1, ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
							usleep ($GLOBALS['camera_state_arr']['max_frame_time']);
							log_msg("5.Trigger $i:\n".file_get_contents($GLOBALS['sysfs_all_frames']));
						}
					}

				}else{
					$trig_condition = $GLOBALS['trig_pars']['TRIG_CONDITION'];
					unset($GLOBALS['trig_pars']['TRIG_CONDITION']);
					//$frame_to_set = elphel_get_frame($GLOBALS['master_port']) + ELPHEL_CONST_FRAME_DEAFAULT_AHEAD;
					//elphel_set_P_arr  ($GLOBALS['master_port'], $GLOBALS['trig_pars'], $frame_to_set);
					//elphel_set_P_value($GLOBALS['master_port'], ELPHEL_TRIG_CONDITION, $trig_condition, $frame_to_set+3);
					elphel_set_P_arr  ($GLOBALS['master_port'], $GLOBALS['trig_pars'], ELPHEL_CONST_FRAME_IMMED, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
					elphel_set_P_value($GLOBALS['master_port'], ELPHEL_TRIG_CONDITION, $trig_condition);
					
					log_msg ('Independently started camera (3). If not master camera - it will wait for external triggers');
				}
			}

			$GLOBALS['camera_state_arr']['state'] = 'INITIALIZED'; // conditionally
			write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path']);
			log_msg ("Reached state " . $GLOBALS['camera_state_arr']['state']);
			if ($GLOBALS['camera_state_arr']['exit_stage'] == $GLOBALS['camera_state_arr']['state']) {
				respond_xml ($GLOBALS['camera_state_arr']['state']);
			}
			if ($GLOBALS['STOP_AFTER'][$GLOBALS['camera_state_arr']['state']]) break;

		case 'SEQUENCERS_ADVANCED': // now set periodic trigger (if needed) in immediate mode - FPGA period will be overwritten
			break;
			$GLOBALS['camera_state_arr']['state'] ='INITIALIZED';
			write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
			log_msg("Reached state ".$GLOBALS['camera_state_arr']['state']);
			if ($GLOBALS['STOP_AFTER'][$GLOBALS['camera_state_arr']['state']]) break;
		default:
			log_msg("init_cameras(): camera_state=".$GLOBALS['camera_state_arr']['state']);
	}

}

/** Read sensor types per port, per subchannel from sysfs*/
function get_sysfs_sensors()
{
	$GLOBALS['ports'] =  array(); // list of enabled ports
	$GLOBALS['sensors']= array('none','none','none','none');
	$GLOBALS['port_mux']=array('none','none','none','none');
	$use_mux=false;
	for ($port = 0; $port < 4; $port++){
		$GLOBALS['sensors'][$port] = array('none','none','none','none');
		for ($chn = 0; $chn < 4; $chn++){
			$GLOBALS['sensors'][$port][$chn]=trim(file_get_contents( $GLOBALS['sysfs_detect_sensors'] . '/sensor' . $port . $chn));
		}
		$GLOBALS['port_mux'][$port]=trim(file_get_contents( $GLOBALS['sysfs_detect_sensors'] . '/port_mux' . $port));
		if ($GLOBALS['port_mux'][$port] != 'none') $use_mux=true;
	}
	if (!$use_mux){
		unset($GLOBALS['port_mux']);
	}
	//  Create list of active ports
	for ($port=0; $port < 4; $port++) { // if at least one subchannel active - enable port
		for ($chn = 0; $chn < 4; $chn++) if ($GLOBALS['sensors'][$port][$chn] != 'none') {
			$GLOBALS['ports'][] = $port;
			break;
		}
	}
	// Master port is the lowest number of existing ports
	if ($GLOBALS['ports']) $GLOBALS['master_port'] = $GLOBALS['ports'][0]; // first port
	else  $GLOBALS['master_port'] = -1; // no sensor ports at all
	log_msg ("Got sensor map from sysfs: \n".str_sensors($GLOBALS['sensors']));
	
	if ($GLOBALS['port_mux']){
		log_msg ("Got port multiplexer map from sysfs: ".implode(',', $GLOBALS['port_mux']));
	}
	log_msg ("Active sensor ports: ".implode(',', $GLOBALS['ports']).' master_port = '. $GLOBALS['master_port']);

//	for ($port=0; $port < 4; $port++) if ($GLOBALS['sensors'][$port][0] != 'none') {
//		$GLOBALS['master_port'] = $port;
//		break;
//	}
}

/** Update sensor types per port, per subchannel to sysfs (after detection or 10389 EEPROM mask */
function update_sysfs_sensors()
{
	$GLOBALS['ports'] = array(); // list of enabled ports
	for ($port=0; $port < 4; $port++){
		log_msg('port='.$port.' GLOBALS[sensors][port][0]='.$GLOBALS['sensors'][$port][0]);
		if ($GLOBALS['sensors'][$port][0] != 'none') $GLOBALS['ports'][] = $port;
	}

	for ($port=0; $port < 4; $port++){
		for ($chn = 0; $chn < 4; $chn++){
			$f = fopen ( $GLOBALS['sysfs_detect_sensors'] . '/sensor' . $port . $chn, 'w' );
			fprintf($f,"%s",$GLOBALS['sensors'][$port][$chn]);
			fclose ( $f );
		}
	}
	if ($GLOBALS['port_mux']){
		for ($port=0; $port < 4; $port++){
			$f = fopen ( $GLOBALS['sysfs_detect_sensors'] . '/port_mux' . $port, 'w' );
			fprintf($f,"%s",$GLOBALS['port_mux'][$port]);
			fclose ( $f );
		}
	}
	// re-read from sysfs, re-set $GLOBALS['ports'] and $GLOBALS['master_port']
	get_sysfs_sensors();
	log_msg ("Updated sysfs sensor map");
}

/** Disable sensors per port, per subchannel to sysfs */
function disable_sysfs_sensors(){	
	for ($port=0; $port < 4; $port++){
		for ($chn = 0; $chn < 4; $chn++){
			$f = fopen ( $GLOBALS['sysfs_detect_sensors'] . '/sensor' . $port . $chn, 'w' );
			fprintf($f,"%s",'none');
			fclose ( $f );
		}
	}
	for ($port=0; $port < 4; $port++){
		$f = fopen ( $GLOBALS['sysfs_detect_sensors'] . '/port_mux' . $port, 'w' );
		fprintf($f,"%s",'none');
		fclose ( $f );
	}
	// re-read from sysfs, re-set $GLOBALS['ports'] and $GLOBALS['master_port']
	log_msg ("Disabled sensors in sysfs sensor map");
}

function colorize($string, $color, $bold) {
	$color = strtoupper($color);
	$attr = array();
	switch ($color) {
		case 'RED':     $attr[]='31'; break;
		case'GREEN':    $attr[]='32'; break;
		case 'YELLOW':  $attr[]='33'; break;
		case 'BLUE':	$attr[]='34'; break;
		case 'MAGENTA':	$attr[]='35'; break;
		case 'CYAN':	$attr[]='36'; break;
		case 'GRAY':	$attr[]='37'; break;
	}
	if ($bold)	$attr[] = '1';
	return sprintf("\x1b[%sm%s\x1b[0m",implode(';',$attr), $string);
}


function get_uptime(){
	exec('cat /proc/uptime',$output,$retval);
	return floatval(explode(" ",trim($output[0]))[0]);
}
//printf("%08.2f\n",$f);
function log_open(){
	$GLOBALS['logFile'] = fopen ( $GLOBALS['logFilePath'], "a" );
}

/** Log message and optionally print to console */
function log_msg($msg,         ///< message to print
		         $mode = -1)   ///< -1 - print only short messages, 0 - never print, 1 - always print, 2 print in bold red (error), 3 - bold white, 4 - bold yellow (warning)
{
// do not output log when in HTTP request mode
	$ut=get_uptime();
	if (($mode != 0) && !array_key_exists ('REQUEST_METHOD', $_SERVER) && (($mode > 0) || (strlen ($msg) < $GLOBALS['LOG_MAX_ECHO']))) {
		switch ($mode) {
			case 2:
				$emsg = colorize($msg,'RED',1); // bold red
				break;
			case 3:
				$emsg = colorize($msg,'',1);    // bold white
				break;
			case 4 :
				$emsg = colorize($msg, 'YELLOW', 1); // bold white
				break;
			default:
				$emsg = $msg;
		}
		printf(colorize(sprintf("[%8.2f] autocampars: ",$ut),"GREEN",0).$emsg."\n");
	}
	fwrite ($GLOBALS['logFile'], sprintf("%08.2f autocampars: %s\n",$ut,$msg)); // date ("F j, Y, G:i:s")
}
function log_error($msg) {
	log_msg ($msg, 2);
	log_close ();
	exit (1);
}
function log_close() {
	log_msg ("Log file saved as " . $GLOBALS['logFilePath'], 3);
	log_msg ("----------------------------------------------", 0);
	log_msg("!.Sensor port ".$port." ELPHEL_BOOTED = ".elphel_get_P_value ( $port, ELPHEL_BOOTED).", frame = ".elphel_get_frame($port));
	
	fclose ($GLOBALS['logFile']);
	unset ($GLOBALS['logFile']); // to catch errors
}

/** Closes log file, optianally responxds with XML (if in HTTP mode), exits with 0/1 */
function respond_xml($result,$error=null,$color_mode = 3){ // default white bold
	if (array_key_exists('REQUEST_METHOD',$_SERVER)){
		$xml = new SimpleXMLElement("<?xml version='1.0'  standalone='yes'?><autocampars/>");
		if ($result !== ""){ //"" will not be loged/output
			if (is_string($result) && ((count($result)==0) || ($result[0] != '"'))){
				$result = '"'.$result.'"';
			}
			$xml->addChild ('result',$result);
		}
		if ($error){
			$xml->addChild ('error','"'.$error.'"');
		}
		$rslt=$xml->asXML();
		header("Content-Type: text/xml");
		header("Content-Length: ".strlen($rslt)."\n");
		header("Pragma: no-cache\n");
		printf($rslt);
	}
	if ($result !== ""){ //"" will not be loged/output
		log_msg(''.$result,$color_mode);
	}
	if ($error){
		log_error($error);
	} else {
		log_close();
		exit (0);
	}
}

/*

 * #define AUTOCAMPARS_CMD_RESTORE 1 /// restore specified groups of parameters from the specified page
 * #define AUTOCAMPARS_CMD_SAVE 2 /// save all current parameters to the specified group (page 0 is write-protected)
 * #define AUTOCAMPARS_CMD_DFLT 3 /// make selected page the default one (used at startup), page 0 OK
 * #define AUTOCAMPARS_CMD_SAVEDFLT 4 /// save all current parameters to the specified group (page 0 is write-protected) and make it default (used at startup)
 * #define AUTOCAMPARS_CMD_INIT 5 /// reset sensor/sequencers, restore all parameters from the specified page
 */


function get_application_mode() {
	if (!file_exists($GLOBALS['camera_state_path'])) {
		log_msg("initializing state file ".$GLOBALS['camera_state_path']);
		$GLOBALS['camera_state_arr'] = array (
				'exit_stage' =>  '',
				'state' =>       'BOOT',
				'rev10389' =>       '',
				'application' => '',
				'mode' =>        0
		);
			// Access the 10389 EEPROM
		$EEPROM_chn = 2;
		$EEPROM_bus0 = 5;
		// This currently is slow
		$info_10389 = trim( i2c_read256b ( 0xa0 + ($EEPROM_chn * 2), $EEPROM_bus0 ) );
		if ($info_10389!=""){
			$xml = simplexml_load_string ( $info_10389 ); // read contents of
		}else{
			log_msg("10389 board is not present, loading /etc/elphel393/default_10389.xml");
			$xml = simplexml_load_string ( file_get_contents("/etc/elphel393/default_10389.xml") );
		}

		if ($xml === false) {
			log_msg("10389 board not present");
		} else {
			
			if (is_file($GLOBALS['driver_disabler_file'])){
				log_msg("Ports will be disabled - found ".$GLOBALS['driver_disabler_file']." flag file",4);
				//disable ports via sysfs
				disable_sysfs_sensors();
			}
			
			$mode = intval($xml->mode);
			
			log_msg ( 'Application - ' . (( string ) $xml->app) . ', mode: ' . $mode . "\n" , 3);
			$GLOBALS ['camera_state_arr'] ['rev10389'] = ''.$xml->rev;
			if ((( string ) $xml->app) != '') {
				$GLOBALS ['camera_state_arr'] ['application'] = ''.$xml->app;
				$GLOBALS ['camera_state_arr'] ['mode'] = $mode;
			}
		}
		write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
	} else {
		$GLOBALS['camera_state_arr'] = parse_ini_file ( $GLOBALS['camera_state_path'] );
		log_msg("Parsed existing ini file",0);
	}

	// Is it reboot or specific init stage?
	$need_update = false;
	foreach ($_SERVER['argv'] as $param) {
		if (substr ($param, 0, 8) == "--reboot") {
			$GLOBALS['camera_state_arr']['state'] = 'REBOOT';
			$GLOBALS['init'] = true;
			$need_update = true;
		} else if (substr ($param, 0, 6) == "--init") {
			$GLOBALS['init'] = true;
			if (substr ($param, 0, 13) == "--init_stage=") {
				$GLOBALS['camera_state_arr']['state'] = substr ($param, 13);
			}
			$need_update = true;
		} else if (substr ($param, 0, 13) == "--exit_stage=") {
			$GLOBALS['camera_state_arr']['exit_stage'] = substr ($param, 13);
			$need_update = true;
		}

	}

	if (array_key_exists ( 'reboot', $_GET )){
		$GLOBALS['camera_state_arr']['state'] = 'REBOOT';
		$GLOBALS['init'] = true;
		$need_update = true;
	}

	if (array_key_exists ( 'init', $_GET )){ // try it
		$GLOBALS['init'] = true;
		$need_update = true;
	}

	if (array_key_exists ( 'init_stage', $_GET )){
		$GLOBALS['camera_state_arr']['state'] = $_GET['init_stage'];
		$GLOBALS['init'] = true;
		$need_update = true;
	}

	if (array_key_exists ( 'exit_stage', $_GET )){
		$GLOBALS['camera_state_arr']['exit_stage'] = $_GET['exit_stage'];
		$GLOBALS['init'] = true;
		$need_update = true;
	}

	if ($need_update) {
		write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
	}

	// Select by camera type, return sensor mask ('mode')
	$GLOBALS['camera_state_arr']['no_async'] = false;
	switch ($GLOBALS['camera_state_arr']['application']){
		case 'MT9P006':
			return get_mt9p006_mode();
		case 'MT9F002':
			// 2018/02/09: TODO: test
			return get_mt9f002_mode();
		case 'Eyesis4pi393':
			return get_eyesis_mode();
		case 'LEPTON35':
		    $GLOBALS['camera_state_arr']['no_async'] = true;
		    return get_lepton35_mode();
		case 'BOSON640':
		    $GLOBALS['camera_state_arr']['no_async'] = true;
		    return get_boson640_mode();
		    
		default:
			respond_xml('','Unknown camera type, '.print_r($GLOBALS['camera_state_arr'],1));
	}
	// 2018/02/09: add other sensors as well?
	if ($GLOBALS['camera_state_arr']['application'] == 'MT9P006')
		return $GLOBALS['camera_state_arr'];
}

function update_max_frame_time(){
	//updatemax frame time here
	$cnt_max = 0;
	foreach ($GLOBALS['ports'] as $port) {
		$cnt = 0;
		for ($chn=0; $chn<4;$chn++) {
			if ($GLOBALS['sensors'][$port][$chn]!='none') {
				$cnt++;
			}
		}
		if ($cnt>$cnt_max) {
			$cnt_max = $cnt;
		}
	}
	$GLOBALS['camera_state_arr']['max_frame_time'] = $cnt_max*100000; // usec, should exceed longest initial free frame period
	//log_msg("Updated max frame time to ".(0.000001*$GLOBALS['camera_state_arr']['max_frame_time'])." s");
}

function get_mt9p006_mode() {
	$mode = intval($GLOBALS['camera_state_arr']['mode']);
	// Use same 16-bit mask as in Eyesis?
	// 1111 -> 1000100010001
	//
	if (($mode&0xf0000)==0){
		$mode = ($mode & 1) | (($mode & 2) << 3) | (($mode & 4) << 6) | (($mode & 8) << 9);
	}
	
	$GLOBALS['STOP_AFTER']=array_merge($GLOBALS['STOP_AFTER'],array( // overwrite specified fields, keep others
			'BOOT'     =>           false,
			'POWERED'  =>           false,
			'PRE10359' =>           true,
			'BITSTREAM'=>           false,
			'SENSORS_DETECTED'=>    false,
			'SENSORS_SYNCHRONIZED'=>false,
			'PARAMETERS_PRESET'=>   false,
			'SEQUENCERS_ADVANCED'=> false,
			'INITIALIZED'=>         false));
	$GLOBALS['camera_state_arr']['is_master'] = 1;
	
	//$GLOBALS['camera_state_arr']['max_frame_time'] = 100000; // usec, should exceed longest initial free frame period
	update_max_frame_time();

	$GLOBALS['camera_state_arr']['is_mt9p006'] = 1;

	write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
	return $mode;
}

// 2018/02/09: TODO: test
function get_mt9f002_mode() {
	
	$mode = get_mt9p006_mode();
	
	$GLOBALS['camera_state_arr']['is_mt9p006'] = 0;
	$GLOBALS['camera_state_arr']['is_mt9f002'] = 1;
	write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
	
	return $mode;
}

function get_lepton35_mode() {
    
    $mode = get_mt9p006_mode();
    
    $GLOBALS['camera_state_arr']['is_mt9p006'] = 0;
    $GLOBALS['camera_state_arr']['is_lepton35'] = 1;
    write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
    
    return $mode;
}

function get_boson640_mode() {
    
    $mode = get_mt9p006_mode();
    
    $GLOBALS['camera_state_arr']['is_mt9p006'] = 0;
    $GLOBALS['camera_state_arr']['is_boson640'] = 1;
    write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
    
    return $mode;
}


/**
 * Eyesis application modes:
 * 1001 - top,    slave,   controls own power,         default IP = 161
 * 1002 - middle, slave,   power controlled by bottom, default IP = 162
 * 1003 - bottom, master,  controls power for middle,  default IP = 163
 *
 * @return unknown|number */

function get_eyesis_mode() { // should be called...
	$eyesis_mode = intval($GLOBALS['camera_state_arr']['mode']);
	$GLOBALS['camera_state_arr']['max_frame_time'] = 300000; // usec, should exceed longest initial free frame period
	switch ($eyesis_mode){
		case 1001 :
			$GLOBALS['camera_state_arr']['is_slave'] = 1;
			$GLOBALS['camera_state_arr']['eyesis_top'] = 1;
			$sensor_mask = 0xf7777; // 3 sensors on each port, 4 port multiplexers
			break;
		case 1002 :
			$GLOBALS['camera_state_arr']['is_slave'] = 1;
			$GLOBALS['camera_state_arr']['eyesis_middle'] = 1;
			$sensor_mask = 0xf7777; //  3 sensors on each port, 4 port multiplexers
			break;
		case 1003 :
			$GLOBALS['camera_state_arr']['is_master'] = 1;
			$GLOBALS['camera_state_arr']['has_slaves'] = 1;
			$GLOBALS['camera_state_arr']['eyesis_bottom'] = 1;
			$sensor_mask = 0x1100; // 1 sensors on each port of 2,3
			break;
		default:
			log_err("Unknown Eyesis mode, only 1001, 1002, and 1003 are defined");
	}
	$GLOBALS['camera_state_arr']['is_eyesis'] = 1;
	$GLOBALS['camera_state_arr']['eyesis_power'] = 1; // disable fan control, reuse it to control power

	write_php_ini ($GLOBALS['camera_state_arr'], $GLOBALS['camera_state_path'] );
	$GLOBALS['STOP_AFTER']=array_merge($GLOBALS['STOP_AFTER'],array( // overwrite specified fields, keep others
			'BOOT'     =>           true,
			'POWERED'  =>           true,
			'PRE10359' =>           false,
			'BITSTREAM'=>           false,
			'SENSORS_DETECTED'=>    false,
			'SENSORS_SYNCHRONIZED'=>true,   // here join branches
			'PARAMETERS_PRESET'=>   true,
			'SEQUENCERS_ADVANCED'=> true,
			'INITIALIZED'=>         true));
	return $sensor_mask;
}

function is_eyesis_master(){
//	return ($GLOBALS ['eyesis_mode'] == 1003);
	return ($GLOBALS['camera_state_arr']['is_eyesis'] && $GLOBALS['camera_state_arr']['is_master']);
}

function is_eyesis_slave(){
//	return ($GLOBALS ['eyesis_mode'] == 1001) || ($GLOBALS ['eyesis_mode'] == 1002);
	return ($GLOBALS['camera_state_arr']['is_eyesis'] && $GLOBALS['camera_state_arr']['is_slave']);
}

function set_eyesis_power_control(){
	exec ( 'autocampars.py localhost pyCmd disable_gpio_10389', $output, $retval );
	$xml = simplexml_load_string (json_decode(implode(" ",$output))[0]); // just first of outputs (more will be for multiple requests)
	return array ('result'=> ((string)  $xml->result), 'retval' => $retval);
}
function my_IP(){
	exec('ifconfig eth0',$output,$retval);
	 return explode(':',explode(" ",trim($output[1]))[1])[1];
}

function offset_IP($offset){
	$aIP=explode('.',my_IP());
	$aIP[3]= (string) ($offset +((int) $aIP[3]));
	return implode('.',$aIP);
}
function multicamera_get_IPs(){
	if (is_eyesis_master ()) {
		return array ('ip_top' =>    offset_IP (-2),
				      'ip_middle' => offset_IP (-1),
				      'ip_bottom' => my_IP ());
	} else { // add other cmares here
		return array();
	}
}

function get_remote_states($IPs){
	exec ( 'autocampars.py ['.implode(',',$IPs).'] pyCmd state', $output, $retval );
    $states=array();
//    print_r($output);
//    echo __LINE__."implode(' ',output) = ".implode(" ",$output)."\n\n";
//    print_r(json_decode(implode(" ",$output)));

    foreach (json_decode(implode(" ",$output)) as $i=>$s) {
//    	echo "### $i: ".print_r($s,1);
    	$xml = simplexml_load_string ($s);
//    	echo "#$i "; print_r($xml);
    	$states[$i]=array();
    	foreach ($xml as $tag=>$value){
    		$svalue=(string) $value;
    		if (strlen($svalue) > 0){
    			if ($svalue[0] == '"')  $states[$i][$tag] = trim($svalue,'"');
    			else $states[$i][$tag] = (int) $svalue;
  			}
    	}
    }
//    print_r($states);
    return $states;
}
// Wait for both slave modules to be in 'BOOT' state
function wait_slaves_boot($retries){
	if (!array_key_exists('REQUEST_METHOD',$_SERVER)){
		echo colorize('Waiting for slaves to come up: ','YELLOW',0);
	}
	for ($retry = 0; $retry < $retries; $retry++){
		if (!array_key_exists('REQUEST_METHOD',$_SERVER)){
			echo colorize("$retry ",'YELLOW',1);
		}
		$states = get_remote_states(array(
				$GLOBALS['camera_state_arr']['ip_top'],
				$GLOBALS['camera_state_arr']['ip_middle']
		));
		if (count($states)==2){ // need both to reply
			if (array_key_exists('state',       $states[0]) &&
		  	    array_key_exists('state',       $states[1]) &&
		  	   !array_key_exists('autocampars', $states[0]) && // autocampars.php should already terminate
		  	   !array_key_exists('autocampars', $states[1])){
	  	   		break; // wrong state - need system reboot TODO: Add slave reboot by master
		  	}
		}
	}
	if (!array_key_exists('REQUEST_METHOD',$_SERVER)){
		echo colorize(" Done\n",'GREEN',0);
	}
	log_msg("In wait_slaves_boot() states = ".print_r($states,1));
	if (($states[0]['state'] == 'BOOT') || ($states[1]['state'] == 'BOOT')) return true;
	return $states;
}

function reboot_me(){
	if (array_key_exists ( 'reboot', $_GET )) {
		// TODO: change the response to respond_xml()
		$rslt = "<?xml version=\"1.0\"?><root><reboot>Rebooting standalone camera...</reboot></root>\n";
		header("Content-Type: text/xml");
		header("Content-Length: ".strlen($rslt)."\n");
		header("Pragma: no-cache\n");
		printf($rslt);
	}
	log_msg('running autocampars.py [localhost] pyCmd reboot');
	exec ( 'autocampars.py [localhost] pyCmd reboot', $output, $retval );
	exit(0); // no time to close log
}

function reboot_all_if_master(){
	$reply_xml=array_key_exists ( 'reboot', $_GET );
	log_msg('Current state: '. print_r($GLOBALS['camera_state_arr'],1));

	if ($GLOBALS['camera_state_arr']['is_master']){
		$IPs=array();
		foreach ($GLOBALS['camera_state_arr'] as $key=>$ip){
			if (substr ( $key, 0, 3 ) == "ip_"){
				$IPs[]=$ip;
			}
		}
		if (count($IPs) == 0){
			$IPs[]='localhost';
		}
		if ($reply_xml) {
			printf ( "<?xml version=\"1.0\"?><root><reboot>Rebooting all sub-cameras...</reboot></root>\n" );
		}
		log_msg('running autocampars.py ['.implode(',',$IPs).'] pyCmd reboot');
		exec ( 'autocampars.py ['.implode(',',$IPs).'] pyCmd reboot', $output, $retval );
		exit(0); // no time to close log
	}
	respond_xml('','Only master can reboot'); // will exit(1)
}

function eyesis_all_power_fpga($include){
	if ($GLOBALS['camera_state_arr']['is_master']){
		$IPs=array();
		foreach ($GLOBALS['camera_state_arr'] as $key=>$ip){
			if (substr ( $key, 0, 3 ) == "ip_"){
				$IPs[]=$ip;
			}
		}
		if (count($IPs) == 0){
			$IPs[]='localhost';
		}

		log_msg('running autocampars.py ['.implode(',',$IPs).'] py393 '.$include);
		exec ( 'autocampars.py ['.implode(',',$IPs).'] py393 '.$include, $output, $retval );
		return true;
	}
	return false;
}

/** Processes commands in daemon mode (not yet tested with nc393 */

function processDaemon($port) {
	//TODO: Make sure $port >=0
	$AUTOCAMPARS = elphel_get_P_arr ( $GLOBALS ['sensor_port'], array (
			"AUTOCAMPARS_CMD" => null,
			"AUTOCAMPARS_GROUPS" => null,
			"AUTOCAMPARS_PAGE" => null
	) );
	// echo "processDaemon()\n";
	// print_r($AUTOCAMPARS);
	$page = myval ( $AUTOCAMPARS ['AUTOCAMPARS_PAGE'] );
	$groupMask = myval ( $AUTOCAMPARS ['AUTOCAMPARS_GROUPS'] );
	switch (( integer ) $AUTOCAMPARS ['AUTOCAMPARS_CMD']) {
		case ELPHEL_CONST_AUTOCAMPARS_CMD_RESTORE :
			$page = setParsFromPage ($port, $page, $groupMask );
			// echo "ELPHEL_CONST_AUTOCAMPARS_CMD_RESTORE\n";
			break;
		case ELPHEL_CONST_AUTOCAMPARS_CMD_SAVE :
			// echo "ELPHEL_CONST_AUTOCAMPARS_CMD_SAVE\n";
			$page = readParsToPage ($port, $page );
			log_msg("processDaemon($port) daemon: ELPHEL_CONST_AUTOCAMPARS_CMD_SAVE");
//			log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));
			saveRotateConfig ($port, $GLOBALS['numBackups'] );
			break;
		case ELPHEL_CONST_AUTOCAMPARS_CMD_DFLT :
			// echo "ELPHEL_CONST_AUTOCAMPARS_CMD_DFLT\n";
			if (($page >= 0) && ($page < $GLOBALS['useDefaultPageNumber']) && ($page != $GLOBALS['useDefaultPageNumber'])) {
				$GLOBALS['configs'][$port]['defaultPage'] = $page;
				log_msg("processDaemon($port) daemon: ELPHEL_CONST_AUTOCAMPARS_CMD_DFLT");
//				log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));
				saveRotateConfig ($port, $GLOBALS['numBackups'] );
			}
			break;
		case ELPHEL_CONST_AUTOCAMPARS_CMD_SAVEDFLT :
			// echo "ELPHEL_CONST_AUTOCAMPARS_CMD_SAVEDFLT\n";
			$page = readParsToPage ($port, $page );
			$GLOBALS['configs'][$port] ['defaultPage'] = $page;
			log_msg("processDaemon($port) daemon: ELPHEL_CONST_AUTOCAMPARS_CMD_SAVEDFLT");
//			log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));
			saveRotateConfig ($port, $GLOBALS['numBackups'] );
			break;
/*
 * Disabled in NC393, as init is about all ports and daemon - single camera port
		case ELPHEL_CONST_AUTOCAMPARS_CMD_INIT :
			// echo "ELPHEL_CONST_AUTOCAMPARS_CMD_INIT\n";
			$pages = processInit ( $page, true ); // needs sensor reset/detection
			                                    // /NOTE: If $page<0 here - sensor failed to initialize, if this script was called from the daemon - it will never restart on it's own. Log record is made
			break;
*/
		default :
			log_msg($_SERVER ['argv'] [0] . ": Unknown command=" . $AUTOCAMPARS ['AUTOCAMPARS_CMD']);
	}
	// print_r($config);
}

function processPost($port) {
	log_msg("processPost($port)");
//	log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));
	// /Updating comments?
	$needle = "update_comment_";
	foreach ( $_POST as $key => $value )
		if (substr ( $key, 0, strlen ( $needle ) ) == $needle) {
			$page = substr ( $key, strlen ( $needle ) );
			$comment = $_POST ['comment_' . $page];
			// echo "updating comment for page $page, it will be $comment\n";
			$GLOBALS['configs'][$port]['paramSets'] [$page] ['comment'] = $comment;
			log_msg("processPost($port) - updating comment");
//			log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));

			saveRotateConfig ($port, $GLOBALS['numBackups'] );
			processGet ($port);
			log_close();
			exit ( 0 );
		}
	if (array_key_exists ( 'update_default', $_POST )) {
		$GLOBALS['configs'] [$port] ['defaultPage'] = $_POST ['default_page'];
		log_msg("processPost($port) - updating default, \$GLOBALS['configs'][\$port]['defaultPage'] = ".$GLOBALS['configs'] [$port] ['defaultPage']);
//		log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));
		saveRotateConfig ($port, $GLOBALS['numBackups'] );
		processGet ($port);
		log_close();
		exit ( 0 );
	}
	$needle = "save_";
	foreach ( $_POST as $key => $value )
		if (substr ( $key, 0, strlen ( $needle ) ) == $needle) {
			$page = substr ( $key, strlen ( $needle ) );
			$page = readParsToPage ($port,  $page );
			$GLOBALS['configs'] [$port] ['defaultPage'] = $page;
			log_msg("processPost($port) - save_");
//			log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));
			saveRotateConfig ($port, $GLOBALS['numBackups'] );
			processGet ($port);
			log_close();
			exit ( 0 );
		}
	$needle = "restore_";
	foreach ( $_POST as $key => $value )
		if (substr ( $key, 0, strlen ( $needle ) ) == $needle) {
			$page = substr ( $key, strlen ( $needle ) );
			$needle = "group_";
			$groupMask = 0;
			foreach ( $_POST as $key => $value )
				if (substr ( $key, 0, strlen ( $needle ) ) == $needle) {
					$groupMask |= (1 << (( integer ) substr ( $key, strlen ( $needle ) )));
				}
			$page = setParsFromPage ($port, $page, $groupMask );
			processGet ($port);
			log_close();
			exit ( 0 );
		}

	echo "<pre>\n";
	print_r ( $_POST );
	echo "</pre>\n";
	log_close();
	exit ( 0 );
}
// /TODO: Make the contol page aware of stuck sensor (sleep 1, then 5)? and suggest init if frame is not changing
function processGet($port) {
	/** NOT YET PORTED */
//	if ($GLOBALS['sensor_port']<0){
	if (($port<0) || !in_array($port,$GLOBALS['ports'])){
		if ($port<0) {
		$warn = <<<WARN_PORT
<p>Sensor port (sensor_port parameter) is not specified</p>
<p>Parameters for only one camera sensor port can be edited with this program. Below are the links to the individual camera ports parameters.</p>
<p/>
WARN_PORT;
		} else {
			$warn = <<<WARN_PORT
<p>Sensor port $port does not exit (or is disabled) in this camera.</p>
<p>Below are the links to the existing camera ports parameters.</p>
<p/>
WARN_PORT;
			}
//		log_msg('$GLOBALS='.print_r($GLOBALS,1));
//		log_msg('$_SERVER='.print_r($_SERVER,1));
//		log_msg('REQUEST_URI='.$_SERVER['REQUEST_URI']);
		$uri = $_SERVER[ 'REQUEST_URI'];
		while (strstr($uri,'sensor_port=')){ // remove existing sensor_port parameter from URI
			$sens_pos = strpos($uri,'sensor_port=');
			$next_pos = strpos($uri,'&',$sens_pos);
			if ($next_pos !== FALSE){
				$uri = substr($uri,0,$sens_pos).substr($uri, $next_pos+1);
			} else {
				$uri = substr($uri,0,$sens_pos-1);
			}
		}
		foreach ( $GLOBALS['ports'] as $port ) {
			$link = $uri;
			if (strstr($link,'?')) $link .='&';
			else  $link .='?';
			$link .= 'sensor_port='.$port;
			$warn .="<p>Port ".$port.": <a href = \"$link\">$link</a></p>\n";
		}
		$link = $uri;
		if (strstr($link,'?')) $link = substr($link,0,strpos($link,'?'));
		$link .= "?log";
		$warn .="<br/><p>autocampars.php log file: <a href = \"$link\">$link</a></p>\n";
		echo $warn;
		endPage ();
		log_close();
		exit ( 1 );
	}
	// New in NC393 - get sensor port from URL - moved to startup of the script
	// }
	if (array_key_exists ( 'ignore-revision', $_GET ) && ($GLOBALS['version'] != $GLOBALS['configs'][$port] ['version'])) {
		$GLOBALS['configs'][$port]['version'] = $GLOBALS['version'];
		log_msg("processGet($port) - processGet ignore-revision");
		saveRotateConfig ($port, $GLOBALS['numBackups'] );
	}
	update_minor_version($port, 0);
	if ($GLOBALS['version'] != $GLOBALS['configs'][$port]['version']) {
		startPage ( "Warning: version numbers mismatch", "function updateLink(){}" );
		$warn = <<<WARN
<center><h4>Warning! Version numbers of this script and the config file mismatch:</h4></center>
Script:  <b>{$GLOBALS['version']}</b>.<br/>
Config file ({$GLOBALS['configDir']}/{$GLOBALS['configPaths'][$port]}): <b>{$GLOBALS['configs'][$port]['version']}</b><br/>
<ol>This may (or may not) cause errors. You have several options:
<li> Follow <a href="?new&sensor_port={$port}">this link </a> and create a new config file </li>
<li> Follow <a href="?ignore-revision&sensor_port={$port}">this other link </a> to ignore the warning and write a new revision number to the config file</li>
<li> First update the version number with the link above, then manually edit/merge old and new data</li>
</ol>
WARN;
		echo $warn;
		echo "<pre>\n".print_r($GLOBALS['ports'],1)."\n</pre>";
		endPage ();
		log_close();
		exit ( 1 );
	}
/*
 *
 *
    [_SERVER] => Array
        (
            [CONSOLE] => /dev/console
            [SHELL] => /bin/sh
            [TERM] => linux
            [INIT_VERSION] => sysvinit-2.88
            [COLUMNS] => 80
            [PATH] => /sbin:/bin:/usr/sbin:/usr/bin
            [runlevel] => 5
            [RUNLEVEL] => 5
            [PWD] => /
            [VERBOSE] => no
            [previous] => N
            [PREVLEVEL] => N
            [LINES] => 24
            [HOME] => /
            [SHLVL] => 4
            [_] => /usr/sbin/lighttpd
            [PHP_FCGI_CHILDREN] => 1
            [HTTP_ACCEPT_LANGUAGE] => en-US,en;q=0.8
            [HTTP_ACCEPT_ENCODING] => gzip, deflate, sdch
            [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,* /*;q=0.8
            [HTTP_USER_AGENT] => Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.100 Safari/537.36
            [HTTP_UPGRADE_INSECURE_REQUESTS] => 1
            [HTTP_CACHE_CONTROL] => max-age=0
            [HTTP_CONNECTION] => keep-alive
            [HTTP_HOST] => 192.168.0.5
            [SERVER_PROTOCOL] => HTTP/1.1
            [REDIRECT_STATUS] => 200
            [REQUEST_METHOD] => GET
            [QUERY_STRING] => new
            [REQUEST_URI] => /autocampars.php?new
            [DOCUMENT_ROOT] => /www/pages
            [SCRIPT_FILENAME] => /www/pages/autocampars.php
            [PATH_INFO] =>
            [SCRIPT_NAME] => /autocampars.php
            [REMOTE_ADDR] => 192.168.0.210
            [REMOTE_PORT] => 36054
            [SERVER_ADDR] => 192.168.0.5
            [SERVER_PORT] => 80
            [GATEWAY_INTERFACE] => CGI/1.1
            [SERVER_NAME] => 192.168.0.5
            [SERVER_SOFTWARE] => lighttpd/1.4.39
            [FCGI_ROLE] => RESPONDER
            [PHP_SELF] => /autocampars.php
            [REQUEST_TIME_FLOAT] => 1480466694.6114
            [REQUEST_TIME] => 1480466694
            [argv] => Array
                (
                    [0] => new
                )

            [argc] => 1
        )
*
              'REQUEST_URI': '/autocampars.py?a=1&b&c=2',
*
 	foreach ( $GLOBALS['ports'] as $port ) {
		log_msg ("Rotating configs for port $port");
		if (file_exists ($GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$port])) {
			rotateConfig ($port, $GLOBALS['numBackups'] );
		}
	}

 */

	$page_title = "Model 393 Camera Parameters save/restore, sensor port {$port}";
	startPage ( $page_title, mainJavascript ($port) );
	if ($GLOBALS['twoColumns'])
		printf ( "<table><tr><td style='vertical-align: top'>\n" );
	writeGroupsTable ($port);
	if ($GLOBALS['twoColumns'])
		printf ( "</td><td>\n" );
	else
		printf ( "<br/>\n" );
	writePagesTable ($port);
	if ($GLOBALS['twoColumns'])
		printf ( "</td></tr></table>\n" );
	endPage ();
}

function print_log(){
	log_close();
	$log = file_get_contents($GLOBALS['logFilePath']);
	echo <<<PAGE
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{$GLOBALS['logFilePath']}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body><pre>
$log
</pre>
</body>
</html>
PAGE;

}
function startPage($page_title, $javascript) {
	$url = str_replace ( 'new', 'same', $_SERVER ['REQUEST_URI'] ); // / form will remove "new" when submitting

	// [REQUEST_URI] => /autocampars.php?new

	$btns_table = getButtonsTable();

	echo <<<HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
 <title>$page_title</title>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript"><!--
$javascript
//-->
</script>
</head>
<body onload='updateLink()'>
$btns_table
<form action="$url" method="post">
HEAD;
	/*
	 * ! // debug
	 * echo "<!--\n";
	 * print_r($_SERVER);
	 * echo "-->\n";
	 */
}

function endPage() {
	echo "\n</form></body></html>\n";
}

function getButtonsTable(){
	$str  = "<table border='0' style='font-family: Courier, monospace;'>\n";
	$str .= "<tr>\n";
	if ($GLOBALS['camera_state_arr']['is_master']){
		$str .= "<td style='padding:0px 5px 5px 0px;'><button onclick='reboot_system()' title='reboot camera(s)'><b>REBOOT</b></button></td>\n";
	}
	$str .= "<td style='padding:0px 5px 5px 0px;'><button onclick='cancel_sync()' title='cancel sync - when booted from NAND flash the changes will not be saved'><b>CANCEL</b> sync</button></td>\n";
	$str .= "<td id='btn_response' style='padding:0px 5px 5px 0px;'></td>\n";
	$str .= "</tr>\n";
	$str .= "</table>\n";
	return $str;
}

function writeGroupsTable($sensor_port) {
	printf ( "<table border='1' style='font-family: Courier, monospace;'>\n" );
	printf ( "<tr>" );
	printf ( "<td><input type='button' value='&nbsp;Select&nbsp;\nAll' onclick='checkAll()' title='Select all parameter groups'/></td>\n" );
	printf ( "<td style='text-align:center'>Bit</td><td style='text-align:center'>Name</td><td style='text-align:center'>Description</td></tr>\n" );
	foreach ( $GLOBALS['configs'][$sensor_port]['groupBits'] as $name => $bit ) {
		printf ( "<tr>" );
		printf ( "<td style='text-align:center'><input type='checkbox' name='group_%d' value='1' id='id_group_%d' onclick='updateLink();'/></td>", $bit, $bit );
		printf ( "<td style='text-align:right' >%d</td>", $bit );
		printf ( "<td style='text-align:left' >%s</td>", $name );
		printf ( "<td style='text-align:left' >%s</td>", $GLOBALS['configs'][$sensor_port]['groupDescriptions'] [$bit] );
		printf ( "</tr>\n" );
	}
	printf ( "<tr>" );
	printf ( "<td><input type='button' value='Deselect\nAll' onclick='unCheckAll()' title='Deselect all parameter groups'/></td>\n" );
	printf ( "<td colspan='3'>&nbsp;<a href='#' target='new' id='id_editLink' title='View/edit current values of the selected parameters groups'>View/Edit Current</a>" );
	printf ( "<label style='border: 0px solid #000;' for='id_embed_image' title='Include image with the parameter edit window'>&nbsp;&nbsp;<input type='checkbox' name='embed' value='1' id='id_embed_image' onclick='updateLink();' checked/>Include image</label></td></tr>\n" );
	printf ( "</table>\n" );
}

function writePagesTable($sensor_port) {
	printf ( "<table border='1' style='font-family: Courier, monospace;'>\n" );
	printf ( "<tr>" );
	printf ( "<td style='text-align:center'>Page</td>" );
	printf ( "<td style='text-align:center'>" );
	printf ( "<input type='submit' name='update_default' value='Update\ndefault' id='id_update_default' title='Update default (used at boot time) configuration page'/>" );
	printf ( "</td>" );
	printf ( "<td style='text-align:center'>Save</td>" );
	printf ( "<td style='text-align:center'>Restore<br/>selected<br/>groups</td>" );
	printf ( "<td style='text-align:center'>Time Saved</td>" );
	printf ( "<td style='text-align:center'>Comments</td>" );
	printf ( "</tr>\n" );
	for($i = 0; $i < $GLOBALS['useDefaultPageNumber']; $i ++) {
		printf ( "<tr>" );
		printf ( "<td style='text-align:right'>%d</td>", $i );

		if (array_key_exists ( $i, $GLOBALS['configs'][$sensor_port]['paramSets'] )) {
			printf ( "<td style='text-align:center'><input type='radio' name='default_page' value='%d' id='id_default_page_%d' %s" . " title='Select config file page %d as default (boot up) page' /></td>", $i, $i, ($i == $GLOBALS['configs'][$sensor_port]['defaultPage']) ? "checked" : "", $i );
		} else {
			printf ( "<td>&nbsp;</td>" );
		}
		if ($i == $GLOBALS['protectedPage']) {
			printf ( "<td>&nbsp;</td>" );
		} else {
			printf ( "<td style='text-align:center'><input type='submit' name='save_%d' value='%s' id='id_save_page_%d' title='Save current parameters to the config file as page %d'/></td>", $i, array_key_exists ( $i, $GLOBALS['configs'][$sensor_port]['paramSets'] ) ? "Overwrite" : "Save", $i, $i );
		}
		if (array_key_exists ( $i, $GLOBALS['configs'][$sensor_port]['paramSets'] )) {
			printf ( "<td style='text-align:center'><input type='submit' name='restore_%d' value='Restore' id='id_restore_page_%d' title='Restore parameters from the config file page %d'/></td>", $i, $i, $i );
			printf ( "<td style='text-align:left'>%s</td>", $GLOBALS['configs'][$sensor_port]['paramSets'] [$i] ['timestamp'] ? date ( "F j, Y, g:i a", $GLOBALS['configs'][$sensor_port]['paramSets'] [$i] ['timestamp'] ) : "&nbsp;" );
			printf ( "<td>" );
			if ($i == $GLOBALS['protectedPage'])
				printf ( "%s&nbsp;", $GLOBALS['configs'][$sensor_port]['paramSets'] [$i] ['comment'] );
			else {
				printf ( "<input type='text'   name='comment_%d' size='40' value='%s' id='id_comment_%d' style='text-align:left'/>", $i, $GLOBALS['configs'][$sensor_port]['paramSets'] [$i] ['comment'], $i );
				printf ( "<input type='submit' name='update_comment_%d' value='Update' id='id_update_comment_%d' title='Update comment in the config file'/>", $i, $i );
			}

			printf ( "</td>" );
		} else {
			printf ( "<td colspan='3'>&nbsp;</td>" );
		}
		printf ( "</tr>\n" );
	}
	printf ( "</table>\n" );
}
function mainJavascript($sensor_port) {
	$thisname = basename($_SERVER['SCRIPT_NAME']);
	$checkboxNumbers = "";
	foreach ( $GLOBALS['configs'][$sensor_port]['groupBits'] as $name => $bit ) {
		$checkboxNumbers .= $bit . ",";
	}
	$checkboxNumbers = rtrim ( $checkboxNumbers, "," );
	$groupNames = "";
	for($i = 0; $i < 32; $i ++)
		$groupNames .= '"' . $GLOBALS['configs'][$sensor_port]['groupNames'] [$i] . '",';
	$groupNames = rtrim ( $groupNames, "," );
	$groupList = "{";
	foreach ( $GLOBALS['configs'][$sensor_port]['groups'] as $name => $value ) {
		if (($name != 'comment') && ($name != 'timestamp')) // / Fixing bug for the group "init"
			$groupList .= $name . ":" . $value . ",";
	}
	$groupList = rtrim ( $groupList, "," ) . '}';
	return <<<JAVASCRIPT
function unCheckAll() {
  var checkboxes=Array($checkboxNumbers);
  var i,cb;
  for (i in checkboxes) {
    if (((cb=document.getElementById('id_group_'+checkboxes[i])))) {
      cb.checked=false;
    }
  }
  updateLink();
}
function checkAll() {
  var checkboxes=Array($checkboxNumbers);
  var i,cb;
  for (i in checkboxes) {
    if (((cb=document.getElementById('id_group_'+checkboxes[i])))) {
      cb.checked=true;
    }
  }
  updateLink();
}
function updateLink() {
  var i,cb;
  var groups=$groupList;
  var groupNames=Array($groupNames);
  var checkboxes=Array($checkboxNumbers);
  var mask=0;
  var selectedGroups="";
  for (i in checkboxes) {
    if ((((cb=document.getElementById('id_group_'+checkboxes[i])))) && cb.checked) {
      mask |= (1 << checkboxes[i]);
      selectedGroups+=groupNames[checkboxes[i]]+"+";
    }
  }
  var selectedPars=Array();
  for (i in groups) {
    if (groups[i] & mask) selectedPars[selectedPars.length]=i;
  }
//  alert (selectedPars.toString());
  document.getElementById('id_editLink').href="{$GLOBALS['parseditPath']}?sensor_port={$sensor_port}&"+
  ((document.getElementById('id_embed_image').checked)?"embed={$GLOBALS['embedImageScale']}&":"")+
  "title=Parameters+for+groups:+"+selectedGroups;
  for (i in selectedPars) {
    document.getElementById('id_editLink').href+="&"+selectedPars[i];
  }
}

function reboot_system(){
  console.log("reboot the system");
  send_request("$thisname?reboot",parse_response);
}

function cancel_sync(){
  console.log("cancel sync");
  send_request("$thisname?cancel_sync",parse_response);
}

function parse_response(resp){
  var result = "";
  console.log(resp);
  if (resp.getElementsByTagName("result").length!=0){
  	result = resp.getElementsByTagName("result")[0].childNodes[0].nodeValue;
  }else if (resp.getElementsByTagName("reboot").length!=0){
  	result = resp.getElementsByTagName("reboot")[0].childNodes[0].nodeValue;
  }else if (resp.getElementsByTagName("error").length!=0){
    result = resp.getElementsByTagName("error")[0].childNodes[0].nodeValue;
  }else{
  	result = "Error";
  }
  document.getElementById("btn_response").innerHTML = result;
}

function send_request(rq,callback){
  var request = new XMLHttpRequest();
  request.open('GET', rq, true);
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var resp = this.responseXML;
  	  callback(resp);
    }
  };
  request.onerror = function() {
    console.log("request error");
  };
  request.send();
}

JAVASCRIPT;
}

/**
 * @brief Scan commands for possible changing gamma tables, calculate them in advance
 * (driver can only scale gamma, not calculate prototypes)
 * $pars can be either array of parameters or array of array of parameters (for multi-port)
 */
function addGammas($mpars) { // common to all channels
	foreach ($mpars as $p){
		if (!is_array($p)){
			$mpars=array($mpars);
		}
		break;
	}
	$gammas = array ();
	foreach ( $mpars as $pars ) {
		if (array_key_exists ( 'GTAB_R', $pars ))
			$gammas [$pars ['GTAB_R'] >> 16] = 1; // / duplicates will be eliminated
		if (array_key_exists ( 'GTAB_G', $pars ))
			$gammas [$pars ['GTAB_G'] >> 16] = 1;
		if (array_key_exists ( 'GTAB_GB', $pars ))
			$gammas [$pars ['GTAB_GB'] >> 16] = 1;
		if (array_key_exists ( 'GTAB_B', $pars ))
			$gammas [$pars ['GTAB_B'] >> 16] = 1;
	}
		// var_dump($gammas);
	log_msg('Collected gammas == '. print_r($gammas,1));

	foreach ( $gammas as $gamma_black => $whatever ) {
		$black = ($gamma_black >> 8) & 0xff;
		$gamma = ($gamma_black & 0xff) * 0.01;
		elphel_gamma_add ( $gamma, $black ); // does not use $GLOBALS['sensor_port'],
	}
}

/** $all - array (indexed by ports) of arrays of parameters
 * keep only parameters listed in $filter - array of parameter names*/
function filterParsKeepRemove($all,     ///< array (indexed by ports) of arrays of parameters
		                      $filter,  ///< array of parameter names
		                      $keep)    ///< true - keep listed in filter, false - remove listed
		                                ///< @return array of per-port array of parameters
{
	$rslt=array();
	foreach ($all as $port=>$port_pars){
		$rslt[$port]=array();
		foreach ($port_pars as $par=>$value){
			if (in_array ( $par, $filter)) {
				if ($keep)	$rslt[$port][$par]=$value;
			} else {
				if (!$keep)	$rslt[$port][$par]=$value;
			}
		}
	}
	return $rslt;
}
/** Get */
function getMaskedPars($port_page, ///< array of parameter pages indexed by ports
                       $mask)      ///< bitmask for parameter groups to use (1 - init)
                                   ///< @return array of per-port array of parameters

{
	$all_port_pars=array();
	foreach ( $port_page as $port=>$page ) {
		$all_port_pars[$port] = array();
		foreach ( $GLOBALS ['configs'] [$port] ['groups'] as $par => $parMask ) {
			if (($mask & $parMask) && array_key_exists ( $par, $GLOBALS ['configs'] [$port] ['paramSets'] [$page] ) &&
					(! $GLOBALS ['configs'] [$port] ['parTypes'] [$par])) // / not 'text'
				$all_port_pars[$port][$par] = myval ( $GLOBALS ['configs'] [$port] ['paramSets'] [$page] [$par] );
		}
	}
	return $all_port_pars;
}


function setParsFromPage($sensor_port, $page, $mask) {
// If init_mode - will set all ports, $sensor_port will be ignored
// will return an array of pages, matching $GLOBALS['ports'] array
	// /FIXME: !!!
	// / Prevent too many i2c register writes for the same frame. If it is the case - split parameter groups between several frames
	// / AS there is a hardware limit of 64 parameters/frame in the sequencers (64 for the i2c, 64 - for the FPGA registers)
	// For that it is possible to use ASAP mode - driver will wait for the i2c command to be sent

log_msg("setParsFromPage($sensor_port, $page, $mask)");
	if ($page == $GLOBALS['useDefaultPageNumber']) {
		$page = $GLOBALS['configs'][$sensor_port]['defaultPage'];
	}
	$parToSet = array ();
	log_msg("setParsFromPage($sensor_port, $page, $mask");
	foreach ( $GLOBALS ['configs'][$sensor_port] ['groups'] as $par => $parMask ) {
		if (($mask & $parMask) &&
				array_key_exists ( $par, $GLOBALS ['configs'] [$sensor_port] ['paramSets'] [$page] ) &&
				(! $GLOBALS ['configs'] [$sensor_port] ['parTypes'] [$par])) // / not 'text'
			$parToSet [$par] = myval ( $GLOBALS ['configs'] [$sensor_port] ['paramSets'] [$page] [$par] );
//		log_msg( __LINE__.': par='.$par.", parMask=".parMask.", parToSet=". print_r($parToSet,1));
	}
	// /NOTE: Important ; add gamma tables if parameters modified involve setting/changing them
//	log_msg('before gamma: parToSet == '. print_r($parToSet,1));
	addGammas ($parToSet );
	elphel_set_P_arr  ( $sensor_port, $parToSet );
	return $page;
}


function readParsToPage($sensor_port, $page) {
	log_msg("readParsToPage($sensor_port, $page)");
//	log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));

	if (($page == $GLOBALS['protectedPage']) || ($page < 0) || ($page > $GLOBALS['useDefaultPageNumber']))
		return - 1;
	if ($page == $GLOBALS['useDefaultPageNumber'])
		$page = $GLOBALS ['configs'] [$sensor_port] ['nextPage']; // / 0 is write protected
	if ($page == $GLOBALS['protectedPage'])
		$page = findNextPage ( $page );
	$GLOBALS ['configs'] [$sensor_port] ['paramSets'] [$page] = elphel_get_P_arr ( $sensor_port, $GLOBALS ['configs'] [$sensor_port] ['groups'] ); // / 'text' parameters will be just ignored
	$GLOBALS ['configs'] [$sensor_port] ['paramSets'] [$page] ['comment'] = "Saved on " . date ( "F j, Y, g:i a" );
	$GLOBALS ['configs'] [$sensor_port] ['paramSets'] [$page] ['timestamp'] = time ();
	$GLOBALS ['configs'] [$sensor_port] ['nextPage'] = findNextPage ( $page );
	elphel_set_P_arr ( $sensor_port, array (
			"AUTOCAMPARS_PAGE" => $page + 0
	) ); // / will set some (3?) frames ahead so not yet available until waited enough
	return $page;
	// date("F j, Y, g:i a")
}
// elphel_parse_P_name
function saveRotateConfig($sensor_port, $numBackups) {
	log_msg("saveRotateConfig($sensor_port, $numBackups), \$GLOBALS['configs'] [$sensor_port] ['defaultPage'] = ".$GLOBALS['configs'] [$sensor_port] ['defaultPage'],0);
//	log_msg("GLOBALS['configs']=".print_r($GLOBALS['configs'],1));

	rotateConfig ($sensor_port, $numBackups );

	$confFile = fopen ( $GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$sensor_port], "w+" );
	fwrite ( $confFile, encodeConfig ( $GLOBALS['configs'][$sensor_port] ) );
	fclose ( $confFile );
	createConfigLink($sensor_port);
	exec ( 'sync; overlay_sync 1' );
}

function createConfigLink($sensor_port){
	// add link to master config - that link will be used by parsedit.php
	get_sysfs_sensors(); // sets $GLOBALS['master_port']
//	log_msg('get_sysfs_sensors()-> master_port = '.$GLOBALS['master_port'].", sensor_port=$sensor_port");
	if ($sensor_port == $GLOBALS['master_port']) {
//		echo __LINE__."sensor_port ". $GLOBALS['master_port'] . "\n";
		chdir($GLOBALS['configDir']); // to make relative links
		if (is_link($GLOBALS['configLink'])){
			if (readlink($GLOBALS['configLink']) != $GLOBALS['configPaths'][$sensor_port]){
				unlink($GLOBALS['configLink']);
			}
			if (file_exists('configLink')){ // was a regular file with the same name
				unlink($GLOBALS['configLink']);
			}
		}
		if (!file_exists($GLOBALS['configLink'])) {
			symlink($GLOBALS['configPaths'][$sensor_port],$GLOBALS['configLink']);
			log_msg("Created link ".$GLOBALS['configDir'].'/'.$GLOBALS['configLink'].' -> '.$GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$sensor_port]);
		}
	}

}

function rotateConfig($sensor_port,$numBackups) {
	log_msg("rotateConfig($sensor_port,$numBackups)",3);
	if (file_exists ( backupName ($sensor_port, $numBackups ) ))
		unlink ( backupName ($sensor_port, $numBackups ) );
	for($i = $numBackups - 1; $i > 0; $i --)
		if (file_exists ( backupName ($sensor_port, $i - 1 ) ))
			rename ( backupName ($sensor_port, $i - 1 ), backupName ($sensor_port, $i ) );
	log_msg("checking path ".$GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$sensor_port].": ".file_exists ( $GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$sensor_port]),0);
	if (($numBackups > 0) && (file_exists ( $GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$sensor_port])))
		rename ( $GLOBALS['configDir'].'/'.$GLOBALS['configPaths'][$sensor_port], backupName ($sensor_port, 0 ) );
}
function backupName($sensor_port, $num) {
	if ($num > 0)
		return $GLOBALS['configDir'].'/'.$GLOBALS['backupConfigPaths'][$sensor_port] . $num;
	else
		return $GLOBALS['configDir'].'/'.$GLOBALS['backupConfigPaths'][$sensor_port];
}
function findNextPage($page) {
	$page ++;
	while ( ($page == $GLOBALS['protectedPage']) || ($page == $GLOBALS['useDefaultPageNumber']) ) {
		$page ++;
		if ($page > $GLOBALS['useDefaultPageNumber'])
			$page = 0;
	}
	return $page;
}
function parseConfig($filename) {
	$config = array (
			'version' => 0,
			'defaultPage' => 0,
			'nextPage' => 0,
			'groupBits' => array (),
			'groupNames' => array (),
			'groupDescriptions' => array (),
			'descriptions' => array (),
			'groups' => array (),
			'parTypes' => array (),
			'paramSets' => array ()
	);
	$xml = simplexml_load_file ( $filename );
	$config ['version'] = ( string ) $xml->version;
	$config ['defaultPage'] = ( string ) $xml->defaultPage;
	$config ['nextPage'] = ( string ) $xml->nextPage;
	foreach ( $xml->groupNames->children () as $entry ) {
		$config ['groupBits'] [$entry->getName ()] = ( integer ) $entry->attributes ()->bit;
		$config ['groupNames'] [( integer ) $entry->attributes ()->bit] = $entry->getName ();
		$config ['groupDescriptions'] [( integer ) $entry->attributes ()->bit] = trim ( ( string ) $entry, "\" " );
	}
	foreach ( $xml->descriptions->children () as $entry ) {
		$config ['descriptions'] [$entry->getName ()] = trim ( ( string ) $entry, "\"" );
	}
	foreach ( $xml->groups->children () as $entry ) { // ignores empty entries
		if (( string ) $entry) {
			$value = 0;
			foreach ( explode ( ',', trim ( ( string ) $entry, '" ' ) ) as $key )
				$value |= (1 << ($config ['groupBits'] [$key]));
			$config ['groups'] [$entry->getName ()] = $value;
			$config ['parTypes'] [$entry->getName ()] = $entry->attributes ()->type;
		}
	}
	foreach ( $xml->paramSets->children () as $paramSet ) {
		$numSet = ( integer ) $paramSet->attributes ()->number;
		$config ['paramSets'] [$numSet] = array ();
		// foreach ($paramSet->children() as $param) $config['paramSets'][$numSet][$param->getName()]=myval((string) $param);
		foreach ( $paramSet->children () as $param ) { // sets empty
			// echo $param->getName();var_dump((string)$param);
			$config ['paramSets'] [$numSet] [$param->getName ()] = trim ( ( string ) $param, "\" " );
		}
	}
	return $config;
}

function encodeConfig($config) { // Can not use SimpleXMLElement as need comments and preserved sequence for human consumption
	log_msg("encodeConfig(): sensor_port=".$GLOBALS['sensor_port'],0); // , config=".print_r($config,1));
	log_msg(" \$config['defaultPage'] = ".$config['defaultPage'],0);
	$script_name=($_GET['SCRIPT_NAME'])?($_GET['SCRIPT_NAME'] . " in HTTP request mode"): ($_SERVER ['argv'] [0] ." in command line mode");
	$xml = "<?xml version=\"1.0\" standalone=\"yes\"?>\n<!-- This file is generated by " . $script_name . " -->\n";
	$xml .= "  <autocampars>\n";
	$xml .= "<!-- File version -->\n";
	$xml .= sprintf ( "    <version>%s</version>\n", $config ['version'] );
	$xml .= "<!-- Parameter groups that can be restored from the saved values  -->\n";
	$xml .= "    <groupNames>\n";
	foreach ( $config ['groupBits'] as $key => $bit )
		$xml .= sprintf ( "      <%s bit=\"%d\">\"%s\"</%s>\n", $key, $bit, $config ['groupDescriptions'] [$bit], $key );
	$xml .= "    </groupNames>\n";

	$xml .= "<!-- Number of parameter page that will be used as default (i.e. after camera boot) -->\n";
	$xml .= sprintf ( "    <defaultPage>%d</defaultPage>\n", $config ['defaultPage'] );
	$xml .= "<!-- Number of parameter page that will be next used to save parameters (if not specified) -->\n";
	$xml .= sprintf ( "    <nextPage>%d</nextPage>\n", $config ['nextPage'] );
	$xml .= "<!-- Descriptions of the parameters -->\n";
	$xml .= "    <descriptions>\n";
	foreach ( $config ['descriptions'] as $name => $description )
		$xml .= sprintf ( "      <%s>\"%s\"</%s>\n", $name, htmlspecialchars ( $description, ENT_QUOTES ), $name );
	$xml .= "    </descriptions>\n";

	$xml .= "<!-- Parameter groups -->\n";
	$xml .= "    <groups>\n";
//	log_msg("======= config ['groups'] = ".print_r($config ['groups'],1));
	foreach ($config['groups'] as $key => $value) {
		$groups = "";
		for($bit = 0; $bit < 24; $bit++)
			if ($value & (1 << $bit)) {
				if ($groups) $groups .= ",";
				$groups .= $config['groupNames'][$bit];
			}
		if ($config['parTypes'][$key]) {
			$xml .= sprintf ("      <%s type=\"%s\">\"%s\"</%s>\n", $key, $config['parTypes'][$key], $groups, $key);
//			log_msg ("======= " . sprintf ("      <%s type=\"%s\">\"%s\"</%s>\n", $key, $config['parTypes'][$key], $groups, $key));
			} else {
			$xml .= sprintf ("      <%s>\"%s\"</%s>\n", $key, $groups, $key);
//			log_msg ("======= " . sprintf ("      <%s>\"%s\"</%s>\n", $key, $groups, $key));
		}
	}
	$xml .= "    </groups>\n";
	$xml .= "<!-- Saved parameter Sets -->\n";
	$xml .= "    <paramSets>\n";
	foreach ( $config ['paramSets'] as $index => $paramSet ) {
		$xml .= sprintf ( "      <set number=\"%d\">\n", $index );
		foreach ( $paramSet as $key => $value ) {
			$xml .= sprintf ( "        <%s>\"%s\"</%s>\n", $key, $value, $key );
		}
		$xml .= "      </set>\n";
	}

	$xml .= "    </paramSets>\n";

	$xml .= "  </autocampars>\n";
	return $xml;
}




function myval($s) {
	$s = trim ( $s, "\" " );
	if (strtoupper ( substr ( $s, 0, 2 ) ) == "0X")
		return intval ( hexdec ( $s ) );
	else
		return intval ( $s );
}
function calculateDefaultPhases() {
	log_msg("calculateDefaultPhases() not implemented in nc393");
	return array();

	$phases = array (
			'SENSOR_PHASE' => 0, // values are not used, just the keys
			'MULTI_PHASE1' => 0,
			'MULTI_PHASE2' => 0,
			'MULTI_PHASE3' => 0
	);
	$phases = elphel_get_P_arr ( $GLOBALS ['sensor_port'], $phases ); // get current values
	/**
	 * TODO: Here read and compare the 10359 (REV>=B) and 10338 (REV>=E), read other persistent parameters (set by /etc/fpga, defined in c313a.h, such as
	 * #define G_CABLE_TIM (FRAMEPAR_GLOBALS + 7) /// Extra cable delay, signed ps)
	 * #define G_FPGA_TIM0 (FRAMEPAR_GLOBALS + 8) /// FPGA timing parameter 0 - difference between DCLK pad and DCM input, signed (ps)
	 * #define G_FPGA_TIM1 (FRAMEPAR_GLOBALS + 9) /// FPGA timing parameter 1\
	 * ...
	 * and calculate sensor/10359 phases using EEPROM data. then replace the $phases array elements in return ed array
	 *
	 * Do not forget 'global' :-)
	 */
	// if Eyesis4pi mode then override SENSOR_PHASE depending on the EEPROM data
	$xml = simplexml_load_string ( i2c_read256b ( 0xa0, 0 ) );
	if ($xml !== false) {
		if ($xml->model == "10359") {
			$cable_len_353_359 = $xml->len;
			if ($cable_len_353_359 == 450) {
				$SP = 0xa9;
				// ///something's wrong////////////////////////////////////////////////////////////
				// $DCM_STEP = 22; // ps/step
				// $clk_period = 1000000000000.0/elphel_get_P_value(ELPHEL_CLK_SENSOR);
				// $cable_len_353_359_ps = $cable_len_353_359*15;//15ps/mm
				// $px_delay = round ( -($clk_period/2 - $cable_len_353_359_ps) );
				// $px_delay_90 = round( (4*$px_delay+$clk_period/2)/$clk_period );
				// $px_delay = round( $px_delay - ($px_delay_90*$clk_period)/4 );
				// $px_delay = round ($px_delay/$DCM_STEP );
				// $SP = ($px_delay & 0xffff) | (($px_delay_90 & 3) <<16) | 0x80000;
				// ////////////////////////////////////////////////////////////////////////////////
				$phases ['SENSOR_PHASE'] = $SP;
				// echo "SENSOR_PHASE for 10359A is set to $SP\n";
			}
		}
		if ($xml->model == "10338") {
			$len = $xml->len;
			$DCM_STEP = 22; // ps/step
			$clk_period = 1000000000000.0 / elphel_get_P_value ( $GLOBALS ['sensor_port'], ELPHEL_CLK_SENSOR );

			$cable_len_ps = $len * 15; // 15ps/mm
			$px_delay = $cable_len_ps;
			// $px_delay_90 = round( (4*$px_delay+$clk_period/2)/$clk_period );
			// $px_delay = round( $px_delay - ($px_delay_90*$clk_period)/4 );
			$px_delay = round ( $px_delay / $DCM_STEP );
			$SP = ($px_delay & 0xffff) | 0x80000;
			file_put_contents ( "/var/log/autocampars_test.log", "extra " . $SP . " and " . $phases ['SENSOR_PHASE'] );
			$phases ['SENSOR_PHASE'] = $SP + $phases ['SENSOR_PHASE'];
		}
	}
	return $phases;
}

// /TODO: Add sensor registers groups, remember not to program more than 64 (better 32 as there could be other writes initiated by the driver)
// /TODO: Trigger modes?
// / "multisensor" here means "eyesis", it defaults to external trigger (may lock up other 10359 boards cameras)
// / Maybe check for all 3 sensors?
// / Note: manual temporary fix for others who use 10359 board.
// / Edit /etc/autocampars.xml and put zero in the following 2 fields:
// / <TRIG> and <TRIG_CONDITION>

//http://stackoverflow.com/questions/5695145/how-to-read-and-write-to-an-ini-file-with-php
function write_php_ini($array, $file) {
	$res = array ();
	foreach ( $array as $key => $val ) {
		if (is_array ( $val )) {
			$res [] = "[$key]";
			foreach ( $val as $skey => $sval )
				$res [] = "$skey = " . (is_numeric ( $sval ) ? $sval : '"' . $sval . '"');
		} else
			$res [] = "$key = " . (is_numeric ( $val ) ? $val : '"' . $val . '"');
	}
	safefilerewrite ( $file, implode ( "\n", $res ) );
}
function safefilerewrite($fileName, $dataToSave) {
	if (!file_exists(dirname($fileName))){
		mkdir (dirname($fileName),  0777 , 1); // recursive
	}
	if ($fp = fopen ( $fileName, 'w' )) {
		$startTime = microtime ( TRUE );
		do {
			$canWrite = flock ( $fp, LOCK_EX );
			// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			if (! $canWrite)
				usleep ( round ( rand ( 0, 100 ) * 1000 ) );
		} while ( (! $canWrite) and ((microtime ( TRUE ) - $startTime) < 5) );

		// file was locked so now we can store information
		if ($canWrite) {
			fwrite ( $fp, $dataToSave );
			flock ( $fp, LOCK_UN );
		}
		fclose ( $fp );
	}
}

// Using parallel requests, PHP has to be configured '--with-curl=' (and libcurl should be installed)
function curl_multi_start($urls) {
	// numprime is needed to actually send the request and remote starts executing it
	// Not really clear - what it should be
	$numprime = 4; // magic number, see http://lampe2e.blogspot.com/2015/03/making-stuff-faster-with-curlmultiexec.html
	$curl_mh = curl_multi_init ();
	$aCurlHandles = array ();
	foreach ($urls as $url) {
		$ch = curl_init ();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		$aCurlHandles[] = $ch;
		curl_multi_add_handle ($curl_mh, $ch);
	}
	$curl_active = count ($urls);
	for($x = 0; $x < $numprime && $curl_active; $x++) {
		curl_multi_exec ($curl_mh, $curl_active);
		// we wait for a bit to allow stuff TCP handshakes to complete and so forth...
		usleep (10000);
//		echo ".";
	}
	return array ("mh" => $curl_mh,"handles" => $aCurlHandles);
}

function curl_multi_finish($data, $use_xml=true, $ntry=0, $echo = false) {
	$curl_active = 1;
	$curl_mrc = CURLM_OK;
	$nrep = 0;
	$curl_mh = $data['mh'];
	while ($curl_active && $curl_mrc == CURLM_OK ) {
		if (curl_multi_select ($curl_mh) != -1) {
			do {
				$curl_mrc = curl_multi_exec ($curl_mh, $curl_active);
			} while ($curl_mrc == CURLM_CALL_MULTI_PERFORM );
		}
		if ($echo) echo colorize("$curl_active ",'YELLOW',1);
		$nrep++;
		if ($ntry && ($nrep > $ntry)) {
			break;
		}
	}
	$results = array ();
	if ($use_xml) {
		foreach ($data['handles'] as $i => $ch) {
			$xml = simplexml_load_string (curl_multi_getcontent ($ch));
			curl_multi_remove_handle ($curl_mh, $ch);
			$results[$i] = array ();
			foreach ($xml as $tag => $value) {
				$svalue = (string) $value;
				if (strlen ($svalue) > 0) {
					if ($svalue[0] == '"') $results[$i][$tag] = trim ($svalue, '"');
					else $results[$i][$tag] = (int) $svalue;
				}
			}
		}
	} else {
		foreach ($data['handles'] as $i => $ch) {
			$r = curl_multi_getcontent ($ch);
			curl_multi_remove_handle ($curl_mh, $ch);
			$results[] = $r;
		}
	}
	curl_multi_close ($curl_mh);
	return $results;
}

function createDefaultConfig($version, $port, $multisensor = false, $eyesis_mode = 0) { // / 0 - not eyesis, 1-3 - camera number
    $lepton35 = $GLOBALS['camera_state_arr']['is_lepton35'];
    $boson640 = $GLOBALS['camera_state_arr']['is_boson640'];
    $SENSOR_RUN = ELPHEL_CONST_SENSOR_RUN_CONT; // / turn on sensor in continuous mode
	$COMPRESSOR_RUN = ELPHEL_CONST_COMPRESSOR_RUN_CONT; // / run compressor in continuous mode
	$HISTMODE_Y = ELPHEL_CONST_TASKLET_HIST_ONCE;
	$HISTMODE_C = ELPHEL_CONST_TASKLET_HIST_ONCE;
	$SCALES_CTL = ELPHEL_CONST_CSCALES_CTL_NORMAL;
	$sensor_regs_groups =       ""; // XML for extra members of init + sensor_essential (new group)
	$sensor_regs_descriptions = ""; // XML for descriptions of sensor-specific registers (add to older sensors)
	$sensor_regs_defaults =     ""; // XML for defaults for some sensor-specific registers 
	// /overwrites
	$TRIG_MASTER = 0; // modify for bottom 2 for eyesis? or rely on auto?
	if ($lepton35){
	   $TRIG = 0;
	} else {
	   $TRIG = 4; // $multisensor ? 4 : 0;
	}
	
	$appmode = get_application_mode();
	if ($boson640){
	    $TRIG_PERIOD =  1667000; //59.99Hz
	    $TRIG =         0; // maybe update later, for now - start with free-running
	}elseif (($appmode&0xf0000)!=0){
		$TRIG_PERIOD = 25000000;
	}else{
		$TRIG_PERIOD = $eyesis_mode ?25000000 : 10000000; // 10 fps
	}
	
	$TRIG_CONDITION = $eyesis_mode ? 0x08000 : 0x00000;
	$TRIG_OUT =       $eyesis_mode ? 0x20000 : 0x00000;
	$MULTI_MODE = $multisensor ? 1 : 0;
	$GAMMA_CORR = $eyesis_mode ? 0x09320400 : 0x0a390400;
	$SATURATION = $eyesis_mode ? 220 : 200; // / should be changed with gamma (lower the gamma - higher the required saturation)
	$WB_EN = $eyesis_mode ? 0 : 1;
	$GAINR = $eyesis_mode ? 0x2d26e : 0x20000;
	$GAING = $eyesis_mode ? 0x20000 : 0x20000;
	$GAINB = $eyesis_mode ? 0x2722c : 0x20000;
	$GAINGB = $eyesis_mode ? 0x20000 : 0x20000;
	$RSCALE = $eyesis_mode ? 0x16937 : 0x10000;
	$GSCALE = $eyesis_mode ? 0x10000 : 0x10000;
	$BSCALE = $eyesis_mode ? 0x13916 : 0x10000;
	$QUALITY = $eyesis_mode ? 97 : 80;
	$CORING_INDEX = $eyesis_mode ? 0x140014 : 0x50005;
	$PORTRAIT = $eyesis_mode ? 1 : 0;
	$AUTOEXP_EXP_MAX = $eyesis_mode ? 1000 : 500000;
	$AEXP_FRACPIX = $eyesis_mode ? 0xf333 : 0xfdf3; // 99.2% - sometimes 99.8% is unstable WB 0xff80; // 95% : 99.8%
	$AEXP_LEVEL = $eyesis_mode ? 0xc800 : 0xf800; // 200 : 250
	$DAEMON_EN_TEMPERATURE = ($eyesis_mode > 100) ? 1 : 0; // or enable it for all?

	// / Default parameters to be overwritten by particular applications
	$FLIPH = 0;
	$FLIPV = 0;
	$MULTI_FLIPH = 0;
	$MULTI_FLIPV = 0;
	$MULTI_SELECTED = 1;
	$HISTWND_RWIDTH = 0x8000;
	$HISTWND_RHEIGHT = 0x8000;
	$HISTWND_RLEFT = 0x8000;
	$HISTWND_RTOP = 0x8000;
	if ($lepton35) {
	    $COLOR = 15; // raw
	    $BITS =  16;
	    $OVERSIZE = 1;
	    $WOI_HEIGHT = 120; // w/o telemetry
	} elseif ($boson640) {
	    $COLOR = 15; // raw
	    $BITS =  16;
	    $OVERSIZE = 1;
	    $WOI_HEIGHT = 513; // with telemetry - was:  512; // w/o telemetry
	} else {
        $COLOR =  0;
        $BITS =   8;
        $OVERSIZE = 0;
        $WOI_HEIGHT = 10000;
	}
	log_msg("boson640=".$boson640.", COLOR=".$COLOR.", BITS=".$BITS);
	
    //old:
    //$SENSOR_PHASE =  $multisensor ? 0x55 : 0x15;
    //new:
    $SENSOR_PHASE =  $multisensor ? 0x55 : 0x00;

	/*
	* if ($GLOBALS['camera_state_arr']['application']=="Eyesis4pi393"){ //get_eyesis_mode
	*   $GLOBALS['camera_state_arr']['is_eyesis'] = 1;
	* }
	*
	* $eyesis_mode = $GLOBALS['camera_state_arr']['is_eyesis']?$GLOBALS['camera_state_arr']['mode']:0;
	*/
	$mode = intval($GLOBALS['camera_state_arr']['mode']);
	if (!$eyesis_mode){
		switch($mode){
			case 15:
				$TRIG_MASTER = $GLOBALS['master_port'];
				break;
		}
		
		if ($mode!=15){
			if ($mode&0xf0000!=0){
				log_msg("setting master port = ".$GLOBALS['master_port']);
				$TRIG_MASTER = $GLOBALS['master_port'];
			}
		}
		
	}

	log_msg("multisensor=".$multisensor.", SENSOR_PHASE=".$SENSOR_PHASE);
	switch ($eyesis_mode) {
		case 1 :
			$MULTI_FLIPH = 0;
			$MULTI_FLIPV = 5;
			$MULTI_SELECTED = 2;
			$HISTWND_RWIDTH = 0xc000;
			$HISTWND_RHEIGHT = 0xffff;
			$HISTWND_RLEFT = 0xffff;
			$HISTWND_RTOP = 0x8000;
			break;
		case 2 :
			$MULTI_FLIPH = 0;
			$MULTI_FLIPV = 2;
			$MULTI_SELECTED = 1;
			$HISTWND_RWIDTH = 0xc000;
			$HISTWND_RHEIGHT = 0xffff;
			$HISTWND_RLEFT = 0xffff;
			$HISTWND_RTOP = 0x8000;
			$COLOR = 5;
			break;
		case 3 :
			$MULTI_FLIPH = 0;
			$MULTI_FLIPV = 4;
			$MULTI_SELECTED = 1;
			$HISTWND_RWIDTH = 0xc000;
			$HISTWND_RHEIGHT = 0xAAAB;
			$HISTWND_RLEFT = 0xffff;
			$HISTWND_RTOP = 0xffff;
			$COLOR = 5;
			break;
		// Eyesis4pi
		case 101 :
		case 103 :
		case 105 :
		case 107 :
			$MULTI_FLIPH = 0;
			$MULTI_FLIPV = 4;
			$MULTI_SELECTED = 1;
			$HISTWND_RWIDTH = 0xc000;
			$HISTWND_RHEIGHT = 0xffff;
			$HISTWND_RLEFT = 0xffff;
			$HISTWND_RTOP = 0x8000;
			$COLOR = 5;
			break;
		case 102 :
		case 104 :
		case 106 :
		case 108 :
			$MULTI_FLIPH = 0;
			$MULTI_FLIPV = 3;
			$MULTI_SELECTED = 1;
			$HISTWND_RWIDTH = 0xc000;
			$HISTWND_RHEIGHT = 0xffff;
			$HISTWND_RLEFT = 0xffff;
			$HISTWND_RTOP = 0x8000;
			$COLOR = 5;
			break;
		case 109 :
			$MULTI_FLIPH = 0;
			$MULTI_FLIPV = 2;
			$MULTI_SELECTED = 1;
			$HISTWND_RWIDTH = 0xc000;
			$HISTWND_RHEIGHT = 0xffff;
			$HISTWND_RLEFT = 0xffff;
			$HISTWND_RTOP = 0x8000;
			$COLOR = 5;
			break;
			// Eyesis4pi393
		case 1001 : // top one
			switch ($port) {
				case 0 :
					$MULTI_FLIPH = 0;
					$MULTI_FLIPV = 4;
					$MULTI_SELECTED = 1;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					break;
				case 1 :
					$MULTI_FLIPH = 0;
					$MULTI_FLIPV = 3;
					$MULTI_SELECTED = 1;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					break;
				case 2 :
					$MULTI_FLIPH = 0;
					$MULTI_FLIPV = 3;
					$MULTI_SELECTED = 1;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					break;
				case 3 :
					$MULTI_FLIPH = 0;
					$MULTI_FLIPV = 4;
					$MULTI_SELECTED = 1;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					break;
			}
			break;
		case 1002 : // middle one
			switch ($port) {
				case 0 :
					$MULTI_FLIPH = 0;
					$MULTI_FLIPV = 4;
					$MULTI_SELECTED = 1;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					break;
				case 1 :
					$MULTI_FLIPH = 0;
					$MULTI_FLIPV = 3;
					$MULTI_SELECTED = 1;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					break;
				case 2 :
					$MULTI_FLIPH = 0;
					$MULTI_FLIPV = 3;
					$MULTI_SELECTED = 1;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					break;
				case 3 :
					$MULTI_FLIPH = 0;
					$MULTI_FLIPV = 4;
					$MULTI_SELECTED = 1;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					break;
			}
			break;
		case 1003 : // bottom one
			switch ($port) {
				case 2 :
					$MULTI_SELECTED = 0;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					$TRIG_MASTER = 2;
					break;
				case 3 :
					$FLIPV = 1;
					$MULTI_SELECTED = 0;
					$HISTWND_RWIDTH = 0xc000;
					$HISTWND_RHEIGHT = 0xffff;
					$HISTWND_RLEFT = 0xffff;
					$HISTWND_RTOP = 0x8000;
					$COLOR = 5;
					$TRIG_MASTER = 2;
					break;
			}
			break;
		log_msg ("**** ERROR: Invalid eyesis_mode value: $eyesis_mode");
	}

	// adding sensor-specific SENSOR_REGS
	if ($boson640){
	    $sensor_regs_groups = <<<SENSOR_REGS_GROUPS
    <SENSOR_REGS23>"sensor_regs"</SENSOR_REGS23>
    <SENSOR_REGS26>"sensor_regs"</SENSOR_REGS26>
    <SENSOR_REGS29>"init,sensor_regs"</SENSOR_REGS29>
SENSOR_REGS_GROUPS;
	    $sensor_regs_descriptions = <<<SENSOR_REGS_DESCRIPTIONS
    <SENSOR_REGS23>"Telemetry control: 0 - disable, 1 - enable (controlled through WOI_WIDTH)"</SENSOR_REGS23>
    <SENSOR_REGS26>"Run FFC (any value)"</SENSOR_REGS26>
    <SENSOR_REGS29>"FFC mode: 0 - manual, 1 - auto , 2 - external, 3 - shutter test"</SENSOR_REGS29>
SENSOR_REGS_DESCRIPTIONS;
	    $sensor_regs_defaults = <<<SENSOR_REGS_DEFAULTS
<!--    <SENSOR_REGS23>"sensor_regs"</SENSOR_REGS23>
    <SENSOR_REGS26>"sensor_regs"</SENSOR_REGS26> -->
    <SENSOR_REGS29>0</SENSOR_REGS29>
SENSOR_REGS_DEFAULTS;
	}
	
	log_msg ("Generating default config for port $port, multiplexed= $multisensor eyesis_mode=$eyesis_mode");

	log_msg ("MULTI_FLIPH =     $MULTI_FLIPH");
	log_msg ("MULTI_FLIPV =     $MULTI_FLIPV");
	log_msg ("MULTI_SELECTED =  $MULTI_SELECTED");
	log_msg ("HISTWND_RWIDTH =  $HISTWND_RWIDTH");
	log_msg ("HISTWND_RHEIGHT = $HISTWND_RHEIGHT");
	log_msg ("HISTWND_RLEFT =   $HISTWND_RLEFT");
	log_msg ("HISTWND_RTOP =    $HISTWND_RTOP");
	log_msg ("COLOR =           $COLOR");
	log_msg ("TRIG_MASTER =     $TRIG_MASTER");
	log_msg ("TRIG =            $TRIG");
	log_msg ("TRIG_PERIOD =     $TRIG_PERIOD");
	log_msg ("TRIG_CONDITION =  $TRIG_CONDITION");
	log_msg ("TRIG_OUT =        $TRIG_OUT");
	log_msg ("MULTI_MODE =      $MULTI_MODE");

/*
	return <<<DEFAULT_CONFIG
DEFAULT_CONFIG

 * function createDefaultConfig($version, $port, $multisensor = false, $eyesis_mode = 0) { // / 0 - not eyesis, 1-3 - camera number

							elphel_set_P_value ( $sensor_port, ELPHEL_MAXAHEAD, 2, 0, 8 ); // / When servicing interrupts, try programming up to 2 frames ahead of due time)
// 2016/09/09: Seems that with defualt 63, even on a single-channel autoexposure+ moving WOI breaks acquisition)
// With increased delay - seems OK
// Resizing - still breaks, probably for different reason
							elphel_set_P_value ( $sensor_port, ELPHEL_MEMSENSOR_DLY, 1024, $frame + 2, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );

							elphel_set_P_value ( $sensor_port, ELPHEL_FPGA_XTRA, 1000, $frame + 3, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC ); // /compressor needs extra 1000 cycles to compress a frame (estimate)
//							elphel_set_P_value ( $sensor_port, ELPHEL_EXTERN_TIMESTAMP, 1, $frame + 3, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC ); // / only used with async trigger

							// / good (latency should be changed, but for now that will make a correct initialization - maybe obsolete)

							elphel_set_P_value ( $sensor_port, ELPHEL_BITS, 8, $frame + 3, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_QUALITY, 80, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_COLOR, ELPHEL_CONST_COLORMODE_COLOR, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_COLOR_SATURATION_BLUE, 200, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_COLOR_SATURATION_RED, 200, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_BAYER, 0, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_GAING, 0x10000, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_GAINGB, 0x10000, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_GAINR, 0x10000, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_GAINB, 0x10000, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_VIGNET_C, 0x8000, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_VIGNET_SHL, 1, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_SENSOR_RUN, 2, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC );
							elphel_set_P_value ( $sensor_port, ELPHEL_COMPRESSOR_RUN, 2, $frame + 4, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC ); // / run compressor

 */




	// will create $SENSOR_PHASE=...;MULTI_PHASE1=...;MULTI_PHASE3=...;)
//	extract ( calculateDefaultPhases () ); // read sensor phases from memory or calculate from the eeproms for newer devices
	// / Now select window orientations based on eyesis_mode
	return <<<DEFAULT_CONFIG
<?xml version="1.0" standalone="yes"?>
<!-- This file is generated by {$_SERVER ['argv'] [0]}  -->
<autocampars>
<!-- File version -->
  <version>$version</version>
<!-- Parameter groups that can be restored from the saved values  -->
  <groupNames>
   <init          bit="0" >"Initialization"</init>
   <woi           bit="1" >"Window of Interest (WOI)"</woi>
   <image         bit="2" >"Image color, quality, ..."</image>
   <histWnd       bit="3" >"Histogram Window"</histWnd>
   <autoexposure  bit="5" >"Autoexposure"</autoexposure>
   <whiteBalance  bit="6" >"White Balance"</whiteBalance>
   <streamer      bit="7" >"Streamer"</streamer>
   <camftp        bit="8" >"FTP Upload"</camftp>
   <camogm        bit="9" >"In-camera recording"</camogm>
   <vignet        bit="10" >"Vignetting correction"</vignet>
   <multisensor   bit="11" >"Muti-sensor parameters"</multisensor>
   <trigger       bit="12" >"Camera trigger modes"</trigger>
   <sensor_regs   bit="13" >"Sensor-specific essential registers"</sensor_regs>
   <persistent    bit="21" >"Global parameters that survive sensor initializaion</persistent>
   <unsafe        bit="22" >"Not safe yet"</unsafe>
   <diagn         bit="23">"Diagnostics, debug"</diagn>
  </groupNames>
<!-- Number of parameter page that will be used as default (i.e. after camera boot) -->
<defaultPage>0</defaultPage>
<!-- Number of parameter page that will be next used to save parameters (if not specified) -->
<nextPage>1</nextPage>
<!-- Descriptions of the parameters -->
  <descriptions>
     <comment>"init"</comment>
     <timestamp>"init"</timestamp>
     <SENSOR>"Sensor ID number is determined by driver. Writing 0 to this location will re-start sensor identification"</SENSOR>
     <SENSOR_RUN>"Sensor can be stopped (0), acquire a single frame (1) or run continuously (2)"</SENSOR_RUN>
     <SENSOR_SINGLE>"Pseudo-register to write SENSOR_RUN_SINGLE here. Same as SENSOR_RUN, just the command will not propagate to the next frames"</SENSOR_SINGLE>
     <ACTUAL_WIDTH>"Actual image size (appropriately divided when decimation is used) - readonly"</ACTUAL_WIDTH>
     <ACTUAL_HEIGHT>"Actual image height (appropriately divided when decimation is used) - readonly"</ACTUAL_HEIGHT>
     <BAYER>"Bayer mosaic shift 0..3 (+1 - swap horisontally,+2 - swap vertically)"</BAYER>
     <PERIOD>"Frame period in pixel clock cycles - readonly"</PERIOD>
     <FP1000SLIM>"FPS limit as integer number of frames per 1000 seconds"</FP1000SLIM>
     <FRAME>"Absolute frame number, counted by the sensor frame sync pulses. Includes the frames that are not compressed and never appeared in the circbuf."</FRAME>
     <CLK_FPGA>"Sensor clock in HZ (so 96MHz is 96000000)"</CLK_FPGA>
     <CLK_SENSOR>"FPGA compressor/memory clock in HZ (so 1600Hz is 160000000)"</CLK_SENSOR>
     <FPGA_XTRA>"Extra clock cycles compressor needs to compress a frame in addition to the number of compressed pixels (for non-jp4 images each sensor pixel needs 3 FPGA clock cycles, for some of the jp4 modes - 2 clocks/pixel"</FPGA_XTRA>
     <TRIG>"Trigger mode. currently 0 - free running, 4 - triggered by external signal or FPGA timing generator, 20 - triggered in GRR molde."</TRIG>
     <TRIG_MASTER>"Master sensor_port (0..3) for triggering setup, other ports will have settings duplicated"</TRIG_MASTER>
     <EXPOS>"Exposure time in microseconds. Sensor driver modifies the value of this parameter to be multiple of sensor scan line times (see VEXPOS)"</EXPOS>
     <BGFRAME></BGFRAME>
     <IMGSZMEM></IMGSZMEM>
     <PAGE_ACQ></PAGE_ACQ>
     <PAGE_READ></PAGE_READ>
     <OVERLAP></OVERLAP>
     <VIRT_KEEP>"Preserve \"virtual window\" size when resizing the window of interest (WOI) if non-zero. That will preserve the same FPS when resizing WOI"</VIRT_KEEP>
     <VIRT_WIDTH>"Width of the virtual window defines the time of reading out 1 sensor scan line. Normally this parameter is determined by the driver automatically, but may be manually modified."</VIRT_WIDTH>
     <VIRT_HEIGHT>"Height of the virtual window defines the frame duration in scan lines. Readout period (in free-running, not externally triggered mode) is equal to the product of VIRT_WIDTH * VIRT_HEIGHT. Normally this parameter is determined by the driver automatically, but may be manually modified."</VIRT_HEIGHT>
     <WOI_LEFT>"Window of interest left margin. Should be even number"</WOI_LEFT>
     <WOI_TOP>"Window of interest top margin. Should be even number"</WOI_TOP>
     <WOI_WIDTH>"Window of interest width. Should be multiple of 16 (divided by decimation if any). This parameter is modified by the driver according to the sensor capabilities, so if you put 10000 this value will be reduced to the full sensor width."</WOI_WIDTH>
     <WOI_HEIGHT>"Window of interest height. Should be multiple of 16 (divided by decimation if any). This parameter is modified by the driver according to the sensor capabilities, so if you put 10000 this value will be reduced to the full sensor width."</WOI_HEIGHT>
     <FLIPH>"Mirroring (flipping) the image horizontally. Driver is aware of the sensor orientation in Elphel cameras so value 0 is used for normal image orientation when captured by the camera (contrary to the previously released software)"</FLIPH>
     <FLIPV>"Mirroring (flipping) the image vertically. Driver is aware of the sensor orientation in Elphel cameras so value 0 is used for normal image orientation when captured by the camera (contrary to the previously released software)"</FLIPV>
     <FPSFLAGS>"FPS limit mode - bit 0 - limit fps (not higher than), bit 1 - maintain fps (not lower than)"</FPSFLAGS>
     <DCM_HOR>"Horizontal decimation of the image (as supported by the sensor). Actual number of pixels read from the senor will is divided (from the WOI size) by this value (0 considered to be the same as 1)"</DCM_HOR>
     <DCM_VERT>"Vertical decimation of the image (as supported by the sensor). Actual number of pixels read from the senor will is divided (from the WOI size) by this value (0 considered to be the same as 1)"</DCM_VERT>
     <BIN_HOR>"Horizontal binning (adding/averaging) of several adjacent  pixels  of the same color (so odd and even pixels are processed separately) as supported by the sensor."</BIN_HOR>
     <BIN_VERT>"Vertical binning (adding/averaging) of several adjacent  pixels  of the same color (so odd and even pixels are processed separately) as supported by the sensor."</BIN_VERT>
     <FPGATEST>"Replace the image from the sensor with the internally generated test pattern. Currently only two values are supported: 0 - npormal image, 1 horizontal gradient.</FPGATEST>
     <TESTSENSOR>"Sensor internal test modes: 0x10000 - enable, lower bits - test mode value"</TESTSENSOR>
     <COLOR>"Compressor modes (only modes 0..2 can be processed with standard libjpeg):`
               0 - color, YCbCr 4:2:0, 3x3 pixels`
               1 - mono6, monochrome (color YCbCr 4:2:0 with zeroed out color componets)`
               2 - jp46 - original jp4, encoded as 4:2:0 with zeroed color components`
               3 - jp46dc, modified jp46 so each color component uses individual DC diffenential encoding`
               4 - reserved for color with 5x5 conversion (not yet implemented)`
               5 - jp4 with ommitted color components (4:0:0)`
               6 - jp4dc, similar to jp46dc encoded as 4:0:0`
               7 - jp4diff, differential where (R-G), G, (G2-G) and (B-G) components are encoded as 4:0:0`
               8 - jp4hdr, (R-G), G, G2,(B-G) are encoded so G2 can be used with high gain`
               9 - jp4fiff2, (R-G)/2, G,(G2-G)/2, (B-G)/2 to avoid possible overflow in compressed values`
              10 - jp4hdr2, (R-G)/2, G,G2,(B-G)/2`
              14 - mono,  monochrome with ommitted color components (4:0:0)"</COLOR>
     <FRAMESYNC_DLY>"not used, should be 0"</FRAMESYNC_DLY>
     <PF_HEIGHT>"Height of the strip in photofinish mode (not functional)"</PF_HEIGHT>
     <BITS>"data width stored from the sensor - can be either 8 or 16. 16 bit mode bypasses gamma-conversion, but it is not supported by the compressor"</BITS>
     <SHIFTL>"not used, should be 0"</SHIFTL>
     <FPNS>"FPN correction subtract scale - not yet supported by current software"</FPNS>
     <FPNM>"FPN correction multiply scale - not yet supported by current software"</FPNM>
     <VEXPOS>"Exposure measured in sensor native units - number of scan lines, it can be any integer number, while the EXPOS measured in microseconds is modified by the driver to make it multiple of scan lines. Both VEXPOS and EXPOS can be used to specify exposure."</VEXPOS>
     <VIRTTRIG>"Not used, should be 0"</VIRTTRIG>
     <PERIOD_MIN>Minimal frame period (in pixel clock cycles) calculated by the driver from the user and hardware limitations (readonly)</PERIOD_MIN>
     <PERIOD_MAX>Maximal frame period (in pixel clock cycles) calculated by the driver from the user and hardware limitations  (readonly)</PERIOD_MAX>
     <SENSOR_PIXH>Pixels to be read from the sensor, horizontal,incliding margins, excluding embedded timestamps (readonly)</SENSOR_PIXH>
     <SENSOR_PIXV>Pixels to be read from the sensor, vertical, incliding margins (readonly)</SENSOR_PIXV>
     <GAINR>"Red channel sensor overall (analog and digital) gain multiplied by 0x10000, so 0x10000 corresponds to  x1.0 gain. If ANA_GAIN_ENABLE is enabled this overall gain is split between the sensor analog gain and digital scalining. Digital scaling is needed to fill the gaps in between large analog gain steps."</GAINR>
     <GAING>"Green channel sensor overall (analog and digital) gain multiplied by 0x10000, so 0x10000 corresponds to  x1.0 gain.  If ANA_GAIN_ENABLE is enabled this overall gain is split between the sensor analog gain and digital scalining. Digital scaling is needed to fill the gaps in between large analog gain steps. Green channel is used in automatic exposure adjustment and as reference to differencial color gains. When changing the value of GAING other gains will be changed proportionally if their ratios to green are preserved (see RSCALE, GSCALE, BSCALE"</GAING>
     <GAINGB>"Second green (GB - green in blue line) channel sensor overall (analog and digital) gain multiplied by 0x10000, so 0x10000 corresponds to  x1.0 gain. Normally the second green channel is programmed to have the same gain as the first green, but can be used separately for HDR applications. If ANA_GAIN_ENABLE is enabled this overall gain is split between the sensor analog gain and digital scalining. Digital scaling is needed to fill the gaps in between large analog gain steps."</GAINGB>
     <GAINB>"Blue channel sensor overall (analog and digital) gain multiplied by 0x10000, so 0x10000 corresponds to  x1.0 gain.  If ANA_GAIN_ENABLE is enabled this overall gain is split between the sensor analog gain and digital scalining. Digital scaling is needed to fill the gaps in between large analog gain steps."</GAINB>
     <RSCALE_ALL>"Combines RSCALE and RSCALE_CTL data"</RSCALE_ALL>
     <GSCALE_ALL>"Combines GSCALE and GSCALE_CTL data"</GSCALE_ALL>
     <BSCALE_ALL>"Combines BSCALE and BSCALE_CTL data"</BSCALE_ALL>
     <RSCALE>"Ratio of gains in Red and Green (base) colors, multiplied by 0x10000. This value is connected to individual gains: GAINR and GAING, when you change RSCALE it will cause GAINR to be updated also (if RSCALE is not disabled in RSCALE_CTL). When GAINR is changed, this RSCALE value may also change (or not - depending on the RSCALE_CTL)"</RSCALE>
     <GSCALE>"Ratio of gains in Green2 and Green (base) colors, multiplied by 0x10000. This value is connected to individual gains: GAINGB and GAING, when you change GSCALE it will cause GAINGB to be updated also (if GSCALE is not disabled in GSCALE_CTL). When GAINGB is changed, this GSCALE value may also change (or not - depending on the GSCALE_CTL). This second green scale should normally have the value 0x10000 (1.0) - it may be different only in some HDR modes."</GSCALE>
     <BSCALE>"Ratio of gains in Blue and Green (base) colors, multiplied by 0x10000. This value is connected to individual gains: GAINB and GAING, when you change BSCALE it will cause GAINB to be updated also (if BSCALE is not disabled in BSCALE_CTL). When GAINB is changed, this BSCALE value may also change (or not - depending on the BSCALE_CTL)"</BSCALE>
     <RSCALE_CTL>"A 2-bit RSCALE control. The following constants are defined:`
        ELPHEL_CONST_CSCALES_CTL_NORMAL -  use RSCALE to update GAINR and be updated when GAINR is changed`
        ELPHEL_CONST_CSCALES_CTL_RECALC -  recalculate RSCALE from GAINR/GAING once, then driver will modify the RSCALE_CTL value to ELPHEL_CONST_CSCALES_CTL_NORMAL`
        ELPHEL_CONST_CSCALES_CTL_FOLLOW -  update RSCALE from GAINR/GAING, but ignore any (external to the driver) changes to RSCALE itself`
        ELPHEL_CONST_CSCALES_CTL_DISABLE - completely disable RSCALE - do not update it from GAINR and ignore any external changes to RSCALE`"</RSCALE_CTL>
     <GSCALE_CTL>"A 2-bit GSCALE control. The following constants are defined:`
        ELPHEL_CONST_CSCALES_CTL_NORMAL -  use GSCALE to update GAINGB and be updated when GAINGB is changed`
        ELPHEL_CONST_CSCALES_CTL_RECALC -  recalculate GSCALE from GAINGB/GAING once, then driver will modify the GRSCALE_CTL value to ELPHEL_CONST_CSCALES_CTL_NORMAL`
        ELPHEL_CONST_CSCALES_CTL_FOLLOW -  update GSCALE from GAINGB/GAING, but ignore any (external to the driver) changes to GSCALE itself`
        ELPHEL_CONST_CSCALES_CTL_DISABLE - completely disable GSCALE - do not update it from GAING and ignore any external changes to GSCALE`"</GSCALE_CTL>
     <BSCALE_CTL>"A 2-bit BSCALE control. The following constants are defined:`
        ELPHEL_CONST_CSCALES_CTL_NORMAL -  use BSCALE to update GAINB and be updated when GAINB is changed`
        ELPHEL_CONST_CSCALES_CTL_RECALC -  recalculate BSCALE from GAINB/GAING once, then driver will modify the BSCALE_CTL value to ELPHEL_CONST_CSCALES_CTL_NORMAL`
        ELPHEL_CONST_CSCALES_CTL_FOLLOW -  update BSCALE from GAINB/GAING, but ignore any (external to the driver) changes to BSCALE itself`
        ELPHEL_CONST_CSCALES_CTL_DISABLE - completely disable BSCALE - do not update it from GAINB and ignore any external changes to BSCALE`"</BSCALE_CTL>
     <FATZERO>"not used"</FATZERO>
     <QUALITY>"JPEG compression quality in percents. Supports individual setting of the Y and C quantization tables and quality values when the second byte is non-zero. Table zero is used always used for Y components (and all JP4/JP46 ones), table for C components is determined by the bit 15. When this bit is 0 the color quantization table (table 1) is used, when it is one - Y quantization table (table 0). If bits 8..14 are all zero, the same quality is used for both Y and C (with Y and C tables, respectively). Bit 7 determins if the standard JPEG table is used (bit7==0) or transposed one for portrait mode (bit7=1)."</QUALITY>
     <PORTRAIT>"JPEG quantization tables are optimezed for human perception when the scan lines are horizontal. If the value of PORTRAIT parameter is odd, these tables are transposed to be optimal for vertical scan lines. 0 - landscape with first line on top,  1 first line oin right, 2 - fierst line on the bottom and 3 - first line on the left. "</PORTRAIT>
     <CORING_INDEX>"Combined coring index for Y and C components - MSW - for C, LSW - for Y. Each is in the range 0f 0..99, default is 0x50005 (5/5). Highter values reduce noise (and file size) but can cause compression artifacts. The optimal values depend on compression quality, the higher the quality the larger the coring idexes are needed. Index 10 corresponds to 1.0 in quantized DCT coefficients, coefficients below index/10 are effectively zeroed out."</CORING_INDEX>
     <FP1000S>"Current sensor frame rate measured in frames per 1000 seconds"</FP1000S>
     <SENSOR_WIDTH>Sensor width in pixels (readonly)</SENSOR_WIDTH>
     <SENSOR_HEIGHT>Sensor height in pixels (readonly)</SENSOR_HEIGHT>
     <COLOR_SATURATION_BLUE>"Saturation of blue color (B-G) in percents. This scale value is used in the Bayer-to-YCbCr converter that feeds the JPEG compressor. Normally the saturation should be more than 100 to compensate the color washout when the gamma correction value is less than 1.0, because the gamma correction (which is applied to the raw Bayer pixel data) decrease relative (divided by the full value) difference between color components"</COLOR_SATURATION_BLUE>
     <COLOR_SATURATION_RED>"Saturation of red color (R-G) in percents. This scale value is used in the Bayer-to-YCbCr converter that feeds the JPEG compressor. Normally the saturation should be more than 100 to compensate the color washout when the gamma correction value is less than 1.0, because the gamma correction (which is applied to the raw Bayer pixel data) decrease relative (divided by the full value) difference between color components"</COLOR_SATURATION_RED>
      <VIGNET_AX>"AX in AX*X^2+BX*X+AY*Y^2+BY*Y+C"</VIGNET_AX>
      <VIGNET_AY>"AY in AX*X^2+BX*X+AY*Y^2+BY*Y+C"</VIGNET_AY>
      <VIGNET_BX>"BX in AX*X^2+BX*X+AY*Y^2+BY*Y+C"</VIGNET_BX>
      <VIGNET_BY>"BY in AX*X^2+BX*X+AY*Y^2+BY*Y+C"</VIGNET_BY>
      <VIGNET_C>"C in AX*X^2+BX*X+AY*Y^2+BY*Y+C"</VIGNET_C>
      <VIGNET_SHL>"Additional shift left of the vignetting correction multiplied by digital gain. Default 1"</VIGNET_SHL>
      <SCALE_ZERO_IN>"Will be subtracted from the 16-bit unsigned scaled sensor data before multiplying by vignetting correction and color balancing scale. It is a 17-bit signed data"</SCALE_ZERO_IN>
      <SCALE_ZERO_OUT>"Will be added to the result of multiplication of the 16-bit sennsor data (with optionally subtracted SCALE_ZERO_IN) by color correction coefficient/vignetting correction coefficient"</SCALE_ZERO_OUT>
      <DGAINR>"&quot;Digital gain&quot; for the red color channel - 17 bit unsigned value. Default value is 0x8000 fro 1.0, so up to 4X gain boost is available before saturation"</DGAINR>
      <DGAING>"&quot;Digital gain&quot; for the green color channel - 17 bit unsigned value. Default value is 0x8000 fro 1.0, so up to 4X gain boost is available before saturation"</DGAING>
      <DGAINGB>"&quot;Digital gain&quot; for second green color channel - 17 bit unsigned value. Default value is 0x8000 fro 1.0, so up to 4X gain boost is available before saturation"</DGAINGB>
      <DGAINB>"&quot;Digital gain&quot; for the blue color channel - 17 bit unsigned value. Default value is 0x8000 fro 1.0, so up to 4X gain boost is available before saturation"</DGAINB>
     <CORING_PAGE>"Number of coring LUT page number. Current software programs only page 0 (of 8) using CORING_INDEX parameter."</CORING_PAGE>
     <TILES>Number of 16x16 (20x20) tiles in a compressed frame (readonly)</TILES>
     <SENSOR_PHASE>"Sensor port signals phase coarse: [1:0] - data 90 degree shift, [6,3:2] - HACT 90 degree (two periods), [5:4] - VACT 90 degree shift  </SENSOR_PHASE>
     <TEMPERATURE_PERIOD>"Period of sensor temperature measurements, ms"</TEMPERATURE_PERIOD>
     <AUTOEXP_ON>"1 - autoexposure enabled when, 0 - autoexpousre disabled. Autoexposure can still be off if the bit responsible for autoexposure daemon in DAEMON_EN is turned off - in the latter case the whole autoexposure daemon will be disabled, including white balancing and hdr mode also."</AUTOEXP_ON>
     <HISTWND_RWIDTH>"Histogram (used for autoexposure, white balancing and just histograms display) window width, relative to the window (WOI) width. It is defined as a fraction of 65536(0x10000), so 0x8000 is 50%"</HISTWND_RWIDTH>
     <HISTWND_RHEIGHT>"Histogram (used for autoexposure, white balancing and just histograms display) window height, relative to the window (WOI) height. It is defined as a fraction of 65536(0x10000), so 0x8000 is 50%"</HISTWND_RHEIGHT>
     <HISTWND_RLEFT>"Histogram (used for autoexposure, white balancing and just histograms display) window left position, relative to the window (WOI) remaining (after HISTWND_RWIDTH). It is defined as a fraction of 65536(0x10000), so when HISTWND_RLEFT=0x8000 and HISTWND_RWIDTH=0x8000 that will put histogram window in the center 50% leaving 25% from each of the left and right WOI limits"</HISTWND_RLEFT>
     <HISTWND_RTOP>"Histogram (used for autoexposure, white balancing and just histograms display) window top position, relative to the window (WOI) remaining (after HISTWND_RHEIGHT). It is defined as a fraction of 65536(0x10000), so when HISTWND_RTOP=0x8000 and HISTWND_RHEIGHT=0x8000 that will put histogram window vertically in the center 50% leaving 25% from each of the top and bottom WOI limits"</HISTWND_RTOP>
     <AUTOEXP_EXP_MAX>"Maximal exposure time allowed to autoexposure daemon  (in microseconds)"</AUTOEXP_EXP_MAX>
     <AUTOEXP_OVEREXP_MAX>"not used"</AUTOEXP_OVEREXP_MAX>
     <AUTOEXP_S_PERCENT>"not used"</AUTOEXP_S_PERCENT>
     <AUTOEXP_S_INDEX>"not used"</AUTOEXP_S_INDEX>
     <AUTOEXP_EXP>"not used"</AUTOEXP_EXP>
     <AUTOEXP_SKIP_PMIN>"not used"</AUTOEXP_SKIP_PMIN>
     <AUTOEXP_SKIP_PMAX>"not used"</AUTOEXP_SKIP_PMAX>
     <AUTOEXP_SKIP_T>"not used"</AUTOEXP_SKIP_T>
     <HISTWND_WIDTH>"Histogram (used for autoexposure, white balancing and just histograms display) window width in pixels (readonly)"</HISTWND_WIDTH>
     <HISTWND_HEIGHT>"Histogram (used for autoexposure, white balancing and just histograms display) window height in pixels (readonly)"</HISTWND_HEIGHT>
     <HISTWND_TOP>"Histogram (used for autoexposure, white balancing and just histograms display) window top position in pixels (readonly)"</HISTWND_TOP>
     <HISTWND_LEFT>"Histogram (used for autoexposure, white balancing and just histograms display) window left position in pixels (readonly)"</HISTWND_LEFT>
     <FOCUS_SHOW>"Show focus information instead of/combined with the image: 0 - regular image, 1 - block focus instead of Y DC (AC=0), 2 - image Y DC combined all frame, 3 combined in WOI"</FOCUS_SHOW>
     <FOCUS_SHOW1>"Additional parameter that modifies visualization mode. Currently just a single bit (how much to add)"</FOCUS_SHOW1>
     <RFOCUS_LEFT>"init"</RFOCUS_LEFT>
     <RFOCUS_WIDTH>"init"</RFOCUS_WIDTH>
     <RFOCUS_TOP>"init"</RFOCUS_TOP>
     <RFOCUS_HEIGHT>"init"</RFOCUS_HEIGHT>
     <MEMSENSOR_DLY>"Delay in memsensor from frame sync (start of command processing) and SoF when it is started"</MEMSENSOR_DLY>
     <FOCUS_LEFT>"Focus WOI left margin, in pixels, inclusive (3 LSB will be zeroed as it should be multiple of 8x8 block width)"</FOCUS_LEFT>
     <FOCUS_WIDTH>"Focus WOI width (3 LSB will be zeroed as it should be multiple of 8x8 block width)"</FOCUS_WIDTH>
     <FOCUS_TOP>"focus WOI top margin, inclusive (3 LSB will be zeroed as it should be multiple of 8x8 block height)"</FOCUS_TOP>
     <FOCUS_HEIGHT>"Focus WOI height (3 LSB will be zeroed as it should be multiple of 8x8 block height)"</FOCUS_HEIGHT>
     <FOCUS_TOTWIDTH>"Total width of the image frame in pixels (readonly)"</FOCUS_TOTWIDTH>
     <FOCUS_FILTER>"Select 8x8 filter used for the focus calculation (same order as quantization coefficients), 0..14"</FOCUS_FILTER>
     <TRIG_CONDITION>"FPGA trigger sequencer trigger condition, 0 - internal, else dibits: 00 - do not use, 01 - keep, 10 - active low, 11 - active high for each GPIO[9:0] pin). 10389 board inverts signals, so 0x80000 - input from external connector,  0x08000 - input from internal flex cable (as in Eyesis). "</TRIG_CONDITION>
     <TRIG_DELAY>"FPGA trigger sequencer trigger delay, 32 bits in pixel clocks"</TRIG_DELAY>
     <TRIG_OUT>"FPGA trigger sequencer trigger output to GPIO, dibits: 00 - do not use, 01 - keep, 10 - active low GPIO output, 11 - active high GPIO output. 10389 board inverts signals, so 0x02000 - output to external connector (active high), 0x20000 - output to internal flex connector"</TRIG_OUT>
     <TRIG_PERIOD>"FPGA trigger sequencer output sync period (32 bits, in 100MHz (10ns) clocks). 0- stop. 1 - single, >=256 repetitive with specified period"</TRIG_PERIOD>
     <TRIG_BITLENGTH>"Bit length minus 1 (in pixel clock cycles) when transmitting/receiving timestamps, without timestamps the output pulse width is 8*(TRIG_BITLENGTH+1). Legal values 2..255."</TRIG_BITLENGTH>
     <EXTERN_TIMESTAMP>"When 1 camera will use external timestamp (received over inter-camera synchronization cable) if it is available (no action when external syncronization is not connected), when 0 - local timestamp will be used"</EXTERN_TIMESTAMP>
     <XMIT_TIMESTAMP>"Specify output signal sent through internal/external connector (defined by TRIG_OUT). 0 - transmit just sync pulse (8*(TRIG_BITLENGTH+1) pixel clock periods long), 1 - pulse+timestamp 64*(TRIG_BITLENGTH+1) pixel clock periods long"</XMIT_TIMESTAMP>
     <TRIG_DECIMATE>"Decimate trigger pulses by (TRIG_DECIMATE + 1). First trigger after setting TRIG_DECIMATE will go through"</TRIG_DECIMATE>
     <SKIP_FRAMES>"Changes parameter latencies tables pages for each of the trigger modes separately (0/1), currently should be 0"</SKIP_FRAMES>
     <I2C_QPERIOD>"Number of system clock periods in 1/4 of i2c SCL period to the sensor/sensor board, set by the driver"</I2C_QPERIOD>
     <I2C_BYTES>"Number of bytes in hardware i2c write (after slave addr) -0/1/2, set by the driver"</I2C_BYTES>
     <IRQ_SMART>"IRQ mode (3 bits) to combine interrupts from the sensor frame sync and compressor when it is running: +1 - wait for VACT in early compressor_done, +2 - wait for dma fifo ready. Current software assumes both bits are set (value=3), set up by the driver". Currently bit 2 (+4) needs to be set to 1 when bit 0 is 0 - otherwise the latest frame will not have parameters copied to. So instead of IRQ_SMART=2 it should be IRQ_SMART=6</IRQ_SMART>
     <OVERSIZE>"0 - normal mode, 1 - ignore sensor dimensions, use absolute WOI_LEFT, WOI_TOP - needed to be able to read optically black pixels"</OVERSIZE>
     <GTAB_R>"Identifies Gamma-table for the red color. Camera can use either automatically generated tables using the provided black level and gamma (in percent) or arbitrary custom tables, in that case the top 16 bits are used as a 16-bit hash (user provided) to distinguish between different loaded tables. The lower 16 bits determine scale applied to the table (saturated to the full scale), so the value is (black_level&lt;&lt;24) | (gamma_in_percent &lt;&lt;16) | (scale_times_0x1000 &amp; 0xffff). In PHP (or PHP scripts) the individual fields of GTAB_R can be referenced with composite names like GTAB_R__0824 for black level, GTAB_R__0816 for gamma in percents and GTAB_R__1600 for scale.</GTAB_R>
     <GTAB_G>"Identifies Gamma-table for the green color. Camera can use either automatically generated tables using the provided black level and gamma (in percent) or arbitrary custom tables, in that case the top 16 bits are used as a 16-bit hash (user provided) to distinguish between different loaded tables. The lower 16 bits determine scale applied to the table (saturated to the full scale), so the value is (black_level&lt;&lt;24) | (gamma_in_percent &lt;&lt;16) | (scale_times_0x1000 &amp; 0xffff). In PHP (or PHP scripts) the individual fields of GTAB_G can be referenced with composite names like GTAB_G__0824 for black level, GTAB_G__0816 for gamma in percents and GTAB_G__1600 for scale.</GTAB_G>
     <GTAB_GB>"Identifies Gamma-table for the second green (in blue line) color. Camera can use either automatically generated tables using the provided black level and gamma (in percent) or arbitrary custom tables, in that case the top 16 bits are used as a 16-bit hash (user provided) to distinguish between different loaded tables. The lower 16 bits determine scale applied to the table (saturated to the full scale), so the value is (black_level&lt;&lt;24) | (gamma_in_percent &lt;&lt;16) | (scale_times_0x1000 &amp; 0xffff). In PHP (or PHP scripts) the individual fields of GTAB_GB can be referenced with composite names like GTAB_GB__0824 for black level, GTAB_GB__0816 for gamma in percents and GTAB_GB__1600 for scale.</GTAB_GB>
     <GTAB_B>"Identifies Gamma-table for the blue color. Camera can use either automatically generated tables using the provided black level and gamma (in percent) or arbitrary custom tables, in that case the top 16 bits are used as a 16-bit hash (user provided) to distinguish between different loaded tables. The lower 16 bits determine scale applied to the table (saturated to the full scale), so the value is (black_level&lt;&lt;24) | (gamma_in_percent &lt;&lt;16) | (scale_times_0x1000 &amp; 0xffff). In PHP (or PHP scripts) the individual fields of GTAB_B can be referenced with composite names like GTAB_B__0824 for black level, GTAB_B__0816 for gamma in percents and GTAB_B__1600 for scale.</GTAB_B>
     <SDRAM_CHN20>"Internal value used by the driver (memory controller register 0 channel 2)"</SDRAM_CHN20>
     <SDRAM_CHN21>"Internal value used by the driver (memory controller register 1 channel 2)"</SDRAM_CHN21>
     <SDRAM_CHN22>"Internal value used by the driver (memory controller register 2 channel 2)"</SDRAM_CHN22>
     <COMPRESSOR_RUN>"Compressor state: 0 - stopped, 1 - compress single frame, 2 - run continuously. Some applications (streamer, videorecorder) rely on this register to be set to 2"</COMPRESSOR_RUN>
     <COMPRESSOR_SINGLE>"Pseudo-register to write COMPERRSOR_RUN_SINGLE here. Same as COMPRESSOR_RUN, just the command will not propagate to the next frames"</COMPRESSOR_SINGLE>
     <COMPMOD_BYRSH>"Additional bayer shift in compressor only (to swap meanings of the colors), 0..3"</COMPMOD_BYRSH>
     <COMPMOD_TILSH>"Diagonal shift of the 16x16 pixel block in the 20x20 tile that compressor receives (0 - top left corner is (0,0), ..., 4 - top left corner is (4,4))"</COMPMOD_TILSH>
     <COMPMOD_DCSUB>"Subtract average block pixel value before DCT and add it back after"</COMPMOD_DCSUB>
     <COMPMOD_QTAB>"Quantization table bank number (set by the driver)"</COMPMOD_QTAB>
     <SENSOR_REGS>Sensor internal registers (sensor-specific). In PHP scripts it is possible to reference individual register/bit fields with composite names, i.e. SENSOR_REGS160__0403 in Micron MT9P031 sensor allows to edit test patter number - bits 3..6 of the sensor register 160 (0xa0). There is additional suffix availble in multi-sensor cameras. Some parameters may have different values for different sensor, in that case __A (and __a) reference register of sensor 1, __B (__b) and __C (__c) - sensors 2 and 3. Parametes with upper case (__A, __B and __C) will reference the base parameter if individual is not defined, low case suffixes are strict and return error if the parameter does not have individual values for sensors.</SENSOR_REGS>
$sensor_regs_descriptions
     <DAEMON_EN>"Controls running daemons (individually turns them on/off by setting/resetting the related bit). It is more convinient to control them as individual bits using defined composite parameters, like DAEMON_EN_AUTOEXPOSURE, DAEMON_EN_STREAMER, etc."</DAEMON_EN>
     <DAEMON_EN_AUTOEXPOSURE>"0 - turns autoexposure daemon off, 1 - on. When off - not just autoexposure, but also white balance and HDR are disabled"</DAEMON_EN_AUTOEXPOSURE>
     <DAEMON_EN_STREAMER>"0 - turns the videostreamer off, 1 - on."</DAEMON_EN_STREAMER>
     <DAEMON_EN_CCAMFTP>"0 - turns the FTP uploader off, 1 - on. (not yet implemented)"</DAEMON_EN_CCAMFTP>
     <DAEMON_EN_CAMOGM>"0 - turns videorecording  off, 1 - on. (not yet implemented)"</DAEMON_EN_CAMOGM>
     <DAEMON_EN_TEMPERATURE>"0 - turns temperature logging  off, 1 - on"</DAEMON_EN_TEMPERATURE>
     <DAEMON_EN_AUTOCAMPARS>"when set to 1 autocampars daemon will wake up, launch autocampars.php script (that will actually process the provided command of saving/restoring parameters from the file) and goes back to sleep by clearing this bit by itself."</DAEMON_EN_AUTOCAMPARS>
     <AEXP_FRACPIX>"Fraction of all pixels that should be below P_AEXP_LEVEL (16.16 - 0x10000 - all pixels)"</AEXP_FRACPIX>
     <AEXP_LEVEL>"Target output level:  [P_AEXP_FRACPIX]/0x10000 of all pixels should have value below it (also 16.16 - 0x10000 - full output scale)"</AEXP_LEVEL>
     <HDR_DUR>"0 - HDR 0ff, >1 - duration of same exposure (currently 1 or 2 - for free running)"</HDR_DUR>
     <HDR_VEXPOS>"Second exposure setting in alternating frames HDR mode. if it is less than 0x10000 - number of lines of exposure, >=10000 - relative to "normal" exposure"</HDR_VEXPOS>
     <EXP_AHEAD>"How many frames ahead of the current frame write exposure to the sensor"</EXP_AHEAD>
     <AE_THRESH>"Autoexposure error (logariphmic difference between calculated and current exposures) is integrated between frame and corrections are scaled when error is below thershold."</AE_THRESH>
     <WB_THRESH>"White balance error (logariphmic difference between calculated and current values) is integrated between frame and corrections are scaled when error is below thershold (not yet implemented)"</WB_THRESH>
     <AE_PERIOD>"Autoexposure period (will be increased if below the latency)"</AE_PERIOD>
     <WB_PERIOD>"White balance period (will be increased if below the latency)"</WB_PERIOD>
     <WB_CTRL>"Combines WB_CTRL and WB_EN fields"</WB_CTRL>
     <WB_MASK>"Bitmask - which colors to adjust (1 - adjust, 0 - keepe). Default on is 0xd - all colors but Green1"</WB_MASK>
     <WB_EN>"Enable (1) or disable (0) automatic white balance adjustment. When enabled each color is individually controlled by WB_MASK"</WB_EN>
     <WB_WHITELEV>"White balance level of white (16.16 - 0x10000 is full scale, 0xfae1 - 98%, default)"</WB_WHITELEV>
     <WB_WHITEFRAC>"White balance fraction (16.16) of all pixels that have level above [P_WB_WHITELEV] for the brightest color [P_WB_WHITELEV] will be decreased if needed to satisfy [P_WB_WHITELEV]. default is 1% (0x028f)"</WB_WHITEFRAC>
     <WB_MAXWHITE>"Maximal allowed white pixels fraction (16.16) to have level above [WB_WHITELEV] for the darkest color of all. If this limit is exceeded there will be no correction performed (waiting for autoexposure to decrease overall brightness)."</WB_MAXWHITE>
     <WB_SCALE_R>"Additional correction for red/green from calulated by white balance.  0x10000 - 1.0 (default)"</WB_SCALE_R>
     <WB_SCALE_GB>"Additional correction for green2/green from calulated by white balance.  0x10000 - 1.0 (default). May be used for the color HDR mode"</WB_SCALE_GB>
     <WB_SCALE_B>"Additional correction for blue/green from calulated by white balance.  0x10000 - 1.0 (default)"</WB_SCALE_B>
     <HISTRQ>"Single histogram calculation request address (used automatically)"</HISTRQ>
     <HISTRQ_Y>"Single histogram calculation request for Y (green1) histogram (used automatically)"</HISTRQ_Y>
     <HISTRQ_C>"Single histogram calculation request for C (red, green2, blue) histograms (used automatically)"</HISTRQ_C>
     <HISTRQ_YC>"Single histogram calculation request for Y and C (red, green, green2, blue) histograms (used automatically)"</HISTRQ_YC>
     <PROFILE>"index to access profiles as pastpars (i.e. from PHP ELPHEL_PROFILE1,PHP ELPHEL_PROFILE2)"</PROFILE>
     <GAIN_MIN>"Minimal sensor analog gain (0x10000 - 1.0) - used for  white balancing. May be user limited from the hardware capabilities."</GAIN_MIN>
     <GAIN_MAX>"Maximal sensor analog gain (0x10000 - 1.0) - used for  white balancing. May be user limited from the hardware capabilities."</GAIN_MAX>
     <GAIN_CTRL>"Analog gain control for white balance. Combines GAIN_STEP and ANA_GAIN_ENABLE"</GAIN_CTRL>
     <GAIN_STEP>"minimal correction to be applied to the analog gain (should be set larger that sensor actual gain step to prevent oscillations (0x100 - 1.0, 0x20 - 1/8)"</GAIN_STEP>
     <ANA_GAIN_ENABLE>"Enabling analog gain control in white balancing (it uses scaling in gamma tables for fine adjustments and may additionally adjust analog gains if this value is 1"</ANA_GAIN_ENABLE>
     <AUTOCAMPARS_CTRL>"Input patrameter for the autocampars daemon to execute when enabled: bits 0..24 - parameter groups to restore, bits 28..30: 1 - restore, 2 - save, 3 - set default 4 save as default 5 - init. Combines AUTOCAMPARS_GROUPS, AUTOCAMPARS_PAGE and AUTOCAMPARS_CMD"</AUTOCAMPARS_CTRL>
     <AUTOCAMPARS_GROUPS>"Input patrameter for the autocampars daemon to execute when enabled: each of the 24 bits enables restoration of the related parameter group"</AUTOCAMPARS_GROUPS>
     <AUTOCAMPARS_PAGE>Input patrameter for the autocampars daemon to execute when enabled - page number to use to save/restore parameters. 0..14 - absolute page number, 15 - default when reading, next after last saved - when writing (15 will be replaced by the particular number by autocampars, so that value can be read back</AUTOCAMPARS_PAGE>
     <AUTOCAMPARS_CMD>Commands for the autocampars daemon to execute (to use from PHP - add ELPHEL_CONST_ to the name):`
              1 - AUTOCAMPARS_CMD_RESTORE - restore specified groups of parameters from the specified page`
              2 - AUTOCAMPARS_CMD_SAVE - save all current parameters to the specified group (page 0 is write-protected)`
              3 - AUTOCAMPARS_CMD_DFLT - make selected page the default one (used at startup)`
              4 - AUTOCAMPARS_CMD_SAVEDFLT - save all current parameters to the specified group (page 0 is write-protected) and make it default (used at startup)`
              5 - AUTOCAMPARS_CMD_INIT - reset sensor/sequencers, restore all parameters from the specified page</AUTOCAMPARS_CMD>
     <FTP_PERIOD>"Desired period of image upload to the remote FTP server (seconds)"</FTP_PERIOD>
     <FTP_TIMEOUT>"Maximal waiting time for the image to be uploaded to the remote server"</FTP_TIMEOUT>
     <FTP_UPDATE>"Maximal time between updates (camera wil re-read remote configuration file)"</FTP_UPDATE>
     <FTP_NEXT_TIME>"Sheduled time of the next FTP upload in seconds from epoch (G_ parameter)"</FTP_NEXT_TIME>
     <MAXAHEAD>"Maximal number of frames ahead of current to be programmed to hardware"</MAXAHEAD>
     <THIS_FRAME>"Current absolute frame number (G_ parameter, readonly)"</THIS_FRAME>
     <COMPRESSOR_FRAME>"Last absolute compressed frame number (G_ parameter, readonly)"</COMPRESSOR_FRAME>
     <COMPRESSOR_SEC>"Timestamp seconds for the last compressed frame (G_ parameter, readonly)"</COMPRESSOR_SEC>
     <COMPRESSOR_USEC>"Timestamp microseconds for the last compressed frame (G_ parameter, readonly)"</COMPRESSOR_USEC>
     <CIRCBUFSIZE>"Circular video buffer size in bytes (G_ parameter, readonly)"</CIRCBUFSIZE>
     <FREECIRCBUF>"Free space in the circular video buffer in bytes - only make sense when used with the global read pointer CIRCBUFRP (G_ parameter, readonly)"</FREECIRCBUF>
     <CIRCBUFWP>"Circular video buffer write pointer - where the next acquired frame will start(G_ parameter, readonly)"</CIRCBUFWP>
     <CIRCBUFRP>"Circular video buffer (global) read pointer. Used for synchronization between applications (i.e. reduce the streamer CPU load/fps if video recorder is not keeping up with the incoming data (G_ parameter)"</CIRCBUFRP>
     <FRAME_SIZE>"Size of the last compressed frame in bytes (w/o Exif and JFIF headers)"</FRAME_SIZE>
     <SECONDS>"Buffer used to read/write FPGA real time timer, seconds from epoch (G_ parameter)"</SECONDS>
     <MICROSECONDS>"Buffer used to read/write FPGA real time timer, microseconds (G_ parameter)"</MICROSECONDS>
     <CALLNASAP>"Bit mask of the internal tasks that can use FPGA sequencer - can be modified with parseq.php (G_ parameter)"</CALLNASAP>
     <CALLNEXT>"Four registers (CALLNEXT1..CALLNEXT4) that specify latencies of the internal tasks - can be modified with parseq.php (G_ parameters)"</CALLNEXT>
     <NEXT_AE_FRAME>"Next frame when autoexposure is scheduled (G_ parameter)"</NEXT_AE_FRAME>
     <NEXT_WB_FRAME>"Next frame when white balancing is scheduled (G_ parameter)"</NEXT_WB_FRAME>
     <HIST_DIM_01>"Zero levels (on 0xffff scale) for red and green1 color components for white balancing and autoexposure (G_ parameter)"</HIST_DIM_01>
     <HIST_DIM_23>"Zero levels (on 0xffff scale) for green2 and blue color components for white balancing and autoexposure (G_ parameter)"</HIST_DIM_23>
     <AE_INTEGERR></AE_INTEGERR>
     <WB_INTEGERR></WB_INTEGERR>
     <TASKLET_CTL>"Tasklet control, parent to HISTMODE_Y, HISTMODE_C and additionally:`
              bit 0 (TASKLET_CTL_PGM) - disable programming parameters (should not be)`
              Bit 1 (TASKLET_CTL_IGNPAST) - ignore overdue parameters`
              Bit 2 (TASKLET_CTL_NOSAME) - do not try to process parameters immediately after being written. If 0, only non-ASAP will be processed`
              Bit 3 (TASKLET_CTL_ENPROF) - enable profiling (saving timing of the interrupts/tasklets in pastpars) - can be controlled through PROFILING_EN (G_parameter)"</TASKLET_CTL>
     <GFOCUS_VALUE>"Sum of all blocks focus values inside focus WOI (G_ parameter,readonly)"</GFOCUS_VALUE>
     <HISTMODE_Y>"Controls when the Y (green1) histograms are calcuted:`
              0 - TASKLET_HIST_ALL - calculate each frame`
              1 - TASKLET_HIST_HALF calculate each even (0,2,4,6 frame of 8)`
              2 - TASKLET_HIST_QUATER  - calculate twice per 8 (0, 4)`
              3 - TASKLET_HIST_ONCE - calculate once  per 8 (0)`
              4 - TASKLET_HIST_RQONLY -  calculate only when specifically requested`
              7 - TASKLET_HIST_NEVER - never calculate.`
              NOTE: It is safer to allow all histograms at least once in 8 frames so applications will not be locked up waiting for the missed histogram (G_ parameter)"</HISTMODE_Y>
     <HISTMODE_C>"Controls when the C (red, green2, blue) histograms are calcuted:`
              0 - TASKLET_HIST_ALL - calculate each frame`
              1 - TASKLET_HIST_HALF calculate each even (0,2,4,6 frame of 8)`
              2 - TASKLET_HIST_QUATER  - calculate twice per 8 (0, 4)`
              3 - TASKLET_HIST_ONCE - calculate once  per 8 (0)`
              4 - TASKLET_HIST_RQONLY -  calculate only when specifically requested`
              7 - TASKLET_HIST_NEVER - never calculate.`
              NOTE: It is safer to allow all histograms at least once in 8 frames so applications will not be locked up waiting for the missed histogram (G_ parameter)"</HISTMODE_C>
     <SKIP_DIFF_FRAME>"Maximal number of frames of the different size streamer should skip before giving up - needed to allow acquisition of the full-frame images during streaming lower resolution ones(G_ parameter)"</SKIP_DIFF_FRAME>
     <HIST_LAST_INDEX>"Index of the last acquired histograms in the histogram cache (G_ parameter,readonly)"</HIST_LAST_INDEX>
     <HIST_Y_FRAME>"Frame number for which last Y (green1) histogram was calculated(G_ parameter,readonly)"</HIST_Y_FRAME>
     <HIST_C_FRAME>"Frame number for which last C (red, green2, blue) histogram was calculated(G_ parameter,readonly)"</HIST_C_FRAME>
     <DAEMON_ERR>"Bits from up to 32 daemons to report problems or requests (G_ parameter)"</DAEMON_ERR>
     <DAEMON_RETCODE>"32 locations - DAEMON_RETCODE0...DAEMON_RETCODE31 to get calues from the running daemons(G_ parameter)"</DAEMON_RETCODE>
     <PROFILING_EN>"Enable profiling (saving timing of the interrupts/tasklets in pastpars) - this is a single bit of the TASKLET_CTL parameter.(G_ parameter)"</PROFILING_EN>
     <STROP_MCAST_EN>"0 - disable videostreamer multicast, 1 - enable."</STROP_MCAST_EN>
     <STROP_MCAST_IP>"Videostream multicast IP. If == 0 - used addres 232.X.Y.Z, where X.Y.Z is last part of the camera IP"</STROP_MCAST_IP>
     <STROP_MCAST_PORT>"Videostream multicast port."</STROP_MCAST_PORT>
     <STROP_MCAST_TTL>"Videostream multicast TTL."</STROP_MCAST_TTL>
     <STROP_AUDIO_EN>"0 - disable videostream audio, 1 - enable."</STROP_AUDIO_EN>
     <STROP_AUDIO_RATE>"Videostream audio rate."</STROP_AUDIO_RATE>
     <STROP_AUDIO_CHANNEL>"Videostream audio channels: 1 - mono, 2 - stereo."</STROP_AUDIO_CHANNEL>
     <STROP_FRAMES_SKIP>"How many frames skip before next output; 0 - outpur each frame, 1 - output/skip = 1/1 for two frames, 2 - output frame and skip next 2 from 3 frames etc."</STROP_FRAMES_SKIP>
     <AUDIO_CAPTURE_VOLUME>"streamer and camogm2 audio capture volume. 0 == 0%, 65535 == 100%."</AUDIO_CAPTURE_VOLUME>
<!--Parameters related to multi-sensor operations -->
     <MULTISENS_EN>"0 - single sensor, no 10359A, otherwise - bitmask of the sensors enabled (obeys G_SENS_AVAIL that should not be modified at runtime)."</MULTISENS_EN>
     <MULTI_PHASE_SDRAM>"Similar to SENSOR_PHASE, contols 10359 SDRAM. Adjusted automatically"</MULTI_PHASE_SDRAM>
     <MULTI_PHASE1>  "Similar to SENSOR_PHASE, but for sensor1, connected to 10359"</MULTI_PHASE1>
     <MULTI_PHASE2>  "Similar to SENSOR_PHASE, but for sensor2, connected to 10359"</MULTI_PHASE2>
     <MULTI_PHASE3>  "Similar to SENSOR_PHASE, but for sensor3, connected to 10359"</MULTI_PHASE3>
     <MULTI_SEQUENCE>"Sensor sequence (bits 0,1 - first, 2,3 - second, 4,5 - third). 0 - disable. Will obey SENS_AVAIL and MULTISENS_EN"</MULTI_SEQUENCE>
     <MULTI_FLIPH>  "additional per-sensor horizontal flip to global FLIPH, same bits as in G_SENS_AVAIL"</MULTI_FLIPH>
     <MULTI_FLIPV>  "additional per-sensor vertical flip to global FLIPV, same bits as in G_SENS_AVAIL"</MULTI_FLIPV>
     <MULTI_MODE>   "Mode 0 - single sensor (first in sequence), 1 - composite (only enabled in triggered mode - TRIG=4)"</MULTI_MODE>
     <MULTI_HBLANK> "Horizontal blanking for buffered frames (2,3) - not needed?"</MULTI_HBLANK>
     <MULTI_CWIDTH> "Composite frame width  (stored while in single-sensor mode, copied to WOI_WIDTH)"</MULTI_CWIDTH>
     <MULTI_CHEIGHT>"Composite frame height (stored while in single-sensor mode)"</MULTI_CHEIGHT>
     <MULTI_CLEFT>  "Composite frame left margin  (stored while in single-sensor mode, copied to WOI_LEFT)"</MULTI_CLEFT>
     <MULTI_CTOP>   "Composite frame top margin (stored while in single-sensor mode)"</MULTI_CTOP>
     <MULTI_CFLIPH> "Horizontal flip for composite image (stored while in single-sensor mode)"</MULTI_CFLIPH>
     <MULTI_CFLIPV> "Vertical flip for composite image (stored while in single-sensor mode)"</MULTI_CFLIPV>
     <MULTI_VBLANK> "Vertical blanking for buffered frames (2,3) BEFORE FRAME, not after"</MULTI_VBLANK>
     <MULTI_WOI>    "Width of frame 1 (direct)  // Same as next"</MULTI_WOI>
     <MULTI_WIDTH1> "Width of frame 1 (direct) // same as MULTI_WOI !!!!"</MULTI_WIDTH1>
     <MULTI_WIDTH2> "Width of frame 2 (first buffered)"</MULTI_WIDTH2>
     <MULTI_WIDTH3> "Width of frame 3 (second buffered)"</MULTI_WIDTH3>
     <MULTI_HEIGHT1>"Height of frame 1 (direct)"</MULTI_HEIGHT1>
     <MULTI_HEIGHT2>"Height of frame 2 (first buffered)"</MULTI_HEIGHT2>
     <MULTI_HEIGHT3>"Height of frame 3 (second buffered)"</MULTI_HEIGHT3>
     <MULTI_LEFT1>  "Left margin of frame 1 (direct) "</MULTI_LEFT1>
     <MULTI_LEFT2>  "Left margin of frame 2 (first buffered)"</MULTI_LEFT2>
     <MULTI_LEFT3>  "Left margin of frame 3 (second buffered)"</MULTI_LEFT3>
     <MULTI_TOP1>   "Top margin of frame 1 (direct)"</MULTI_TOP1>
     <MULTI_TOP2>   "Top margin of frame 2 (first buffered)"</MULTI_TOP2>
     <MULTI_TOP3>   "Top margin of frame 3 (second buffered)"</MULTI_TOP3>
     <MULTI_TOPSENSOR>"Number of sensor channel used in first (direct) frame: 0..2, internal parameter (window->sensorin) - used internally"</MULTI_TOPSENSOR>
     <MULTI_SELECTED>"Number of sensor channel (1..3) used when composite mode is disabled"</MULTI_SELECTED>
     <M10359_REGS>10359 board internal registers, total of 96. First 64 are 16-bit, next 32 - 32 bit wide (Register definitions in http://elphel.cvs.sourceforge.net/viewvc/elphel/elphel353-8.0/os/linux-2.6-tag--devboard-R2_10-4/arch/cris/arch-v32/drivers/elphel/multisensor.h?view=markup).</M10359_REGS>
<!-- Global parameters , persistent through sensor (re-) detection TODO: move other ones here-->
     <SENS_AVAIL>"Bitmask of the sensors attached to 10359 (0 if no 10359 brd, multisensor operations disabled). It is automatically set during sensor detection."</SENS_AVAIL>
     <FPGA_TIM0>"FPGA timing parameter 0 - difference between DCLK pad and DCM input, signed (ps). Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</FPGA_TIM0>
     <FPGA_TIM1>"FPGA timing parameter 1. Persistent through initialization."</FPGA_TIM1>
     <DLY359_OUT>"Output delay in 10359 board (clock to out) in ps, signed. Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</DLY359_OUT>
     <DLY359_P1>"Delay in 10359 board sensor port 1 (clock to sensor - clock to DCM) in ps, signed. Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</DLY359_P1>
     <DLY359_P2>"Delay in 10359 board sensor port 2 (clock to sensor - clock to DCM) in ps, signed. Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</DLY359_P2>
     <DLY359_P3>"Ddelay in 10359 board sensor port 3 (clock to sensor - clock to DCM) in ps, signed. Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</DLY359_P3>
     <DLY359_C1>"Cable delay in sensor port 1 in ps, Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</DLY359_C1>
     <DLY359_C2>"Cable delay in sensor port 2 in ps, signed. Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</DLY359_C2>
     <DLY359_C3>"Cable delay in sensor port 3 in ps, signed. Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</DLY359_C3>
     <MULTI_CFG>"Additional configuration options for 10359 board. Bit 0 - use 10353 system clock, not the local one (as on 10359 rev 0). Persistent through sensor detection/initialization, should be set prior to it (in startup script or modified before running &quot;/usr/html/autocampars.php --init&quot; from the command line)."</MULTI_CFG>
     <DEBUG>"Selectively enables debug outputs from differnt parts of the drivers. Can easily lock the system as some output goes from inside the interrupt service code or from the parts of the code where interrups are disabled. To us it safely you need to kill the klog daemon an redirect debug output to file with &quot;printk_mod &amp;&quot; command. After that the output will be available as http://camera_ip/var/klog.txt". The printk_mod also kills restart restart daemon so any normally restarted applications (like lighttpd, php, imgsrv) will not be restarted automatically (G_ parameter, not frame-related)</DEBUG>
     <TEMPERATURE01>"Temperature data from the 10359 board (if available, lower 16 bits) and the first sensor front end (high 16 bits). In each short word bit 12 (0x1000) is set for negative Celsius, lower 12 bits - absolute value of the Celsius, lower bit weight is 1/16 grad. C. This data is provided by the temperature daemon if it is enabled and running, data is embedded in the Exif MakerNote bytes 56-59"</TEMPERATURE01>
     <TEMPERATURE23>"Temperature data from the second sensor front end (if available, lower 16 bits) and the third sensor front end (high 16 bits). In each short word bit 12 (0x1000) is set for negative Celsius, lower 12 bits - absolute value of the Celsius, lower bit weight is 1/16 grad. C. This data is provided by the temperature daemon if it is enabled and running, data is embedded in the Exif MakerNote bytes 56-59"</TEMPERATURE23>
</descriptions>
<!-- "init,woi,image,histWnd,autoexposure,whiteBalance,streamer,camftp,camogm,vignet,multisensor,diagn" -->
<!-- Parameter groups -->
  <groups>
     <comment type="text">"init"</comment>
     <timestamp type="text">"init"</timestamp>
     <SENSOR></SENSOR>
     <SENSOR_RUN>"init"</SENSOR_RUN>
     <ACTUAL_WIDTH></ACTUAL_WIDTH>
     <ACTUAL_HEIGHT></ACTUAL_HEIGHT>
     <BAYER>"init"</BAYER>
     <PERIOD></PERIOD>
     <FP1000SLIM>"init,streamer"</FP1000SLIM>
     <FRAME></FRAME>
     <CLK_FPGA>"unsafe"</CLK_FPGA>
     <CLK_SENSOR>"unsafe"</CLK_SENSOR>
     <FPGA_XTRA>"init"</FPGA_XTRA>
     <TRIG>"init,multisensor,trigger"</TRIG>
     <TRIG_MASTER>"init,multisensor,trigger"</TRIG_MASTER>
     <EXPOS>"init,autoexposure"</EXPOS>
     <BGFRAME></BGFRAME>
     <IMGSZMEM></IMGSZMEM>
     <PAGE_ACQ></PAGE_ACQ>
     <PAGE_READ></PAGE_READ>
     <OVERLAP></OVERLAP>
     <VIRT_KEEP>"init,woi"</VIRT_KEEP>
     <VIRT_WIDTH>"woi"</VIRT_WIDTH>
     <VIRT_HEIGHT>"woi"</VIRT_HEIGHT>
     <WOI_LEFT>"init,woi,multisensor"</WOI_LEFT>
     <WOI_TOP>"init,woi,multisensor"</WOI_TOP>
     <WOI_WIDTH>"init,woi,multisensor"</WOI_WIDTH>
     <WOI_HEIGHT>"init,woi,multisensor"</WOI_HEIGHT>
     <FLIPH>"init,woi,multisensor"</FLIPH>
     <FLIPV>"init,woi,multisensor"</FLIPV>
     <FPSFLAGS>"init,streamer"</FPSFLAGS>
     <DCM_HOR>"init,woi"</DCM_HOR>
     <DCM_VERT>"init,woi"</DCM_VERT>
     <BIN_HOR>"init,woi"</BIN_HOR>
     <BIN_VERT>"init,woi"</BIN_VERT>
     <FPGATEST>"init,image,diagn"</FPGATEST>
     <TESTSENSOR>"init,image,diagn"</TESTSENSOR>
     <COLOR>"init,image"</COLOR>
     <FRAMESYNC_DLY>"init"</FRAMESYNC_DLY>
     <PF_HEIGHT>"init"</PF_HEIGHT>
     <BITS>"init"</BITS>
     <SHIFTL>"init,image"</SHIFTL>
     <FPNS>"init,image"</FPNS>
     <FPNM>"init,image"</FPNM>
     <VEXPOS></VEXPOS>
     <VIRTTRIG>"init"</VIRTTRIG>
     <PERIOD_MIN></PERIOD_MIN>
     <PERIOD_MAX></PERIOD_MAX>
     <SENSOR_PIXH></SENSOR_PIXH>
     <SENSOR_PIXV></SENSOR_PIXV>
     <GAINR>"init,image"</GAINR>
     <GAING>"init,image"</GAING>
     <GAINB>"init,image"</GAINB>
     <GAINGB>"init,image"</GAINGB>
     <RSCALE_ALL></RSCALE_ALL>
     <GSCALE_ALL></GSCALE_ALL>
     <BSCALE_ALL></BSCALE_ALL>
<!-- *SCALE removed as they interfere with gains when updated together during init. Maybe it is just a bug in mt9x001.c -->
     <RSCALE></RSCALE>
     <GSCALE></GSCALE>
     <BSCALE></BSCALE>
     <RSCALE_CTL>"init,image"</RSCALE_CTL>
     <GSCALE_CTL>"init,image"</GSCALE_CTL>
     <BSCALE_CTL>"init,image"</BSCALE_CTL>
     <FATZERO>"not used"</FATZERO>
     <FATZERO></FATZERO>
     <QUALITY>"init,image"</QUALITY>
     <PORTRAIT>"init,image"</PORTRAIT>
     <CORING_INDEX>"init,image"</CORING_INDEX>
     <FP1000S></FP1000S>
     <SENSOR_WIDTH></SENSOR_WIDTH>
     <SENSOR_HEIGHT></SENSOR_HEIGHT>
     <COLOR_SATURATION_BLUE>"init,image"</COLOR_SATURATION_BLUE>
     <COLOR_SATURATION_RED>"init,image"</COLOR_SATURATION_RED>
     <VIGNET_AX>"init,vignet"</VIGNET_AX>
     <VIGNET_AY>"init,vignet"</VIGNET_AY>
     <VIGNET_BX>"init,vignet"</VIGNET_BX>
     <VIGNET_BY>"init,vignet"</VIGNET_BY>
     <VIGNET_C>"init,vignet"</VIGNET_C>
     <VIGNET_SHL>"init,vignet"</VIGNET_SHL>
     <SCALE_ZERO_IN>"init,vignet"</SCALE_ZERO_IN>
     <SCALE_ZERO_OUT>"init,vignet"</SCALE_ZERO_OUT>
     <DGAINR>"init,vignet"</DGAINR>
     <DGAING>"init,vignet"</DGAING>
     <DGAINGB>"init,vignet"</DGAINGB>
     <DGAINB>"init,vignet"</DGAINB>
     <CORING_PAGE>"init,image"</CORING_PAGE>
     <TILES></TILES>
     <SENSOR_PHASE>"init,diagn"</SENSOR_PHASE>
     <TEMPERATURE_PERIOD>"init,diagn"</TEMPERATURE_PERIOD>
     <AUTOEXP_ON>"init,autoexposure"</AUTOEXP_ON>
     <HISTWND_RWIDTH>"init,histWnd"</HISTWND_RWIDTH>
     <HISTWND_RHEIGHT>"init,histWnd"</HISTWND_RHEIGHT>
     <HISTWND_RLEFT>"init,histWnd"</HISTWND_RLEFT>
     <HISTWND_RTOP>"init,histWnd"</HISTWND_RTOP>
     <AUTOEXP_EXP_MAX>"init,autoexposure"</AUTOEXP_EXP_MAX>
     <AUTOEXP_OVEREXP_MAX></AUTOEXP_OVEREXP_MAX>
     <AUTOEXP_S_PERCENT></AUTOEXP_S_PERCENT>
     <AUTOEXP_S_INDEX></AUTOEXP_S_INDEX>
     <AUTOEXP_EXP></AUTOEXP_EXP>
     <AUTOEXP_SKIP_PMIN></AUTOEXP_SKIP_PMIN>
     <AUTOEXP_SKIP_PMAX></AUTOEXP_SKIP_PMAX>
     <AUTOEXP_SKIP_T></AUTOEXP_SKIP_T>
     <HISTWND_WIDTH></HISTWND_WIDTH>
     <HISTWND_HEIGHT></HISTWND_HEIGHT>
     <HISTWND_TOP></HISTWND_TOP>
     <HISTWND_LEFT></HISTWND_LEFT>
     <FOCUS_SHOW>"init"</FOCUS_SHOW>
     <FOCUS_SHOW1>"init"</FOCUS_SHOW1>
     <RFOCUS_LEFT>"init"</RFOCUS_LEFT>
     <RFOCUS_WIDTH>"init"</RFOCUS_WIDTH>
     <RFOCUS_TOP>"init"</RFOCUS_TOP>
     <RFOCUS_HEIGHT>"init"</RFOCUS_HEIGHT>
     <MEMSENSOR_DLY>"init"</MEMSENSOR_DLY>
     <FOCUS_LEFT></FOCUS_LEFT>
     <FOCUS_WIDTH></FOCUS_WIDTH>
     <FOCUS_TOP></FOCUS_TOP>
     <FOCUS_HEIGHT></FOCUS_HEIGHT>
     <FOCUS_TOTWIDTH></FOCUS_TOTWIDTH>
     <FOCUS_FILTER>"init"</FOCUS_FILTER>
     <TRIG_CONDITION>"init,multisensor,trigger"</TRIG_CONDITION>
     <TRIG_DELAY>"init,multisensor,trigger"</TRIG_DELAY>
     <TRIG_OUT>"init,trigger"</TRIG_OUT>
     <TRIG_PERIOD>"init,multisensor,trigger"</TRIG_PERIOD>
     <TRIG_BITLENGTH>"init,trigger"</TRIG_BITLENGTH>
     <EXTERN_TIMESTAMP>"init,multisensor,trigger"</EXTERN_TIMESTAMP>
     <XMIT_TIMESTAMP>"init,multisensor,trigger"</XMIT_TIMESTAMP>
     <TRIG_DECIMATE>"init,multisensor,trigger"</TRIG_DECIMATE>
     <SKIP_FRAMES>"init"</SKIP_FRAMES>
     <I2C_QPERIOD>"unsafe"</I2C_QPERIOD>
     <I2C_BYTES>"unsafe"</I2C_BYTES>
     <IRQ_SMART></IRQ_SMART>
     <OVERSIZE>"init"</OVERSIZE>
     <GTAB_R>"init,image,whiteBalance"</GTAB_R>
     <GTAB_G>"init,image,whiteBalance"</GTAB_G>
     <GTAB_GB>"init,image,whiteBalance"</GTAB_GB>
     <GTAB_B>"init,image,whiteBalance"</GTAB_B>
     <SDRAM_CHN20></SDRAM_CHN20>
     <SDRAM_CHN21></SDRAM_CHN21>
     <SDRAM_CHN22></SDRAM_CHN22>
     <COMPRESSOR_RUN>"init"</COMPRESSOR_RUN>
     <COMPMOD_BYRSH>"init,image"</COMPMOD_BYRSH>
     <COMPMOD_TILSH>"init,image"</COMPMOD_TILSH>
     <COMPMOD_DCSUB>"init,image"</COMPMOD_DCSUB>
     <COMPMOD_QTAB></COMPMOD_QTAB>
     <!--<SENSOR_REGS></SENSOR_REGS>-->
$sensor_regs_groups
     <DAEMON_EN>"init,diagn"</DAEMON_EN>
     <DAEMON_EN_AUTOEXPOSURE>"init,diagn"</DAEMON_EN_AUTOEXPOSURE>
     <DAEMON_EN_STREAMER>"init,streamer,diagn"</DAEMON_EN_STREAMER>
     <DAEMON_EN_CCAMFTP>"init,camftp,diagn"</DAEMON_EN_CCAMFTP>
     <DAEMON_EN_CAMOGM>"init,camogm,diagn"</DAEMON_EN_CAMOGM>
     <DAEMON_EN_AUTOCAMPARS>"diagn"</DAEMON_EN_AUTOCAMPARS>
     <DAEMON_EN_TEMPERATURE>"init,diagn"</DAEMON_EN_TEMPERATURE>
     <AEXP_FRACPIX>"init,autoexposure"</AEXP_FRACPIX>
     <AEXP_LEVEL>"init,autoexposure"</AEXP_LEVEL>
     <HDR_DUR>"init,autoexposure"</HDR_DUR>
     <HDR_VEXPOS>"init,autoexposure"</HDR_VEXPOS>
     <EXP_AHEAD>"init"</EXP_AHEAD>
     <AE_THRESH>"init,autoexposure"</AE_THRESH>
     <WB_THRESH>"init,whiteBalance"</WB_THRESH>
     <AE_PERIOD>"init,autoexposure"</AE_PERIOD>
     <WB_PERIOD>"init,whiteBalance"</WB_PERIOD>
     <WB_CTRL></WB_CTRL>
     <WB_MASK>"init,whiteBalance"</WB_MASK>
     <WB_EN>"init,whiteBalance"</WB_EN>
     <WB_WHITELEV>"init,whiteBalance"</WB_WHITELEV>
     <WB_WHITEFRAC>"init,whiteBalance"</WB_WHITEFRAC>
     <WB_MAXWHITE>"init,whiteBalance"</WB_MAXWHITE>
     <WB_SCALE_R>"init,whiteBalance"</WB_SCALE_R>
     <WB_SCALE_GB>"init,whiteBalance"</WB_SCALE_GB>
     <WB_SCALE_B>"init,whiteBalance"</WB_SCALE_B>
     <HISTRQ></HISTRQ>
     <HISTRQ_Y></HISTRQ_Y>
     <HISTRQ_C></HISTRQ_C>
     <HISTRQ_YC></HISTRQ_YC>
     <PROFILE></PROFILE>
     <GAIN_MIN>"init,whiteBalance"</GAIN_MIN>
     <GAIN_MAX>"init,whiteBalance"</GAIN_MAX>
     <GAIN_CTRL>"init,whiteBalance"</GAIN_CTRL>
     <GAIN_STEP>"init,whiteBalance"</GAIN_STEP>
     <ANA_GAIN_ENABLE>"init,whiteBalance"</ANA_GAIN_ENABLE>
     <AUTOCAMPARS_CTRL></AUTOCAMPARS_CTRL>
     <AUTOCAMPARS_GROUPS></AUTOCAMPARS_GROUPS>
     <AUTOCAMPARS_PAGE></AUTOCAMPARS_PAGE>
     <AUTOCAMPARS_CMD></AUTOCAMPARS_CMD>
     <FTP_PERIOD>"init,camftp"</FTP_PERIOD>
     <FTP_TIMEOUT>"init,camftp"</FTP_TIMEOUT>
     <FTP_UPDATE>"init,camftp"</FTP_UPDATE>
     <FTP_NEXT_TIME></FTP_NEXT_TIME>
     <MAXAHEAD>"init"</MAXAHEAD>
     <THIS_FRAME></THIS_FRAME>
     <CIRCBUFSIZE></CIRCBUFSIZE>
     <FREECIRCBUF></FREECIRCBUF>
     <CIRCBUFWP></CIRCBUFWP>
     <CIRCBUFRP></CIRCBUFRP>
     <FRAME_SIZE></FRAME_SIZE>
     <SECONDS></SECONDS>
     <MICROSECONDS></MICROSECONDS>
     <CALLNASAP></CALLNASAP>
     <CALLNEXT></CALLNEXT>
     <NEXT_AE_FRAME></NEXT_AE_FRAME>
     <NEXT_WB_FRAME></NEXT_WB_FRAME>
     <HIST_DIM_01>"init,autoexposure"</HIST_DIM_01>
     <HIST_DIM_23>"init,autoexposure"</HIST_DIM_23>
     <AE_INTEGERR></AE_INTEGERR>
     <WB_INTEGERR></WB_INTEGERR>
     <TASKLET_CTL>"diagn"</TASKLET_CTL>
     <GFOCUS_VALUE></GFOCUS_VALUE>
     <HISTMODE_Y>"diagn"</HISTMODE_Y>
     <HISTMODE_C>"diagn"</HISTMODE_C>
     <SKIP_DIFF_FRAME>"streamer"</SKIP_DIFF_FRAME>
     <HIST_LAST_INDEX></HIST_LAST_INDEX>
     <HIST_Y_FRAME></HIST_Y_FRAME>
     <HIST_C_FRAME></HIST_C_FRAME>
     <DAEMON_ERR></DAEMON_ERR>
     <DAEMON_RETCODE></DAEMON_RETCODE>
     <PROFILING_EN>"diagn"</PROFILING_EN>
     <STROP_MCAST_EN>"init,streamer"</STROP_MCAST_EN>
     <STROP_MCAST_IP>"init,streamer"</STROP_MCAST_IP>
     <STROP_MCAST_PORT>"init,streamer"</STROP_MCAST_PORT>
     <STROP_MCAST_TTL>"init,streamer"</STROP_MCAST_TTL>
     <STROP_AUDIO_EN>"init,streamer"</STROP_AUDIO_EN>
     <STROP_AUDIO_RATE>"init,streamer"</STROP_AUDIO_RATE>
     <STROP_AUDIO_CHANNEL>"init,streamer"</STROP_AUDIO_CHANNEL>
     <STROP_FRAMES_SKIP>"init,streamer"</STROP_FRAMES_SKIP>
     <AUDIO_CAPTURE_VOLUME>"init,streamer"</AUDIO_CAPTURE_VOLUME>
<!--Parameters related to multi-sensor operations -->
     <MULTISENS_EN></MULTISENS_EN>
     <MULTI_PHASE_SDRAM>"diagn,unsafe"</MULTI_PHASE_SDRAM>
     <MULTI_PHASE1>  "init,diagn,unsafe"</MULTI_PHASE1>
     <MULTI_PHASE2>  "init,diagn,unsafe"</MULTI_PHASE2>
     <MULTI_PHASE3>  "init,diagn,unsafe"</MULTI_PHASE3>
     <MULTI_SEQUENCE>"multisensor"</MULTI_SEQUENCE>
     <MULTI_FLIPH>  "init,multisensor"</MULTI_FLIPH>
     <MULTI_FLIPV>  "init,multisensor"</MULTI_FLIPV>
     <MULTI_MODE>   "init,multisensor"</MULTI_MODE>
     <MULTI_HBLANK> "init,multisensor"</MULTI_HBLANK>
     <MULTI_CWIDTH> </MULTI_CWIDTH>
     <MULTI_CHEIGHT></MULTI_CHEIGHT>
     <MULTI_CLEFT></MULTI_CLEFT>
     <MULTI_CTOP></MULTI_CTOP>
     <MULTI_CFLIPH></MULTI_CFLIPH>
     <MULTI_CFLIPV></MULTI_CFLIPV>
     <MULTI_VBLANK>"init,multisensor"</MULTI_VBLANK>
     <MULTI_WOI></MULTI_WOI>
     <MULTI_WIDTH1> "init,multisensor"</MULTI_WIDTH1>
     <MULTI_WIDTH2> "init,multisensor"</MULTI_WIDTH2>
     <MULTI_WIDTH3> "init,multisensor"</MULTI_WIDTH3>
     <MULTI_HEIGHT1> "init,multisensor"</MULTI_HEIGHT1>
     <MULTI_HEIGHT2> "init,multisensor"</MULTI_HEIGHT2>
     <MULTI_HEIGHT3> "init,multisensor"</MULTI_HEIGHT3>
     <MULTI_LEFT1> "init,multisensor"</MULTI_LEFT1>
     <MULTI_LEFT2> "init,multisensor"</MULTI_LEFT2>
     <MULTI_LEFT3> "init,multisensor"</MULTI_LEFT3>
     <MULTI_TOP1> "init,multisensor"</MULTI_TOP1>
     <MULTI_TOP2> "init,multisensor"</MULTI_TOP2>
     <MULTI_TOP3> "init,multisensor"</MULTI_TOP3>
     <MULTI_TOPSENSOR></MULTI_TOPSENSOR>
     <MULTI_SELECTED> "init,multisensor"</MULTI_SELECTED>
     <TEMPERATURE01>"diagn"</TEMPERATURE01>
     <TEMPERATURE23>"diagn"</TEMPERATURE23>
     <M10359_REGS></M10359_REGS>
<!-- Global parameters , persistent through sensor (re-) detection TODO: move other ones here-->
     <SENS_AVAIL>"diagn"</SENS_AVAIL>
     <FPGA_TIM0>"persistent"</FPGA_TIM0>
     <FPGA_TIM1></FPGA_TIM1>
     <DLY359_OUT>"persistent"</DLY359_OUT>
     <DLY359_P1>"persistent"</DLY359_P1>
     <DLY359_P2>"persistent"</DLY359_P2>
     <DLY359_P3>"persistent"</DLY359_P3>
     <DLY359_C1>"persistent"</DLY359_C1>
     <DLY359_C2>"persistent"</DLY359_C2>
     <DLY359_C3>"persistent"</DLY359_C3>
     <MULTI_CFG>"persistent"</MULTI_CFG>
     <DEBUG>"persistent"</DEBUG>

  </groups>
  <paramSets>
    <set number="0">
     <comment>Default values of parameters (page 0)</comment>
     <SENSOR_RUN>$SENSOR_RUN</SENSOR_RUN>
     <BAYER>0</BAYER>
     <FP1000SLIM>0</FP1000SLIM>
     <FPGA_XTRA>1000</FPGA_XTRA>
     <TRIG>$TRIG</TRIG>
     <TRIG_MASTER>$TRIG_MASTER</TRIG_MASTER>
     <EXPOS>10000</EXPOS>
     <VIRT_KEEP>0</VIRT_KEEP>
     <WOI_LEFT>0</WOI_LEFT>
     <WOI_TOP>0</WOI_TOP>
     <WOI_WIDTH>10000</WOI_WIDTH>
     <WOI_HEIGHT>$WOI_HEIGHT</WOI_HEIGHT>
     <FLIPH>$FLIPH</FLIPH>
     <FLIPV>$FLIPV</FLIPV>
     <FPSFLAGS>0</FPSFLAGS>
     <DCM_HOR>1</DCM_HOR>
     <DCM_VERT>1</DCM_VERT>
     <BIN_HOR>1</BIN_HOR>
     <BIN_VERT>1</BIN_VERT>
     <FPGATEST>0</FPGATEST>
     <TESTSENSOR>0</TESTSENSOR>
     <COLOR>$COLOR</COLOR>
     <FRAMESYNC_DLY>0</FRAMESYNC_DLY>
     <PF_HEIGHT>0</PF_HEIGHT>
     <BITS>$BITS</BITS>
     <SHIFTL>0</SHIFTL>
     <FPNS>0</FPNS>
     <FPNM>0</FPNM>
     <VIRTTRIG>0</VIRTTRIG>
     <GAINR>$GAINR</GAINR>
     <GAING>$GAING</GAING>
     <GAINB>$GAINB</GAINB>
     <GAINGB>$GAINGB</GAINGB>
     <RSCALE>$RSCALE</RSCALE>
     <GSCALE>$GSCALE</GSCALE>
     <BSCALE>$BSCALE</BSCALE>
     <RSCALE_CTL>$SCALES_CTL</RSCALE_CTL>
     <GSCALE_CTL>$SCALES_CTL</GSCALE_CTL>
     <BSCALE_CTL>$SCALES_CTL</BSCALE_CTL>
     <QUALITY>$QUALITY</QUALITY>
     <PORTRAIT>$PORTRAIT</PORTRAIT>
     <CORING_INDEX>$CORING_INDEX</CORING_INDEX>
     <COLOR_SATURATION_BLUE>$SATURATION</COLOR_SATURATION_BLUE>
     <COLOR_SATURATION_RED>$SATURATION</COLOR_SATURATION_RED>
     <VIGNET_AX>0</VIGNET_AX>
     <VIGNET_AY>0</VIGNET_AY>
     <VIGNET_BX>0</VIGNET_BX>
     <VIGNET_BY>0</VIGNET_BY>
     <VIGNET_C>32768</VIGNET_C>
     <VIGNET_SHL>1</VIGNET_SHL>
     <SCALE_ZERO_IN>2560</SCALE_ZERO_IN>
     <SCALE_ZERO_OUT>2560</SCALE_ZERO_OUT>
     <DGAINR>32768</DGAINR>
     <DGAING>32768</DGAING>
     <DGAINGB>32768</DGAINGB>
     <DGAINB>32768</DGAINB>
     <CORING_PAGE>0</CORING_PAGE>
     <TEMPERATURE_PERIOD>2000</TEMPERATURE_PERIOD>
     <AUTOEXP_ON>1</AUTOEXP_ON>
     <HISTWND_RWIDTH>$HISTWND_RWIDTH</HISTWND_RWIDTH>
     <HISTWND_RHEIGHT>$HISTWND_RHEIGHT</HISTWND_RHEIGHT>
     <HISTWND_RLEFT>$HISTWND_RLEFT</HISTWND_RLEFT>
     <HISTWND_RTOP>$HISTWND_RTOP</HISTWND_RTOP>
     <AUTOEXP_EXP_MAX>$AUTOEXP_EXP_MAX</AUTOEXP_EXP_MAX>
     <FOCUS_SHOW>0</FOCUS_SHOW>
     <FOCUS_SHOW1>0</FOCUS_SHOW1>
     <RFOCUS_LEFT>0x8000</RFOCUS_LEFT>
     <RFOCUS_WIDTH>0x8000</RFOCUS_WIDTH>
     <RFOCUS_TOP>0x8000</RFOCUS_TOP>
     <RFOCUS_HEIGHT>0x8000</RFOCUS_HEIGHT>
     <MEMSENSOR_DLY>1024</MEMSENSOR_DLY>
     <FOCUS_FILTER>0</FOCUS_FILTER>
     <TRIG_CONDITION>$TRIG_CONDITION</TRIG_CONDITION>
     <TRIG_DELAY>0</TRIG_DELAY>
     <TRIG_OUT>$TRIG_OUT</TRIG_OUT>
     <TRIG_PERIOD>$TRIG_PERIOD</TRIG_PERIOD>
     <TRIG_BITLENGTH>31</TRIG_BITLENGTH>
     <EXTERN_TIMESTAMP>1</EXTERN_TIMESTAMP>
     <XMIT_TIMESTAMP>1</XMIT_TIMESTAMP>
     <TRIG_DECIMATE>0</TRIG_DECIMATE>
<!--     <SKIP_FRAMES>0</SKIP_FRAMES>
     <IRQ_SMART>3</IRQ_SMART> -->
     <OVERSIZE>$OVERSIZE</OVERSIZE>
     <GTAB_R>$GAMMA_CORR</GTAB_R>
     <GTAB_G>$GAMMA_CORR</GTAB_G>
     <GTAB_GB>$GAMMA_CORR</GTAB_GB>
     <GTAB_B>$GAMMA_CORR</GTAB_B>
     <COMPRESSOR_RUN>$COMPRESSOR_RUN</COMPRESSOR_RUN>
     <COMPMOD_BYRSH>0</COMPMOD_BYRSH>
<!--     <COMPMOD_TILSH>0</COMPMOD_TILSH> -->
     <COMPMOD_DCSUB>0</COMPMOD_DCSUB>
<!--     <SENSOR_REGS></SENSOR_REGS> -->
$sensor_regs_defaults
     <DAEMON_EN>0</DAEMON_EN>
     <DAEMON_EN_AUTOEXPOSURE>1</DAEMON_EN_AUTOEXPOSURE>
     <DAEMON_EN_STREAMER>1</DAEMON_EN_STREAMER>
     <DAEMON_EN_CCAMFTP>0</DAEMON_EN_CCAMFTP>
     <DAEMON_EN_CAMOGM>0</DAEMON_EN_CAMOGM>
     <DAEMON_EN_AUTOCAMPARS>1</DAEMON_EN_AUTOCAMPARS>
     <DAEMON_EN_TEMPERATURE>$DAEMON_EN_TEMPERATURE</DAEMON_EN_TEMPERATURE>
     <AEXP_FRACPIX>$AEXP_FRACPIX</AEXP_FRACPIX>
     <AEXP_LEVEL>$AEXP_LEVEL</AEXP_LEVEL>
     <HDR_DUR>0</HDR_DUR>
     <HDR_VEXPOS>0x40000</HDR_VEXPOS>
     <EXP_AHEAD>3</EXP_AHEAD>
     <AE_THRESH>500</AE_THRESH>
     <WB_THRESH>500</WB_THRESH>
     <AE_PERIOD>4</AE_PERIOD>
     <WB_PERIOD>16</WB_PERIOD>
     <WB_MASK>0xd</WB_MASK>
     <WB_EN>$WB_EN</WB_EN>
     <WB_WHITELEV>0xfae1</WB_WHITELEV>
     <WB_WHITEFRAC>0x028f</WB_WHITEFRAC>
     <WB_MAXWHITE>0xccc</WB_MAXWHITE>
     <WB_SCALE_R>0x10000</WB_SCALE_R>
     <WB_SCALE_GB>0x10000</WB_SCALE_GB>
     <WB_SCALE_B>0x10000</WB_SCALE_B>
     <GAIN_MIN>0x10000</GAIN_MIN>
     <GAIN_MAX>0xfc000</GAIN_MAX>
     <GAIN_STEP>0x20</GAIN_STEP>
     <ANA_GAIN_ENABLE>1</ANA_GAIN_ENABLE>
     <FTP_PERIOD>180</FTP_PERIOD>
     <FTP_TIMEOUT>360</FTP_TIMEOUT>
     <FTP_UPDATE>600</FTP_UPDATE>
     <DEBUG>0</DEBUG>
     <MAXAHEAD>3</MAXAHEAD>

<!--     <HIST_DIM_01>0x0a000a00</HIST_DIM_01>
     <HIST_DIM_23>0x0a000a00</HIST_DIM_23> -->

     <AE_INTEGERR></AE_INTEGERR>
     <WB_INTEGERR></WB_INTEGERR>
     <TASKLET_CTL>0</TASKLET_CTL>
     <GFOCUS_VALUE></GFOCUS_VALUE>

<!--     <HISTMODE_Y>$HISTMODE_Y</HISTMODE_Y>
     <HISTMODE_C>$HISTMODE_C</HISTMODE_C>
     <SKIP_DIFF_FRAME>4</SKIP_DIFF_FRAME>
     <PROFILING_EN>0</PROFILING_EN> -->

     <STROP_MCAST_EN>0</STROP_MCAST_EN>
     <STROP_MCAST_IP>0</STROP_MCAST_IP>
     <STROP_MCAST_PORT>20020</STROP_MCAST_PORT>
     <STROP_MCAST_TTL>2</STROP_MCAST_TTL>
     <STROP_AUDIO_EN>0</STROP_AUDIO_EN>
     <STROP_AUDIO_RATE>44100</STROP_AUDIO_RATE>
     <STROP_AUDIO_CHANNEL>2</STROP_AUDIO_CHANNEL>
     <STROP_FRAMES_SKIP>0</STROP_FRAMES_SKIP>
     <AUDIO_CAPTURE_VOLUME>58981</AUDIO_CAPTURE_VOLUME>
     <MULTI_MODE>$MULTI_MODE</MULTI_MODE>
     <MULTI_FLIPH> $MULTI_FLIPH</MULTI_FLIPH>
     <MULTI_FLIPV> $MULTI_FLIPV</MULTI_FLIPV>
     <MULTI_SELECTED>$MULTI_SELECTED</MULTI_SELECTED>
     <SENSOR_PHASE>$SENSOR_PHASE</SENSOR_PHASE>
<!-- <MULTI_PHASE1>$MULTI_PHASE1</MULTI_PHASE1>
     <MULTI_PHASE2>$MULTI_PHASE2</MULTI_PHASE2>
     <MULTI_PHASE3>$MULTI_PHASE3</MULTI_PHASE3> -->

     <TEMPERATURE01>-1</TEMPERATURE01>
     <TEMPERATURE23>-1</TEMPERATURE23>
    </set>
  </paramSets>
</autocampars>

DEFAULT_CONFIG;
}
?>
