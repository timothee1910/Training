<?php

$query = "SELECT ID, Parent, Nom FROM Animaux ORDER BY Nom ASC";
$result = mysql_query($query);
$categories = array();
while($row = mysql_fetch_array($result)) {
    $categories[] = array('parent_id' => $row['Parent'], 'categorie_id' => $row['ID'], 'nom_categorie' => $row['Nom']);
}

/**
 * Cela donne cela pour les 3 premiers
(
    [0] => Array
        (
            [parent_id] => 1
                    [categorie_id] => 5
                    [nom_categorie] => Petits Félins
        )
    [1] => Array
        (
            [parent_id] => 9
                    [categorie_id] => 13
                    [nom_categorie] => Chiens
         )
    [2] => Array
        (
            [parent_id] => 8
                    [categorie_id] => 10
                    [nom_categorie] => Saumons
         )
)
 */


/**
 * Affichage de la liste en mode Arborescence
 *
 * on parcoure la liste désorganisée
 * si on rencontre une catégorie qui a un lien de parenté avec une autre,
 * après l’avoir ajoutée dans le code HTML (variable $html)
 * on fait encore appel à la même fonction afin de vérifier si celle-ci n’a pas aussi une sous-catégorie
 * On sauvegarde le nombre de fois que la fonction est appelée par elle-même dans la variable $niveau
 * afin de déterminer combien de symboles « – » on devrait ajouter
 * afin de démontrer sa position dans la structure arborescente
 */
function afficher_menu($parent, $niveau, $array) {
    $html = "";
    foreach ($array AS $noeud) {
        if ($parent == $noeud['parent_id']) {

            for ($i = 0; $i < $niveau; $i++) $html .= "-";
            $html .= " " . $noeud['nom_categorie'] . "<br>";
            $html .= afficher_menu($noeud['categorie_id'], ($niveau + 1), $array);

        }
    }
    return $html;
}


/**
 * Le premier 0 de la fonction pour la variable $parent
 * indique que nous voulons afficher le menu en commençant avec les catégories principales à la racine
 * qui ont une valeur de parenté de 0
 *
 *  L’autre 0 initialise la valeur de $niveau à 0.
 * $categories est évidemment notre liste précédente
 */
echo afficher_menu(0, 0, $categories);
/**
 * Résultats affiché
    Canins
    - Chiens
    - Loups
    Félins
    - Grands Félins
    -- Lions
    -- Panthères
    -- Tigres
    - Petits Félins
    Poissons
    - Requins
    - Saumons
*/

/**
 * Fonction avec un mailleur fomattage
 *
 * La variable $niveau_precedent garde en mémoire le niveau précédent dans l’arborescence du menu, donc on peut la comparer au niveau actuel, $niveau
 * pour voir s’il y a eu un changement pour contrôler les débuts et les fins de liste avec la balise ul
 */
function afficher_menu2($parent, $niveau, $array) {
    $html = "";
    $niveau_precedent = 0;
    if (!$niveau && !$niveau_precedent) $html .= "\n<ul>\n";
    foreach ($array AS $noeud) {
        if ($parent == $noeud['parent_id']) {
            if ($niveau_precedent < $niveau) $html .= "\n<ul>\n";
            $html .= "<li>" . $noeud['nom_categorie'];

            $niveau_precedent = $niveau;
            $html .= afficher_menu($noeud['categorie_id'], ($niveau + 1), $array);

        }
    }
    if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</li></ul>\n\n";
    else if ($niveau_precedent == $niveau) $html .= "</ul>\n";
    else $html .= "\n";
    return $html;
}
