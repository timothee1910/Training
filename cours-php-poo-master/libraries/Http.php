<?php

/**
 * Class Http
 * Tous ce qui concerne la requête ou la réponse (redirection...)
 */
class Http
{
    /**
     * L'utilisateur est redirigé vers l'url voulu lors d'une action
     * @param string $url
     */
    public static function redirect (string $url):void {

        header("Location: ".$url);
        exit();
    }
}