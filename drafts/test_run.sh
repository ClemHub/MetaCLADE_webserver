#!/bin/bash
metaclade2 -i ./test.fasta -N testDataSet -d PF00875,PF03441,PF03167,PF12546 -W ./ --sge --pe smp -j 2 -t 2