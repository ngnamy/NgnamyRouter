<?php

use src\ArticleController;
use src\Helpers;
use src\Router;

try {
    //code...
    spl_autoload_register(function ($class) {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $class . '.php';
    });
    
    // Chemin vers le fichier de vues
    define('VIEWSPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views');
    
    $router = new Router(VIEWSPATH);
    $router
        ->action('GET', '/article-[*]-[i]', [new ArticleController(), 'show'], 'article')
        ->action('GET', '/', 'home', 'accueil')
        ->action('GET|POST','/contact', function () { require_once VIEWSPATH . DIRECTORY_SEPARATOR . 'contact.php';
        }, 'contact');

    $match = $router->match();
        if (is_array($match)) {
            $target = $match['target'];
            $params = $match['params'] ?? [];

            // Démarrer la temporisation de sortie pour capturer le contenu de la vue
            ob_start();

            if (is_callable($target)) {
                call_user_func_array($target, $params);
            } else if (is_string($target)) {
                // C'est une vue simple à inclure
                require_once VIEWSPATH . DIRECTORY_SEPARATOR . $target . '.php';
            } else {
                throw new \Exception("La cible de la route n'est pas valide.");
            }

            // Récupérer le contenu de la vue et nettoyer le tampon
            $content = ob_get_clean();

            // Inclure le layout principal qui affichera le $content
            require VIEWSPATH . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . 'layout.php';
        } else {
            throw new \Exception("Aucune root ne correspond à cette URL ({$_SERVER['REQUEST_URI']})");
        }
    
} catch (\Exception $th) {
    Helpers::dump($th);
    // echo "<p style='background-color: rgba(250, 181, 181, 0.3); padding: 1.2em; border-radius: .5em; color: red; font-family: varela round;'>Erreur : {$th->getMessage()}</p>";
}