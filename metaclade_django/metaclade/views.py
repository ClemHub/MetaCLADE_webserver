from django.shortcuts import render

# Create your views here.
def home_page(request):
	return render(request, 'metaclade/home.html')

def help_page(request):
	return render(request, 'metaclade/help.html', locals())

def references_page(request):
	return render(request, 'metaclade/references.html', locals())

def large_annotation_page(request):
	if request.method == 'POST':
	return render(request, 'metaclade/large_annotation.html', locals())

def small_annotation_page(request):
	return render(request, 'metaclade/small_annotation.html', locals())