from django.urls import path
from metaclade import views


urlpatterns = [
    path('home', views.home_page, name='home'),
	path('help', views.help_page, name='help'),
	path('references', views.references_page, name='references'),
	path('small_annotation', views.small_annotation_page, name='small_annotation'),
	path('large_annotation', views.large_annotation_page, name='large_annotation')
]