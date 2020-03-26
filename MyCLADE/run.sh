#!/bin/bash

#PBS -l walltime=48:00:00

#set -e # exit when any command fails

#Goes on the working directory
#cd $PBS_O_WORKDIR
#Log file for the application
#LOGFILE="logfile.txt"

#function before_exit {
#Execute something whenever there is an errur in the script
#if you have the user email, send a notification about the fact the job ended with errors
#}

#trap before_exit EXIT

#if you have the user email, send a notification about the beginning of the job

opts="i:N:ad:D:e:E:W:t:j:hV"
longopts="input:,name:,arch,domain-list:,domain-file:,evalue-cutoff:,evalue-cutconf:,work-dir,threads:,jobs:,help,version,sge,pe:,queue:,time-limit:"
ARGS=$(getopt -o "${opts}" -l "${longopts}" -n "${CMD_NAME}" -- "${@}")
if [ $? -ne 0 ] || [ $# -eq 0 ]; then # do not change the order of this test!
    print_usage
    exit 1
fi
eval set -- "${ARGS}"

while [ -n "${1}" ]; do
    case ${1} in
        -i|--input)
            shift
            INPUT_FASTA=${1}
            ;;
        -N|--name)
            shift
            MCLADE_JOBNAME=${1}
            ;;
        -a|--arch)
            MCLADE_USEDAMA=true
            ;;
        -e|--evalue-cutoff)
            shift
            MCLADE_EVALUECUTOFF=${1}
            ;;
        -E|--evalue-cutconf)
            shift
            MCLADE_EVALUECUTCONF=${1}
            ;;
        -d|--domain-list)
            shift
            MCLADE_DOMLIST=${1}
            ;;
        -W|--work-dir)
            shift
            MCLADE_WORKDIR=${1}
            ;;
        -j|--max-jobs)
            shift
            NJOBS=${1}
            ;;
        --)
            shift
            break
            ;;
    esac
    shift
done

if [a]
then 
    metaclade2 -i "$INPUT_FASTA" -N "$MCLADE_JOBNAME" -d "$MCLADE_DOMLIST" -e "$MCLADE_EVALUECUTOFF" -a -E "$MCLADE_EVALUECUTCONF" -W "$MCLADE_WORKDIR" -j "$NJOBS" 
else 
    metaclade2 -i "$INPUT_FASTA" -N "$MCLADE_JOBNAME" -e "$MCLADE_EVALUECUTOFF" -W "$MCLADE_WORKDIR" -j "$NJOBS"
fi
#if you have the user email, send a notification about the correct end of the job