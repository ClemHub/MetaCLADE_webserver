"""
Author: Riccardo Vicedomini
"""

import sys, os, argparse, gzip
import subprocess
import glob
import time

from mclade_utils import *

script_name = os.path.splitext( os.path.basename(__file__) )[0]

def main():

    #TODO: set all arguments as MANDATORY
    parser = argparse.ArgumentParser(description="Create DAMA architecture from MetaCLADE filter step")
    parser.add_argument('--damaCmd', dest='damaCmd', help='absolute path to dama executable', type=str)
    parser.add_argument('--domainsInfoFile', dest='domainsInfoFile', help='.domains file of MetaCLADE library', type=str)
    parser.add_argument('--knownArchFile', dest='knownArchFile', help='.knownArch file of MetaCLADE library', type=str)
    parser.add_argument('--overlappingDomainFile', dest='overlappingDomainFile', help='.overlapping file of MetaCLADE library', type=str)
    parser.add_argument('--evalueCutOff', dest='evalueCutOff', help='e-value cutoff for DAMA', type=str)
    parser.add_argument('--evalueCutOffConf', dest='evalueCutOffConf', help='e-value confidence threshold for DAMA', type=str)
    parser.add_argument('--filterResDir', dest='filterResDir', help='results directory of MetaCLADE filter step', type=str)
    parser.add_argument('--tempDir', dest='tempDir', help='temporary directory for intermediate files', type=str)
    parser.add_argument('--outputFile', dest='outputFile', help='output file of DAMA architecture', type=str)
    parser.add_argument('--taxid2name', dest='taxid2nameFile', help='TSV file mapping taxids to species names', type=str)
    args = parser.parse_args()
    
    temp_dir = f'{args.tempDir}'
    if not os.path.exists(temp_dir):
        try:
            os.makedirs(temp_dir)
        except OSError as error:
            pass

    start_time = time.time()

    print(f'[{script_name}] formatting DAMA input file')
    domainsHitDict = {}
    domains_hit_file = f'{temp_dir}/domainsHitFile.best.res'
    with open(domains_hit_file,'w') as dhf:
        for bdfile in glob.glob(f'{args.filterResDir}/*.best.res'):
            domid = os.path.basename(bdfile).split('.')[0]
            with open(bdfile,'r') as bdf:
                for line in bdf:
                    cols = line.rstrip().split('\t')
                    mid,mbeg,mend,mlen,sid,sbeg,send,slen,evalue,score,acc,mtaxid = cols
                    hit_key = (sid,sbeg,send,domid)
                    domainsHitDict[hit_key]=cols
                    dhf.write(f'{evalue}\t{sbeg}\t{send}\t{sid}\t{domid}\t{acc}\t{mid}\t{mbeg}\t{mend}\t{domid}\n')
    
    print(f'[{script_name}] running DAMA')
    dama_temp_out = f'{temp_dir}/dama.arch.txt'
    dama_args = [ args.damaCmd,
                  '-domainsInfoFile', args.domainsInfoFile,
                  '-knownArchFile', args.knownArchFile,
                  '-overlappingDomainFile', args.overlappingDomainFile,
                  '-evalueCutOff', args.evalueCutOff,
                  '-evalueCutOffConf', args.evalueCutOffConf,
                  '-domainsHitFile', domains_hit_file,
                  '-outputFile', dama_temp_out ]
    dama_cmd = ' '.join(dama_args)
    dama_process = subprocess.call(dama_args)
    if dama_process != 0: # if dama failed
        eprint(f'[{script_name}] error running: {hmmsearch_cmd}')
        return 1

    taxid2name = {}
    with gzip.open(args.taxid2nameFile,'rt') as taxFile:
        for line in taxFile:
            line = line.strip()
            if len(line) == 0:
                continue
            taxid,name = line.split('\t')
            taxid2name[taxid] = name

    # fix dama output
    print(f'[{script_name}] creating architecture file')
    with open(args.outputFile,'w') as farch, open(dama_temp_out,'r') as fdama:
        for line in fdama:
            evalue,sbeg,send,sid,domid,_ = line.rstrip().split('\t')
            hit_key = (sid,sbeg,send,domid)
            mid,mbeg,mend,mlen,sid,sbeg,send,slen,evalue,score,acc,mtaxid = domainsHitDict[hit_key]
            record = [sid,sbeg,send,slen,domid,mid,mbeg,mend,mlen,evalue,score,acc,taxid2name.get(mtaxid,'unavailable')]
            farch.write('\t'.join(record) + '\n')

    os.remove(domains_hit_file)
    os.remove(dama_temp_out)
    runtime = time.time()-start_time
    print(f'[{script_name}] runtime: {runtime:.2f}')

    return 0

# Check if the program is not being imported
if __name__ == "__main__":
    sys.exit(main())
