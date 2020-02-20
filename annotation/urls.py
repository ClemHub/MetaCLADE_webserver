from django.urls import path
from . import views

urlpatterns = [
    path('large_annotation', views.largeannotation_page, name="large_annotation"),
    path('small_annotation', views.smallannotation_page, name="small_annotation"),
    path('help', views.help_page, name="help"),
    path('home', views.home_page, name="home"),
    path('references', views.references_page, name = "references"),
    
    ]