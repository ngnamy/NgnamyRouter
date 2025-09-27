<?php
namespace src;

class Helpers {

    /**
     * Summary of dump
     * @param mixed $variable
     * @return void
     */
    public static function dump($variable) {
        echo "<pre style='font-weight: bold; padding: 1.3em; border-radius: .5em; background-color: rgba(250, 181, 181, 0.3); max-width: 600px; margin: 1.2em auto;'>" .print_r($variable, true). "</pre>";
    }

    /**
     * Summary of link
     * Crais un lien stylé en fonction de la page active
     * @param string $href
     * @param string $name
     * @param mixed $icon
     * @return string
     */
    public static function link(string $href, array $attributes = [], string $name, ?string $icon): string 
    {   
        $at = null;
        if (!empty($attributes)) {
            if (array_key_exists('class', $attributes)) {
                if($href === $_SERVER['REQUEST_URI']) {
                   $attributes['class'] =  implode(' ', [$attributes['class'], 'active']); 
                }
            } else {
                $attributes['class'] = 'active';
            }
        }
        
        foreach($attributes as $key => $value) {
            $at .= "$key=\"$value\" ";
        }

        return <<<HTML
        <a href="$href" $at ><i class="fas fa-$icon"></i> $name</a>
        HTML;
    }
}