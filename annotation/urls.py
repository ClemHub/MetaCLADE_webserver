from django.urls import path
from . import views

urlpatterns = [
    path('outils', views.tools_page, name="outils"),
    path('aide', views.help_page, name="aide"),
    path('accueil', views.home_page, name="accueil"),
    path('references', views.reference_page, name = "references"),
    
    ]