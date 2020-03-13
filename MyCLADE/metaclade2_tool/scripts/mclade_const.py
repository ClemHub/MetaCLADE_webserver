"""
Authors: Ari Ugarte, Riccardo Vicedomini
"""

#####################################
# CLADE CONFIG SECTIONS AND OPTIONS #
#####################################

# [clade] main CLADE options
CLADE_SECTION            = "metaclade"
CLADE_OPT_ROOT           = "mclade_root"
CLADE_OPT_CCMS_PATH      = "ccms_path"
CLADE_OPT_CCMS_EXT       = "ccms_ext"
CLADE_OPT_HMMS_PATH      = "hmms_path"
CLADE_OPT_HMMS_EXT       = "hmms_ext"
CLADE_OPT_DOM_LIST_PATH  = "domain_list"
CLADE_OPT_KNOWNARCHS     = "knownarchs"
CLADE_OPT_OVERLAPPING    = "overlapping_doms"
CLADE_OPT_TAXID2NAME     = "taxid2name"
CLADE_OPT_CCMS_DICT_PATH = "ccms_domain_dict"
CLADE_OPT_MOD_LIST_PATH  = "model_list_path"
CLADE_OPT_MOD_LIST_EXT   = "model_list_ext"

# [scripts] options related to CLADE's auxiliary scripts
SCRIPTS_SECTION            = "scripts"
SCRIPTS_OPT_SCRIPTS_PATH   = "scripts_path"
SCRIPTS_OPT_SEARCH_HMM     = "search_hmm"
SCRIPTS_OPT_SEARCH_CCM     = "search_ccm"
SCRIPTS_OPT_FILTER_BD      = "filter_bd"
SCRIPTS_OPT_SIMPLE_ARCH    = "simple_arch"
SCRIPTS_OPT_DAMA_ARCH      = "dama_arch"

# [program] external software used in CLADE's pipeline
PROGRAM_SECTION            = "programs"
PROGRAM_OPT_HMMER_PATH     = "hmmer_path"
PROGRAM_OPT_HMMSEARCH_EXE  = "hmmsearch_exec"
PROGRAM_OPT_PYTHON_PATH    = "python_path"
PROGRAM_OPT_PYTHON_EXEC    = "python_exec"
PROGRAM_OPT_DAMA_PATH      = "dama_path"
PROGRAM_OPT_DAMA_EXEC      = "dama_exec"

# [run] options for a particular run of CLADE
RUN_SECTION            = "run"
RUN_OPT_NAME           = "name"
RUN_OPT_FASTA_PATH     = "fasta_path"
RUN_OPT_JOBS_NUM       = "jobs_num"
RUN_OPT_WALLTIME       = "walltime"
RUN_OPT_TEMP_PATH      = "temp_path"
RUN_OPT_RESULTS_PATH   = "results_path"
RUN_OPT_JOBS_PATH      = "jobs_path"
RUN_OPT_USE_DAMA       = "use_dama"

RUN_OPT_DO_SEARCH      = "do_search"
RUN_OPT_SEARCH_DIRNAME = "search_dirname"
RUN_OPT_DO_FILTER      = "do_filter"
RUN_OPT_FILTER_DIRNAME = "filter_dirname"
RUN_OPT_DO_ARCH        = "do_arch"
RUN_OPT_ARCH_DIRNAME   = "arch_dirname"


#######################
# ARI's OLD DEFINITIONS #
#######################

#MODELS_SECTION                   = "models"
#MODELS_CCMS_PATH                = "CCMS_PATH"
#MODELS_CCMS_PATH_DEFAULT        = "%s/data/models/CCMs"
#MODELS_CCM_BREAKS_DIR          = "CCM_BREAKS_DIR"
#MODELS_CCM_BREAKS_DIR_DEFAULT  = "%s/data/models/ccm_breaks/"
#MODELS_HMMS_PATH                = "HMMS_PATH"
#MODELS_HMMS_PATH_DEFAULT        = "%s/data/models/HMMs"
#MODELS_SCM_BREAKS_DIR          = "SCM_BREAKS_DIR"
#MODELS_SCM_BREAKS_DIR_DEFAULT  = "%s/data/models/scm_breaks/"

# [run]
#RUNENV_SECTION                    = "Run"
#RUNENV_SCRIPTS_DIR                = "SCRIPTS_DIR"
#RUNENV_SCRIPTS_DIR_DEFAULT        = "%s/scripts/"
#RUNENV_FILES_DIR                  = "FILES_DIR"
#RUNENV_FILES_DIR_DEFAULT          = "%s/files/"

#METACLADE_CONFIG_SECTIONS         = [PROGRAM_SECTION,MODEL_SECTION,DOMAINS_SECTION,RUNENV_SECTION]

######################################
# RUN INI OPTIONS AND DEFAULT VALUES #
######################################

#PARAMETERS_SECTION                = "Parameters"
#PARAMETERS_FASTA_FILE             = "FASTA_FILE"
#PARAMETERS_DATASET_NAME           = "DATASET_NAME"
#PARAMETERS_NUMBER_OF_JOBS         = "NUMBER_OF_JOBS"
#PARAMETERS_NUMBER_OF_JOBS_DEFAULT = "1"
#PARAMETERS_CREATE_BLASTDB         = "CREATE_BLASTDB"
#PARAMETERS_CREATE_BLASTDB_DEFAULT = "False"
#PARAMETERS_SEARCH                 = "SEARCH"
#PARAMETERS_SEARCH_DEFAULT         = "True"
#PARAMETERS_SAME_OL_FILTER         = "SAME_OL_FILTER"
#PARAMETERS_SAME_OL_FILTER_DEFAULT = "True"
#PARAMETERS_NB_FILTER              = "NB_FILTER"
#PARAMETERS_NB_FILTER_DEFAULT      = "True"
#PARAMETERS_NB_THRESHOLD           = "NB_THRESHOLD"
#PARAMETERS_NB_THRESHOLD_DEFAULT   = "0.9"
#PARAMETERS_ALL_OL_FILTER          = "ALL_OL_FILTER"
#PARAMETERS_ALL_OL_FILTER_DEFAULT  = "True"

##################
# MCLADE SCRIPTS #
##################

HMMER_SCRIPT = "hmmer.py"
CCM_SCRIPT   = "ccm.py"
#OL_FILTER_SCRIPT                  = "parse_output.py"
#NB_EVAL_SCRIPT                    = "evaluate_attributes_mclade.py"
#NB_FILTER_SCRIPT                  = "filter_nb_results.py"
#ALL_OV_SCRIPT                     = "final_prediction.py"

################
# MODEL SEARCH #
################

SEARCH_DIR_NAME                   = "model_search"
SEARCH_OUTPUT_DIR_NAME            = "models_output"
CCM_SEARCH_RES_EXT                = ".res"
SCM_SEARCH_RES_EXT                = ".domtblout"
CCM_ZIP_EXT                       = ".tar.gz"
CCM_EXT                           = ".chk"
SCM_EXT                           = ".hmm"

####################################
# ARFF AND BEST DOMAINS GENERATION #
####################################

ARFF_JOB_DIR_NAME                 = "arff_files"
ARFF_RESULT_DIR_NAME              = "arff_files"
NB_JOB_DIR_NAME                   = "mclade_eval"
NB_RESULT_DIR_NAME                = "mclade_eval"
BD_JOB_DIR_NAME                   = "best_domains"
BD_RESULT_DIR_NAME                = "best_domains"
NB_FILTER_OUT_FILE                = "best_domains.mclade"
FP_JOB_DIR_NAME                   = "final_prediction"
FP_RESULT_DIR_NAME                = "final_prediction"
FINAL_PREDICTION_OUT_FILE         = "final_prediction.mclade"

CCM_ARFF_RES_EXT                  = ".ccm.arff"
SCM_ARFF_RES_EXT                  = ".scm.arff"
CCM_RESULT_RESUME                 = "result_resume.ccm.best"
SCM_RESULT_RESUME                 = "result_resume.scm.best"

#################
# NB EVALUATION #
#################

CCM_EVAL_RES_EXT                  = ".ccm.arff.res"
SCM_EVAL_RES_EXT                  = ".scm.arff.res"

MIN_NEG_CCM                       = "min_neg_ccm.dict"
MIN_NEG_SCM                       = "min_neg_scm.dict"
MIN_POS_CCM                       = "min_pos_ccm.dict"
MIN_POS_SCM                       = "min_pos_scm.dict"

###################
# OTHER CONSTANTS #
###################

OUT_DIR_NAME                      = "out"
QSUB_SCRIPT_NAME                  = "submit"
SCRIPT_EXT                        = ".sh"
QSUB_LINE                         = "qsub -S /bin/bash -N %s_%d -e %s -o %s %s_%d%s\n"
PSSMS_MODELS_DICT                 = "models_dict.txt"

