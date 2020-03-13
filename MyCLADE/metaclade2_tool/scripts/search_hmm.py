#!/usr/bin/env python3

import sys, os, argparse
import time
import subprocess

from mclade_utils import *

script_name = os.path.splitext( os.path.basename(__file__) )[0]

#SCRIPT CONSTANTS
HMMER_PROG           = "hmmsearch"
HMMER_VAL_T          = "0" # -T
HMMER_VAL_DOME       = "1" # -domE
PROTEIN_ID           = 'HMMer-3'
TAXON                = 'ALL'

SEQID_INDEX          = 0
SEQLEN_INDEX         = 2
SEQALI_START_INDEX   = 17
SEQALI_END_INDEX     = 18
SEQENV_START_INDEX   = 19
SEQENV_END_INDEX     = 20

MODID_INDEX          = 3
MODLEN_INDEX         = 5
MODHMM_START_INDEX   = 15
MODHMM_END_INDEX     = 16

EVAL_INDEX           = 12
SCORE_INDEX          = 13
ACCURACY_INDEX       = 21

#ARGUMENTS
ACC_ID                       = "acc_id"
HMM_PATH                     = "hmm_path"
HMMSEARCH_CMD                = "hmmsearch_cmd"
FASTA_PATH                   = "fasta_path"
TMP_DIR                      = "tmp_dir"
RESULTS_DIR                  = "results_dir"
RUN_NAME                     = "run_name"

#MESSAGES
DESCRIPTION                  = "This script searches for SCM models matching sequences in the fasta file"
ACC_ID_HELP                  = "Accesion of the domain"
MODEL_ID_HELP                = "Model id"
HMM_PATH_HELP                = "Model path"
HMMSEARCH_CMD_HELP           = "hmmscan command"
FASTA_PATH_HELP              = "Fasta File Path"
TMP_DIR_HELP                 = "Directory for temporary files"
RESULTS_DIR_HELP             = "Directory for mclade results"
RUN_NAME_HELP                = "Run Name"

def main():
    parser = argparse.ArgumentParser(description=DESCRIPTION)
    parser.add_argument(ACC_ID, help=ACC_ID_HELP, type=str)
    parser.add_argument(HMMSEARCH_CMD, help=HMMSEARCH_CMD_HELP, type=str)
    parser.add_argument(HMM_PATH, help=HMM_PATH_HELP, type=str)
    parser.add_argument(FASTA_PATH, help=FASTA_PATH_HELP, type=str)
    parser.add_argument(TMP_DIR, help=TMP_DIR_HELP, type=str)
    parser.add_argument(RESULTS_DIR, help=RESULTS_DIR_HELP, type=str)
    parser.add_argument(RUN_NAME, help=RUN_NAME_HELP, type=str)
    args = vars(parser.parse_args())

    temp_dir = f'{args[TMP_DIR]}'
    if not os.path.exists(temp_dir):
        try:
            os.makedirs(temp_dir)
        except OSError as error:
            pass

    results_dir = f'{args[RESULTS_DIR]}/{args[ACC_ID]}'
    if not os.path.exists(results_dir):
        os.makedirs(results_dir)

    start_time  = time.time()
    hmmsearch_args = [ args[HMMSEARCH_CMD],
                       '--noali',
                       '-T', HMMER_VAL_T,
                       '--domE', HMMER_VAL_DOME,
                       #'-o', '{path}/{outprefix}.out'.format(path=temp_dir,outprefix=args[ACC_ID]),
                       '--domtblout', f'{temp_dir}/{args[ACC_ID]}.domtblout',
                       args[HMM_PATH], args[FASTA_PATH] ]
    hmmsearch_cmd = ' '.join(hmmsearch_args)
    with open(os.devnull,'wb') as DEVNULL:
        hmmsearch = subprocess.call(hmmsearch_args, stdout=DEVNULL, stderr=DEVNULL)
        if hmmsearch != 0: # hmmsearch did not run successfully
            eprint(f'[{script_name}] error running: {hmmsearch_cmd}')
            return 1

    # compute HMMs alignment identities
    #percentIden = {}
    #with open('{path}/{outprefix}.out'.format(path=temp_dir,outprefix=args[ACC_ID]), 'r') as resAlignFile:
    #    iterHMMRes = hmmer_align_iter(resAlignFile)
    #    for seq,start,end,countPos in iterHMMRes:
    #        percentIden["{0}_{1}_{2}".format(seq,int(start),int(end))] = countPos

    # write result files
    with open(f'{results_dir}/{args[ACC_ID]}.hmm.res','w') as outFile, open(f'{temp_dir}/{args[ACC_ID]}.domtblout','r') as resFile:
        for line in resFile:
            line = line.strip()
            if not line.startswith("#"):
                    elts = line.split()
                    modid      = 'HMMer-3' #PROTEIN_ID
                    modlen     = int(elts[MODLEN_INDEX])
                    modbeg     = int(elts[MODHMM_START_INDEX])
                    modend     = int(elts[MODHMM_END_INDEX])
                    seqid      = elts[SEQID_INDEX]
                    seqlen     = int(elts[SEQLEN_INDEX])
                    seqenv_beg = int(elts[SEQENV_START_INDEX])
                    seqenv_end = int(elts[SEQENV_END_INDEX])
                    seqali_beg = int(elts[SEQALI_START_INDEX])
                    seqali_end = int(elts[SEQALI_END_INDEX])
                    evalue     = elts[EVAL_INDEX]
                    bitscore   = elts[SCORE_INDEX]
                    alnacc     = elts[ACCURACY_INDEX]
                    modtaxid   = 0
                    #modcov     = (modend-modbeg+1)/float(modlen) if modlen != 0 else 0.0
                    outFile.write(f'{modid}\t{modbeg}\t{modend}\t{modlen}\t{seqid}\t{seqenv_beg}\t{seqenv_end}\t{seqlen}\t{evalue}\t{bitscore}\t{alnacc}\t{modtaxid}\n')

    os.remove(f'{temp_dir}/{args[ACC_ID]}.domtblout')
    runtime = time.time()-start_time
    print(f'[{script_name}] runtime: {args[ACC_ID]} HMM {runtime:.2f}')
    return 0

if __name__ == "__main__":
    sys.exit(main())
