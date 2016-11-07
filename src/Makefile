LN = ln
PROGS      = autocampars launch_php_script
PHPSCRIPTS = autocampars.php
PYSCRIPTS =  autocampars.py
WWW_PAGES = /www/pages
SRCS       = autocampars.c launch_php_script.c
#OBJS =       $(SRC:.c=.o)
OBJS       = autocampars.o launch_php_script.o


BINDIR       = /usr/bin
SYSCONFDIR   =/etc
CONFDIR      = $(SYSCONFDIR)/elphel393
AUTOCAMPARS_PHP = $(WWW_PAGES)/autocampars.php

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
	

install: $(PROGS) $(PHPSCRIPTS) $(PYSCRIPTS)
	$(INSTALL) -d $(DESTDIR)$(BINDIR)
	$(INSTALL) -d $(DESTDIR)$(WWW_PAGES)
	$(INSTALL) -d $(DESTDIR)$(SYSCONFDIR)
	$(INSTALL) -d $(DESTDIR)$(CONFDIR)
	$(INSTALL) $(OWN) -m $(INSTMODE) $(PROGS) $(DESTDIR)$(BINDIR)
	$(INSTALL) $(OWN) -m $(INSTMODE) $(PYSCRIPTS) $(DESTDIR)$(BINDIR)
	$(INSTALL) $(OWN) -m $(INSTMODE) $(PHPSCRIPTS) $(DESTDIR)$(BINDIR)
	$(LN) -sf $(BINDIR)/autocampars.py  $(DESTDIR)$(WWW_PAGES)/autocampars.py
	$(LN) -sf $(BINDIR)/autocampars.php $(DESTDIR)$(WWW_PAGES)/autocampars.php

clean:
	rm -rf $(PROGS) *.o core .depend

depend: .depend

.depend: $(SRCS)
	rm -f ./.depend
	$(CC) $(CFLAGS) -MM $^ > ./.depend;

include .depend
