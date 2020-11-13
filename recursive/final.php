<?php
/**
 * error_reporting(E_ALL);
 * ini_set("display_errors", 1);
 */
/**
 * Affichage du parcour du labyrinthe
 */

$tmp = "1110000111110000011
0000000100000001110
1100000100100111000
1111111100100100100
0000000111111100100
000000000000111100";
$maze = array();
$tab = explode("\n", $tmp);
?>
<table>
    <?php $y = 0;
    foreach ($tab as $v) { ?>
		<tr>
            <?php
            $length = strlen($v); //taille de la chaine
            $x = 0;
            for ($i = 0; $i < $length - 1; $i++) {
                $value = mb_substr($v, $i, 1);// Valeur de la case
                $maze[$x][$y] = $value;
                ?>
				<td style="width:30px; height:30px; background:<?php echo $value == 1 ? "#00FF00" : "#FF0000"; ?>"></td>
                <?php $x++;
            } ?>
		</tr>
        <?php $y++;
    } ?>
</table>

<?php
/**
 * Comment trouver le parcour permettant d'arriver à la sortie ?
 */
$parcours = [];
$tested = [];
function is_way($x, $y) {
    global $maze;
    global $parcours; //Tableau qui contient le parcour (et a la fin la solution).
    global $tested;

    /**
     * Pour éviter le allow memory limit
     */
    if (in_array("$x-$y", $tested)) {
        return false;
    }
    $tested[] = "$x-$y";
    /**
     * Si c'est un mur
     */
    if ($maze[$x][$y] == 0) {
        return false;
    }
    /**
     * On signifie la fin du tableau pour indiquer a la fonction quand s'arréter
     * Si c'est pas défini alors on est a la fin du tableau
     */
    if (!isset($maze[$x + 1][$y])) {
        return true;
    }
    /**
     * On regarde les cases qui sont autour de la précédente
     * On les note true si verte
     * On appel la même fonction pour que chaque case autour devienne une case a analyser
     */
    if ((isset($maze[$x][$y - 1]) && is_way($x, $y - 1)) || (isset($maze[$x + 1][$y]) && is_way($x + 1, $y)) || (isset($maze[$x][$y + 1]) && is_way($x, $y + 1))) {
        $parcours[] = "$x-$y";
        return true;
    } else {
        return false;
    }

}

echo is_way(0, 2) ? "Bonne case" : "mur";
//is_way(0,2);//C'est la première case qui mène vers la fin
?>
<table>
    <?php $y = 0;
    foreach ($tab as $v) { ?>
		<tr>
            <?php
            $length = strlen($v); //taille de la chaine
            $x = 0;
            for ($i = 0; $i < $length - 1; $i++) {
                $value = in_array("$x-$y", $parcours);// Valeur de la case
                $maze[$x][$y] = $value;
                ?>
				<td style="width:30px; height:30px; background:<?php echo $value ? "#00FF00" : ""; ?>"></td>
                <?php $x++;
            } ?>
		</tr>
        <?php $y++;
    } ?>
</table>
<pre><?php print_r(array_reverse($parcours)) ?></pre>

