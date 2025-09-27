<?php
namespace src;

/**
 * Classe utilitaire (Helper)
 *
 * Fournit un ensemble de méthodes statiques pour des tâches courantes
 * comme le débogage de variables ou la génération de balises HTML.
 */
class Helpers {

    /**
     * Affiche le contenu d'une variable de manière lisible pour le débogage.
     *
     * @param mixed $variable La variable à inspecter.
     * @return void
     */
    public static function dump($variable) {
        echo "<pre style='font-weight: bold; padding: 1.3em; border-radius: .5em; background-color: rgba(250, 181, 181, 0.3); max-width: 950px; margin: 1.2em auto; backdrop-filter: blur(10px);'>" .print_r($variable, true). "</pre>";
    }

    /**
     * Génère une balise de lien HTML <a>.
     *
     * Cette méthode crée un lien hypertexte. Elle peut automatiquement ajouter la classe 'active'
     * si le lien correspond à l'URL actuelle. Elle gère également l'ajout d'attributs HTML
     * personnalisés et d'une icône Font Awesome.
     *
     * @param string $href L'URL du lien (attribut href).
     * @param string $name Le texte visible du lien.
     * @param array $attributes Un tableau associatif d'attributs HTML supplémentaires (ex: ['class' => 'btn', 'id' => 'my-link']).
     * @param string|null $icon Le nom de l'icône Font Awesome à afficher (sans le préfixe 'fa-'). Si null, aucune icône n'est ajoutée.
     * @return string La chaîne de caractères HTML de la balise <a>.
     */
    public static function link(string $href, string $name, array $attributes = [], ?string $icon = null): string 
    {
        // Ajoute la classe 'active' si l'URL correspond à la page actuelle.
        if ($href === $_SERVER['REQUEST_URI']) {
            // Utilise l'opérateur de coalescence null pour ajouter 'active' à la classe existante ou la créer.
            $attributes['class'] = trim(($attributes['class'] ?? '') . ' active');
        }

        // Construit la chaîne des attributs HTML.
        $attributeParts = [];
        foreach ($attributes as $key => $value) {
            // Échappe les valeurs pour la sécurité, bien que non critique pour les attributs connus ici.
            $attributeParts[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }
        $attributeString = implode(' ', $attributeParts);

        // Construit la balise <i> pour l'icône si elle est fournie.
        $iconHtml = $icon ? "<i class=\"fas fa-{$icon}\"></i> " : '';

        // Retourne le lien HTML complet.
        return sprintf(
            '<a href="%s" %s>%s%s</a>',
            $href, $attributeString, $iconHtml, $name
        );
    }
}
