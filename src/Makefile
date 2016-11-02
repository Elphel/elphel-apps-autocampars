PROGS      = autocampars launch_php_script
PHPSCRIPTS = autocampars.php
SRCS       = autocampars.c launch_php_script.c
#OBJS =       $(SRC:.c=.o)
OBJS       = autocampars.o launch_php_script.o


BINDIR       = $(DESTDIR)/usr/bin
SYSCONFDIR   = $(DESTDIR)/etc
CONFDIR      = $(SYSCONFDIR)/elphel393
DOCUMENTROOT = $(DESTDIR)/www/pages
AUTOCAMPARS_PHP = /www/pages/autocampars.php

INSTALL    = install
INSTMODE   = 0755
INSTDOCS   = 0644
OWN =        -o root -g root

INCDIR     = $(STAGING_DIR_HOST)/usr/include-uapi
#CFLAGS   += -Wall -I$(INCDIR)
CFLAGS   += -Wall -I$(STAGING_DIR_HOST)/usr/include-uapi -D 'AUTOCAMPARS_PHP="$(AUTOCAMPARS_PHP)"'

all: $(PROGS)

#$(PROGS): $(OBJS)
#	$(CC) $(LDFLAGS) $^ $(LDLIBS) -o $@

autocampars: autocampars.o
	$(CC) $(LDFLAGS) $^ $(LDLIBS) -o $@
	@echo "AUTOCAMPARS_PHP=$(AUTOCAMPARS_PHP)"
   
launch_php_script: launch_php_script.o
	$(CC) $(LDFLAGS) $^ $(LDLIBS) -o $@
	

install: $(PROGS) $(PHPSCRIPTS)
	$(INSTALL) -d $(BINDIR)
	$(INSTALL) -d $(DOCUMENTROOT)
	$(INSTALL) -d $(SYSCONFDIR)
	$(INSTALL) -d $(CONFDIR)
	$(INSTALL) $(OWN) -m $(INSTMODE) $(PROGS) $(BINDIR)
	$(INSTALL) $(OWN) -m $(INSTMODE) $(PHPSCRIPTS) $(DOCUMENTROOT)

clean:
	rm -rf $(PROGS) *.o core

depend: .depend

.depend: $(SRCS)
	rm -f ./.depend
	$(CC) $(CFLAGS) -MM $^ > ./.depend;

include .depend
