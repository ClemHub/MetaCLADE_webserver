from django import forms

class form_information(models.Model):
	pfam_domain = models.TextField()
	sequences = models.TextField()
	fasta_file = models.FileField()