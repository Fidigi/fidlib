Un plugin doit respecter une arborescence d�finie :

nomdeplugin ([a-z0-9_]+)
>bg
ce r�pertoire sert a mettre a disposition des scripts pour les composants
>bin
les fichiers qui seront charg�s pour charger le plugin
>etc
Ce r�pertoire sert a mettre a disposition des fichiers pour les composants
>var
>>index.php
Ce fichier devra cr�er, pour le c�ur, un attribut "pluginNomdeplugin" o� sera charg� le plugin.
Par contre, si le plugin met a disposition une nouvelle classe objet. Il ne sera pas n�cessaire de charger l'attribut.