#!/bin/bash
#
day=$(date +%Y%m -d -2month)
sudo rm -rf /var/www/html/MetaCLADE_webserver/jobs/*_$day**