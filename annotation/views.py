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
        domains = form.cleaned_data["domains"]
        sequences = form.cleaned_data["sequences"]
        valid = True
    return render(request, 'annotation/outils.html', locals())