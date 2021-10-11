#!/bin/bash
#
source includes/configure.sh 
day=$(date +%Y%m -d -2month)
sudo rm -rf $APPROOT/jobs/*_$day**