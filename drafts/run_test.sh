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
/home/blachon/Documents/Tools/metaclade2/metaclade2 -i /var/www/html/MetaCLADE_webserver/MyCLADE/fasta_file/example.fasta -N testDataSet -d PF00875,PF03441,PF03167,PF12546 -W ./ --sge --pe smp -j 2 -t 2
# Send mail at submission and completion of script
#$ -m be
#$ -M clemenceblachon@gmail.com