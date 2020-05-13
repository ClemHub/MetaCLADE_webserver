#!/bin/bash
#
# Usage: MetaCLADE.sh

#PBS -l walltime=48:00:00

set -e # exit when any command fails

#Goes on the working directory
#cd $PBS_O_WORKDIR
#Log file for the application
#LOGFILE="logfile.txt"
#$ -cwd

#function before_exit {
#Execute something whenever there is an errur in the script
#if you have the user email, send a notification about the fact the job ended with errors
#}

#trap before_exit EXIT

#if you have the user email, send a notification about the beginning of the job
MCLADE_LIB_PATH=""
MCLADE_EVALUECUTOFF=1e-3
MCLADE_EVALUECUTCONF=1e-10
MCLADE_USEDAMA=false
MCLADE_USESGE=false
MCLADE_WORKDIR=${PWD}
NTHREADS=1
NJOBS=16

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
        -D|--domain-file)
            shift
            MCLADE_DOMFILE=${1}
            ;;
        -W|--work-dir)
            shift
            MCLADE_WORKDIR=${1}
            ;;
        -j|--max-jobs)
            shift
            NJOBS=${1}
            ;;
        --overlappingAA)
            shift
            OVERLAPPING_AA=${1}
            ;;
        --overlappingMaxDomain)
            shift
            OVERLAPPING_MAXDOMAIN=${1}
            ;;
        --)
            shift
            break
            ;;
    esac
    shift
done
#/bin/echo "$MCLADE_USEDAMA"
if $MCLADE_USEDAMA
then
    metaclade2 -i "$INPUT_FASTA" -N "$MCLADE_JOBNAME" -D "$MCLADE_DOMFILE" -e "$MCLADE_EVALUECUTOFF" -a -E "$MCLADE_EVALUECUTCONF" -W "$MCLADE_WORKDIR" --overlappingAA "$OVERLAPPING_AA"  --overlappingMaxDomain "$OVERLAPPING_MAXDOMAIN" -j 4 --sge --pe smp
else 
    metaclade2 -i "$INPUT_FASTA" -N "$MCLADE_JOBNAME" -D "$MCLADE_DOMFILE" -e "$MCLADE_EVALUECUTOFF" -W "$MCLADE_WORKDIR" -j 4 --sge --pe smp 
fi


#if you have the user email, send a notification about the correct end of the job