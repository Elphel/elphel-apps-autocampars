/*!***************************************************************************
 *! FILE NAME  : autocampars.c
 *! DESCRIPTION: Daemon to save/restore parameter (calls autocampars.php)
 *!              This program is needed not to have an extra instance of
 *!              PHP (2+ MB) just waiting as a daemon.
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
 *!  $Log: autocampars.c,v $
 *!  Revision 1.2  2008/12/08 08:13:37  elphel
 *!  added one more error check
 *!
 *!  Revision 1.1.1.1  2008/11/27 20:04:01  elphel
 *!
 *!
 *!  Revision 1.4  2008/11/22 05:52:47  elphel
 *!  added TODO
 *!
 *!  Revision 1.3  2008/11/18 07:37:10  elphel
 *!  snapshot
 *!
 *!  Revision 1.2  2008/11/17 23:42:59  elphel
 *!  snapshot
 *!
 *!  Revision 1.1  2008/11/17 06:41:20  elphel
 *!  8.0.alpha18 - started autocampars - camera parameters save/restore/init manager
 *!
 *!
 */
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include <signal.h>
#include <fcntl.h>
#include <errno.h>
#include <elphel/c313a.h>
#include <elphel/x393_devices.h>
#ifdef AUTOCAMPARS_PHP
    #pragma message "content of AUTOCAMPARS_PHP: " AUTOCAMPARS_PHP
#else
    #warning AUTOCAMPARS_PHP is NOT defined
#endif

int main (int argc, char *argv[]) {
    const char script_path[]= AUTOCAMPARS_PHP;
    const char *framepars_driver_name[]={DEV393_PATH(DEV393_FRAMEPARS0), DEV393_PATH(DEV393_FRAMEPARS1),//"/dev/frameparsall";
                                         DEV393_PATH(DEV393_FRAMEPARS2), DEV393_PATH(DEV393_FRAMEPARS3)};
    int fd_fparmsall;
    unsigned long write_data[4];
    int rslt;
    char sport[10];
    int port = 0;
    if (argc <2){
        ELP_FERR(fprintf(stderr, "Sensor porft not provided (%s)\n", argv[0]));
        exit (1);
    }
    port = strtol(argv[1], NULL,10) & 3;
    sprintf(sport,"--port=%d",port);
    //DEV393_FRAMEPARS0
    //  const char php_path[]="/usr/local/sbin/php";
    fd_fparmsall= open(framepars_driver_name[port], O_RDWR);
    if (fd_fparmsall <0) {
        ELP_FERR(fprintf(stderr, "Open failed: (%s)\n", framepars_driver_name[port]));
        exit (1);
    }
    ///TODO: Use it to re-running ccamftp.php
    /// see if any arguments are provided. If they are - they are bit number, sleep time in seconds and command/arguments of an external program
    /// to be launched. Usually - a php script, so it makes sense not to keep the php instance in memory if it is not used most of the time
    /// (done so for ccamftp.php)
    ///
    /// Daemon loop - waiting for being enabled, run autocampars.php
    /// NOTE: Requires sequencers and sensor to be running, to reset everything if stuck - use autocampars.php directly
    int pid;
    while (1) {
        lseek(fd_fparmsall, LSEEK_DAEMON_FRAME+DAEMON_BIT_AUTOCAMPARS, SEEK_END);   /// wait for autocampars bit to be enabled (let it sleep if not)
        write_data[0]= FRAMEPARS_SETFRAMEREL;
        write_data[1]= 1;
        write_data[2]= P_DAEMON_EN_AUTOCAMPARS;
        write_data[3]= 0;
        rslt=write(fd_fparmsall, write_data, sizeof(write_data));
        if (rslt < sizeof(write_data)) {
            ELP_FERR(fprintf(stderr, "Failed writing to %s\n", framepars_driver_name[port]));
            exit (1);
        }
        fprintf(stderr, "autocampars triggered\n");
        signal(SIGCHLD, SIG_IGN); // no zombies
        //! do we need any fork at all if we now serve images to one client at a time?
        if (((pid=fork())) <0) { /// error
            ELP_FERR(fprintf(stderr, "fork() failed\n"));
            exit (1);
        } else if (pid == 0) { /// child
            fflush(stdout);
            fflush(stderr);
            //       rslt= execl(php_path, "-q", script_path, "", "--daemon", (char *) NULL); /// We do not need to pass any parameters here, but if we had to - first argument is lost (probably to "-q" in the script line 1
            rslt= execl(script_path, "", "--daemon", sport, (char *) NULL); /// We do not need to pass any parameters here, but if we had to - first argument is lost (probably to "-q" in the script line 1
            ELP_FERR(fprintf(stderr, "execl failed, returned %d, errno=%d\n", rslt,errno));
            _exit(2);/// comes here only if error
        } // end of child process
        lseek(fd_fparmsall, LSEEK_FRAME_WAIT_REL+1, SEEK_END);   /// skip 1 frame before returning
    }
    return 0;
}

