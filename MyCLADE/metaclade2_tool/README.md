MetaCLADE2
==========

#### A multi-source domain annotation pipeline for quantitative metagenomic and metatranscriptomic functional profiling

Biochemical and regulatory pathways have until recently been thought and modelled within one cell type, one organism, one species. This vision is being dramatically changed by the advent of whole microbiome sequencing studies, revealing the role of symbiotic microbial populations in fundamental biochemical functions. The new landscape we face requires the reconstruction of biochemical and regulatory pathways at the community level in a given environment. In order to understand how environmental factors affect the genetic material and the dynamics of the expression from one environment to another, one wishes to quantitatively relate genetic information with these factors. For this, we need to be as precise as possible in evaluating the quantity of gene protein sequences or transcripts associated to a given pathway. We wish to estimate the precise abundance of protein domains, but also recognise their weak presence or absence.

We introduce MetaCLADE2, and improved profile-based domain annotation pipeline based on the multi-source domain annotation strategy. It provides a domain annotation realised directly from reads, and reaches an improved identification of the catalog of functions in a microbiome. MetaCLADE2 can be applied to either metagenomic or metatranscriptomic datasets as well as proteomes.


# System requirements

+ MetaCLADE2 has been developed under a Linux environment.
+ The bash environment should be installed.
+ Python 3 is required for this package.


# Software requirements

+ HMMer-3
+ DAMA
+ GNU parallel (optional but recommended for running jobs on multiple threads)


# Installation

Latest development version of MetaCLADE2 can be obtained running the following command:
```
git clone http://gitlab.lcqb.upmc.fr/vicedomini/metaclade2.git
```
Then, it is advised to include MetaCLADE2 directory in your PATH environment variable by adding the following line to your `~/.bashrc` file:
```
export PATH=[MetaCLADE2_DIR]:${PATH}
```
where `[MetaCLADE2_DIR]` is MetaCLADE2 installation directory.


# MetaCLADE2 usage

```
  USAGE: metaclade2 -i <input_fasta> -N <name> [options]

  MANDATORY OPTIONS:    
    -i, --input <path>  Input file of AA sequences in FASTA format
                        (protein sequences or predicted CDS)
    -N, --name <str>    Dataset/job name

  MetaCLADE OPTIONS:              
    -a, --arch                    Use DAMA to properly compute domain architectures
                                  (useful only for long protein sequences)
    -d, --domain-list <str>       Comma-spearated list of Pfam accession numbers of
                                  the domains to be considered in the analysis
                                  (e.g., "PF00875,PF03441")
    -D, --domain-file <path>      File that contains the Pfam accession numbers
                                  of the domains to be considered (one per line)
    -e, --evalue-cutoff <float>   E-value cutoff
    -E, --evalue-cutconf <float>  Confidence threshold used by DAMA to add new domains into the architecture.
    -W, --work-dir <path>         Working directory, where jobs and results are saved

  OTHER OPTIONS:         
    -j, --jobs <num>     Number of jobs to be created (default:16)
    -t, --threads <num>  Number of threads for each job (default:1)
    -h, --help           Print this help message
    -V, --version        Print version

  SGE OPTIONS:               
    --sge                    Run MetaCLADE jobs on a SGE HPC environment
    --pe <name>              Parallel environment to use (mandatory)
    --queue <name>           Name of a specific queue where jobs are submitted
    --time-limit <hh:mm:ss>  Time limit for submitted jobs formatted as hh:mm:ss
                             where hh, mm, ss represent hours, minutes, and seconds respectively
                             (e.g., use --time-limit 2:30:00 for setting a limit of 2h and 30m)
```

#### Optional MetaCLADE2 configuration file (available soon)
MetaCLADE2 optionnally accepts a configuration file that allows the user to set custom paths to the MetaCLADE model library.
Lines starting with a semicolon are not taken into account and are considered as comments. 
You **must** also provide absolute paths.
```
[metaclade]
;ccms_path    = /absolute/path/to/data/models/CCMs
;hmms_path    = /absolute/path/to/data/models/HMMs
```


# MetaCLADE jobs
By default jobs are created in `[WORKING_DIR]/[DATASET_NAME]/jobs/`. By default `[WORKING_DIR]` is the current directory where the `metaclade2` command has been run.
Using the `--sge` parameter it is possible to automatically handle MetaCLADE2 pipeline in a SGE-based cluster (see [MetaCLADE2 usage](#metaclade2-usage) section).

Each (numbered) folder in this directory represents a step of the pipeline and contains several `*.sh` files (depending on the value provided with the `-j [NUMBER_OF_JOBS]` parameter):
```
[DATASET_NAME]_1.sh
[DATASET_NAME]_2.sh
...
[DATASET_NAME]_[NUMBER_OF_JOBS].sh
```

Jobs **must** be run in the following order:
```
[WORKING_DIR]/[DATASET_NAME]/jobs/1_search/
[WORKING_DIR]/[DATASET_NAME]/jobs/2_filter/
[WORKING_DIR]/[DATASET_NAME]/jobs/3_arch/
```
Each file in a given directory can be submitted independently to the HPC environment.


# MetaCLADE2 results
By default results are stored in the `[WORKING_DIR]/[DATASET_NAME]/results/` directory.
Each (numbered) folder in this directory contains the results after each step of the pipeline. 
After running each step, the final annotation is saved in the file named
```
[WORKING_DIR]/[DATASET_NAME]/results/3_arch/[DATASET_NAME].arch.txt
```
It is a tab-separated values (TSV) file whose lines represent annotations.
Each annotation has the following fields:
* Sequence identifier
* Sequence start
* Sequence end
* Sequence length
* Domain identifier (_i.e._, Pfam accession number)
* Model identifier 
* Model start
* Model end
* Model size
* E-value of the prediction
* Bitscore of the prediction
* Accuracy value in the interval [0,1]


# Example
A test dataset is available in the `test` directory and can be run with the following command:
```
cd [METACLADE2_DIR]
metaclade2 -i ./test/test.fa -N testDataSet -d PF00875,PF03441,PF03167,PF12546 -W ./test -j 2
```
This will create at most two scrips (jobs) in each directory of the pipeline.

Alternatively, if you are running MetaCLADE2 in a SGE cluster, the following script will run at most 2 jobs, each one using 2 CPUs, for each step of the pipeline:
```
cd [METACLADE2_DIR]
metaclade2 -i ./test/test.fa -N testDataSet -d PF00875,PF03441,PF03167,PF12546 -W ./test --sge --pe smp -j 2 -t 2
```

Results will be stored in the `[METACLADE2_DIR]/test/testDataSet/results` directory.
The final annotation file should look as follows:
```
tr|V7B5W0|V7B5W0_PHAVU          285  476  682  PF03441  E4X2Z2_OIKDI_300-507      4  193  196  3.3e-71  226.4  0.97  Oikopleura dioica
tr|V7B5W0|V7B5W0_PHAVU          7    173  682  PF00875  HMMer-3                   1  157  164  2.5e-44  138.9  0.93  unavailable
tr|V7B5W0|V7B5W0_PHAVU          505  645  682  PF12546  S8D414_9LAMI_84-208       1  138  139  2.9e-45  142.1  0.82  Genlisea aurea
tr|F0RPZ8|F0RPZ8_DEIPM          586  748  757  PF03167  A0A0G0AUB5_9BACT_52-217   2  162  163  7.8e-72  228.5  0.98  Candidatus Roizmanbacteria bacterium GW2011_GWC2_34_23
tr|F0RPZ8|F0RPZ8_DEIPM          267  461  757  PF03441  A0A0F3K8Y1_9NEIS_266-465  1  188  193  4e-64    203.4  0.97  Aquitalea magnusonii
tr|F0RPZ8|F0RPZ8_DEIPM          5    127  757  PF00875  K8GNY4_9CYAN_4-128        1  119  122  1.7e-35  110.2  0.97  Oscillatoriales cyanobacterium JSC-12
tr|A0A072NB93|A0A072NB93_9DEIO  591  753  766  PF03167  A0A0G0AUB5_9BACT_52-217   1  162  163  5.6e-75  238.8  0.99  Candidatus Roizmanbacteria bacterium GW2011_GWC2_34_23
tr|A0A072NB93|A0A072NB93_9DEIO  274  469  766  PF03441  HMMer-3                   1  199  202  6.9e-56  176.6  0.91  unavailable
tr|A0A072NB93|A0A072NB93_9DEIO  12   141  766  PF00875  A6FVP5_9RHOB_1-137        1  129  130  8.2e-36  111.3  0.97  Roseobacter sp. AzwK-3b
```
