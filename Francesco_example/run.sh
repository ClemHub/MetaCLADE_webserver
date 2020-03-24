#!/bin/bash

#PBS -l walltime=48:00:00

set -e # exit when any command fails

#Goes on the working directory
cd $PBS_O_WORKDIR
#Log file for the application
LOGFILE="logfile.txt"

function before_exit {
#Execute something whenever there is an errur in the script
#if you have the user email, send a notification about the fact the job ended with errors
}

trap before_exit EXIT


#if you have the user email, send a notification about the beginning of the job

#put here your commands

#if you have the user email, send a notification about the correct end of the job

