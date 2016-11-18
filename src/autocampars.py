#!/usr/bin/python
from __future__ import division
from __future__ import print_function
'''
# Copyright (C) 2015, Elphel.inc.
#   
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http:#www.gnu.org/licenses/>.

@author:     Andrey Filippov
@copyright:  2015 Elphel, Inc.
@license:    GPLv3.0+
@contact:    andrey@elphel.coml
@deffield    updated: Updated
'''
__author__ = "Andrey Filippov"
__copyright__ = "Copyright 2015, Elphel, Inc."
__license__ = "GPL"
__version__ = "3.0+"
__maintainer__ = "Andrey Filippov"
__email__ = "andrey@elphel.com"
__status__ = "Development"

import os
import urlparse
import urllib
import time
#import shutil
import sys
import subprocess
import urllib2
import xml.etree.ElementTree as ET
import threading
import Queue

import time
import random
PYDIR = "/usr/local/bin"
VERILOG_DIR = "/usr/local/verilog"
WWW_SCRIPT="autocampars.py"
TIMEOUT = 20 # seconds
def process_py393(args):
    cmd=PYDIR+'/test_mcntrl.py'
    try:    
        cmd +=' @'+VERILOG_DIR+'/'+args['include'][0]
    except:
        pass    
    try:
        if len(args['other'])  > 0:  
            cmd +=' '+args['other'][0]
        l=len(args['other'][0])    
    except:
        pass
        l=0    
    rslt= subprocess.call(cmd,shell=True)
#    rslt = l
    fmt="""<?xml version="1.0"?>
<result>%d</result>    
"""
    print (fmt%(rslt))
    print ('<!--process_py393 (',args,')-->')
    print('<!-- called ',cmd,'-->')    
    
def remote_parallel393(args, host, timeout=0): #fp in sec
    if not isinstance(host,(list,tuple)):
        if not isinstance(args,(list,tuple)):
            host=[host]
        else:
            host=[host]*len(args)  
    if not isinstance(args,(list,tuple)):
        args=[args]*len(host)
    argshosts=zip(args,host)    
    print ('remote_parallel393(args=',args,' host=',host,')')
    print ('argshosts=',argshosts)
    urls=[]
    for i in argshosts:
        i[0]['cmd'] = 'py393'
        urls.append("http://"+i[1]+"/"+WWW_SCRIPT+"?"+urllib.urlencode(i[0]))
    rslts =  remote_parallel_urls(urls=urls, timeout=timeout)
    for num,result in enumerate(results):
        print (">>>>> ",num,": ",result);
    return  rslts

def remote_parallel_urls(urls, timeout=0): #imeout will restart for each next url
    def read_url(index, queue, url):
        queue.put((index, urllib2.urlopen(url).read()))

    queue = Queue.Queue()
    for index, url in enumerate(urls):
        thread = threading.Thread(target=read_url, args = (index, queue, url))
        thread.daemon = True
        thread.start()
    rslts=[None]*len(urls)
    if not timeout:
        timeout = None
    for _ in urls:
        try:
            rslt = queue.get(block=True, timeout=timeout)
            rslts[rslt[0]] = rslt[1]
        except:
            break
    return rslts
    #if None in rslts - likely timeout happened
    
 
def remote_wget(url, host, timeout=0) : #split_list_arg(sys.argv[1]))
    print ("remote_wget(",args,',',host,')')
    if not isinstance(host,(list,tuple)):
        host=[host]
    if len(host) > len(url):
        url+= [url[-1]]*(len(host) - len(url))
    elif len(url) > len(url):
        host += [host[-1]]*(len(url) - len(host))
        
    argshosts=zip(args,host)    
    print ('remote_wget(args=',args,' host=',host,')')
    print ('argshosts=',argshosts)
    urls=[]
    for i in argshosts:
        urls.append("http://"+i[1]+"/"+i[0])
    rslts=remote_parallel_urls(urls=urls, timeout=timeout)
    # parse results here
    return rslts
    
def process_http():
    try:
        qs=urlparse.parse_qs(os.environ['QUERY_STRING'])
#        print ('os.environ=',os.environ)
    except:
        qs={}
#    print ('QUERY_STRING=',qs)
    try:
        if qs['cmd'][0] == 'py393':
            process_py393(qs)
            return
    except:
        pass
              
def split_list_arg(arg):
    if (arg[0]=='[') and (arg[-1]==']'):
        return [i.strip() for i in arg[1:-1].split(',')]
    else:
        return arg
    
def process_cmdline():
    print('sys.argv = ',sys.argv)
    print('sys.argv[1] = ',split_list_arg(sys.argv[1]))
    args={}
    if sys.argv[2] == "py393":
        try:
            args['include'] =sys.argv[3]
        except:
            pass      
        try:
            if sys.argv[4]:
                args['other'] =' '.join(sys.argv[4:])
        except:
            pass
        print ('args=',args)     
        remote_parallel393 (args=args, host = split_list_arg(sys.argv[1]), timeout=TIMEOUT)
    elif sys.argv[2] == "wget":
        urls = sys.argv[3:]
        remote_wget(urls, split_list_arg(sys.argv[1]))
        
    else:
        pass     
def main():
    if 'REQUEST_METHOD' in os.environ:
        return process_http()
    else: 
        return process_cmdline()
#    print ('os.environ=',os.environ)
     
if __name__ == "__main__":
    main()
"""
    shout("wget -O /dev/null \"localhost/parsedit.php?immediate&sensor_port=3&MULTI_FLIPV=4\"")
root@elphel393:/usr/local/bin# autocampars.py [localhost,192.168.0.5] py393 a b c
sys.argv =  ['/usr/bin/autocampars.py', '[localhost,192.168.0.5]', 'py393', 'a', 'b', 'c']
sys.argv[1] =  ['localhost', '192.168.0.5']
args= {'include': 'a', 'other': 'b c'}
remote_py393( {'include': 'a', 'other': 'b c'} , ['localhost', '192.168.0.5'] )
remote_parallel393(args= [{'include': 'a', 'other': 'b c'}, {'include': 'a', 'other': 'b c'}]  host= ['localhost', '192.168.0.5'] )
argshosts= [({'include': 'a', 'other': 'b c'}, 'localhost'), ({'include': 'a', 'other': 'b c'}, '192.168.0.5')]
len(ready_to_read)=  1
--Got response from  http://localhost/autocampars.py?cmd=py393&include=a&other=b+c  ( 0 ) @  1478366434.69
len(ready_to_read)=  1
--Got response from  http://192.168.0.5/autocampars.py?cmd=py393&include=a&other=b+c  ( 1 ) @  1478366434.69
results= ['<?xml version="1.0"?>\n<result>3</result>    \n\n<!--process_py393 ( {\'include\': [\'a\'], \'cmd\': [\'py393\'], \'other\': [\'b c\']} )-->\n<!-- called  /usr/local/bin/test_mcntrl.py @/usr/local/verilog/a b c -->\n', '<?xml version="1.0"?>\n<result>3</result>    \n\n<!--process_py393 ( {\'include\': [\'a\'], \'cmd\': [\'py393\'], \'other\': [\'b c\']} )-->\n<!-- called  /usr/local/bin/test_mcntrl.py @/usr/local/verilog/a b c -->\n']
root@elphel393:/usr/local/bin# autocampars.py localhost py393 a b c
sys.argv =  ['/usr/bin/autocampars.py', 'localhost', 'py393', 'a', 'b', 'c']
sys.argv[1] =  localhost
args= {'include': 'a', 'other': 'b c'}
remote_py393( {'include': 'a', 'other': 'b c'} , localhost )
opening url: http://localhost/autocampars.py?cmd=py393&include=a&other=b+c
Received: ['<?xml version="1.0"?>\n<result>3</result>    \n\n<!--process_py393 ( {\'include\': [\'a\'], \'cmd\': [\'py393\'], \'other\': [\'b c\']} )-->\n<!-- called  /usr/local/bin/test_mcntrl.py @/usr/local/verilog/a b c -->\n']


>>> import urllib
>>> urllib.urlencode({'arg':'"/test_mcntrl.py @"+verilogdir+"/hargs"'})
'arg=%22%2Ftest_mcntrl.py+%40%22%2Bverilogdir%2B%22%2Fhargs%22'
>>> u=urllib.urlencode({'arg':'"/test_mcntrl.py @"+verilogdir+"/hargs"'})
>>> import urlparse
>>> urlparse.parse_qs(u)
{'arg': ['"/test_mcntrl.py @"+verilogdir+"/hargs"']}



root@elphel393:/www/pages# autocampars.py a b c d
failed  in os.environ['QUERY_STRING']
sys.argv =  ['/usr/bin/autocampars.py', 'a', 'b', 'c', 'd']
os.environ= {'TERM': 'xterm',
             'SHELL': '/bin/sh',
             'TZ': 'UTC',
             'SHLVL': '1',
             'SSH_TTY': '/dev/pts/0',
             'OLDPWD': '/home/root',
             'PWD': '/www/pages',
             'SSH_CLIENT': '192.168.0.210 44430 22',
             'LOGNAME': 'root',
             'USER': 'root',
             'PATH': '/usr/local/bin:/usr/bin:/bin:/usr/local/sbin:/usr/sbin:/sbin',
             'SSH_CONNECTION': '192.168.0.210 44430 192.168.0.5 22',
             'MAIL': '/var/mail/root',
             'PS1': '\\u@\\h:\\w\\$ ',
             'HOME': '/home/root',
             '_': '/usr/bin/autocampars.py',
             'EDITOR': 'vi'}

http:
QUERY_STRING= {'a': ['1'], 'c': ['2']}
sys.argv =  ['/www/pages/autocampars.py']
os.environ= {'REDIRECT_STATUS': '200',
             'SERVER_SOFTWARE': 'lighttpd/1.4.39',
             'SCRIPT_NAME': '/autocampars.py',
             'REQUEST_METHOD': 'GET',
             'SERVER_PROTOCOL': 'HTTP/1.1',
             'QUERY_STRING': 'a=1&b&c=2',
             'CONTENT_LENGTH': '0',
             'HTTP_USER_AGENT': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36',
             'HTTP_CONNECTION': 'keep-alive',
             'SERVER_NAME': '192.168.0.5',
             'REMOTE_PORT': '35942',
             'SERVER_PORT': '80',
             'SERVER_ADDR': '0.0.0.0',
             'DOCUMENT_ROOT': '/www/pages',
             'SCRIPT_FILENAME': '/www/pages/autocampars.py',
             'HTTP_HOST': '192.168.0.5',
             'HTTP_UPGRADE_INSECURE_REQUESTS': '1',
             'HTTP_CACHE_CONTROL': 'max-age=0',
             'REQUEST_URI': '/autocampars.py?a=1&b&c=2',
             'HTTP_ACCEPT': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
             'GATEWAY_INTERFACE': 'CGI/1.1',
             'REMOTE_ADDR': '192.168.0.210',
             'HTTP_ACCEPT_LANGUAGE': 'en-US,en;q=0.8',
             'HTTP_ACCEPT_ENCODING': 'gzip, deflate, sdch'}
"""