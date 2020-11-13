<?php
namespace Models;

/**
 * Class Article
 * extends veut qu'elle herite de la classe Model
 * Quand j'herite de Model cela revient a dire que j'ai copier coller dans la classe Article les fonctions de Model
 */
class Article extends Model
{
    protected $table = 'articles';
}
