<?php

/**
 * MIGRATION de LMB Community 2.071.0 vers Sooth ERP RC1.1
 * Script à placer à la racine de l'application LMB Community, dont vous aurez préalablement remplacé tous les fichiers par ceux de Sooth ERP RC1.1 (https://github.com/yvesb/soothERP/archive/RC1.1.zip , sous dossier "2.071.0" de l'archive)
 * Seuls les dossiers suivants sont à préserver de LMB Community: “config”, “fichiers” et “modeles_pdf”
 * Ce script réalise la mise à jour des fichiers de config, le lancer dans le navigateur
 *
 *
 * @author Nours312 <bm[at]nours312[dot]com>
 * @author Yves <sootherp[at]gmail[dot]com>
 *
 */
require ("_dir.inc.php");
require ($DIR."_session.inc.php");

// Sortie affichage encodée en utf-8
header('Content-Type: text/html; charset=utf-8');

echo "<p>En cas de problème de migration en utilisant ce script, merci de remonter les infos via <a href='http://community.sootherp.fr/'>Questions / Réponses</a>
et d'utiliser la procédure manuelle décrite dans le <a href='https://wiki.sootherp.fr/doku.php?id=wiki:sootherp:migration_depuis_lmb'>WIKI</a></p>";

// Suppression de variable inutilisée
maj_configuration_file ("config_generale.inc.php", "del_line", "\$USER_NOT_DECLARED", "\$USER_NOT_DECLARED", $DIR."config/");


// Ajout des variables nouvelles
// Au préalable on en teste l'existence (cas de rajout antérieur manuel), on n'ajoute que si la variable n'existe pas encore pour éviter la création de doublon.

if (! variableExist ("\$_SERVER['REF_DOC']", "config_generale.inc.php")) {
maj_configuration_file ("config_generale.inc.php", "add_line", 135, utf8_decode("// La variable suivante \$_SERVER['REF_DOC'] est ajoutée pour pouvoir modifier le format du nom des documents."), $DIR."config/");
maj_configuration_file ("config_generale.inc.php", "add_line", 136, utf8_decode("// Elle est initialisée ici par la valeur de la variable \$_SERVER['REF_SERVEUR'] pour compatibilité avec LMB officiel."), $DIR."config/");
maj_configuration_file ("config_generale.inc.php", "add_line", 137, utf8_decode("// L'initialisation peut utilement être remplacée, par exemple, par \$_SERVER['REF_DOC']=date(\"Y\") pour un nom de document plus conventionnel, du type FAC-2011-xxxxx"), $DIR."config/");
maj_configuration_file ("config_generale.inc.php", "add_line", 138, "\$_SERVER['REF_DOC']=\$_SERVER['REF_SERVEUR'];", $DIR."config/");
}

if (! variableExist ("\$AFFICHAGE_NEWS", "config_generale.inc.php")) {
maj_configuration_file ("config_generale.inc.php", "add_line", 200, "// *************************************************************************************************************", $DIR."config/");
maj_configuration_file ("config_generale.inc.php", "add_line", 201, "// CONFIGURATION DE l'AFFICHAGE DES NEWS SOOTH ERP", $DIR."config/");
maj_configuration_file ("config_generale.inc.php", "add_line", 202, "// *************************************************************************************************************", $DIR."config/");
maj_configuration_file ("config_generale.inc.php", "add_line", 203, "\$AFFICHAGE_NEWS = false;", $DIR."config/");
}

if (! variableExist ("\$SESSION_START_BACKUP", "config_serveur.inc.php")) {
maj_configuration_file ("config_serveur.inc.php", "add_line", 29, "// *************************************************************************************************************", $DIR."config/");
maj_configuration_file ("config_serveur.inc.php", "add_line", 30, "// BACKUP", $DIR."config/");
maj_configuration_file ("config_serveur.inc.php", "add_line", 31, "// *************************************************************************************************************", $DIR."config/");
maj_configuration_file ("config_serveur.inc.php", "add_line", 32, "\$SESSION_START_BACKUP = false;".utf8_decode("        // réalise un backup MySQL au démarrage de la session si true"), $DIR."config/");
}

if (! variableExist ("\$_SERVER['SOOTHERP_VERSION']", "config_serveur.inc.php")) {
maj_configuration_file ("config_serveur.inc.php", "add_line", 17, "\$_SERVER['SOOTHERP_VERSION'] = 'RC1.1';", $DIR."config/");
}

// Mise à jour de variables.
maj_configuration_file ("config_serveur.inc.php", "maj_line", "\$_SERVER['VERSION']", "\$_SERVER['VERSION'] = '2.0710';", $DIR."config/");
maj_configuration_file ("config_serveur.inc.php", "maj_line", "\$ACTIVE_MAJ", "\$ACTIVE_MAJ = false;", $DIR."config/");
maj_configuration_file ("config_serveur.inc.php", "maj_line", "\$EMAIL_DEV ", "\$EMAIL_DEV = null; // Configurez ici l'adresse email de l'administrateur, sert aussi pour test d'envoi de mail", $DIR."config/");
maj_configuration_file ("config_systeme.inc.php", "maj_line", "\$CALCUL_TARIFS_NB_DECIMALS", "\$CALCUL_TARIFS_NB_DECIMALS = 5;", $DIR."config/");


echo "<p>Opérations de mise à jour réalisées</p>";

// On efface le présent script pour des raisons de sécurité.
unlink(__FILE__);


/**
 * Fonction test d'existence d'une variable de config dans un fichier de config donné
 *
 * @param string $variable Variable recherchée
 * @param string $confFile Fichier de config à checker
 *
 * @return boolean
 *
 */
function variableExist ($variable, $confFile) {

global $DIR;

// Création d'un array des lignes du fichier de config
$line = File($DIR."config/".$confFile);

for ($i=0; $i<count($line); $i++) {
						// recherche de l'existence de la variable en début de ligne (=> ignore l'éventuelle occurence en commentaire ou en entrée de variable)
						if (substr($line[$i], 0, strlen($variable)) === $variable) {
						return true;
						break;
						}
					}
return false;
}

?>