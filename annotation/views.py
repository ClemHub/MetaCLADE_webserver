from django.shortcuts import render
from .forms import annotation_form

def home_page(request):
    return render(request, 'annotation/accueil.html', locals())

def help_page(request):
    return render(request, 'annotation/aide.html', locals())

def reference_page(request):
    return render(request, 'annotation/reference.html', locals())

def tools_page(request):
    form = annotation_form(request.POST or None)
    if form.is_valid():
        organism = form.cleaned_data["org"]
        domains = form.cleaned_data["domains"]
        sequences = form.cleaned_data["sequences"]
        renvoi = form.cleaned_data['renvoi']
        valid = True
    return render(request, 'annotation/outils.html', locals())