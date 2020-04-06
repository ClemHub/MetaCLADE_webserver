# MetaCLADE_webserver
![](https://img.shields.io/badge/course-INTERSHIP-blue)
![](https://img.shields.io/badge/status-active-active)

## Introduction
This project consist in implement a website to host the MetaCLADE program. 
This way the scientific international community would be able to use it easily.
MetaCLADE is an algorithm used to determine the architecture of sequences, the advantage is the possibility to use either metagenomic data or metatranscriptomic data.


## Description
The webserver is composed of several pages:
* Home -> presentation of the project
* Tools
  * Few domain annotation -> annotation of a given domain list
  * All domain annotation -> annotation of every domain
* Help -> user guide 
* References -> list of articles used to build this program and this website
* Contact -> list of person implied in the project and their informations
## Folder structure
```
.
├── data              # All data (example, pfam, go-terms)
├── drafts            # Draft used to try and keep some stuff in memory
├── Francesco_example # Model of the submission part
├── MyCLADE           # PHP files, css stylesheet, JS functions... (all the structure of the webserver)
├── server_images     # Images used for the webserver
└── README.md
```
```
MyCLADE
├── css_style              # css stylesheet
├── includes               # every functions (PHP, js...)
├── jobs                   # directory used to keep the jobs
├── architecture.php       # architecture representation webpage
├── contact.php            # contact webpage
├── example.php            # example webpage
├── help.php               # help webpage
├── home.php               # home webpage
├── large_annotation.php   # large annotation form webpage
├── small_annotation.php   # small annotation form webpage
├── references.php         # references webpage
├── results.php            # results webpage
├── submit.php             # submission webpage
└── run.sh                 # run.sh script
```

## Partners
* Website designed by Clemence BLACHON - trainee at the Laboratory of Computational and Quantitative Biology
* MetaCLADE implemented by Riccardo Vicedomini
