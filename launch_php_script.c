/*!***************************************************************************
*! FILE NAME  : launch_php_script.c
*! DESCRIPTION: Companion to PHP scripts used as daemons (currently camftp.php)
*!              to save memory when script is disabled. When the specidied bit
*!              is set in P_DAEMON_EN it launches the script and waits for it
*!              to finish, then sleeps (if the enable bit is off)
*!              The script is supposed to monitor enable bit and termonate when
*!              it is off
*! Copyright (C) 2008 Elphel, Inc.
*! -----------------------------------------------------------------------------**
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
*!  $Log: launch_php_script.c,v $
*!  Revision 1.1  2008/12/08 08:12:42  elphel
*!  added launch_php_script - a companion program to the php scripts used as daemons (like ccamftp.php) - it is used to reduce memory footprint when daemon is disabled
*!
*!
*/ 
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include <signal.h>
#include <fcntl.h>
#include <errno.h>
#include <sys/wait.h>

#include <elphel/c313a.h>
#include <elphel/x393_devices.h>
int main (int argc, char *argv[]) {
  if (argc < 4) {
    printf ("  This is a companion program that works with PHP scripts to make a daemon,\n"
            "conserving memory footprint when disabled.\n"
            "It waits for the the specified bit in the global daemon control word (P_DAEMON_EN)\n"
            "to be set (using driver functionality) and then launches the specified script\n"
            "with optional parameters, blocking itself until the script terminates.\n\n"
            "  After that it sleeps again until the bit is reenabled (normally script exits\n"
            "when this bit is cleared) and launches the script again. That reduces memory usage\n"
            "when the script is disabled - no need to keep PHP interpreter in teh memory (>2MB)\n\n"
            "Usage:\n"
            "%s <sensor_port> <bit_number> <command> [<parameter>...]\n",argv[0]);
            exit (1);
  }
  int pid, status;
  const char *framepars_driver_name[]={DEV393_PATH(DEV393_FRAMEPARS0), DEV393_PATH(DEV393_FRAMEPARS1),//"/dev/frameparsall";
                                       DEV393_PATH(DEV393_FRAMEPARS2), DEV393_PATH(DEV393_FRAMEPARS3)};
  int port = strtol(argv[1], NULL, 10) & 3;
  int en_bit=strtol(argv[2], NULL, 10);
  int rslt;
  int fd_fparmsall;
  if ((en_bit<0) || (en_bit>31)) {printf ("Invalid bit number %d (should be 0..31)\n", en_bit); exit (1);}
  fd_fparmsall= open(framepars_driver_name[port], O_RDONLY);
  if (fd_fparmsall <0) {
     ELP_FERR(fprintf(stderr, "Open failed: (%s)\n", framepars_driver_name[port]));
     exit (1);
  }
/// Daemon loop - waiting for being enabled, run script
//int execv(const char *path, char *const argv[]);
  while (1) {
   lseek(fd_fparmsall, LSEEK_DAEMON_FRAME+en_bit, SEEK_END);   /// wait for the specified bit in P_DAEMON_EN to be enabled (and sleep while not)
   signal(SIGCHLD, SIG_IGN); // no zombies
//! do we need any fork at all if we now serve images to one client at a time? 
   if (((pid=fork())) <0) { /// error
     ELP_FERR(fprintf(stderr, "fork() failed\n"));
     exit (1);
   } else if (pid == 0) { /// child
       fflush(stdout);
       fflush(stderr);
       rslt= execv(argv[3], &argv[3]); /// script path, all parameters starting with the script path
       ELP_FERR(fprintf(stderr, "execl failed, returned %d, errno=%d\n", rslt,errno));
      _exit(2);/// comes here only if error
   } /// Only parent will get below here
   wait(&status); // always returns -1, errno=10 -  no child processes
///   lseek(fd_fparmsall, LSEEK_FRAME_WAIT_REL+1, SEEK_END);   /// skip 1 frame before returning
  }
  return 0; ///will never get here
}
