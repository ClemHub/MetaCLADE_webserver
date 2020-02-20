from django import forms

class smallannotation_form(forms.Form):
	domains = forms.CharField(widget=forms.Textarea, label="PFAM accession number (ex: PF04526,PF03624)")
	domain_file = forms.FileField(label="Domain File")
	sequences = forms.CharField(widget=forms.Textarea, label = "Sequences in fasta format")
	fasta_file = forms.FileField(label="Fasta File")
	def as_table(self):
		return self._html_output(
			normal_row='<tr%(html_class_attr)s><th>%(label)s</th><td>%(errors)s%(field)s%(help_text)s</td></tr>',
			error_row='<tr><td colspan="2">%s</td></tr>',
			row_ender='</td></tr>',
			help_text_html='<a class="helptext">?<span>%s</span></a>',
			errors_on_separate_row=False)

class largeannotation_form(forms.Form):
	sequences = forms.CharField(widget=forms.Textarea, label = "Sequences in fasta format")
	fasta_file = forms.FileField(label="Fasta File")
	def as_table(self):
		return self._html_output(
			normal_row='<tr%(html_class_attr)s><th>%(label)s</th><td>%(errors)s%(field)s%(help_text)s</td></tr>',
			error_row='<tr><td colspan="2">%s</td></tr>',
			row_ender='</td></tr>',
			help_text_html='<a class="helptext">?<span>%s</span></a>',
			errors_on_separate_row=False)
