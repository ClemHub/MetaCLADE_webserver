#!/usr/bin/env python3

import sys, os, argparse, gzip
import glob
import time
import operator
from collections import defaultdict

from mclade_utils import *

script_name = os.path.splitext( os.path.basename(__file__) )[0]

#SCRIPT CONSTANTS
I_EVALUE                       = "i_evalue"
MODEL_NAME                     = "model_name"
TARGET_ACC                     = "target_acc"
NB_PROB                        = "nb_prob"
MODEL_START                    = "model_start"
MODEL_END                      = "model_end"
MODEL_LEN                      = "model_len"
MODEL_TAXID                    = "model_taxid"
SEQ_START                      = "seq_start"
SEQ_END                        = "seq_end"
SEQ_LEN                        = "seq_len"
SCORE                          = "score"
ACCURACY                       = "accuracy"
SORT_SCORE                     = "sort_score"
QUERY_NAME                     = "query_name"
HMMER_MODEL                    = "HMMer-3"
FP_OVERLAPPING_THRESHOLD       = 10

#ARGUMENTS
BD_RESULT_DIR_NAME             = "bestdomains_result_dir_name"
NB_FILTER_OUT_FILE             = "nb_filter_out_file"
ALL_OL_FILTER_OUT_DIR          = "all_ol_filter_out_dir"
FINAL_PREDICTION_OUT_FILE      = "final_prediction_out_file"
TAXID2NAME_FILE                = "taxid2name_file"

DESCRIPTION                    = "This script generates final non-overlapping mclade predictions"
BD_RESULT_DIR_NAME_HELP        = "Directory for best domains and final predictions"
NB_FILTER_OUT_FILE_HELP        = "Best domains file name"
ALL_OL_FILTER_OUT_DIR_HELP     = "Directory for best domains and final predictions"
FINAL_PREDICTION_OUT_FILE_HELP = "Final Prediction file name"
TAXID2NAME_FILE_HELP           = "TSV file mapping taxonomy identifiers to species names"


def main():
    start_time = time.time()
    parser = argparse.ArgumentParser(description=DESCRIPTION)
    parser.add_argument('-e',dest='evalCutoff', help="e-value cutoff", type=float, default=float(1e-3))
    parser.add_argument(BD_RESULT_DIR_NAME, help=BD_RESULT_DIR_NAME_HELP, type=str)
    #parser.add_argument(NB_FILTER_OUT_FILE, help=NB_FILTER_OUT_FILE_HELP, type=str)
    #parser.add_argument(ALL_OL_FILTER_OUT_DIR, help=ALL_OL_FILTER_OUT_DIR_HELP, type=str)
    parser.add_argument(FINAL_PREDICTION_OUT_FILE, help=FINAL_PREDICTION_OUT_FILE_HELP, type=str)
    parser.add_argument(TAXID2NAME_FILE, help=TAXID2NAME_FILE_HELP, type=str)
    args = vars(parser.parse_args())

    bestDomainsDir = args[BD_RESULT_DIR_NAME]
    finalPredictionsFile = args[FINAL_PREDICTION_OUT_FILE]
    taxid2nameFile = args[TAXID2NAME_FILE]
    if not os.path.isdir(bestDomainsDir):
        eprint(f'[{script_name}] error: best domains directory "{bestDomainsDir}" does not exist')
        return 1

    # TODO: SIMPLIFY THE FOLLOWING MODIFIED ARI CODE
    rankobj = defaultdict(list)
    for bdfile in glob.glob(f'{bestDomainsDir}/*.best.res'):
        with open(bdfile,'r') as fh:
            for line in fh:
                line = line.strip()
                if line == "":
                    continue
                elts = line.split()
                data = {}
                data[MODEL_NAME]  = elts[0]
                data[TARGET_ACC]  = os.path.basename(bdfile).split('.')[0]
                #data[NB_PROB]     = float(elts[11])
                data[I_EVALUE]    = float(elts[8])
                data[MODEL_START] = int(elts[1])
                data[MODEL_END]   = int(elts[2])
                data[MODEL_LEN]   = int(elts[3])
                data[SEQ_START]   = int(elts[5])
                data[SEQ_END]     = int(elts[6])
                data[SEQ_LEN]     = int(elts[7])
                data[SCORE]       = float(elts[9])
                data[ACCURACY]    = float(elts[10])
                data[MODEL_TAXID] = elts[11]
                data[SORT_SCORE]  = data[SCORE] * data[ACCURACY]
                data[QUERY_NAME]  = elts[4]
                if data[I_EVALUE] <= args['evalCutoff']:
                    rankobj[data[QUERY_NAME]].append(data)
    objReturn = {}
    for query in rankobj:
        objReturn[query] = []
        #accHMMer         = set()
        sortIndexRes     = {}
        for i in range(0,len(rankobj[query])):
            imodel  = rankobj[query][i][MODEL_NAME]
            tacc    = rankobj[query][i][TARGET_ACC]
            #if imodel == HMMER_MODEL:
            #    accHMMer.add(tacc)
        for i in range(0,len(rankobj[query])):
            imodel = rankobj[query][i][MODEL_NAME]
            tacc   = rankobj[query][i][TARGET_ACC]
            #if imodel != HMMER_MODEL:
            #    if tacc in accHMMer:
            #        rankobj[query][i][SORT_SCORE] *= 2
            sortIndexRes[i] = rankobj[query][i][SORT_SCORE]
        for index,value in sorted(sortIndexRes.items(), key=operator.itemgetter(1), reverse=True):
            objReturn[query].append(rankobj[query][index])
    overlappcount = 0
    for query in objReturn:
        for i in range(0,len(objReturn[query])):
            if not objReturn[query][i] is None:
                istart   = objReturn[query][i][SEQ_START]
                iend     = objReturn[query][i][SEQ_END]
                iacc     = objReturn[query][i][TARGET_ACC]
                iievalue = objReturn[query][i][I_EVALUE]
                iiidentity = objReturn[query][i][SORT_SCORE]
                bestevalue = iievalue
                bestidentity = iiidentity
                for j in range(i+1,len(objReturn[query])):
                    if not objReturn[query][j] is None:
                        jstart   = objReturn[query][j][SEQ_START]
                        jend     = objReturn[query][j][SEQ_END]
                        jacc     = objReturn[query][j][TARGET_ACC]
                        jievalue = objReturn[query][j][I_EVALUE]
                        jiidentity = objReturn[query][j][SORT_SCORE]
                        irange = (istart,iend)
                        jrange = (jstart,jend)
                        ol,_,_ = overlap(irange,jrange)
                        if ol >= FP_OVERLAPPING_THRESHOLD:
                            if jiidentity <= bestidentity:
                                objReturn[query][j] = None
                            else:
                                bestidentity = jiidentity
                if iiidentity != bestidentity:
                    objReturn[query][i] = None

    taxid2name = {}
    with gzip.open(taxid2nameFile,'rt') as taxFile:
        for line in taxFile:
            line = line.strip()
            if len(line) == 0:
                continue
            taxid,name = line.split('\t')
            taxid2name[taxid] = name

    with open(finalPredictionsFile,'w') as outFile:
        for query in objReturn:
            for i in range(0,len(objReturn[query])):
                if not objReturn[query][i] is None:
                    data     = objReturn[query][i]
                    evalue   = data[I_EVALUE]
                    bitscore = data[SCORE]
                    modid    = data[MODEL_NAME]
                    modbeg   = data[MODEL_START]
                    modend   = data[MODEL_END]
                    modlen   = data[MODEL_LEN]
                    domid    = data[TARGET_ACC]
                    seqid    = data[QUERY_NAME]
                    seqbeg   = data[SEQ_START]
                    seqend   = data[SEQ_END]
                    seqlen   = data[SEQ_LEN]
                    accuracy = data[ACCURACY]
                    modtaxid = data[MODEL_TAXID]
                    modtaxname = taxid2name.get(modtaxid,'unavailable')
                    outFile.write(f'{seqid}\t{seqbeg}\t{seqend}\t{seqlen}\t{domid}\t{modid}\t{modbeg}\t{modend}\t{modlen}\t{evalue}\t{bitscore}\t{accuracy}\t{modtaxname}\n')
    # END OF ARI CODE #
    runtime = time.time()-start_time
    eprint(f'[{script_name}] runtime: {runtime:.2f}')
    return 0

if __name__ == "__main__":
    sys.exit(main())
