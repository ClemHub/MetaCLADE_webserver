#!/bin/sh
#
# Usage: metaclade.sh [time]]
#        default for time is 60 seconds

# -- our name ---
#$ -N MetaCLADE
#$ -S /bin/sh
# Make sure that the .e and .o file arrive in the
# working directory
#$ -cwd
#Merge the standard out and standard error to one file
#$ -j y
/home/blachon/Documents/Tools/metaclade2/metaclade2 -i  /var/www/html/MetaCLADE_webserver/MyCLADE/jobs/zR6AFb8Mcay/data.fa -N  zR6AFb8Mcay -e  1e-3 -W  /var/www/html/MetaCLADE_webserver/MyCLADE/jobs/zR6AFb8Mcay -j 2 --sge --pe smp -t 2
# Send mail at submission and completion of script
#$ -m be
#$ -M clemenceblachon@gmail.com