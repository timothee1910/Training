<?php
// cf https://regex101.com

/**
 * Deux normes possible POSIX et PCRE
 * pattern :
 *          si ^lemot alors detecte au début de l'expression si le mot est la
 *          si lemot$ alors detecte à le fin de l'expression si le mot est la
 *          si lemot|lemot2 alors detecte si lemot ou lemot2 sont présents dans l'expression
 *          si [tl]itre alors detecte si litre ou titre sont présents dans l'expression
 *              => possible avec [a-z]itre prend tous les mots ayant[abcde...]itre
 *          si lemot? alors detecte si lemot ou lemo sont présents dans l'expression (ne prend pas en compte la dernière lettre)
 *          si si[gn]{2}e alors detecte si signe ou singe sont présents dans l'expression
 *              ({} veut dire que les lettres dans [] doivent être présentes dans le mot, le g en premier ou deuxieme)
 *          si si[gne]{2}e alors detecte si signe ou singe ou sige sont présents dans l'expression
 *              ({w} veut dire qu'au moin w  les lettres dans [] doivent être présentes dans le mot, le g en premier ou deuxieme)
 *          si si[a-z]+ alors detecte si une occurence ou plus sont présents dans l'expression (ici tous les mots fonctionnent)
 *              + commence a 1 et * a 0
 *
 *          Lien HTTP: lien http://primfix.com/ ou https://primfix.com/ ou www.primfix.com/
 *          -> (https?:\/\/|www\.)[a-zA-z0-9-_\.\/\?=&]+
 *              attention a mettre \ devans les / sinon pas reconnu
 *              on peut concatener les lettres, chiffres, caractères spéciaux autorisé dans l'interval
 *              il faut echapper le point après www avec \ sinon le point aura l'effet 'autorize all'
 *              le + permet de s'assurer qu'une url est bien présente (et pas uniquement www. ou http://)
 *
 *          si dans une liste il a ^ alors cela prendra tous sauf ceux renseigné dans les crochets (exemple [^ ])
 *              cela prend tous sauf les espace (pratique pour les url)
 *
 *
 */

/**
 * Raccourcis regex
 * cf le site pus haut
 *      si on a [[:alnum:]] -> cela detecte tous les chiffres et les lettres renseignés
 *      si on a [[:alpha:]] -> cela detecte toutes les lettres renseignés
 *      si on a [^[:alpha:]] -> cela detecte toutes sauf les lettres renseignés
 *
 */

/**
 * preg_match
 * expression rationnel = expression regulière
 * Fonctionne avec des délimiter sur le premier param (, ; \ % ...)
 * Retourne 0 si il ne trouve rien et 1 si il trouve le (et non LES) mot dans l'expression
 *
 * Les expression regulière sont CaseSensitive -> prennent en compte les majuscules
 * Si on veut changer cela il faut rajouter un flag après les delimiter (i pour retirer l'option CaseSensitive)
 */

$regexPregMatch = preg_match("%(https?://|www\.)[a-zA-z0-9-_./?=&]+%",
                            'Je suis le time voici le site web: https://letime.com/ , ainsi que mon autre site www.test.com', //-> retourne 1
                            $matches); //-> retourne les groupes reconnus, ils sont crées quand on met des parenthèses
                            /**
                             * ici
                             *      [0] => https://letime.com/
                             *      [1] => https:// -> ici le groupe crée grâce a (https?://|www\.)
                             */
echo "<pre>";
print_r($matches);
echo "</pre>";

/**
 * preg_match_all() -> pareil que preg_match sauf que cela renvoie toutes les occurences de l'expression renseignée
 */

$regexPregMatchAll = preg_match_all("%(https?://|www\.)[a-zA-z0-9-_./?=&]+%",
    'Je suis le time voici le site web: https://letime.com/, ainsi que mon autre site www.test.com', //-> retourne le nombres d'occurences
    $matchesPreg); //-> retourne les groupes reconnus, ils sont crées quand on met des parenthèses
/**
 * ici
 *     [0] => Array
                    (
                    [0] => https://letime.com/
                    [1] => www.test.com
                    )

        [1] => Array
                    (
                    [0] => https://
                    [1] => www.
                    )
 */
echo "<pre>";
print_r($matchesPreg);
echo "</pre>";

/**
 * preg_replace -> permet de remplacer les élements
 *                 La valeur de remplacement s'indique après le premier param (le pattern)
 */
$regexPregReplace = preg_replace("%(https?://|www\.)[a-zA-z0-9-_./?=&]+%",
                                'URL',                                 //=> on peut récupérer les valeurs des tableau dans les $matches
                                                                                  //=>  (exemple '$0' = la valeur dans [0] des $matches
                                 'Je suis le time voici le site web: https://letime.com/, 
                                          ainsi que mon autre site www.test.com' //-> retourne le subject
    );
/**
 * ici
 *     [0] => Array
(
[0] => https://letime.com/
[1] => www.test.com
)

[1] => Array
(
[0] => https://
[1] => www.
)
 */
echo "<pre>";
print_r($regexPregReplace);
echo "</pre>";