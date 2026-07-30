<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-kongresse-bundle.
 *
 * (c) Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoKongresseBundle\Helper;

/**
 * Formatiert den Zeitraum einer Veranstaltung.
 *
 * Die Klasse hängt bewusst nicht von Contao ab, damit die Formatierung ohne
 * geladenes Framework getestet werden kann.
 */
class DateRange
{
	/**
	 * Fasst Anfangs- und Enddatum zu einer möglichst kurzen Zeitspanne zusammen.
	 *
	 * Gleiche Bestandteile werden nur einmal genannt: aus dem 1. bis 3. März
	 * 2020 wird "01. - 03.03.2020", aus dem 30. März bis 2. April 2020 wird
	 * "30.03. - 02.04.2020" und aus dem 30. Dezember 2019 bis 2. Januar 2020
	 * das vollständige "30.12.2019 - 02.01.2020".
	 *
	 * Die Spalten datum_von und datum_bis sind in der Datenbank varchar, halten
	 * aber Unix-Zeitstempel. Sie werden deshalb hier nach int gewandelt; ein
	 * leerer String ergibt 0 und gilt als "nicht gepflegt".
	 *
	 * @param mixed $varVon Anfangsdatum als Unix-Zeitstempel, leer erlaubt
	 * @param mixed $varBis Enddatum als Unix-Zeitstempel, leer erlaubt
	 *
	 * @return string Die formatierte Zeitspanne; nur das Anfangsdatum, wenn kein
	 *                oder dasselbe Enddatum gepflegt ist; ein leerer String,
	 *                wenn kein Anfangsdatum vorliegt
	 */
	public static function merge($varVon, $varBis): string
	{
		$intVon = (int) $varVon;
		$intBis = (int) $varBis;

		if (!$intVon)
		{
			return '';
		}

		if (!$intBis || $intVon === $intBis)
		{
			return date('d.m.Y', $intVon);
		}

		// Reihenfolge Tag, Monat, Jahr
		$arrStart = array(date('d', $intVon), date('m', $intVon), date('Y', $intVon));
		$arrEnde = array(date('d', $intBis), date('m', $intBis), date('Y', $intBis));

		$blnGleichesJahr = $arrStart[2] === $arrEnde[2];
		$blnGleicherMonat = $blnGleichesJahr && $arrStart[1] === $arrEnde[1];

		// Von hinten nach vorn aufbauen: ein Bestandteil erscheint nur dann auch
		// im ersten Datum, wenn er sich vom zweiten unterscheidet.
		$strLinks = $blnGleichesJahr ? '' : $arrStart[2];
		$strRechts = $arrEnde[2];

		if ($blnGleicherMonat)
		{
			$strRechts = $arrEnde[1] . '.' . $strRechts;
		}
		else
		{
			$strLinks = $arrStart[1] . '.' . $strLinks;
			$strRechts = $arrEnde[1] . '.' . $strRechts;
		}

		if ($blnGleicherMonat && $arrStart[0] === $arrEnde[0])
		{
			$strRechts = $arrEnde[0] . '.' . $strRechts;
		}
		else
		{
			$strLinks = $arrStart[0] . '.' . $strLinks;
			$strRechts = $arrEnde[0] . '.' . $strRechts;
		}

		return $strLinks . ' - ' . $strRechts;
	}
}
