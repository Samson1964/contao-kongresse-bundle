<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-kongresse-bundle.
 *
 * (c) Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoKongresseBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

/**
 * Setzt bestehende Veranstaltungen einmalig auf "veröffentlicht".
 *
 * Bis Version 1.2.2 wurde das Feld aktiv im Frontend nicht ausgewertet; alle
 * Datensätze waren unabhängig davon sichtbar. Seit Version 2.0.0 filtert das
 * Frontend-Modul darauf. Ohne diese Migration würden bei einem Update
 * schlagartig alle Veranstaltungen verschwinden, deren Häkchen nie gesetzt
 * wurde.
 */
class AktivDefaultMigration extends AbstractMigration
{
	/**
	 * Datenbankverbindung.
	 *
	 * @var Connection
	 */
	private $connection;

	/**
	 * @param Connection $connection Wird von Symfony automatisch eingesetzt
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Entscheidet, ob die Migration ausgeführt werden muss.
	 *
	 * Als Merkmal dient der Spaltenvorgabewert von tl_kongresse.aktiv: bis
	 * Version 1.2.2 lautete er '', seit 2.0.0 lautet er '1'. Den neuen Wert
	 * setzt run() selbst, denn contao:migrate ruft die Migrationen so lange
	 * wiederholt auf, wie shouldRun() true meldet – auf den Schemaabgleich zu
	 * warten würde in einer Endlosschleife enden. So läuft die Migration genau
	 * einmal und kann eine später bewusst zurückgezogene Veranstaltung nicht
	 * wieder veröffentlichen.
	 *
	 * @return bool true, wenn die Tabelle existiert und noch den alten
	 *              Vorgabewert trägt
	 */
	public function shouldRun(): bool
	{
		$varDefault = $this->connection->fetchOne(
			"SELECT COLUMN_DEFAULT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tl_kongresse' AND COLUMN_NAME = 'aktiv'"
		);

		// Tabelle oder Spalte gibt es noch nicht – dann ist nichts zu tun
		if (false === $varDefault || null === $varDefault)
		{
			return false;
		}

		// MariaDB liefert den Vorgabewert als Ausdruck, also mit Hochkommata
		return '1' !== trim((string) $varDefault, "'");
	}

	/**
	 * Veröffentlicht alle bisher nicht markierten Veranstaltungen.
	 *
	 * Anschließend wird der Spaltenvorgabewert auf '1' gesetzt. Das ist
	 * zugleich das Merkmal, an dem shouldRun() erkennt, dass die Migration
	 * erledigt ist; der spätere Schemaabgleich von contao:migrate findet die
	 * Spalte dann bereits im Sollzustand vor.
	 *
	 * Seiteneffekt: schreibt in tl_kongresse und ändert deren Struktur. Der
	 * Zeitstempel der Datensätze bleibt unverändert, weil es sich nicht um eine
	 * inhaltliche Änderung handelt.
	 *
	 * @return MigrationResult Ergebnis mit der Anzahl der geänderten Datensätze
	 */
	public function run(): MigrationResult
	{
		$intCount = (int) $this->connection->executeStatement("UPDATE tl_kongresse SET aktiv = '1' WHERE aktiv = ''");

		$this->connection->executeStatement("ALTER TABLE tl_kongresse MODIFY aktiv CHAR(1) NOT NULL DEFAULT '1'");

		return $this->createResult(
			true,
			sprintf('%s Veranstaltung(en) in tl_kongresse wurden auf veröffentlicht gesetzt', $intCount)
		);
	}
}
