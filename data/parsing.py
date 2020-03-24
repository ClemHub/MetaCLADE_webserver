import re 

file = open('./goterms_pfam.txt', 'r')
new_file = open('./goterms.txt', 'w')

for line in file:
	if line[0] != '!':
		s_line = re.split(r'[;,>]', line)
		pfam = re.split(r'[:\s]', s_line[0])[1]
		fam = re.split(r'[:\s]', s_line[0])[2]
		function = re.split(r'[:]', s_line[1][1:-1])[1]
		goterm = s_line[-1].strip()
		#print(pfam, function, goterm)
		new_file.write(pfam+'\t'+fam+'\t'+goterm+': '+function+'\n')

