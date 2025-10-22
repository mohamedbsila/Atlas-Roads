<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entrées (assets)
    |--------------------------------------------------------------------------
    |
    | Liste des fichiers d'entrée pour Vite. Laravel les utilisera
    | pour injecter les bons fichiers CSS et JS.
    |
    */

    'entry_points' => [
        'resources/css/app.css',
        'resources/js/app.js',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dossier du build
    |--------------------------------------------------------------------------
    |
    | Emplacement du fichier manifest.json généré par Vite.
    | Par défaut : public/build
    |
    */

    'build_path' => public_path('build/manifest.json'),

];
