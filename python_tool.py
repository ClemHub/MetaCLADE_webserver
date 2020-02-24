# -*- coding:utf-8 -*-

name = './data/pfam32_list.domain'
new_name = './data/pfam32_link.txt'
file = open(name, 'r')
new_file = open(new_name, 'w')
for line in file:
	new_file.write('https://pfam.xfam.org/family/'+line)
file.close()
new_file.close()