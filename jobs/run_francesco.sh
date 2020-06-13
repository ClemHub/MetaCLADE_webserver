#!/bin/bash

/home/blachon/Documents/Tools/metaclade2/metaclade2 -i /var/www/html/MetaCLADE_webserver/data/example.fasta -N 'testMC' -d 'PF00875,PF03441,PF03167,PF12546' --sge --pe smp -j 2
