"""
Author: Riccardo Vicedomini
"""

import os, sys, subprocess, argparse
import gzip, pickle
import mclade_const as C
import configparser

from mclade_utils import *

script_abs_dir = os.path.dirname(os.path.abspath(__file__))
script_name     = os.path.splitext( os.path.basename(__file__) )[0]
clade_def_root  = os.path.abspath(script_abs_dir + '/..') # clade basepath is one level before script's directory
clade_def_cfg   = clade_def_root + '/config/mclade.default.cfg'

class CladeController:

    def __init__(self, args):
        # check that all configuration files exist
        if not os.path.exists(clade_def_cfg):
            raise FileNotFoundError("MetaCLADE default config file NOT FOUND: " + clade_def_cfg)
        if args.user_cfg and not os.path.exists(args.user_cfg):
            raise FileNotFoundError("MetaCLADE user config file NOT FOUND: " + self.mclade_cfg)
        # load configuration files
        self.cladeCfgParser  = configparser.ConfigParser(allow_no_value=True)
        with open(clade_def_cfg,'r') as clade_def:
            self.cladeCfgParser.read_file(clade_def) # first, read default values (read_file function must be used for those)
        # load user parameters
        self.fasta_path = os.path.abspath(args.fasta_path)
        eprint(f"[{script_name}] fasta_path: {self.fasta_path}")
        self.name = args.name
        self.use_dama = args.use_dama
        self.usr_domain_list = args.usr_domain_list
        self.usr_domain_file = args.usr_domain_file
        self.evalue_cutoff = args.evalue_cutoff
        self.evalue_cutconf = args.evalue_cutconf
        self.working_dir = os.path.abspath(args.work_dir)
        self.temp_out_path = f'{self.working_dir}/temp'
        self.jobs_out_path = f'{self.working_dir}/jobs'
        self.results_out_path = f'{self.working_dir}/results'
        self.jobs_num = args.jobs_num
        # load user configuration file if provided
        if args.user_cfg:
            self.cladeCfgParser.read(args.user_cfg) # possibly overwrite defaults with provided custom config file

    def createFiles(self):
        self.processConfig()
        self.preprocess()
        if self.do_search:
            self.createSearchFiles()
        if self.do_filter:
            self.createFilterFiles()
        if self.do_arch:
            self.createArchFiles(self.use_dama)
        return self

    # process and check consistency of options provided with the configuration files
    def processConfig(self):
        # process [clade] section
        # FIRST: get MetaCLADE root path
        self.clade_root = self.cladeCfgParser.get('DEFAULT',C.CLADE_OPT_ROOT,fallback=clade_def_root)
        if self.clade_root == '' or (not os.path.isdir(self.clade_root)):
            self.clade_root = clade_def_root
        self.cladeCfgParser.set('DEFAULT',C.CLADE_OPT_ROOT,self.clade_root)
        eprint(f'[{script_name}] MetaCLADE root path: {self.clade_root}')
        # Get remaining options which might depend on CLADE's base path
        self.CCMs_path = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_CCMS_PATH)
        self.CCMs_ext = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_CCMS_EXT)
        self.HMMs_path = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_HMMS_PATH)
        self.HMMs_ext = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_HMMS_EXT)
        self.domain_list = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_DOM_LIST_PATH)
        self.knownarchs = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_KNOWNARCHS)
        self.overlapping = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_OVERLAPPING)
        self.taxid2name = self.cladeCfgParser.get(C.CLADE_SECTION, C.CLADE_OPT_TAXID2NAME)
        self.ccms_dict_path = self.cladeCfgParser.get(C.CLADE_SECTION, C.CLADE_OPT_CCMS_DICT_PATH)
        self.model_list_path = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_MOD_LIST_PATH)
        self.model_list_ext = self.cladeCfgParser.get(C.CLADE_SECTION,C.CLADE_OPT_MOD_LIST_EXT)
        # process [scripts] section
        self.scripts_path = self.cladeCfgParser.get(C.SCRIPTS_SECTION,C.SCRIPTS_OPT_SCRIPTS_PATH)
        self.search_hmm = self.cladeCfgParser.get(C.SCRIPTS_SECTION,C.SCRIPTS_OPT_SEARCH_HMM)
        self.search_ccm = self.cladeCfgParser.get(C.SCRIPTS_SECTION,C.SCRIPTS_OPT_SEARCH_CCM)
        self.filter_bd = self.cladeCfgParser.get(C.SCRIPTS_SECTION,C.SCRIPTS_OPT_FILTER_BD)
        self.simple_arch = self.cladeCfgParser.get(C.SCRIPTS_SECTION,C.SCRIPTS_OPT_SIMPLE_ARCH)
        self.dama_arch = self.cladeCfgParser.get(C.SCRIPTS_SECTION,C.SCRIPTS_OPT_DAMA_ARCH)
        # process [program] section
        self.hmmer_path = self.cladeCfgParser.get(C.PROGRAM_SECTION,C.PROGRAM_OPT_HMMER_PATH,fallback='')
        self.hmmsearch_cmd = "hmmsearch" if not self.hmmer_path else self.hmmer_path + '/hmmsearch'
        self.python_path = self.cladeCfgParser.get(C.PROGRAM_SECTION,C.PROGRAM_OPT_PYTHON_PATH,fallback='')
        self.python_cmd = "python3" if not self.python_path else self.python_path + '/python3'
        self.dama_path = self.cladeCfgParser.get(C.PROGRAM_SECTION,C.PROGRAM_OPT_DAMA_PATH,fallback='')
        self.dama_cmd = "DAMA" if not self.dama_path else self.dama_path + '/DAMA'
        # process [run] section
        # search options
        self.do_search = self.cladeCfgParser.getboolean(C.RUN_SECTION,C.RUN_OPT_DO_SEARCH)
        self.search_dirname = self.cladeCfgParser.get(C.RUN_SECTION,C.RUN_OPT_SEARCH_DIRNAME)
        self.search_jobs_path = f"{self.jobs_out_path}/{self.search_dirname}"
        self.search_results_path = f"{self.results_out_path}/{self.search_dirname}"
        if not self.search_dirname:
            raise IOError(C.RUN_SECTION + "->" + C.RUN_OPT_SEARCH_DIRNAME + " cannot be empty")
        # filter options
        self.do_filter = self.cladeCfgParser.getboolean(C.RUN_SECTION,C.RUN_OPT_DO_FILTER)
        self.filter_dirname = self.cladeCfgParser.get(C.RUN_SECTION,C.RUN_OPT_FILTER_DIRNAME)
        self.filter_jobs_path = f"{self.jobs_out_path}/{self.filter_dirname}"
        self.filter_results_path = f"{self.results_out_path}/{self.filter_dirname}"
        if not self.filter_dirname:
            raise IOError(C.RUN_SECTION + "->" + C.RUN_OPT_FILTER_DIRNAME + " cannot be empty")
        self.do_arch = self.cladeCfgParser.getboolean(C.RUN_SECTION,C.RUN_OPT_DO_ARCH, fallback=True)
        self.arch_dirname = self.cladeCfgParser.get(C.RUN_SECTION,C.RUN_OPT_ARCH_DIRNAME)
        self.arch_jobs_path = f"{self.jobs_out_path}/{self.arch_dirname}"
        self.arch_results_path = f"{self.results_out_path}/{self.arch_dirname}"
        if not self.arch_dirname:
            raise IOError(C.RUN_SECTION + "->" + C.RUN_OPT_ARCH_DIRNAME + " cannot be empty")
        return self


    def preprocess(self):
        self.work_domains = set()
        if self.usr_domain_list:
            for dom in self.usr_domain_list.split(','):
                self.work_domains.add(dom)
        if self.usr_domain_file:
            with open(self.usr_domain_file,'r') as f:
                for line in f:
                    dom = line.strip()
                    if dom != "":
                        self.work_domains.add(dom)
        if len(self.work_domains) == 0:
            with open(self.domain_list,'r') as f:
                for line in f:
                    line = line.strip()
                    if line != "":
                        self.work_domains.add(line.split()[0])
        return self


    def createQsubJob(self, jobspath, outprefix):
        with open( "{0}/{1}.submit.sh".format(jobspath,outprefix), 'w' ) as qsub_file:
            for i in range(0,self.jobs_num):
                qsub_file.write( f"bash {jobspath}/{outprefix}_{i+1}.sh &\n" )
            qsub_file.write("wait")

    def createSearchFiles(self):
        print(f"[{script_name}] creating search jobs")
        if not os.path.exists(self.search_jobs_path):
            os.makedirs(self.search_jobs_path)
        if not os.path.exists(self.search_results_path):
            os.makedirs(self.search_results_path)
        #print(f"[{script_name}] loading CCMs dictionary")
        self.loadCCMsDict()
        self.createSearchJobs(self.search_jobs_path,self.name)
        #self.createQsubJob(self.search_jobs_path,self.name)

    def createFilterFiles(self):
        print(f"[{script_name}] creating filter jobs")
        if not os.path.exists(self.filter_results_path):
            os.makedirs(self.filter_results_path)
        if not os.path.exists(self.filter_jobs_path):
            os.makedirs(self.filter_jobs_path)
        self.createFilterJobs(self.filter_jobs_path,self.name)
        #self.createQsubJob(self.filter_jobs_path,self.name)

    def createArchFiles(self,use_dama=False):
        print(f"[{script_name}] creating architecture jobs")
        if not os.path.exists(self.arch_results_path):
            os.makedirs(self.arch_results_path)
        if not os.path.exists(self.arch_jobs_path):
            os.makedirs(self.arch_jobs_path)
        if use_dama:
            self.createDamaArchJob(self.arch_jobs_path,self.name)
        else:
            self.createSimpleArchJob(self.arch_jobs_path,self.name)


    def searchHMM(self, domId, fastaPath):
        hmmFile = "{path}/{dom}{ext}".format( path=self.HMMs_path, dom=domId, ext=self.HMMs_ext)
        return " ".join((self.python_cmd, self.search_hmm, domId, self.hmmsearch_cmd, hmmFile, fastaPath, self.temp_out_path, self.search_results_path, self.name, '\n'))

    def searchCCM(self, domId, fastaPath):
        ccmFile  = "{path}/{dom}{ext}".format( path=self.CCMs_path, dom=domId, ext=self.CCMs_ext )
        listFile = "{path}/{dom}{ext}".format( path=self.model_list_path, dom=domId, ext=self.model_list_ext )
        return " ".join((self.python_cmd, self.search_ccm, domId, self.hmmsearch_cmd, ccmFile, listFile, fastaPath, self.temp_out_path, self.search_results_path, self.name, '\n'))

    def loadCCMsDict(self):
        try:
            self.readCCMsDict(0)
        except OSError as e:
            eprint(f"[{script_name}] creating CCMs dictionary file: {self.ccms_dict_path}")
            self.dumpModelsDict()
            self.readCCMsDict(1)

    def readCCMsDict(self,attempts=0):
        if attempts > 0:
            return
        with gzip.open(self.ccms_dict_path,'rb') as fh:
            self.ccmsModelDict = pickle.load(fh)

    # TODO: check whether keep this function here or create a separate script
    #       in case someone wants to update the model library dictionary
    def dumpModelsDict(self):
        self.ccmsModelDict = {}
        # retrieve domain identifiers
        with open(self.domain_list,'r') as domain_list_file:
            for line in domain_list_file:
                domId = line.strip().split()[0]
                self.ccmsModelDict[domId] = []
        # load model list for each domain
        for domId in self.ccmsModelDict.keys():
            listFile = f'{self.model_list_path}/{domId}{self.model_list_ext}'
            if os.path.exists(listFile):
                with open(listFile,'r') as dom_list_file:
                    for line in dom_list_file:
                        elts = line.strip().split()
                        modelId = (elts[1],elts[2],elts[3],elts[4])
                        self.ccmsModelDict[domId].append(modelId)
            else:
                eprint(f'[{script_name}] warning: list file \"{listFile}\" for domain {domId} does not exist!')
        with gzip.open(self.ccms_dict_path,'wb') as fh:
            pickle.dump(self.ccmsModelDict,fh)

    # TODO: GESTIRE FILE OUTPUT RISULTATI
    def createSearchJobs(self, jobspath, outprefix):
        domains = []
        tot_models = 0
        # create list of domains sorted by the number of models (in descending order)
        for dom_id in self.work_domains:
            models = len(self.ccmsModelDict[dom_id])
            tot_models += models
            domains.append((dom_id,models))
        domains.sort(key=lambda x: x[1],reverse=True)
        # fill job scripts with commands
        fileArray = [ open(f'{jobspath}/{outprefix}_{i+1}.sh','w') for i in range(0,self.jobs_num) ]
        fileIndex = 0
        for dom_id, num_models in domains:
            fileArray[fileIndex].write( self.searchHMM(dom_id,self.fasta_path) )
            fileArray[fileIndex].write( self.searchCCM(dom_id,self.fasta_path) )
            fileIndex = (fileIndex + 1) % self.jobs_num
        # close opened files
        for f in fileArray:
            f.close()

    # TODO GESTIRE FILE OUTPUT RISULTATI DI SEARCH
    def createFilterJobs(self, jobspath, outprefix):
        fileArray = [ open(f'{jobspath}/{outprefix}_{i+1}.sh','w') for i in range(0,self.jobs_num) ]
        fileIndex = 0
        for dom in self.work_domains:
            hmm_resfile  = f'{self.search_results_path}/{dom}/{dom}.hmm.res'
            ccms_resfile = f'{self.search_results_path}/{dom}/{dom}.ccms.res'
            best_resfile = f'{self.filter_results_path}/{dom}.best.res'
            filter_cmd = f'{self.python_cmd} {self.filter_bd} {hmm_resfile} {ccms_resfile} {best_resfile}\n'
            fileArray[fileIndex].write(filter_cmd)
            fileIndex = (fileIndex + 1) % self.jobs_num
        # close opened files
        for f in fileArray:
            f.close()

    def createSimpleArchJob(self, jobspath, outprefix):
        with open(f'{jobspath}/{outprefix}.sh','w') as fh:
            bd_dir   = f'{self.filter_results_path}'
            out_file = f'{self.arch_results_path}/{self.name}.arch.txt'
            taxdb_file = f'{self.taxid2name}'
            arch_cmd = f'{self.python_cmd} {self.simple_arch} -e {self.evalue_cutoff} {bd_dir} {out_file} {taxdb_file}'
            fh.write(f'{arch_cmd}\n')

    def createDamaArchJob(self, jobspath, outprefix):
        with open(f'{jobspath}/{outprefix}.sh','w') as fh:
            bd_dir   = f'{self.filter_results_path}'
            out_file = f'{self.arch_results_path}/{self.name}.arch.txt'
            taxdb_file = f'{self.taxid2name}'
            arch_cmd = f'{self.python_cmd} {self.dama_arch} --damaCmd {self.dama_cmd} --domainsInfoFile {self.domain_list} --knownArchFile {self.knownarchs} --overlappingDomainFile {self.overlapping} --evalueCutOff {self.evalue_cutoff} --evalueCutOffConf {self.evalue_cutconf} --filterResDir {bd_dir} --tempDir {self.temp_out_path} --outputFile {out_file} --taxid2name {taxdb_file}'
            fh.write(f'{arch_cmd}\n')

# ~/software/DAMA/Release/DAMA -domainsHitFile /home/vicedomini/projects/metaclade2/test/pippo/results/domainsHitFile.best.res -knownArchFile /home/vicedomini/projects/metaclade2/data/pfamLists/pfam32/pfam32.knownArch -domainsInfoFile /home/vicedomini/projects/metaclade2/data/pfamLists/pfam32/pfam32.domains -outputFile /home/vicedomini/projects/metaclade2/test/pippo/results/3_arch/pippo.arch.txt -overlappingDomainFile /home/vicedomini/projects/metaclade2/data/pfamLists/pfam32/pfam32.overlapping

#    def createOverLappingFilterJobs(self, jobDir):
#        searchResultsDir = "%s%s/%s/" % (self.RESULTS_DIR, self.DATASET_NAME, C.SEARCH_OUTPUT_DIR_NAME)
#        arffFilesDir = "%s%s/%s/" % (self.RESULTS_DIR, self.DATASET_NAME, C.ARFF_RESULT_DIR_NAME)
#        fileArray = [open("%s%s_%d%s" % (jobDir, self.DATASET_NAME, i, C.SCRIPT_EXT), "w") for i in range(0, self.NUMBER_OF_JOBS)]
#        index = 0
#        for line in open(self.ACC_LIST_PATH):
#            accId = line.split()[0]
#            params = (self.PYTHON_CMD, "%s%s" % (self.SCRIPTS_DIR, C.OL_FILTER_SCRIPT), searchResultsDir, accId, arffFilesDir, C.CCM_SEARCH_RES_EXT, C.SCM_SEARCH_RES_EXT, C.CCM_ARFF_RES_EXT, C.SCM_ARFF_RES_EXT, C.CCM_RESULT_RESUME, C.SCM_RESULT_RESUME)
#            command = "%s %s %s %s %s %s %s %s %s %s %s\n" % params
#            fileArray[index].write(command)
#            index += 1
#            if index == self.NUMBER_OF_JOBS:
#                index = 0
#        for f in fileArray:
#            f.close()
#        return self
#
#    def createOverLappingFilterFiles(self):
#        jobDir = "%s/%s/%s/" % (self.JOBS_DIR, self.DATASET_NAME, C.ARFF_JOB_DIR_NAME)
#        jobOutDir = "%s/%s" % (jobDir, C.OUT_DIR_NAME)
#        if not os.path.exists(jobOutDir):
#            os.makedirs(jobOutDir)
#        return self.createOverLappingFilterJobs(jobDir).createQsubJob(jobDir, jobOutDir)
#
#
#    def createAllNonOverlappingJob(self, jobDir):
#        finalpredictionsDir = "%s%s/%s/" % (self.RESULTS_DIR, self.DATASET_NAME, C.FP_RESULT_DIR_NAME)
#        bestdomainsDir = "%s%s/%s/" % (self.RESULTS_DIR, self.DATASET_NAME, C.BD_RESULT_DIR_NAME)
#        params = (self.PYTHON_CMD, "%s%s" % (self.SCRIPTS_DIR, C.ALL_OV_SCRIPT), bestdomainsDir, C.NB_FILTER_OUT_FILE, finalpredictionsDir, C.FINAL_PREDICTION_OUT_FILE)
#        with open("%s%s%s" % (jobDir, self.DATASET_NAME, C.SCRIPT_EXT), "w") as outFile:
#            outFile.write("%s %s %s %s %s %s\n" % (params))
#        return self
#
#    def createAllNonOverlappingFile(self):
#        jobDir = "%s/%s/%s/" % (self.JOBS_DIR, self.DATASET_NAME, C.FP_JOB_DIR_NAME)
#        jobOutDir = "%s/%s" % (jobDir, C.OUT_DIR_NAME)
#        if not os.path.exists(jobOutDir):
#            os.makedirs(jobOutDir)
#        return self.createAllNonOverlappingJob(jobDir)


def main():
    parser = argparse.ArgumentParser(description="Creates MetaCLADE job files")
    parser.add_argument('--config', dest='user_cfg', help='user config file', type=str)
    parser.add_argument('-i','--input', dest='fasta_path', required=True, help='fasta file containing protein sequences to annotate', type=str)
    parser.add_argument('-N','--name', dest='name', required=True, help='dataset/job name')
    parser.add_argument('-a','--arch', dest='use_dama', action='store_true', help='domain architecture computation using DAMA', default=False)
    parser.add_argument('-d','--domain-list', dest='usr_domain_list', help='comma-separated Pfam accession numbers', type=str)
    parser.add_argument('-D','--domain-file', dest='usr_domain_file', help='file containing Pfam accession numbers (one per line)', type=str)
    parser.add_argument('-e','--evalue-cutoff', dest='evalue_cutoff', help='e-value cutoff', type=float, default=float(1e-3))
    parser.add_argument('-E','--evalue-cutconf', dest='evalue_cutconf', help='confidence threshold used by DAMA to add domains into the architecture', type=float, default=float(1e-10))
    parser.add_argument('-W','--work-dir', dest='work_dir', help='working directory for MetaCLADE jobs/results', type=str)
    parser.add_argument('-j','--jobs', dest='jobs_num', help='number of job scripts to create', type=int, default=int(16))
    args = parser.parse_args()
    try:
        mclade=CladeController(args)
        mclade.createFiles()
    except (IOError, configparser.NoOptionError) as error:
        eprint(f'[{script_name}] error: {error}')
        return 1
    return 0

if __name__ == "__main__":
    sys.exit(main())
