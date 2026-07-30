<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-kongresse-bundle.
 *
 * (c) Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoKongresseBundle\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Schachbulle\ContaoKongresseBundle\Helper\DateRange;

/**
 * Prüft die Zusammenfassung von Anfangs- und Enddatum.
 */
class DateRangeTest extends TestCase
{
	/**
	 * Setzt vor jedem Test eine feste Zeitzone.
	 *
	 * Die Zeitstempel der Testfälle sind für Europe/Berlin gerechnet; ohne
	 * feste Zeitzone würden die Tests je nach php.ini einen Tag daneben liegen.
	 */
	protected function setUp(): void
	{
		parent::setUp();

		date_default_timezone_set('Europe/Berlin');
	}

	/**
	 * Prüft die Formatierung anhand der Fälle aus dem Datenprovider.
	 *
	 * @param mixed  $varVon    Anfangsdatum als Zeitstempel oder leerer Wert
	 * @param mixed  $varBis    Enddatum als Zeitstempel oder leerer Wert
	 * @param string $strErwartet Die erwartete Ausgabe
	 *
	 * @dataProvider dateProvider
	 */
	public function testMerge($varVon, $varBis, string $strErwartet): void
	{
		$this->assertSame($strErwartet, DateRange::merge($varVon, $varBis));
	}

	/**
	 * Liefert die Testfälle für testMerge().
	 *
	 * Die Zeitstempel werden über mktime() erzeugt, damit im Test lesbar bleibt,
	 * welches Datum gemeint ist.
	 *
	 * @return array<string, array{0: mixed, 1: mixed, 2: string}>
	 */
	public function dateProvider(): array
	{
		return array
		(
			'kein Datum gepflegt' => array('', '', ''),
			'nur Anfangsdatum' => array((string) mktime(0, 0, 0, 3, 1, 2020), '', '01.03.2020'),
			'Anfang und Ende identisch' => array
			(
				(string) mktime(0, 0, 0, 3, 1, 2020),
				(string) mktime(0, 0, 0, 3, 1, 2020),
				'01.03.2020'
			),
			'gleicher Monat, gleiches Jahr' => array
			(
				(string) mktime(0, 0, 0, 3, 1, 2020),
				(string) mktime(0, 0, 0, 3, 3, 2020),
				'01. - 03.03.2020'
			),
			'Monatswechsel im selben Jahr' => array
			(
				(string) mktime(0, 0, 0, 3, 30, 2020),
				(string) mktime(0, 0, 0, 4, 2, 2020),
				'30.03. - 02.04.2020'
			),
			'Jahreswechsel' => array
			(
				(string) mktime(0, 0, 0, 12, 30, 2019),
				(string) mktime(0, 0, 0, 1, 2, 2020),
				'30.12.2019 - 02.01.2020'
			),
			'gleicher Tag und Monat, anderes Jahr' => array
			(
				(string) mktime(0, 0, 0, 1, 1, 2019),
				(string) mktime(0, 0, 0, 1, 1, 2020),
				'01.01.2019 - 01.01.2020'
			),
			'nur Enddatum gepflegt' => array('', (string) mktime(0, 0, 0, 3, 1, 2020), ''),
			'Zeitstempel als int' => array(mktime(0, 0, 0, 3, 1, 2020), 0, '01.03.2020'),
		);
	}
}
