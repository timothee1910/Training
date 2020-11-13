<?php


class Renderer
{
    /**
     * Sert a faire un rendu html
     * @param string $path
     * @param array  $variable
     */
    public static function render (string $path, array $variable = []) {
        //$pageTitle = $article['title'];
        //transforme toutes les clès d'un tableau en variable contenant la valeur associé a la clé
        extract($variable);
        ob_start();
        require('templates/'.$path.'.html.php');
        $pageContent = ob_get_clean();

        require('templates/layout.html.php');
    }

}