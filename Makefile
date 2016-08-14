PROGS      = autocampars launch_php_script
PHPSCRIPTS = autocampars.php
SRCS       = autocampars.c launch_php_script.c
OBJS =       $(SRC:.c=.o)
#OBJS       = autocampars.o launch_php_script.o


BINDIR       = $(DESTDIR)/usr/bin/
SYSCONFDIR   = $(DESTDIR)/etc/
DOCUMENTROOT = $(DESTDIR)/www/pages

INSTALL    = install
INSTMODE   = 0755
INSTDOCS   = 0644
OWN =        -o root -g root

INCDIR     = $(STAGING_DIR_HOST)/usr/include-uapi/elphel
CFLAGS   += -Wall -I$(INCDIR)

all: $(PROGS)

$(PROGS): $(OBJS)
	$(CC) $(LDFLAGS) $^ $(LDLIBS) -o $@

install: $(PROGS)
	$(INSTALL) -d $(BINDIR)
	$(INSTALL) -d $(DOCUMENTROOT)
	$(INSTALL) -d $(SYSCONFDIR)
	$(INSTALL) $(OWN) -m $(INSTMODE) $(PROGS) $(BINDIR)
	$(INSTALL) $(OWN) -m $(INSTMODE) $(PHPSCRIPTS)  $(DOCUMENTROOT)

clean:
	rm -rf $(PROGS) *.o core
configsubs:

