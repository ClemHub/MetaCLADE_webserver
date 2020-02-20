from django import forms

class annotation_form(forms.Form):
	org = forms.CharField(max_length=100)
	domains = forms.CharField(widget=forms.Textarea, label="PFAM accession number", help_text="Ex: PF06425,PF04263")
	sequences = forms.CharField(widget=forms.Textarea, label = "Sequences", help_text="Sequences in Fasta format")
	renvoi = forms.BooleanField(help_text="Cochez si vous souhaitez obtenir une copie du mail envoyé.", required=False)