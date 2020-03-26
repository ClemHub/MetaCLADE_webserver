#!/usr/bin/env bash
#
#  This file is part of MetaCLADE2.
# 
#  MetaCLADE2 is free software: you can redistribute it and/or modify
#  it under the terms of the CeCILL 2.1 Licence
#
#  MetaCLADE2 is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
#
#  You should have received a copy of the Licence CeCILL 2.1 along
#  with MetaCLADE2. If not, see <https://cecill.info/>.
#

CMD_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}" )" && pwd)
CMD_NAME=$(basename "${BASH_SOURCE[0]}")
SCRIPTS_DIR="${CMD_DIR}"/scripts

# Include common definitions
source "${CMD_DIR}/metaclade2-common"

# Pressing CTRL-C will stop the whole execution of the script
trap ctrl_c INT; function ctrl_c() { exit 5; }

# Definition of functions and global variables specific to this script

MCLADE_LIB_PATH=""
MCLADE_EVALUECUTOFF=1e-3
MCLADE_EVALUECUTCONF=1e-10
MCLADE_USEDAMA=false
MCLADE_USESGE=false
MCLADE_WORKDIR=${PWD}
NTHREADS=1
NJOBS=16

function print_usage() {
    echo -en "\n  USAGE: ${CMD_NAME} -i <input_fasta> -N <name> [options]\n"
    echo -en "\n"
    echo -en "  MANDATORY OPTIONS:\n
    -i, --input <path>\tInput file of AA sequences in FASTA format\n
    -N, --name <str>\tDataset/job name\n
    " | column -t -s $'\t'
    echo -en "\n"
    echo -en "  MetaCLADE OPTIONS:\n
    -a, --arch\tUse DAMA to properly compute domain architectures\n
              \t(useful only for long protein sequences)\n
    -d, --domain-list <str>\tComma-spearated list of Pfam accession numbers of\n
                        \tthe domains to be considered in the analysis\n
                        \t(e.g., \"PF00875,PF03441\")\n
    -D, --domain-file <path>\tFile that contains the Pfam accession numbers\n
                            \tof the domains to be considered (one per line)\n
    -e, --evalue-cutoff <float>\tE-value cutoff (default:${MCLADE_EVALUECUTOFF})\n
    -E, --evalue-cutconf <float>\tConfidence threshold used by DAMA to add new domains 
                                \tin the architecture. (default:${MCLADE_EVALUECUTCONF})\n
    -W, --work-dir <path>\tWorking directory, where jobs and results are saved\n
    " | column -t -s $'\t'
    echo -en "\n"
    echo -en "  OTHER OPTIONS:\n
    -j, --jobs <num>\tNumber of jobs to be created (default:${NJOBS})\n
    -t, --threads <num>\tNumber of threads for each job (default:${NTHREADS})\n
    -h, --help\tPrint this help message\n
    -V, --version\tPrint version\n" | column -t -s $'\t'
    echo -en "\n"
    echo -en "  SGE OPTIONS:\n
    --sge\tRun MetaCLADE jobs on a SGE HPC environment\n
    --pe <name>\tParallel environment to use (mandatory)\n
    --queue <name>\tName of a specific queue where jobs are submitted\n
    --time-limit <hh:mm:ss>\tTime limit for submitted jobs formatted as hh:mm:ss\n
                           \twhere hh, mm, ss represent hours, minutes, and seconds respectively\n
                           \t(e.g., use --time-limit 2:30:00 for setting a limit of 2h and 30m)\n
    " | column -t -s $'\t'
    echo -en "\n"
}


# retrieve provided arguments
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
        -D|--domain-file)
            shift
            MCLADE_DOMFILE=${1}
            ;;
        -W|--work-dir)
            shift
            MCLADE_WORKDIR=${1}
            ;;
        -t|--threads)
            shift
            NTHREADS=${1}
            ;;
        -j|--max-jobs)
            shift
            NJOBS=${1}
            ;;
        -h|--help)
            print_usage
            exit 0
            ;;
        -V|--version)
            print_version
            exit 0
            ;;
        --sge)
            MCLADE_USESGE=true
            ;;
        --pe)
            shift
            SGE_PENAME=${1}
            ;;
        --queue)
            shift
            SGE_QUEUE=${1}
            ;;
        --time-limit)
            shift
            SGE_TIMELIM=${1}
            ;;
        --)
            shift
            break
            ;;
    esac
    shift
done

# Input parameters validation
PEXEC_CMD="parallel --halt now,fail=1 -j ${NTHREADS} ::::"
if ! command -v parallel >/dev/null 2>&1; then
    print_warning "cannot find GNU parallel, all jobs will be run sequentially"
    NTHREADS=1
    PEXEC_CMD="/usr/bin/env bash --"
fi

if [ -z "${INPUT_FASTA}" ] || [ ! -f ${INPUT_FASTA} ]; then
    print_error "-i|--input file is missing or does not exist: ${INPUT_FASTA}"
    exit 1
fi

if [ -z "${MCLADE_JOBNAME}" ]; then
    print_error "-N|--name parameter is mandatory"
    exit 1
fi
if ! [[ "${MCLADE_JOBNAME}" =~ ^[-_a-zA-Z0-9]+$ ]] ; then
    print_error "-N|--name parameter must be an alphanumeric string (may also contain \"_\" and \"-\" characters)"
    exit 1
fi

MCLADE_DOMARG=""
if [ ! -z "${MCLADE_DOMLIST}" ]; then
    MCLADE_DOMARG="${MCLADE_DOMARG} -d ${MCLADE_DOMLIST}"
fi
if [ ! -z "${MCLADE_DOMFILE}" ]; then
    if [ ! -f "${MCLADE_DOMFILE}" ]; then
        print_error "-D|--domain-file path does not exist: ${MCLADE_DOMFILE}"
    else
        MCLADE_DOMARG="${MCLADE_DOMARG} -D ${MCLADE_DOMFILE}"
    fi
fi

MCLADE_DAMAARG=""
if [ "${MCLADE_USEDAMA}" = true ]; then
    MCLADE_DAMAARG="--arch"
fi

if ! [[ "${NTHREADS}" =~ ^[0-9]+$ ]] || [ ${NTHREADS} -lt 1 ] ; then
    print_error "-t|--threads parameter must be a positive integer"
    exit 1
fi

if ! [[ "${NJOBS}" =~ ^[0-9]+$ ]] || [ ${NJOBS} -lt 1 ] ; then
    print_warning "-j|--max-jobs parameter should be a positive integer"
    exit 1
fi

check_cmds "python3"

# Validate SGE parameters
if [ "${MCLADE_USESGE}" = true ] ; then
    if [ -z ${SGE_PENAME} ]; then
        print_error "you must set a parallel environment name with -P|--pe option along with the --sge option"
        exit 1
    fi
    if [ ! -z ${SGE_QUEUE} ]; then
        SGE_QUEUEARG="-q ${SGE_QUEUE}"
    fi
    if [ ! -z ${SGE_TIMELIM} ]; then
        SGE_TIMELIMARG="-l h_rt=${SGE_TIMELIM}"
    fi
fi

# Create Working directory
MCLADE_WORKDIR=${MCLADE_WORKDIR}"/"${MCLADE_JOBNAME}
mkdir -p "${MCLADE_WORKDIR}" "${MCLADE_WORKDIR}/log"
if [ $? -ne 0 ]; then
    print_error "cannot create/access MetaCLADE working directory: ${MCLADE_WORKDIR}"
    exit 1
fi
print_status "MetaCLADE working directory: ${MCLADE_WORKDIR}"

# Create MetaCLADE scripts
print_status "creating MetaCLADE script/job files"
python3 "${SCRIPTS_DIR}/mclade_create_jobs.py" -i "${INPUT_FASTA}" -N "${MCLADE_JOBNAME}" ${MCLADE_DOMARG} -W "${MCLADE_WORKDIR}" -e "${MCLADE_EVALUECUTOFF}" -E "${MCLADE_EVALUECUTCONF}" -j "${NJOBS}" ${MCLADE_DAMAARG}

# Possibly run MetaCLADE scripts in a SGE environment
if [ "${MCLADE_USESGE}" = true ] ; then
    # submit search jobs
    print_status "submitting search jobs"
    pidarr=()
    for i in $(seq 1 ${NJOBS}); do
        f="${MCLADE_WORKDIR}/jobs/1_search/${MCLADE_JOBNAME}_${i}.sh"
        # run a qsub job for each non-empty script
        if [ -f "${f}" ] && [ -s "${f}" ]; then
            CMD="${PEXEC_CMD} ${f}"
            qsub $SGE_QUEUEARG $SGE_TIMELIMARG -e ${MCLADE_WORKDIR}/log/search_${i}.err -o ${MCLADE_WORKDIR}/log/search_${i}.out -cwd -sync yes -N ${MCLADE_JOBNAME} -pe ${SGE_PENAME} ${NTHREADS} -b y "${CMD}" 2>&1 >"${MCLADE_WORKDIR}/log/search_qsub_${i}.log" &
            pid=$!
            pidarr[$i]=$pid
        fi
    done
    # wait search jobs to finish
    print_status "waiting the search jobs to finish"
    for i in ${!pidarr[@]}; do
        wait ${pidarr[${i}]}
        pret=$?
        if ((pret != 0)); then
            echo "search job ${i} failed (exit status: ${pret})"
            exit 1
        fi
    done
    print_status "search jobs finished successfully"


    # submit filter jobs
    print_status "submitting filter ijobs"
    unset pidarr; pidarr=()
    for i in $(seq 1 ${NJOBS}); do
        f="${MCLADE_WORKDIR}/jobs/2_filter/${MCLADE_JOBNAME}_${i}.sh"
        # run a qsub job for each non-empty script
        if [ -f "${f}" ] && [ -s "${f}" ]; then
            CMD="${PEXEC_CMD} ${f}"
            qsub $SGE_QUEUEARG $SGE_TIMELIMARG -e "${MCLADE_WORKDIR}/log/filter_${i}.err" -o "${MCLADE_WORKDIR}/log/filter_${i}.out" -cwd -sync yes -N ${MCLADE_JOBNAME} -pe ${SGE_PENAME} ${NTHREADS} -b y "${CMD}" 2>&1 >"${MCLADE_WORKDIR}/log/filter_qsub_${i}.log" &
            pid=$!
            pidarr[$i]=$pid
        fi
    done
    # wait search jobs to finish
    print_status "waiting the filter jobs to finish"
    for i in ${!pidarr[@]}; do
        wait ${pidarr[${i}]}
        pret=$?
        if ((pret != 0)); then
            echo "filter job ${i} failed (exit status: ${pret})"
            exit 1
        fi
    done
    print_status "search jobs finished successfully"

    # create architecture
    print_status "computing architecture"
    f="${MCLADE_WORKDIR}/jobs/3_arch/${MCLADE_JOBNAME}.sh"
    qsub $SGE_QUEUEARG $SGE_TIMELIMARG -e "${MCLADE_WORKDIR}/log/arch.err" -o "${MCLADE_WORKDIR}/log/arch.out" -cwd -sync yes -N ${MCLADE_JOBNAME} -pe ${SGE_PENAME} 1 -b y ${PEXEC_CMD} ${f} 2>&1 >>"${MCLADE_WORKDIR}/log/arch_qsub.log"
    qret=$?
    if ((qret != 0)); then
        echo "architecture job failed (exit status: ${qret})"
        exit 1
    fi
    print_status "architecture job finished successfully"
fi