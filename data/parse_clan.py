file = open('pfam32.clans', 'r')
data = file.readlines()
file.close()
clan_dict = dict()
string = str()
for line in data:
	l = line.split()
	#print(l)
	if len(l) == 4 and l[2].strip() not in clan_dict.keys():
		clan_dict[l[2].strip()] = l[3].strip()
		string += "'"+l[2].strip()+" - "+l[3].strip()+"', "
print(string[:-2])
#print(clan_dict)