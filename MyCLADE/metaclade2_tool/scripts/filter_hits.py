"""
Author: Riccardo Vicedomini
"""

import sys, os, argparse
import time
from collections import defaultdict

from mclade_utils import *

# ARGUMENTS
RESULT_FILE = "result_file"
OUT_RESULT_FILE = "out_result_files"
# HELP MESSAGES
DESCRIPTION      = "This script takes as input the result file of CLADE search step and outputs only best ones."
RESULT_FILE_HELP = "Path of input result file"
OUT_RESULT_FILE_HELP = "Path of output best result file"

EVALUE_INDEX=8
SCORE_INDEX=9

def main():
    parser = argparse.ArgumentParser(description=DESCRIPTION)
    parser.add_argument(RESULT_FILE, nargs='+', help=RESULT_FILE_HELP, type=str)
    parser.add_argument(OUT_RESULT_FILE, help=OUT_RESULT_FILE_HELP, type=str)
    args = vars(parser.parse_args())

    start_time = time.time()

    # load results file and sort the hits by decreasing score
    results = []
    for result_filename in args[RESULT_FILE]:
        with open(result_filename,'r') as result_file:
            for line in result_file:
                results.append( line.rstrip(' \n\r').split('\t') )
    results.sort(key=lambda x: float(x[SCORE_INDEX]),reverse=True)

    resDict = defaultdict(list)
    for hit in results:
        hit_ann = {
            'MOD_ID':hit[0],
            'MOD_START':int(hit[1]),
            'MOD_END':int(hit[2]),
            'MOD_LEN':int(hit[3]),
            'SEQ_ID':hit[4],
            'SEQ_START':int(hit[5]),
            'SEQ_END':int(hit[6]),
            'SEQ_LEN':int(hit[7]),
            'EVALUE':float(hit[8]),
            'SCORE':float(hit[9]),
            'ALN_ACC':float(hit[10]),
            'MOD_TAXID':int(hit[11])
        }
        if hit_ann['SCORE'] <= 0.0 or hit_ann['ALN_ACC'] < 0.80:
            continue
        isNewBestAnnotation = True
        hit_range = (hit_ann['SEQ_START'],hit_ann['SEQ_END'])
        for bd_ann in resDict[hit_ann['SEQ_ID']]:
            bd_range  = (bd_ann['SEQ_START'],bd_ann['SEQ_END'])
            o_len,o_pmin,o_pmax = overlap(bd_range,hit_range)
            if o_len > 0 and o_pmin >= .9 and o_pmax >= .9: # there exists already a similar annotation (covering the same range)
                isNewBestAnnotation = False
                break
        if isNewBestAnnotation:
            resDict[hit_ann['SEQ_ID']].append(hit_ann)

    out_filename = args[OUT_RESULT_FILE]
    with open(out_filename,'w') as out:
        for seq in resDict:
            for ann in resDict[seq]:
                out.write(
                    "{modid}\t{modstart}\t{modend}\t{modlen}\t{seqid}\t{seqstart}\t{seqend}\t{seqlen}\t{evalue}\t{score}\t{alnacc}\t{modtaxid}\n".format(
                            modid=ann['MOD_ID'], modstart=ann['MOD_START'], modend=ann['MOD_END'], modlen=ann['MOD_LEN'],
                            seqid=ann['SEQ_ID'], seqstart=ann['SEQ_START'], seqend=ann['SEQ_END'], seqlen=ann['SEQ_LEN'],
                            evalue=ann['EVALUE'], score=ann['SCORE'], alnacc=ann['ALN_ACC'], modtaxid=ann['MOD_TAXID'] )
                )
    return 0


# Check if the program is not being imported
if __name__ == "__main__":
    sys.exit(main())
