#!/usr/bin/env php -q
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
require 'i2c.inc'; /// to read 10359 info //TODO NC393 - update to read sensor port!

//main()
  $sensor_port = 0;
  $p=strpos ($cvslog,"Revision");
  $version=strtok(substr($cvslog,$p+strlen("Revision"),20)," ");
//  echo "<pre>";
//  echo $version;
//  echo "</pre>";
//  $version="1.0";
  $thisScript=$_SERVER['argv'][0];
  $numBackups=5;
  $parseditPath="/parsedit.php";
  $embedImageScale=0.15;
  $twoColumns=false;
//  $configPath=      "/var/autocampars.xml";
//  $backupConfigPath="/var/autocampars.xml.backup";
//  $configPath=      "/etc/autocampars.xml";
//  $backupConfigPath="/etc/autocampars.xml.backup";
  $logFilePath=     "/var/log/autocampars.log";
//  $m10359Path=      "/var/state/10359"; /// used to select multisensor defaults
//  $sensor_state_file="/var/state/ctype";

  $configPaths=   array("/etc/elphel393/autocampars0.xml",
				  		"/etc/elphel393/autocampars1.xml",
				  		"/etc/elphel393/autocampars2.xml",
				  		"/etc/elphel393/autocampars3.xml");
  $backupConfigPaths= array("/etc/elphel393/autocampars0.xml.backup",
					  		"/etc/elphel393/autocampars1.xml.backup",
					  		"/etc/elphel393/autocampars2.xml.backup",
					  		"/etc/elphel393/autocampars3.xml.backup");
  // FIXME: NC393 - use sysfs to read 10359 and sensor configuration 
  $m10359Paths=       array("/var/state/10359.0",
					  		"/var/state/10359.1",
					  		"/var/state/10359.2",
					  		"/var/state/10359.3"); /// used to select multisensor defaults
  $sensor_state_files=array ("/var/state/ctype.0",
  		                     "/var/state/ctype.1",
					  		 "/var/state/ctype.2",
					  		 "/var/state/ctype.3");
  $framepars_paths = array ("/dev/frameparsall0",
					  		"/dev/frameparsall1",
					  		"/dev/frameparsall2",
					  		"/dev/frameparsall3");
///  $framepars_file=fopen("/dev/frameparsall","r");
  
  
  $logFile=         fopen($logFilePath,"a"); // common for all ports
                                           
	// process program arguments tgo get sensor port:
	foreach ( $_SERVER ['argv'] as $param ) {
		if (substr ( $param, 0, 7 ) == "--port=") {
			$param = substr ( $param, 7 );
			if (strlen ( $param ) > 0)
				$GLOBALS ['sensor_port'] = (myval ( $param )) & 3;
		}
	}
	// Now - HTTP GET (may overwrite)
	if (array_key_exists ( 'sensor_port', $_GET )) {
		$sensor_port = (myval($_GET ['sensor_port'])) & 3;
	}
   
	$configPath=        $configPaths        [$sensor_port];
	$backupConfigPath=  $backupConfigPaths  [$sensor_port];
	$m10359Path=        $m10359Paths        [$sensor_port]; /// used to select multisensor defaults
	$sensor_state_file= $sensor_state_files [$sensor_port];
	$framepars_path =   $framepars_files    [$sensor_port];
	
	
  $useDefaultPageNumber=15;
  $protectedPage=0;  /// change to -1 to enable saving to page 0
  $needDetection= (elphel_get_P_value($GLOBALS['sensor_port'],ELPHEL_SENSOR)<=0); /// we need sensor detection to be started before we can read 10359 eeprom and so select default parameters
  // check load command
  if($_GET['load'] != '') {
    $load_filename = $_GET['load'];
    if(is_file($load_filename)) {
      // load file
      $config = parseConfig($load_filename);
      /// skip revision check
      /// and simulate loading of the page '0' with GET request
      $_GET['ignore-revision'] = 'true';
      // and apply page '0'
      $groupMask = 0;
      for($i = 0; $i < count($config['groupNames']); $i++) {
        $name = $config['groupNames'][$i];
        $shift = -1;
//echo $name."\n";
        if($name != "") {
          if($name == 'init')
            $shift = $i;
          if($name == 'woi')
            $shift = $i;
          if($name == 'image')
            $shift = $i;
          if($name == 'whiteBalance')
            $shift = $i;
          if($name == 'autoexposure')
            $shift = $i;
          if($name == 'vignet')
            $shift = $i;
        }
        if($shift != -1)
          $groupMask |= (1 << ((integer)$shift));
      }
      echo $groupMask."\n";
//var_dump($config['groupNames']);
      $page = setParsFromPage(0, $groupMask, false); ///only init parameters?
      exit (0);
    }
  }
///============
//foreach ($_SERVER['argv'] as $param)
//  echo "1- This is autocampars.php, argv="; print_r ($_SERVER['argv']); echo "\n";

/// test if any sensor is present, otherwise exit (set by /etc/fpga at boot time, will not work if sensor does not reply)
  if (file_exists ($sensor_state_file)) $sensor_board=parse_ini_file($sensor_state_file);
  else $sensor_board=array ("ctype"=>"NONE");
  if ($sensor_board['ctype']=='NONE') {
    echo "No board is detected on the sensor port, exiting\n";
    exit (1);
  }


  $init=false;
  $daemon=false;
  $initPage=$useDefaultPageNumber;
  if ((array_key_exists('new',$_GET)) || (array_key_exists('--new',$_SERVER['argv']))) {
     if (file_exists ($configPath)) {
       rotateConfig($numBackups);
     }
  }
//  $needDetection=true; /// we need sensor detection to be started before we can read 10359 eeprom and so select default parameters
  if ($needDetection) { // sensor number is 0
    if (!detectSensor()) {
      echo "Failed sensor detection\n";
      exit (1);
    }
    $needDetection=false;
  } else $needDetection=true; /// if sensor was initialized before, it will need to be re-initialized in --init command
  $eyesis_mode=0;
  $multisensor= file_exists  ($m10359Path);
  if ($multisensor) $eyesis_mode=get_eyesis_mode(); /// will create /var/state/*
  if (!file_exists  ($configPath)) {
    $confFile=fopen ($configPath,"w+");
    fwrite ($confFile,createDefaultConfig($version, $multisensor,$eyesis_mode)); // use multisensor defaults if 10359 +sensors present

    fclose($confFile);
    echo "autocampars.php created a new configuration file $configPath from defaults.".
         ($multisensor?(($eyesis_mode>0)?(' Used Eyesis mode, camera '.$eyesis_mode):' Used multisensor mode.'):'')."\n";
    exec ('sync');
  }
  $config=parseConfig($configPath);
  if ($_SERVER['REQUEST_METHOD']=="GET") {
    processGet();
    exit(0);
  } else if ($_SERVER['REQUEST_METHOD']=="POST") {
    processPost();
    processGet();
    exit(0);
  } else {
    $old_version=$config['version'];
    foreach ($_SERVER['argv'] as $param) {
      if (substr($param,0,6)=="--init") {
        $param=substr($param,7);
        if (strlen($param)>0) $initPage=myval($param);
        if (($initPage<0) || ($initPage > $useDefaultPageNumber)) $initPage=$useDefaultPageNumber;
        $init=true; 
      } else if (substr($param,0,8)=="--daemon") {
        $daemon=true;
      } else if ($param=='--ignore-revision') {
        $config['version']=$version;
        saveRotateConfig($numBackups);
      }
    }
    if (!$daemon && !$init) {
      echo <<<USAGE

Usage: {$_SERVER['argv'][0]} --init[=page_number]
Initialise parameters using the saved ones in $configPath, page number 0<='page_number'<15)
If page number is not specified the current default one will be used.

Other functionality (parameters save/restore is provided when this script is called from the daemon,
in that case command is read from the AUTOCAMPARS_* parameter

USAGE;
      exit (0);
    }
  }
  if ($version != $old_version) { // issue warning if mismatch, but may continue
    $warn= <<<WARN
Warning! Version numbers of this script and the config file mismatch:
Script ({$_SERVER['argv'][0]}): $version.
Config file ($configPath): {$old_version}
This may (or may not) cause errors. You have several options:
 1 - re-run this script with '--ignore-revision' - the file will have
     new revision number written
 2 - re-run the script with '--new' parameter - the old config file
     will be deleted and the new fresh one created
You may also provide the same parameters in the HTTP GET request, i.e.:
http://192.168.0.9/autocampars.php?new

WARN;
    echo $warn;
    fwrite ($logFile,$warn);
//echo "version=$version\n";
//echo "old_version=$old_version\n";
//echo "config['version']=".$config['version']."\n";
    if ($version != $config['version']) exit (1); /// abort
  }
  if ($init) {
      $page=processInit($initPage, $needDetection);
      if ($page <0) {
        echo "Sensor failed to initialize, see $logFilePath for detailes\n";
        exit (1);
      } else {
        echo "Sensor was successfully initialized at ".date("F j, Y, g:i a")." from $configPath page $page\n";
/// Currently - only for eyesis
       sync2master();

      }
//echo "*version=$version\n";
//echo "*old_version=$old_version\n";
//echo "*config['version']=".$config['version']."\n";
     if ($version != $old_version) exit (1);
      exit (0);
  }
  if ($daemon) {
    processDaemon();
    exit (0);
  }
  exit (0);
/*
#define AUTOCAMPARS_CMD_RESTORE  1  /// restore specified groups of parameters from the specified page
#define AUTOCAMPARS_CMD_SAVE     2  /// save all current parameters to the specified group (page 0 is write-protected)
#define AUTOCAMPARS_CMD_DFLT     3  /// make selected page the default one (used at startup), page 0 OK 
#define AUTOCAMPARS_CMD_SAVEDFLT 4  /// save all current parameters to the specified group (page 0 is write-protected) and make it default (used at startup)
#define AUTOCAMPARS_CMD_INIT     5  /// reset sensor/sequencers, restore all parameters from the specified page
*/
/// Even as sync happens simultaneously, frame sync is not - it depends on exposure time.
/// So we need to disable autoexposure and set minimal exposure on this camera, restore on exit
function sync2master($timeout=120,$min_frame_master=30) {
 $hardware_mask=7; // only 8 frames command buffer in hardware
 $fpga_trig_period=0x7b;

  if (!file_exists('/var/state/APPLICATION') || !file_exists('/var/state/APPLICATION_MODE')) return -1; 
  $application=     file_get_contents('/var/state/APPLICATION');
  $IP_shift=(file_get_contents('/var/state/APPLICATION_MODE')+0) % 100; // 0 - SINGLE, 1..3 - Eyesis, 101+ Eyesis4pi
  if ($application!='EYESIS') return 0; /// not supported yet
  if ($IP_shift==1) return 0;   /// it is the master (101 will also get here)
  $neteth0=file('/etc/conf.d/net.eth0');
  $this_ip=array();
  foreach ($neteth0 as $line) {
    if (strpos ( $line,'=')!==false) {
      $aline=explode("=",trim($line));
      if (trim($aline[0]=='IP')) {
        $this_ip=explode('.',trim($aline[1]));
      }
    }
  }
  if (count ($this_ip)!=4 ) return -1;
  $master_ip=$this_ip[0].'.'.$this_ip[1].'.'.$this_ip[2].'.'.($this_ip[3]-$IP_shift+1);
  $noaexp=array("DAEMON_EN"=>0,"EXPOS"=>1);
  elphel_skip_frames($GLOBALS['sensor_port'],4); /// 3 here is minimum, otherwise DAEMON_EN is still 0 (after init itself) 
  $saved_autoexp=elphel_get_P_arr($GLOBALS['sensor_port'],$noaexp);
  elphel_set_P_arr($GLOBALS['sensor_port'],$noaexp);
  elphel_skip_frames($GLOBALS['sensor_port'],4); /// to be sure exposure is applied
  echo ('Synchronizing with '.$master_ip."\n");
/// Make sure eth0 is up, otherwise wait for $timeout seconds and reboot
  $abort_time=time()+$timeout;
  $retval=1;
  while ((time()<$abort_time) && ($retval != 0 )) {
    exec  ('/usr/sbin/mii-diag eth0 --status >/dev/null',$output, $retval);
    if ($retval!=0) {
      echo time().": waiting for eth0 to come up\n";
      elphel_skip_frames($GLOBALS['sensor_port'],1);
    }
  }
//  passthru ('/usr/sbin/mii-diag eth0 --status');
/*passthru ('route');
  passthru ('ifconfig');
  passthru ('wget "http://'.$master_ip.':8081/wframe" -O -');*/

  if ($retval !=0 ) {
    echo "Giving up waiting for the eth0 to come up (if you are watching console). Rebooting\n";
    exec ('reboot -f');
  }
  $master_frame=false;
  while ((time()<$abort_time) && (($master_frame===false) || ($master_frame<$min_frame_master))) {
    $master_frame=file_get_contents('http://'.$master_ip.':8081/wframe)')+0; /// will wait
    if (($master_frame===false) || ($master_frame<$min_frame_master)) {
      echo time().": waiting for eth0 to come up\n";
      elphel_skip_frames($GLOBALS['sensor_port'],1);
    }
  }

  if ($master_frame===false) {
    echo "Giving up waiting $master_il to respond (if you are watching console). Rebooting\n";
    exec ('reboot -f');
  }


  $this_frame=elphel_get_frame($GLOBALS['sensor_port']);
  echo 'Master frame='.$master_frame."\n";
  echo 'This frame='.$this_frame."\n";
  $skip= (1+$hardware_mask + ($this_frame& $hardware_mask)  - ($master_frame & $hardware_mask)) & $hardware_mask;
  echo 'skip '.$skip." frames\n";
  // FIXME: NC393 - replace
  /*
  if ($skip>0) {
    elphel_fpga_write($fpga_trig_period,0);/// stop trigger input
    for ($i=0;$i<$skip;$i++) $master_frame=file_get_contents('http://'.$master_ip.':8081/wframe)')+0; /// will wait
    elphel_fpga_write($fpga_trig_period,elphel_get_P_value($GLOBALS['sensor_port'],ELPHEL_TRIG_PERIOD));/// restore trigger
  }*/
  elphel_set_P_value($GLOBALS['sensor_port'],ELPHEL_THIS_FRAME,$master_frame+0);
//  $master_frame=file_get_contents('http://'.$master_ip.':8081/wframe)')+0; /// will wait
//  $this_frame=elphel_get_frame();
//  echo "\nafter sync:\n";
//  echo 'Master frame='.$master_frame."\n";
//  echo 'This frame='.$this_frame."\n";
/// restore (auto)exposure
  elphel_set_P_arr($GLOBALS['sensor_port'],$saved_autoexp);
//  var_dump($saved_autoexp); 
}



function  get_eyesis_mode() {
  $xml=simplexml_load_string(i2c_read256b(0xa0,0)); // read contents of 
  if ($xml===false) return 0;
  echo 'Application - '.((string)$xml->app).', mode: '.((string) $xml->mode)."\n";
  if (((string)$xml->app)!='') {
    $FS=fopen('/var/state/'.strtoupper((string)$xml->app),'w');
    fprintf($FS,"%s",strtoupper((string) $xml->mode));
    fclose($FS);

    $FS=fopen('/var/state/APPLICATION','w');
    fprintf($FS,"%s",strtoupper((string) $xml->app));
    fclose($FS);

    $FS=fopen('/var/state/APPLICATION_MODE','w');
    fprintf($FS,"%s",strtoupper((string) $xml->mode));
    fclose($FS);


  }
  if (strtoupper((string)$xml->app)=='EYESIS') {
    $eyesis_mode=($xml->mode)+0;
    return $eyesis_mode;
  }
  return 0;
}
function detectSensor() {
  global $logFile, $framepars_path;
  $maxWait=5.0; ///sec
  $waitDaemons=5.0; /// Wait for daemons to stop (when disabled) before resetting frame number.
                    /// They should look at thei enable bit periodically and restart if the frame is
                    /// the frame is not what they were expecting to be
  $sleepOnce=0.1;
/// Here trying full reset with zeroing the absolute frame number, setting all frame parameters to 0 and starting
  $framepars_file=fopen($framepars_path,"r");
///TODO: Improve sequence here so it will not depend on delays
  fseek($framepars_file,ELPHEL_LSEEK_FRAMEPARS_INIT,SEEK_END);                      /// NOTE: resets all the senor parameters (tasklet control)
///  elphel_set_P_value($GLOBALS['sensor_port'],ELPHEL_SENSOR, 0x00, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);/// set sensor to 0 will start detection
  echo "before reset - current frame=".elphel_get_frame($GLOBALS['sensor_port'])."\n";
//  elphel_set_P_value($GLOBALS['sensor_port'],ELPHEL_SENSOR, 0x00, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);/// set sensor to 0 will start detection
  elphel_set_P_value($GLOBALS['sensor_port'],ELPHEL_SENSOR, 0x00, elphel_get_frame($GLOBALS['sensor_port']), ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);/// set sensor to 0 will start detection
  fseek($framepars_file,ELPHEL_LSEEK_SENSORPROC,    SEEK_END);                      /// In case the autoprocessing after parameter write will be disabled in the future
                                                                                    /// (normally parameters are processed at frame sync interrupts - not yet available)
  fclose ($framepars_file);
/// Sensor should be up and running. let's wait for up to $maxWait seconds
  for ($t=0; elphel_get_frame($GLOBALS['sensor_port'])==0; $t+=$sleepOnce) {
    usleep ($sleepOnce*1000000);
    if ($t>$maxWait) {
      fwrite ($logFile,"Sensor failed to initialize at ".date("F j, Y, g:i a")."\n");
      fclose ($logFile);
      exec ('sync');
      return false; /// sensor timeout
    }
  }
  return true; /// Sensor started OK
}

function processInit($initPage, $needDetection=true) {
  global $config;

  $waitDaemons=5.0; /// Wait for daemons to stop (when disabled) before resetting frame number.
                    /// They should look at thei enable bit periodically and restart if the frame is
                    /// the frame is not what they were expecting to be

  if (elphel_get_frame($GLOBALS['sensor_port'])>0) {
     echo "Current frame=".elphel_get_frame($GLOBALS['sensor_port']).", sleeping to give daemons a chance\n";
     elphel_set_P_value($GLOBALS['sensor_port'],ELPHEL_COMPRESSOR_RUN, 0x00, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC); /// turn compressor off
     elphel_set_P_value($GLOBALS['sensor_port'],ELPHEL_DAEMON_EN, 0x00, 0, ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);      /// turn daemons off
     usleep ($waitDaemons*1000000);
     echo "Current frame=".elphel_get_frame($GLOBALS['sensor_port']).", waking up, daemons should be dead already\n";
  } 
  if ($needDetection) {
    if (!detectSensor()) RETURN -1;
  }
  echo "after reset - current frame=".elphel_get_frame($GLOBALS['sensor_port'])."\n";
//  $page=setParsFromPage($initPage,0xffffffff,true); /// all parameters, init mode - treat all parameters as new, even when they are the same as current (0)
  $page=setParsFromPage($initPage,0x1,true); ///only init parameters?
  echo "after setParsFromPage - current frame=".elphel_get_frame($GLOBALS['sensor_port'])."\n";
  return $page;
/*!
Strange with autoexposure - for frame #006 it gets zero pixels in the histogram, 7 - 0x3fff
8..10 yet unknown)
frame 11 (0xb) - normal total_pixels=0x4c920
*/


}
function processDaemon() {
  global $config,  $useDefaultPageNumber, $protectedPage,$numBackups;

  $AUTOCAMPARS=elphel_get_P_arr($GLOBALS['sensor_port'],array("AUTOCAMPARS_CMD"=>null, "AUTOCAMPARS_GROUPS"=>null,"AUTOCAMPARS_PAGE"=>null));
//  echo "processDaemon()\n";
//  print_r($AUTOCAMPARS);
  $page=     myval($AUTOCAMPARS['AUTOCAMPARS_PAGE']);
  $groupMask=myval($AUTOCAMPARS['AUTOCAMPARS_GROUPS']);
  switch ((integer) $AUTOCAMPARS['AUTOCAMPARS_CMD']) {
    case ELPHEL_CONST_AUTOCAMPARS_CMD_RESTORE:
      $page=setParsFromPage($page,$groupMask);
//      echo "ELPHEL_CONST_AUTOCAMPARS_CMD_RESTORE\n";
      break;
    case ELPHEL_CONST_AUTOCAMPARS_CMD_SAVE:
//      echo "ELPHEL_CONST_AUTOCAMPARS_CMD_SAVE\n";
      $page=readParsToPage($page);
      saveRotateConfig($numBackups);
      break;
    case ELPHEL_CONST_AUTOCAMPARS_CMD_DFLT:
//      echo "ELPHEL_CONST_AUTOCAMPARS_CMD_DFLT\n";
      if (($page>=0) && ($page<$useDefaultPageNumber) && ($page !=$useDefaultPageNumber)) {
        $config['defaultPage']=$page;
        saveRotateConfig($numBackups);
      }
      break;
    case ELPHEL_CONST_AUTOCAMPARS_CMD_SAVEDFLT:
//      echo "ELPHEL_CONST_AUTOCAMPARS_CMD_SAVEDFLT\n";
      $page=readParsToPage($page);
      $config['defaultPage']=$page;
      saveRotateConfig($numBackups);
      break;
    case ELPHEL_CONST_AUTOCAMPARS_CMD_INIT:
//      echo "ELPHEL_CONST_AUTOCAMPARS_CMD_INIT\n";
      $page=processInit($page, true); // needs sensor reset/detection
///NOTE: If $page<0 here - sensor failed to initialize, if this script was called from the daemon - it will never restart on it's own. Log record is made
      break;
    default:
      echo $_SERVER['argv'][0].": Unknown command=".$AUTOCAMPARS['AUTOCAMPARS_CMD'];
  }
//  print_r($config);
}
function processPost() {
 global $config;
///Updating comments?
 $needle="update_comment_";
 foreach ($_POST as $key=>$value) if (substr($key,0,strlen($needle))==$needle) {
   $page=substr($key,strlen($needle));
   $comment=$_POST['comment_'.$page];
//   echo "updating comment for page $page, it will be $comment\n";
   $config['paramSets'][$page]['comment']=$comment;
   saveRotateConfig($numBackups);
   processGet();
   exit(0);
 } 
 if (array_key_exists('update_default',$_POST)) {
   $config['defaultPage']=$_POST['default_page'];
   saveRotateConfig($numBackups);
   processGet();
   exit(0);
 }
 $needle="save_";
 foreach ($_POST as $key=>$value) if (substr($key,0,strlen($needle))==$needle) {
   $page=substr($key,strlen($needle));
   $page=readParsToPage($page);
   $config['defaultPage']=$page;
   saveRotateConfig($numBackups);
   processGet();
   exit(0);
 } 
 $needle="restore_";
 foreach ($_POST as $key=>$value) if (substr($key,0,strlen($needle))==$needle) {
   $page=substr($key,strlen($needle));
   $needle="group_";
   $groupMask=0;
   foreach ($_POST as $key=>$value) if (substr($key,0,strlen($needle))==$needle) {
     $groupMask |= (1 << ((integer)substr($key,strlen($needle))));
   }
   $page=setParsFromPage($page,$groupMask);
   processGet();
   exit(0);
 } 


 echo "<pre>\n";
 print_r ($_POST);
 echo "</pre>\n";
 exit (0);
}
///TODO: Make the contol page aware of stuck sensor (sleep 1, then 5)? and suggest init if frame is not changing

function processGet() {
  global $config,$twoColumns,$configPath,$version, $sensor_port;
  // New in NC393 - get sensor port from URL - moved to startup of the script
//  if (array_key_exists('sensor_port',$_GET)) {
//  	 $sensor_port = (int) $_GET['sensor_port'];
//  }
  if (array_key_exists('ignore-revision',$_GET) && ($version != $config['version'])) {
     $config['version']=$version;
     saveRotateConfig($numBackups);
  }
  if ($version != $config['version']) {
    startPage("Warning: version numbers mismatch", "function updateLink(){}");
    $warn= <<<WARN
<center><h4>Warning! Version numbers of this script and the config file mismatch:</h4></center>
Script:  <b>$version</b>.<br/>
Config file ($configPath): <b>{$config['version']}</b><br/>
<ol>This may (or may not) cause errors. You have several options:
<li> Follow <a href="?new">this link </a> and create a new config file </li>
<li> Follow <a href="?ignore-revision">this other link </a> to ignore the warning and write a new revision number to the config file</li>
<li> First update the version number with the link above, then manually edit/merge old and new data</li>
</ol>
WARN;
    echo $warn;
    endPage();
    exit (1);
  }

  $page_title="Model 393 Camera Parameters save/restore";
  startPage($page_title, mainJavascript());
  if ($twoColumns) printf ("<table><tr><td style='vertical-align: top'>\n");
  writeGroupsTable();
  if ($twoColumns)  printf ("</td><td>\n");
  else              printf ("<br/>\n");
  writePagesTable();
  if ($twoColumns) printf ("</td></tr></table>\n");
  endPage();
}

function startPage($page_title, $javascript) {
$url=str_replace('new','same',$_SERVER['REQUEST_URI']); /// form will remove "new" when submitting

//    [REQUEST_URI] => /autocampars.php?new

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
<form action="$url" method="post">
HEAD;
/*! // debug
echo "<!--\n";
print_r($_SERVER);
echo "-->\n";
*/
}
function  endPage(){
  echo "\n</form></body></html>\n";
}

function writeGroupsTable() {
   global $config;
   printf ("<table border='1' style='font-family: Courier, monospace;'>\n");
   printf ("<tr>");
   printf ("<td><input type='button' value='&nbsp;Select&nbsp;\nAll' onclick='checkAll()' title='Select all parameter groups'/></td>\n");
   printf ("<td style='text-align:center'>Bit</td><td style='text-align:center'>Name</td><td style='text-align:center'>Description</td></tr>\n");
   foreach ($config['groupBits'] as $name=> $bit) {
     printf("<tr>");
     printf ("<td style='text-align:center'><input type='checkbox' name='group_%d' value='1' id='id_group_%d' onclick='updateLink();'/></td>",$bit,$bit);
     printf ("<td style='text-align:right' >%d</td>",$bit);
     printf ("<td style='text-align:left' >%s</td>",$name);
     printf ("<td style='text-align:left' >%s</td>",$config['groupDescriptions'][$bit]);
     printf("</tr>\n");
   }
   printf ("<tr>");
   printf ("<td><input type='button' value='Deselect\nAll' onclick='unCheckAll()' title='Deselect all parameter groups'/></td>\n");
   printf ("<td colspan='3'>&nbsp;<a href='#' target='new' id='id_editLink' title='View/edit current values of the selected parameters groups'>View/Edit Current</a>");
   printf ("<label style='border: 0px solid #000;' for='id_embed_image' title='Include image with the parameter edit window'>&nbsp;&nbsp;<input type='checkbox' name='embed' value='1' id='id_embed_image' onclick='updateLink();' checked/>Include image</label></td></tr>\n");
   printf ("</table>\n");
}
function writePagesTable() {
   global $config,$protectedPage,$useDefaultPageNumber;
   printf ("<table border='1' style='font-family: Courier, monospace;'>\n");
   printf ("<tr>");
   printf ("<td style='text-align:center'>Page</td>");
   printf ("<td style='text-align:center'>");
       printf ("<input type='submit' name='update_default' value='Update\ndefault' id='id_update_default' title='Update default (used at boot time) configuration page'/>");
   printf ("</td>");
   printf ("<td style='text-align:center'>Save</td>");
   printf ("<td style='text-align:center'>Restore<br/>selected<br/>groups</td>");
   printf ("<td style='text-align:center'>Time Saved</td>");
   printf ("<td style='text-align:center'>Comments</td>");
   printf ("</tr>\n");
   for ($i=0;$i<$useDefaultPageNumber;$i++) {
     printf ("<tr>");
     printf ("<td style='text-align:right'>%d</td>",$i);

     if (array_key_exists($i,$config['paramSets'])) {
       printf ("<td style='text-align:center'><input type='radio' name='default_page' value='%d' id='id_default_page_%d' %s".
             " title='Select config file page %d as default (boot up) page' /></td>",
               $i,$i,($i==$config['defaultPage'])?"checked":"",$i);
     } else {
       printf ("<td>&nbsp;</td>");
     }
     if ($i==$protectedPage) {
       printf ("<td>&nbsp;</td>");
     } else  {
       printf ("<td style='text-align:center'><input type='submit' name='save_%d' value='%s' id='id_save_page_%d' title='Save current parameters to the config file as page %d'/></td>",
                  $i,array_key_exists($i,$config['paramSets'])?"Overwrite":"Save",$i,$i);
     }
     if (array_key_exists($i,$config['paramSets'])) {
       printf ("<td style='text-align:center'><input type='submit' name='restore_%d' value='Restore' id='id_restore_page_%d' title='Restore parameters from the config file page %d'/></td>",
                  $i,$i,$i);
       printf ("<td style='text-align:left'>%s</td>",$config['paramSets'][$i]['timestamp']?date("F j, Y, g:i a",$config['paramSets'][$i]['timestamp']):"&nbsp;");
       printf ("<td>");
       if ($i==$protectedPage)
         printf ("%s&nbsp;",$config['paramSets'][$i]['comment']);
       else {
         printf ("<input type='text'   name='comment_%d' size='40' value='%s' id='id_comment_%d' style='text-align:left'/>",$i,$config['paramSets'][$i]['comment'],$i);
         printf ("<input type='submit' name='update_comment_%d' value='Update' id='id_update_comment_%d' title='Update comment in the config file'/>",$i,$i);
       }

       printf ("</td>");
     } else {
       printf ("<td colspan='3'>&nbsp;</td>");
     }
     printf ("</tr>\n");
   }
   printf ("</table>\n");
}

function mainJavascript() {
   global $config,$protectedPage,$useDefaultPageNumber,$parseditPath,$embedImageScale;
   $checkboxNumbers="";
   foreach ($config['groupBits'] as $name=> $bit) {
     $checkboxNumbers.=$bit.",";
   }
   $checkboxNumbers=rtrim ($checkboxNumbers,",");
   $groupNames="";
   for($i=0;$i<32;$i++) $groupNames.='"'.$config['groupNames'][$i].'",';
   $groupNames=rtrim ($groupNames,",");
   $groupList="{";
   foreach ($config['groups'] as $name=> $value) {
     if (($name!='comment') && ($name!='timestamp')) /// Fixing bug for the group "init"
     $groupList.=$name.":".$value.",";
   }
   $groupList=rtrim($groupList,",").'}';
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
  document.getElementById('id_editLink').href="$parseditPath?"+
  ((document.getElementById('id_embed_image').checked)?"embed=$embedImageScale&":"")+
  "title=Parameters+for+groups:+"+selectedGroups;
  for (i in selectedPars) {
    document.getElementById('id_editLink').href+="&"+selectedPars[i];
  }
}
JAVASCRIPT;
}

/**
 * @brief Scan commands for possible changing gamma tables, calculate them in advance
 * (driver can only scale gamma, not calculate prototypes)
 */

function  addGammas($pars) {
   $gammas=array();
   if (array_key_exists('GTAB_R', $pars)) $gammas[$pars['GTAB_R' ]>>16]=1; /// duplicates will be eliminated
   if (array_key_exists('GTAB_G', $pars)) $gammas[$pars['GTAB_G' ]>>16]=1;
   if (array_key_exists('GTAB_GB',$pars)) $gammas[$pars['GTAB_GB']>>16]=1;
   if (array_key_exists('GTAB_B', $pars)) $gammas[$pars['GTAB_B' ]>>16]=1;
//   var_dump($gammas);
   foreach ($gammas as $gamma_black=>$whatever) {
     $black=($gamma_black>>8) & 0xff;
     $gamma=($gamma_black & 0xff)*0.01;
     elphel_gamma_add ($gamma, $black); // does not use $GLOBALS['sensor_port'],
   }
}


function setParsFromPage($page,$mask,$initmode=false) {
  global $config, $useDefaultPageNumber;
  if ($page==$useDefaultPageNumber) $page=$config['defaultPage'];
  $parToSet=array();
  foreach ($config['groups'] as $par=>$parMask) {
    if (($mask & $parMask) &&
         array_key_exists($par, $config['paramSets'][$page]) &&
         (!$config['parTypes'][$par])) /// not 'text'
                $parToSet[$par]=myval($config['paramSets'][$page][$par]);
  }
///NOTE: Important ; add gamma tables if parameters modified involve setting/chnaging them
  addGammas($parToSet);
///FIXME: !!!
/// Prevent too many i2c register writes for the same frame. If it is the case - split parameter groups between several frames
/// AS there is a hardware limit of 64 parameters/frame in the sequencers (64 for the i2c, 64 - for the FPGA registers)

  if ($initmode) {
    $compressor_run=array('COMPRESSOR_RUN'=>2);
    $daemon_en=     array('DAEMON_EN_AUTOEXPOSURE'=>1,
                          'DAEMON_EN_STREAMER'=>1,
                          'DAEMON_EN_CCAMFTP'=>0,
                          'DAEMON_EN_CAMOGM'=>0,
                          'DAEMON_EN_TEMPERATURE'=>0
                         );


    if (isset($parToSet['COMPRESSOR_RUN']))  {$compressor_run['COMPRESSOR_RUN']=$parToSet['COMPRESSOR_RUN'];unset($parToSet['COMPRESSOR_RUN']);}
    if (isset($parToSet['DAEMON_EN']))       {$daemon_en['DAEMON_EN']=     $parToSet['DAEMON_EN'];          unset($parToSet['DAEMON_EN']);}
    if (isset($parToSet['DAEMON_EN_AUTOEXPOSURE'])){$daemon_en['DAEMON_EN_AUTOEXPOSURE']= $parToSet['DAEMON_EN_AUTOEXPOSURE']; unset($parToSet['DAEMON_EN_AUTOEXPOSURE']);}
    if (isset($parToSet['DAEMON_EN_STREAMER']))    {$daemon_en['DAEMON_EN_STREAMER']=     $parToSet['DAEMON_EN_STREAMER'];     unset($parToSet['DAEMON_EN_STREAMER']);}
    if (isset($parToSet['DAEMON_EN_CCAMFTP']))     {$daemon_en['DAEMON_EN_CCAMFTP']=      $parToSet['DAEMON_EN_CCAMFTP'];      unset($parToSet['DAEMON_EN_CCAMFTP']);}
    if (isset($parToSet['DAEMON_EN_CAMOGM']))      {$daemon_en['DAEMON_EN_CAMOGM']=       $parToSet['DAEMON_EN_CAMOGM'];       unset($parToSet['DAEMON_EN_CAMOGM']);}
    if (isset($parToSet['DAEMON_EN_TEMPERATURE'])) {$daemon_en['DAEMON_EN_TEMPERATURE']=  $parToSet['DAEMON_EN_TEMPERATURE'];  unset($parToSet['DAEMON_EN_TEMPERATURE']);}
    if (isset ($daemon_en['DAEMON_EN'])) {
      if (isset($daemon_en['DAEMON_EN_AUTOEXPOSURE'])) $daemon_en['DAEMON_EN'] |= $daemon_en['DAEMON_EN_AUTOEXPOSURE']?1:0;
      if (isset($daemon_en['DAEMON_EN_STREAMER']))     $daemon_en['DAEMON_EN'] |= $daemon_en['DAEMON_EN_STREAMER']?    2:0;
      if (isset($daemon_en['DAEMON_EN_CCAMFTP']))      $daemon_en['DAEMON_EN'] |= $daemon_en['DAEMON_EN_CCAMFTP']?     4:0;
      if (isset($daemon_en['DAEMON_EN_CAMOGM']))       $daemon_en['DAEMON_EN'] |= $daemon_en['DAEMON_EN_CAMOGM']?      8:0;
      if (isset($daemon_en['DAEMON_EN_TEMPERATURE']))  $daemon_en['DAEMON_EN'] |= $daemon_en['DAEMON_EN_TEMPERATURE']?32:0;
    }
    elphel_set_P_arr($GLOBALS['sensor_port'],array('COMPRESSOR_RUN'=>0,'DAEMON_EN'=>0),-1,ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
    elphel_skip_frames($GLOBALS['sensor_port'],2);
    elphel_set_P_arr($GLOBALS['sensor_port'],$parToSet,-1,ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
    elphel_skip_frames($GLOBALS['sensor_port'],2); ///Adjust?
    elphel_set_P_arr($GLOBALS['sensor_port'],$compressor_run,-1,ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
    echo "setting COMPRESSOR_RUN=".$compressor_run['COMPRESSOR_RUN']."\n";
    elphel_skip_frames($GLOBALS['sensor_port'],4); ///Adjust? So streamer will have at least 2 good frames in buffer?
    elphel_set_P_arr($GLOBALS['sensor_port'],$daemon_en,-1,ELPHEL_CONST_FRAMEPAIR_FORCE_NEWPROC);
    echo "setting DAEMON_EN=";print_r($daemon_en);echo "\n";
//    print_r($parToSet);echo "\n";
  } else {
    elphel_set_P_arr($GLOBALS['sensor_port'],$parToSet);
  }
  return $page;
}

function readParsToPage($page) {
  global $config,$protectedPage,$useDefaultPageNumber;
  if (($page == $protectedPage) || ($page < 0) || ($page>$useDefaultPageNumber)) return -1;
  if ($page==$useDefaultPageNumber) $page=$config['nextPage']; /// 0 is write protected
  if ($page == $protectedPage) $page=findNextPage($page);
// echo "\nbefore:";print_r($config);
  $config['paramSets'][$page]=elphel_get_P_arr($GLOBALS['sensor_port'],$config['groups']); /// 'text' parameters will be just ignored
  $config['paramSets'][$page]['comment']="Saved on ".date("F j, Y, g:i a");
  $config['paramSets'][$page]['timestamp']=time();
// echo "\nafter:";print_r($config);
  $config['nextPage']=findNextPage($page);
  elphel_set_P_arr($GLOBALS['sensor_port'],array("AUTOCAMPARS_PAGE"=>$page+0)); /// will set some (3?) frames ahead so not yet available until waited enough
//  echo "Saved to page $page, result=$result\n";
  return $page;
//date("F j, Y, g:i a")
}
//elphel_parse_P_name

function saveRotateConfig($numBackups) {
  global  $config,$configPath, $backupConfigPath;
  rotateConfig($numBackups);

  $confFile=fopen ($configPath,"w+");
  fwrite ($confFile,encodeConfig($config));
  fclose($confFile);
  exec ('sync');
}
function rotateConfig($numBackups) {
  global  $config,$configPath, $backupConfigPath;
  if (file_exists (backupName($numBackups))) unlink  (backupName($numBackups));
  for ($i=$numBackups-1;$i>0;$i--) if (file_exists (backupName($i-1)))  rename  (backupName($i-1),backupName($i));
  if (($numBackups>0) && (file_exists ($configPath) )) rename  ($configPath,backupName(0));
}

function backupName($num) {
   global $backupConfigPath;
   if ($num>0) return $backupConfigPath.$num;
   else return $backupConfigPath;
}

function findNextPage($page) {
  global $config,$protectedPage,$useDefaultPageNumber;
  $page++;
  while (($page==$protectedPage) || ($page==$useDefaultPageNumber)) {
    $page++;
    if ($page>$useDefaultPageNumber) $page=0;
  }
  return $page;
}


function parseConfig($filename) {
   $config=array('version'=>0,
                 'defaultPage'=>0,
                 'nextPage'=>0,
                 'groupBits'=>array(),
                 'groupNames'=>array(),
                 'groupDescriptions'=>array(),
                 'descriptions'=>array(),
                 'groups'=>array(),
                 'parTypes'=>array(),
                 'paramSets'=>array());
   $xml = simplexml_load_file($filename);
   $config['version']=(string) $xml->version;
   $config['defaultPage']=(string) $xml->defaultPage;
   $config['nextPage']=(string) $xml->nextPage;
   foreach ($xml->groupNames->children() as $entry) {
     $config['groupBits'][$entry->getName()] = (integer) $entry->attributes()->bit;
     $config['groupNames'][(integer) $entry->attributes()->bit] = $entry->getName();
     $config['groupDescriptions'][(integer) $entry->attributes()->bit]=trim((string)$entry,"\" ");
   }
   foreach ($xml->descriptions->children() as $entry) {
     $config['descriptions'][$entry->getName()]=trim((string)$entry,"\"");
   }
   foreach ($xml->groups->children() as $entry) {
     if ((string)$entry) {
        $value=0;
        foreach (explode(',',trim((string)$entry,'" ')) as $key) $value |= (1<< ($config['groupBits'][$key]));
        $config['groups'][$entry->getName()]=$value;
        $config['parTypes'][$entry->getName()]=$entry->attributes()->type;
     }
   }
   foreach ($xml->paramSets->children() as $paramSet) {
     $numSet=(integer)$paramSet->attributes()->number;
     $config['paramSets'][$numSet] = array();
//     foreach ($paramSet->children() as $param)  $config['paramSets'][$numSet][$param->getName()]=myval((string) $param);
     foreach ($paramSet->children() as $param) {
//        echo $param->getName();var_dump((string)$param);
          $config['paramSets'][$numSet][$param->getName()]= trim((string) $param, "\" ") ;
     }
   }
 return $config;
}

function encodeConfig($config) {
//echo "encodeConfig()";print_r($config);
     $xml ="<?xml version=\"1.0\" standalone=\"yes\"?>\n<!-- This file is generated by ".$_SERVER['argv'][0]." -->\n";
     $xml.="  <autocampars>\n";
     $xml.="<!-- File version -->\n";
     $xml.=sprintf("    <version>%s</version>\n",$config['version']);
     $xml.="<!-- Number of parameter page that will be used as default (i.e. after camera boot) -->\n";
     $xml.=sprintf("    <defaultPage>%d</defaultPage>\n",$config['defaultPage']);
     $xml.="<!-- Number of parameter page that will be next used to save parameters (if not specified) -->\n";
     $xml.=sprintf("    <nextPage>%d</nextPage>\n",$config['nextPage']);
     $xml.="<!-- Descriptions of the parameters -->\n";
     $xml.="    <descriptions>\n";
     foreach ($config['descriptions'] as $name=>$description)
        $xml.=sprintf("      <%s>%s</%s>\n",$name,htmlspecialchars($description,ENT_QUOTES),$name);
     $xml.="    </descriptions>\n";
     $xml.="<!-- Parameter groups that can be restored from the saved values  -->\n";
     $xml.="    <groupNames>\n";
     foreach ($config['groupBits'] as $key=>$bit)
         $xml.=sprintf("      <%s bit=\"%d\">%s</%s>\n",$key,$bit,$config['groupDescriptions'][$bit],$key);
     $xml.="    </groupNames>\n";

     $xml.="<!-- Parameter groups -->\n";
     $xml.="    <groups>\n";
     foreach ($config['groups'] as $key=>$value) {
        $groups="";
        for ($bit=0;$bit<24;$bit++) if ($value & (1<<$bit)){
          if ($groups) $groups.=",";
           $groups.=$config['groupNames'][$bit];
        } 
        if ($config['parTypes'][$key]) $xml.=sprintf("      <%s type=\"%s\">\"%s\"</%s>\n",$key,$config['parTypes'][$key],$groups,$key);
        else                           $xml.=sprintf("      <%s>\"%s\"</%s>\n",$key,$groups,$key);
     }
     $xml.="    </groups>\n";
     $xml.="<!-- Saved parameter Sets -->\n";
     $xml.="    <paramSets>\n";
     foreach ($config['paramSets'] as $index=>$paramSet) {
        $xml.=sprintf("      <set number=\"%d\">\n",$index);
        foreach ($paramSet as $key=>$value) {
          $xml.=sprintf("        <%s>\"%s\"</%s>\n",$key,$value,$key);
        }
        $xml.="      </set>\n";
     }

     $xml.="    </paramSets>\n";

     $xml.="  </autocampars>\n";
  return $xml;
}

function myval ($s) {
  $s=trim($s,"\" ");
  if (strtoupper(substr($s,0,2))=="0X")   return intval(hexdec($s));
  else return intval($s);
}

function calculateDefaultPhases(){

 $phases=array('SENSOR_PHASE'=>0, // values are not used, just the keys
               'MULTI_PHASE1'=>0,
               'MULTI_PHASE2'=>0,
               'MULTI_PHASE3'=>0);
 $phases=elphel_get_P_arr($GLOBALS['sensor_port'],$phases); // get current values
/**TODO:  Here read and compare the 10359 (REV>=B)  and 10338 (REV>=E), read other persistent parameters (set by /etc/fpga, defined in c313a.h, such as
#define G_CABLE_TIM     (FRAMEPAR_GLOBALS + 7) /// Extra cable delay, signed ps)
#define G_FPGA_TIM0     (FRAMEPAR_GLOBALS + 8) /// FPGA timing parameter 0 - difference between DCLK pad and DCM input, signed (ps)
#define G_FPGA_TIM1     (FRAMEPAR_GLOBALS + 9) /// FPGA timing parameter 1\
...
and calculate sensor/10359 phases using EEPROM data. then replace the $phases array elements in return ed array

Do not forget 'global' :-)
*/
  //if Eyesis4pi mode then override SENSOR_PHASE depending on the EEPROM data
  $xml=simplexml_load_string(i2c_read256b(0xa0,0));
  if ($xml!==false){
      if ($xml->model=="10359") {
	$cable_len_353_359 = $xml->len;
	if ($cable_len_353_359==450) {
	    $SP = 0xa9;
	    /////something's wrong////////////////////////////////////////////////////////////
	    //$DCM_STEP = 22; //  ps/step
	    //$clk_period = 1000000000000.0/elphel_get_P_value(ELPHEL_CLK_SENSOR);
	    //$cable_len_353_359_ps = $cable_len_353_359*15;//15ps/mm
	    //$px_delay = round ( -($clk_period/2 - $cable_len_353_359_ps) );
	    //$px_delay_90 = round( (4*$px_delay+$clk_period/2)/$clk_period );
	    //$px_delay = round( $px_delay - ($px_delay_90*$clk_period)/4 );
	    //$px_delay = round ($px_delay/$DCM_STEP );
	    //$SP = ($px_delay & 0xffff) | (($px_delay_90 & 3) <<16) | 0x80000;
	    //////////////////////////////////////////////////////////////////////////////////
	    $phases['SENSOR_PHASE']=$SP;
	    //echo "SENSOR_PHASE for 10359A is set to $SP\n";
	}
      }
      if ($xml->model=="10338"){
	    $len = $xml->len;
	    $DCM_STEP = 22; //  ps/step
	    $clk_period = 1000000000000.0/elphel_get_P_value($GLOBALS['sensor_port'],ELPHEL_CLK_SENSOR);
	    
	    $cable_len_ps = $len*15;//15ps/mm
	    $px_delay = $cable_len_ps;
	    //$px_delay_90 = round( (4*$px_delay+$clk_period/2)/$clk_period );
	    //$px_delay = round( $px_delay - ($px_delay_90*$clk_period)/4 );
	    $px_delay = round($px_delay/$DCM_STEP);
	    $SP = ($px_delay & 0xffff) | 0x80000;
	    file_put_contents("/var/log/autocampars_test.log","extra ".$SP." and ".$phases['SENSOR_PHASE']);
	    $phases['SENSOR_PHASE']=$SP+$phases['SENSOR_PHASE'];
      }
  }
  return $phases;
}

///TODO: Add sensor registers groups, remember not to program more than 64 (better 32 as there could be other writes initiated by the driver)
///TODO: Trigger modes?
/// "multisensor" here means "eyesis", it defaults to external trigger (may lock up other 10359 boards cameras)
/// Maybe check for all 3 sensors?
/// Note: manual temporary fix for others who use 10359 board.
/// Edit /etc/autocampars.xml and put zero in the following 2 fields:
///  <TRIG> and <TRIG_CONDITION>

function createDefaultConfig($version,$multisensor=false, $eyesis_mode=0) { /// 0 - not eyesis, 1-3 - camera number
  $SENSOR_RUN=     ELPHEL_CONST_SENSOR_RUN_CONT;     /// turn on sensor in continuous mode
  $COMPRESSOR_RUN= ELPHEL_CONST_COMPRESSOR_RUN_CONT; /// run compressor in continuous mode
  $HISTMODE_Y=     ELPHEL_CONST_TASKLET_HIST_ONCE;
  $HISTMODE_C=     ELPHEL_CONST_TASKLET_HIST_ONCE;
  $SCALES_CTL=     ELPHEL_CONST_CSCALES_CTL_NORMAL;
  $thisScript=$_SERVER['argv'][0];
///overwrites
  $TRIG=           $multisensor?4:0;
  $TRIG_CONDITION= $eyesis_mode?0x20000:0;
  $TRIG_OUT=       $eyesis_mode?0x80000:0;
  $TRIG_PERIOD=    $eyesis_mode?38400000:0;
  $MULTI_MODE=     $multisensor?1:0;
  $GAMMA_CORR=     $eyesis_mode?0x09320400:0x0a390400;
  $SATURATION=     $eyesis_mode?220:200; /// should be changed with gamma (lower the gamma - higher the required saturation)

  $WB_EN=          $eyesis_mode?0:1;
  $GAINR=          $eyesis_mode?0x2d26e:0x20000;
  $GAING=          $eyesis_mode?0x20000:0x20000;
  $GAINB=          $eyesis_mode?0x2722c:0x20000;
  $GAINGB=         $eyesis_mode?0x20000:0x20000;
  $RSCALE=         $eyesis_mode?0x16937:0x10000;
  $GSCALE=         $eyesis_mode?0x10000:0x10000;
  $BSCALE=         $eyesis_mode?0x13916:0x10000;
  $QUALITY=        $eyesis_mode?0x5f:80;
  $CORING_INDEX=   $eyesis_mode?0x140014:0x50005;
  $PORTRAIT=       $eyesis_mode?1:0;
  $AUTOEXP_EXP_MAX=$eyesis_mode?1000:500000;
  $AEXP_FRACPIX=   $eyesis_mode?0xf333:0xff80; // 95% : 99.8%
  $AEXP_LEVEL=     $eyesis_mode?0xc800:0xf800; //200 : 250
  $DAEMON_EN_TEMPERATURE=($eyesis_mode>100)?1:0; // or enable it for all?

/// Default parameters to be overwritten by particular applications
      $MULTI_FLIPH=0;
      $MULTI_FLIPV=0;
      $MULTI_SELECTED=1;
      $HISTWND_RWIDTH=  0x8000;
      $HISTWND_RHEIGHT= 0x8000;
      $HISTWND_RLEFT=   0x8000;
      $HISTWND_RTOP=    0x8000;
      $COLOR=1;

  switch ($eyesis_mode) {
    case 1:
      $MULTI_FLIPH=0;
      $MULTI_FLIPV=5;
      $MULTI_SELECTED=2;
      $HISTWND_RWIDTH=  0xc000;
      $HISTWND_RHEIGHT= 0xffff;
      $HISTWND_RLEFT=   0xffff;
      $HISTWND_RTOP=    0x8000;
      break;
    case 2:
      $MULTI_FLIPH=0;
      $MULTI_FLIPV=2;
      $MULTI_SELECTED=1;
      $HISTWND_RWIDTH=  0xc000;
      $HISTWND_RHEIGHT= 0xffff;
      $HISTWND_RLEFT=   0xffff;
      $HISTWND_RTOP=    0x8000;
      $COLOR=5;
      break;
    case 3:
      $MULTI_FLIPH=0;
      $MULTI_FLIPV=4;
      $MULTI_SELECTED=1;
      $HISTWND_RWIDTH=  0xc000;
      $HISTWND_RHEIGHT= 0xAAAB;
      $HISTWND_RLEFT=   0xffff;
      $HISTWND_RTOP=    0xffff;
      $COLOR=5;
      break;
// Eyesis4pi
    case 101:
    case 103:
    case 105:
    case 107:
      $MULTI_FLIPH=0;
      $MULTI_FLIPV=4;
      $MULTI_SELECTED=1;
      $HISTWND_RWIDTH=  0xc000;
      $HISTWND_RHEIGHT= 0xffff;
      $HISTWND_RLEFT=   0xffff;
      $HISTWND_RTOP=    0x8000;
      $COLOR=5;
      break;
    case 102:
    case 104:
    case 106:
    case 108:
      $MULTI_FLIPH=0;
      $MULTI_FLIPV=3;
      $MULTI_SELECTED=1;
      $HISTWND_RWIDTH=  0xc000;
      $HISTWND_RHEIGHT= 0xffff;
      $HISTWND_RLEFT=   0xffff;
      $HISTWND_RTOP=    0x8000;
      $COLOR=5;
      break;
    case 109:
      $MULTI_FLIPH=0;
      $MULTI_FLIPV=2;
      $MULTI_SELECTED=1;
      $HISTWND_RWIDTH=  0xc000;
      $HISTWND_RHEIGHT= 0xffff;
      $HISTWND_RLEFT=   0xffff;
      $HISTWND_RTOP=    0x8000;
      $COLOR=5;
      break;
  }
// will create $SENSOR_PHASE=...;MULTI_PHASE1=...;MULTI_PHASE3=...;)
  extract(calculateDefaultPhases()); // read sesnor phases from memory or calculate from the eeproms for newer devices

/// Now select window orientations based on eyesis_mode
return <<<DEFAULT_CONFIG
<?xml version="1.0" standalone="yes"?>
<!-- This file is generated by $thisScript  -->
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
     <TRIG>"Trigger mode. currently 0 - free running, 4 - triggered by external signal or FPGA timing generator."</TRIG>
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
               0 - mono6, monochrome (color YCbCr 4:2:0 with zeroed out color componets)`
               1 - color, YCbCr 4:2:0, 3x3 pixels`
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
     <SENSOR_PHASE>"Sensor phase adjusment, packed, low 16 bit - signed fine phase, bits [18:17] - 90-degrees shift"</SENSOR_PHASE>
     <TEMPERATURE_PERIOD>"Period of sesnor temperature measurements, ms"</TEMPERATURE_PERIOD>
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
     <FOCUS_LEFT>"Focus WOI left margin, in pixels, inclusive (3 LSB will be zeroed as it should be multiple of 8x8 block width)"</FOCUS_LEFT>
     <FOCUS_WIDTH>"Focus WOI width (3 LSB will be zeroed as it should be multiple of 8x8 block width)"</FOCUS_WIDTH>
     <FOCUS_TOP>"focus WOI top margin, inclusive (3 LSB will be zeroed as it should be multiple of 8x8 block height)"</FOCUS_TOP>
     <FOCUS_HEIGHT>"Focus WOI height (3 LSB will be zeroed as it should be multiple of 8x8 block height)"</FOCUS_HEIGHT>
     <FOCUS_TOTWIDTH>"Total width of the image frame in pixels (readonly)"</FOCUS_TOTWIDTH>
     <FOCUS_FILTER>"Select 8x8 filter used for the focus calculation (same order as quantization coefficients), 0..14"</FOCUS_FILTER>
     <TRIG_CONDITION>"FPGA trigger sequencer trigger condition, 0 - internal, else dibits ((use&lt;&lt;1) | level) for each GPIO[11:0] pin). Example:0x200000 - input from external connector (J15 - http://wiki.elphel.com/index.php?title=10369#J15_-_SYNC_.28external.29 ),  0x20000 - input from internal (J13/J14 - http://wiki.elphel.com/index.php?title=10369#J13_-_SYNC_.28internal.2C_slave.29 )"</TRIG_CONDITION>
     <TRIG_DELAY>"FPGA trigger sequencer trigger delay, 32 bits in pixel clocks"</TRIG_DELAY>
     <TRIG_OUT>"FPGA trigger sequencer trigger output to GPIO, dibits ((use &lt;&lt; 1) | level_when_active). Bit 24 - test mode, when GPIO[11:10] are controlled by other internal signals. Example: 0x800000 - output to external (J15 - http://wiki.elphel.com/index.php?title=10369#J15_-_SYNC_.28external.29 ) connector, 0x80000 - to internal (J12 - http://wiki.elphel.com/index.php?title=10369#J12_-_SYNC_.28internal.2C_master.29 )"</TRIG_OUT>
     <TRIG_PERIOD>"FPGA trigger sequencer output sync period (32 bits, in pixel clocks). 0- stop. 1 - single, >=256 repetitive with specified period"</TRIG_PERIOD>
     <TRIG_BITLENGTH>"Bit length minus 1 (in pixel clock cycles) when transmitting/receiving timestamps, without timestamps the output pulse width is 8*(TRIG_BITLENGTH+1). Legal values 2..255."</TRIG_BITLENGTH>
     <EXTERN_TIMESTAMP>"When 1 camera will use external timestamp (received over inter-camera synchronization cable) if it is available (no action when external syncronization is not connected), when 0 - local timestamp will be used"</EXTERN_TIMESTAMP>
     <XMIT_TIMESTAMP>"Specify output signal sent through internal/external connector (defined by TRIG_OUT). 0 - transmit just sync pulse (8*(TRIG_BITLENGTH+1) pixel clock periods long), 1 - pulse+timestamp 64*(TRIG_BITLENGTH+1) pixel clock periods long"</XMIT_TIMESTAMP>

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
     <SENSOR_REGS>Sensor internal registers (sensor-specific). In PHP scripts it is possible to reference individual register/bit fields with composite names, i.e. SENSOR_REGS160__0403 in Micron MT9P031 sensor allows to edit test patter number - bits 3..6 of the sensor register 160 (0xa0). There is additional suffix availble in multi-sesnor cameras. Some parameters may have different values for different sensor, in that case __A (and __a) reference register of sensor 1, __B (__b) and __C (__c) - sensors 2 and 3. Parametes with upper case (__A, __B and __C) will reference the base parameter if individual is not defined, low case suffixes are strict and return error if the parameter does not have individual values for sensors.</SENSOR_REGS>
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
     <M10359_REGS>10359 board inrternal registers, total of 96. First 64 are 16-bit, next 32 - 32 bit wide (Register definitions in http://elphel.cvs.sourceforge.net/viewvc/elphel/elphel353-8.0/os/linux-2.6-tag--devboard-R2_10-4/arch/cris/arch-v32/drivers/elphel/multisensor.h?view=markup).</M10359_REGS>
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
     <SENSOR_PHASE>"init,unsafe,diagn"</SENSOR_PHASE>
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
     <SKIP_FRAMES>"init"</SKIP_FRAMES>
     <I2C_QPERIOD>"unsafe"</I2C_QPERIOD>
     <I2C_BYTES>"unsafe"</I2C_BYTES>
     <IRQ_SMART>"init,trigger"</IRQ_SMART>
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
     <SENSOR_REGS></SENSOR_REGS>
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
     <TASKLET_CTL>"init,diagn"</TASKLET_CTL>
     <GFOCUS_VALUE></GFOCUS_VALUE>
     <HISTMODE_Y>"init,diagn"</HISTMODE_Y>
     <HISTMODE_C>"init,diagn"</HISTMODE_C>
     <SKIP_DIFF_FRAME>"init,streamer"</SKIP_DIFF_FRAME>
     <HIST_LAST_INDEX></HIST_LAST_INDEX>
     <HIST_Y_FRAME></HIST_Y_FRAME>
     <HIST_C_FRAME></HIST_C_FRAME>
     <DAEMON_ERR></DAEMON_ERR>
     <DAEMON_RETCODE></DAEMON_RETCODE>
     <PROFILING_EN>"init,diagn"</PROFILING_EN>
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
     <EXPOS>10000</EXPOS>
     <VIRT_KEEP>0</VIRT_KEEP>
     <WOI_LEFT>0</WOI_LEFT>
     <WOI_TOP>0</WOI_TOP>
     <WOI_WIDTH>10000</WOI_WIDTH>
     <WOI_HEIGHT>10000</WOI_HEIGHT>
     <FLIPH>0</FLIPH>
     <FLIPV>0</FLIPV>
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
     <BITS>8</BITS>
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
     <FOCUS_FILTER>0</FOCUS_FILTER>
     <TRIG_CONDITION>$TRIG_CONDITION</TRIG_CONDITION>
     <TRIG_DELAY>0</TRIG_DELAY>
     <TRIG_OUT>$TRIG_OUT</TRIG_OUT>
     <TRIG_PERIOD>$TRIG_PERIOD</TRIG_PERIOD>
     <TRIG_BITLENGTH>31</TRIG_BITLENGTH>
     <EXTERN_TIMESTAMP>1</EXTERN_TIMESTAMP>
     <XMIT_TIMESTAMP>1</XMIT_TIMESTAMP>
     <SKIP_FRAMES>0</SKIP_FRAMES>
     <IRQ_SMART>3</IRQ_SMART>
     <OVERSIZE>0</OVERSIZE>
     <GTAB_R>$GAMMA_CORR</GTAB_R>
     <GTAB_G>$GAMMA_CORR</GTAB_G>
     <GTAB_GB>$GAMMA_CORR</GTAB_GB>
     <GTAB_B>$GAMMA_CORR</GTAB_B>
     <COMPRESSOR_RUN>$COMPRESSOR_RUN</COMPRESSOR_RUN>
     <COMPMOD_BYRSH>0</COMPMOD_BYRSH>
     <COMPMOD_TILSH>0</COMPMOD_TILSH>
     <COMPMOD_DCSUB>0</COMPMOD_DCSUB>
     <SENSOR_REGS></SENSOR_REGS>
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
     <MAXAHEAD>2</MAXAHEAD>
     <HIST_DIM_01>0x0a000a00</HIST_DIM_01>
     <HIST_DIM_23>0x0a000a00</HIST_DIM_23>
     <AE_INTEGERR></AE_INTEGERR>
     <WB_INTEGERR></WB_INTEGERR>
     <TASKLET_CTL>0</TASKLET_CTL>
     <GFOCUS_VALUE></GFOCUS_VALUE>
     <HISTMODE_Y>$HISTMODE_Y</HISTMODE_Y>
     <HISTMODE_C>$HISTMODE_C</HISTMODE_C>
     <SKIP_DIFF_FRAME>4</SKIP_DIFF_FRAME>
     <PROFILING_EN>0</PROFILING_EN>
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
     <MULTI_PHASE1>$MULTI_PHASE1</MULTI_PHASE1>
     <MULTI_PHASE2>$MULTI_PHASE2</MULTI_PHASE2>
     <MULTI_PHASE3>$MULTI_PHASE3</MULTI_PHASE3>
     <TEMPERATURE01>-1</TEMPERATURE01>
     <TEMPERATURE23>-1</TEMPERATURE23>
    </set>
  </paramSets>
</autocampars> 

DEFAULT_CONFIG;
}
?>
