<?php

/**
 * Kongresse und Hauptausschüsse für Contao 4.13 und Contao 5
 *
 * @author    Frank Hoppe
 * @license   LGPL-3.0-or-later
 */

use Schachbulle\ContaoKongresseBundle\Modules\Kongresse;

/**
 * Backend-Modul
 */
$GLOBALS['BE_MOD']['content']['kongresse'] = array
(
	'tables' => array('tl_kongresse'),
);

/**
 * Frontend-Modul
 */
$GLOBALS['FE_MOD']['application']['kongresse'] = Kongresse::class;
