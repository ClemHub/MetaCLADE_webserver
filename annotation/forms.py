from django import forms

class annotation_form(forms.Form):
	domains = forms.CharField(widget=forms.Textarea, label="PFAM accession number", help_text="Ex: PF06425,PF04263")
	sequences = forms.CharField(widget=forms.Textarea, label = "Sequences", help_text="Sequences in Fasta format")
	def as_table(self):
		return self._html_output(
            normal_row='<tr%(html_class_attr)s><th>%(label)s</th><td>%(errors)s%(field)s%(help_text)s</td></tr>',
            error_row='<tr><td colspan="2">%s</td></tr>',
            row_ender='</td></tr>',
            help_text_html='<a class="helptext">?<span>%s</span></a>',
            errors_on_separate_row=False)
