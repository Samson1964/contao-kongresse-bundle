<?php

declare(strict_types=1);

/*
 * This file is part of schachbulle/contao-kongresse-bundle.
 *
 * (c) Frank Hoppe
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoKongresseBundle\Modules;

use Contao\BackendTemplate;
use Contao\Database;
use Contao\FilesModel;
use Contao\Module;
use Contao\StringUtil;
use Contao\System;
use Contao\Validator;
use Schachbulle\ContaoKongresseBundle\Helper\DateRange;

/**
 * Frontend-Modul "Kongresse & Ausschüsse".
 *
 * Gibt die Datensätze aus tl_kongresse als Tabelle aus, wahlweise eingegrenzt
 * auf einen Jahresbereich und auf bestimmte Veranstaltungsarten.
 */
class Kongresse extends Module
{
	/**
	 * Name des Frontend-Templates.
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_kongresse';

	/**
	 * Erzeugt die Modulausgabe.
	 *
	 * Im Backend wird lediglich ein Platzhalter ("Wildcard") gezeichnet, damit
	 * die Modulliste nicht versucht, die Frontend-Ausgabe zu erzeugen. Im
	 * Frontend übernimmt die Elternklasse und ruft compile() auf.
	 *
	 * @return string Der fertige HTML-Code des Moduls
	 */
	public function generate()
	{
		if ($this->isBackendRequest())
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . ($GLOBALS['TL_LANG']['FMD']['kongresse'][0] ?? 'KONGRESSE') . ' ###';
			$objTemplate->title = $this->name;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}

	/**
	 * Lädt die Veranstaltungen und schreibt sie in die Templatevariable records.
	 *
	 * Ausgegeben werden nur veröffentlichte Datensätze. Der Jahresbereich
	 * stammt aus den Moduleinstellungen; ohne Angabe wird ein bewusst weiter
	 * Bereich (1800 bis 2100) verwendet, damit alle Datensätze erscheinen. Ist
	 * "Veranstaltungen wählen" aktiv, wird zusätzlich auf die angehakten Arten
	 * eingeschränkt. Die Arten werden als Platzhalter an die Abfrage gebunden
	 * und nicht in den SQL-String geschrieben, weil sie aus der
	 * Moduleinstellung stammen und damit veränderbarer Eingabewert sind.
	 *
	 * Seiteneffekt: füllt $this->Template->records.
	 */
	protected function compile()
	{
		// Die Bezeichnungen der Veranstaltungsarten stehen in default.php. Ohne
		// diesen Aufruf bliebe der Titel der Typ-Spalte im Template leer, weil
		// Contao die Sprachdatei nicht zwingend vorher geladen hat.
		System::loadLanguageFile('default');

		$intVonJahr = (int) ($this->kongresse_from ?: 1800);
		$intBisJahr = (int) ($this->kongresse_to ?: 2100);

		$arrParams = array($intVonJahr, $intBisJahr);
		$strTypenSql = '';

		if ($this->kongresse_select)
		{
			$arrTypen = StringUtil::deserialize($this->kongresse_typ, true);
			$arrTypen = array_values(array_filter($arrTypen, static fn ($v) => '' !== (string) $v));

			if (!empty($arrTypen))
			{
				$strTypenSql = ' AND typ IN (' . implode(',', array_fill(0, \count($arrTypen), '?')) . ')';
				$arrParams = array_merge($arrParams, $arrTypen);
			}
		}

		// Die Parameter werden entpackt übergeben, weil execute() ein übergebenes
		// Array sonst serialisiert und als ein einzelner Wert behandelt.
		$objKongresse = Database::getInstance()
			->prepare("SELECT * FROM tl_kongresse WHERE aktiv = '1' AND jahr >= ? AND jahr <= ?" . $strTypenSql . ' ORDER BY jahr DESC, datum_von DESC')
			->execute(...$arrParams);

		$arrRecords = array();

		while ($objKongresse->next())
		{
			$strBroschuere = $this->createFileLink($objKongresse->file_broschuere, 'buch_24.png', 'Buch/Broschüre herunterladen', 'Buch/Broschüre');
			$strProtokoll = $this->createFileLink($objKongresse->file_protokoll, 'protokoll_24.png', 'Protokoll herunterladen', 'Protokoll');

			// Jahr und Ort werden verlinkt, sofern eine Veranstaltungsseite hinterlegt ist
			$strUrl = (string) $objKongresse->url;

			if ('' !== $strUrl)
			{
				$strHref = StringUtil::specialchars($this->parseInsertTags($strUrl));
				$strTarget = $objKongresse->newWindow ? ' target="_blank" rel="noreferrer noopener"' : '';
				$strJahr = '<a href="' . $strHref . '"' . $strTarget . '>' . StringUtil::specialchars((string) $objKongresse->jahr) . '</a>';
				$strOrt = '<a href="' . $strHref . '"' . $strTarget . '>' . StringUtil::specialchars((string) $objKongresse->ort) . '</a>';
			}
			else
			{
				$strJahr = StringUtil::specialchars((string) $objKongresse->jahr);
				$strOrt = StringUtil::specialchars((string) $objKongresse->ort);
			}

			$strTyp = (string) $objKongresse->typ;

			$arrRecords[] = array
			(
				'jahr'       => $strJahr,
				'typ'        => $strTyp,
				'typTitle'   => $GLOBALS['TL_LANG']['tl_kongresse']['typen'][$strTyp] ?? '',
				'ort'        => $strOrt,
				'datum'      => DateRange::merge($objKongresse->datum_von, $objKongresse->datum_bis),
				'info'       => (string) $objKongresse->info,
				'online'     => (bool) $objKongresse->online,
				'broschuere' => $strBroschuere,
				'protokoll'  => $strProtokoll,
				'links'      => $this->createExtraLinks($objKongresse->extra_links),
			);
		}

		$this->Template->records = $arrRecords;
	}

	/**
	 * Baut den Downloadlink zu einer im Datensatz hinterlegten Datei.
	 *
	 * Die Spalten file_broschuere und file_protokoll enthalten die UUID der
	 * Datei als 16 Byte langen Binärwert. Dieser Wert darf nicht direkt in
	 * einen Insert-Tag geschrieben werden: die Rohbytes enthalten regelmäßig
	 * Zeichen mit Sonderbedeutung – ein enthaltenes "|" beendet den Tag und
	 * lässt Contao den Rest als Insert-Tag-Flag deuten ("Unknown insert tag
	 * flag"). Deshalb wird die UUID hier in ihre Textform gewandelt und die
	 * Datei direkt über das Model aufgelöst.
	 *
	 * @param mixed  $varUuid   Binäre oder textuelle UUID aus der Datenbank, darf null oder leer sein
	 * @param string $strImage  Dateiname des Icons in Resources/public/images
	 * @param string $strTitle  Text für das title-Attribut des Links
	 * @param string $strAlt    Alternativtext des Icons
	 *
	 * @return string Der fertige Link, oder ein leerer String wenn keine Datei
	 *                hinterlegt ist, die UUID unbrauchbar ist oder die Datei
	 *                im Dateisystem nicht mehr existiert
	 */
	private function createFileLink($varUuid, string $strImage, string $strTitle, string $strAlt): string
	{
		if (empty($varUuid))
		{
			return '';
		}

		if (Validator::isBinaryUuid($varUuid))
		{
			$strUuid = StringUtil::binToUuid($varUuid);
		}
		elseif (Validator::isStringUuid($varUuid))
		{
			$strUuid = (string) $varUuid;
		}
		else
		{
			// Unbrauchbarer Wert – lieber gar kein Link als ein kaputter
			return '';
		}

		$objFile = FilesModel::findByUuid($strUuid);

		if (null === $objFile)
		{
			return '';
		}

		$strHref = StringUtil::specialchars(System::urlEncode($objFile->path));

		return '<a href="' . $strHref . '" target="_blank" rel="noreferrer noopener" title="' . StringUtil::specialchars($strTitle) . '">'
			. '<img src="bundles/contaokongresse/images/' . $strImage . '" alt="' . StringUtil::specialchars($strAlt) . '">'
			. '</a>';
	}

	/**
	 * Setzt die im MultiColumnWizard gepflegten Zusatzlinks zu einem HTML-Fragment zusammen.
	 *
	 * Die URLs stammen aus einem Feld mit dcaPicker und können daher Insert-Tags
	 * wie {{link_url::42}} enthalten; sie werden vor der Ausgabe aufgelöst.
	 * Zeilen ohne URL werden übersprungen, damit keine leeren Links entstehen.
	 * Fehlt der Linktext, wird die URL selbst angezeigt.
	 *
	 * @param mixed $varLinks Serialisierter Inhalt der Spalte extra_links, darf null sein
	 *
	 * @return string Die durch " | " getrennten Links, oder ein leerer String
	 */
	private function createExtraLinks($varLinks): string
	{
		$arrExtra = StringUtil::deserialize($varLinks, true);
		$arrLinks = array();

		foreach ($arrExtra as $arrLink)
		{
			$strUrl = trim((string) ($arrLink['url'] ?? ''));

			if ('' === $strUrl)
			{
				continue;
			}

			$strHref = $this->parseInsertTags($strUrl);
			$strText = trim((string) ($arrLink['text'] ?? ''));
			$strTarget = !empty($arrLink['newWindow']) ? ' target="_blank" rel="noreferrer noopener"' : '';

			$arrLinks[] = '<a href="' . StringUtil::specialchars($strHref) . '"' . $strTarget . '>'
				. StringUtil::specialchars('' !== $strText ? $strText : $strHref)
				. '</a>';
		}

		return implode(' | ', $arrLinks);
	}

	/**
	 * Löst Insert-Tags in einem Text auf.
	 *
	 * Genutzt wird der Service contao.insert_tag.parser, weil die alte Methode
	 * Controller::replaceInsertTags() in Contao 5 als veraltet gilt. Steht der
	 * Container ausnahmsweise nicht bereit (etwa im Unit-Test), wird der Text
	 * unverändert zurückgegeben.
	 *
	 * @param string $strText Der zu prüfende Text, darf leer sein
	 *
	 * @return string Der Text mit aufgelösten Insert-Tags
	 */
	private function parseInsertTags(string $strText): string
	{
		if ('' === $strText || false === strpos($strText, '{{'))
		{
			return $strText;
		}

		$container = System::getContainer();

		if (null === $container || !$container->has('contao.insert_tag.parser'))
		{
			return $strText;
		}

		return $container->get('contao.insert_tag.parser')->replaceInline($strText);
	}

	/**
	 * Prüft, ob der aktuelle Aufruf aus dem Backend kommt.
	 *
	 * Ersetzt die in Contao 5 entfallene Konstante TL_MODE. Ohne aktiven
	 * Request (etwa auf der Kommandozeile) wird false angenommen.
	 *
	 * @return bool true, wenn es sich um einen Backend-Request handelt
	 */
	private function isBackendRequest(): bool
	{
		$container = System::getContainer();

		if (null === $container || !$container->has('request_stack'))
		{
			return false;
		}

		$request = $container->get('request_stack')->getCurrentRequest();

		return null !== $request && $container->get('contao.routing.scope_matcher')->isBackendRequest($request);
	}
}
