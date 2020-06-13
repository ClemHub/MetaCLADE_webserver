file = open('goterms.txt', 'r')
data = file.readlines()
file.close()
file = open('new_goterms.txt', 'w')
nb = 1
for line in data:
	file.write(str(nb)+'\t'+line)
	nb+=1
file.close()
