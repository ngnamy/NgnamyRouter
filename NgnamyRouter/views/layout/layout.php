<?php 
use src\Helpers;
?><!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $title ?? 'Mon Super Site' ?></title>
        <link rel="stylesheet" href="/assets/css/style.css">
        <script src="/assets/js/script.js" type="module" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Police Nunito, typique de Laravel -->
        <link rel="preconnect" href="https://fonts.googleapis.com"> 
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Instrument+Serif:ital@0;1&family=Varela+Round&display=swap" rel="stylesheet">
    </head> 
    <body>
        <header>
            <nav class="main-nav">
                <?= Helpers::link($router->url('accueil') , 'Accueil', ['id' => 1, "class"=>'link'], 'home') ?>
                <?= Helpers::link($router->url('contact') , 'Contact', ['id' => 2], 'phone') ?>
            </nav>
        </header>

        <main class="container">
            <?= $content ?>
        </main>

        <footer>
            <p>&copy; <?= date('Y') ?> - Tous droits réservés</p>
        </footer>
    </body>
</html>