from django.db import models

class pfam_link(models.Model):
	pfam_id = models.CharField(max_length=7)
	link = models.CharField(max_length=36)
	def __str__(self):
		return self.pfam_id	