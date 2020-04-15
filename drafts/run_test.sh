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

# Send mail at submission and completion of script
#$ -m be
#$ -M clemenceblachon@gmail.com
