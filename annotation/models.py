from django.db import models

# Create your models here.
class pfam_domains(models.Model):
    domain_id = models.CharField(max_length=7)
    ccm = models.IntegerField(default = 3)
    hmm = models.IntegerField(default = 32)
    def __str__(self):
        """ 
        Cette méthode que nous définirons dans tous les modèles
        nous permettra de reconnaître facilement les différents objets que 
        nous traiterons plus tard dans l'administration
        """
        return self.domain_id
