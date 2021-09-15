#!/bin/bash
echo "LOGO	building" >> parameters.txt
python3 /var/www/html/MetaCLADE_webserver/get_logodata.py  $@
ret_code=$?
if [ $ret_code = 0 ]; then
  echo "LOGO	true" >> parameters.txt
else
  echo "LOGO	error" >> parameters.txt
fi
