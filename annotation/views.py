from django.shortcuts import render
from .forms import smallannotation_form, largeannotation_form

def home_page(request):
    return render(request, 'annotation/home.html', locals())

def help_page(request):
    return render(request, 'annotation/help.html', locals())

def references_page(request):
    return render(request, 'annotation/references.html', locals())

def smallannotation_page(request):
    form = smallannotation_form(request.POST or None)
    if form.is_valid():
        domains = form.cleaned_data["domains"]
        sequences = form.cleaned_data["sequences"]
        valid = True
    return render(request, 'annotation/small_annotation.html', locals())

def largeannotation_page(request):
    form = largeannotation_form(request.POST or None)
    if form.is_valid():
        sequences = form.cleaned_data["sequences"]
        valid = True
    return render(request, 'annotation/large_annotation.html', locals())
