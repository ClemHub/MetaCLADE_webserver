#!/bin/bash
source ../../includes/configure.sh
echo "LOGO	building" >> parameters.txt
python3 $APPROOT/get_logodata.py --mclade_cfg ~/Documents/Tools/metaclade2/config/mclade.complete.cfg  $@
ret_code=$?
if [ $ret_code = 0 ]; then
  echo "LOGO	true" >> parameters.txt
else
  echo "LOGO	error" >> parameters.txt
fi
