<?php
namespace src;

use ReflectionFunction;

/**
 * Classe Router
 * Gère les routes de l'application. Elle permet de définir des routes, de trouver
 * la route correspondant à l'URL actuelle et de générer des URLs pour des routes nommées.
 */
class Router {

    private string $viewsPath;
    private const TYPE_METHOD = ['POST', 'GET'];
    
    public array $routes = [];

    /**
     * Constructeur de la classe Router.
     *
     * @param string $viewsPath Chemin vers le dossier des vues.
     */
    public function __construct(string $viewsPath) {
        $this->viewsPath = $viewsPath;
    }

    /**
     * Génère une URL pour une route nommée.
     *
     * @param string $name Le nom de la route.
     * @param array|null $params Les paramètres à injecter dans l'URL.
     * @return string L'URL générée.
     * @throws \Exception Si la route n'est pas trouvée ou si les paramètres sont manquants.
     */
    public function url(string $name, ?array $params = null) : string
    {
        foreach ($this->routes as $route) {
            if (isset($route['name']) && $route['name'] === $name) {
                $root = $route['root'];

                // Si la route attend des paramètres
                if (str_contains($root, '[*]-[i]')) {
                    if (!is_array($params) || !isset($params['slug']) || !isset($params['id'])) {
                        throw new \Exception("Paramètres 'slug' et 'id' manquants pour la route '{$name}'");
                    }
                    return str_replace(['[*]', '[i]'], [$params['slug'], $params['id']], $root);
                }

                // Si la route n'a pas de paramètres, on retourne son URL directement
                return $root;
            }
        }
        // Si aucune route n'est trouvée, on lève une exception
        throw new \Exception("Aucune route nommée '{$name}' n'a été trouvée.");
    }

    /**
     * Ajoute une nouvelle route au routeur.
     *
     * @param string $method La méthode HTTP (GET, POST).
     * @param string $root Le chemin de l'URL (la route).
     * @param string|callable|array $target La cible de la route (un nom de vue, une fonction anonyme, ou un tableau [contrôleur, méthode]).
     * @param string|null $name Le nom de la route (optionnel).
     * @return self L'instance du routeur pour permettre le chaînage des méthodes.
     * @throws \Exception Si la méthode HTTP est invalide, si la méthode du contrôleur n'existe pas, ou si un nom de route est dupliqué.
     */
    public function action(string $method, string $root, string|callable|array $target, ?string $name = null) : self
    {
        // 1. Valider les méthodes HTTP fournies pour la route.
        $methods = explode('|', $method);
        foreach ($methods as $m) {
            if (!in_array($m, self::TYPE_METHOD)) {
                throw new \Exception("Methode invalide : {$m} dans '{$method}', doit être 'POST' ou 'GET'");
            }
        }
        
        // 2. Récupérer les noms des arguments pour les cibles "callable".
        $args = [];
        if (is_array($target)) {
            $class = $target[0];
            if (method_exists($class, $target[1]) === false) {
                throw new \Exception("Aucune méthode du nom {$target[1]} dans la classe {$target[0]}");
            }
            $reflection = new \ReflectionMethod($target[0], $target[1]);
        } else if (is_callable($target)) {
            $reflection = new ReflectionFunction($target);
            $paramètreInfos = $reflection->getParameters();
            foreach ($paramètreInfos as $key => $value) {
                $args[] = $paramètreInfos[$key]->name;
            }
        }
        
        // 3. Construire et stocker la définition de la route.
        $newArray = [
            'methods' => $methods, // Stocke les méthodes autorisées (ex: ['GET', 'POST'])
            'root'=> $root,
            'target'=> $target,
            'name'=> $name,
            'arguments' => $args // These are parameter names, not values yet
        ];

        if (!is_callable($target)) {
            unset($newArray['arguments'], $newArray['params']);
        }
        if ($name === null) {
            unset($newArray['name']);
        }

        // 4. Vérifier l'unicité des noms de route.
        $this->routes[] = $newArray;
        $nameFileRoute = null;
        for ($i=0; $i < count($this->routes); $i++) { 
            if (array_key_exists('name', $this->routes[$i])) {
                $nameFileRoute[] = $this->routes[$i]['name'];
            }
        }
        $occurences = array_count_values($nameFileRoute);
        foreach ($occurences as $key => $value) {
            # code...
            if ($value > 1) {
                throw new \Exception("Impossible de définir deux roots avec le même nom ($key)", 1);
            }
        }
        return $this;
    }

    /**
     * Tente de faire correspondre l'URL actuelle avec une des routes définies.
     *
     * @return false|array Retourne un tableau contenant les informations de la route correspondante (incluant les paramètres), ou `false` si aucune correspondance n'est trouvée.
     * @throws \Exception Si aucune route n'a été définie.
     */
    public function match(): false|array
    {
        if (empty($this->routes)) {
            throw new \Exception("Aucune root n'a été définie.");
        }
    
        foreach ($this->routes as $route) {
            // 1. Vérifier si la méthode de la requête actuelle est autorisée pour cette route.
            if (!in_array($_SERVER['REQUEST_METHOD'], $route['methods'])) {
                continue; // Si la méthode ne correspond pas, on passe à la route suivante.
            }

            // 2. Gérer les correspondances exactes (sans paramètres dynamiques).
            if ($route['root'] === $_SERVER['REQUEST_URI']) { 
                return $route;
            }
    
            // If the route contains a dynamic pattern
            if (str_contains($route['root'], '-[*]-[i]')) {
                // Extract the base root from the route configuration
                $baseRoot = str_replace('-[*]-[i]', '', $route['root']);
    
                // Construct a regex pattern based on the route
                $pattern = preg_quote($baseRoot, '/') . '-([a-z]+)-([0-9]+)';
                $pattern = '/^' . $pattern . '$/';
    
                // Perform the regex match
                if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
                    // If the URI matches the pattern, extract the parameters
                    $params = [
                        'slug' => $matches[1],
                        'id' => $matches[2]
                    ];
    
                    // Merge the extracted parameters into the route
                    $route['params'] = $params;
                    return $route;
                }
            }
        }
    
        return false; // No matching route found
    }
}
