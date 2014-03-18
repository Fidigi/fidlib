Un plugin doit respecter une arborescence définie :

nomdeplugin ([a-z0-9_]+)
>bg
ce répertoire sert a mettre a disposition des scripts pour les composants
>bin
les fichiers qui seront chargés pour charger le plugin
>etc
Ce répertoire sert a mettre a disposition des fichiers pour les composants
>var
>>index.php
Ce fichier devra créer, pour le cœur, un attribut "pluginNomdeplugin" où sera chargé le plugin.
Par contre, si le plugin met a disposition une nouvelle classe objet. Il ne sera pas nécessaire de charger l'attribut.