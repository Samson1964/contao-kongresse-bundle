<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @package   Chessboardjs
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2016 - 2017
 */
namespace Schachbulle\ContaoKongresseBundle\Modules;

class Kongresse extends \Module
{

	protected $strTemplate = 'mod_kongresse';

	var $hash; // Variable für den Hashwert, um ein Brett eindeutig zuzuordnen
	var $position;
	var $halbzug;
	var $brett;

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### KONGRESSE ###';
			$objTemplate->title = $this->name;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}

		return parent::generate(); // Weitermachen mit dem Modul
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{

		// Gewünschte Datensätze
		$vonJahr = $this->kongresse_from ? $this->kongresse_from : 1800;
		$bisJahr = $this->kongresse_to ? $this->kongresse_to : 2100;
		$typen = unserialize($this->kongresse_typ);

		// SQL-String für die Veranstaltungstypen erstellen
		$typen_sql = '';
		foreach($typen as $typ)
		{
			if($typen_sql) $typen_sql .= ' OR ';
			$typen_sql .= "typ = '".$typ."'";
		}

		// Datensätze laden
		$objKongresse = \Database::getInstance()->prepare('SELECT * FROM tl_kongresse WHERE jahr >= ? AND jahr <= ? AND('.$typen_sql.') ORDER BY jahr DESC, datum_von DESC')
		                                        ->execute($vonJahr, $bisJahr);

		// Elo zuweisen
		if($objKongresse->numRows > 1)
		{
			$records = array();
			// Datensätze anzeigen
			while($objKongresse->next())
			{
				// Links zusammensetzen
				$links = '';
				$link_broschuere = '';
				$link_protokoll = '';
				if($objKongresse->file_broschuere)
				{
					$link_broschuere = '<a href="'.$this->replaceInsertTags('{{file::'.$objKongresse->file_broschuere.'}}').'" target="_blank" title="Buch/Broschüre herunterladen"><img src="bundles/contaokongresse/images/buch_24.png" alt="Buch/Broschüre"></a>';
				}
				if($objKongresse->file_protokoll)
				{
					$link_protokoll = '<a href="'.$this->replaceInsertTags('{{file::'.$objKongresse->file_protokoll.'}}').'" target="_blank" title="Protokoll herunterladen"><img src="bundles/contaokongresse/images/protokoll_24.png" alt="Protokoll"></a>';
				}

				// Extra-Links ermitteln
				$extra = unserialize($objKongresse->extra_links);
				if($extra)
				{
					foreach($extra as $key => $value)
					{
						$links .= '<a href="'.$this->replaceInsertTags($value['url']).'">'.$value['text'].'</a>';
						if($key + 1 < count($extra)) $links .= ' | ';
					}
				}

				// Jahr und Ort modifizieren
				if($objKongresse->url)
				{
					$href = $this->replaceInsertTags($objKongresse->url);
					$target = $objKongresse->newWindow ? ' target="_blank"' : '';
					$jahr = '<a href="'.$href.'"'.$target.'>'.$objKongresse->jahr.'</a>';
					$ort = '<a href="'.$href.'"'.$target.'>'.$objKongresse->ort.'</a>';
				}
				else
				{
					$jahr = $objKongresse->jahr;
					$ort = $objKongresse->ort;
				}

				// Datensatz in Templatevariable schreiben
				$records[] = array
				(
					'jahr'       => $jahr,
					'ort'        => $ort,
					'datum'      => self::DatumVerschmelzen($objKongresse->datum_von, $objKongresse->datum_bis),
					'info'       => $objKongresse->info,
					'broschuere' => $link_broschuere,
					'protokoll'  => $link_protokoll,
					'links'      => $links,
				);
			}
		}

		// Template füllen
		$objTemplate = new \FrontendTemplate($this->strTemplate);
		$this->Template->records = $records;
	}

	function DatumVerschmelzen($von, $bis)
	{
		// Starttag und Endetag vergleichen
		if($von && $bis) 
		{
		  $start[0] = date("d",$von); // Starttag
		  $start[1] = date("m",$von); // Startmonat
		  $start[2] = date("Y",$von); // Startjahr
		  $ende[0] = date("d",$bis); // Endetag
		  $ende[1] = date("m",$bis); // Endemonat
		  $ende[2] = date("Y",$bis); // Endejahr
		  if($start[2] == $ende[2]) 
		  {
		    // gleiches Jahr
		    $temp[0] = "";
		    $temp[1] = $ende[2];
		  }
		  else
		  {
		    // unterschiedliches Jahr
		    $temp[0] = $start[2];
		    $temp[1] = $ende[2];
		  }
		  if($start[1] == $ende[1]) 
		  {
		    // gleicher Monat
		    $temp[1] = $ende[1].".".$temp[1];
		  }
		  else
		  {
		    // unterschiedlicher Monat
		    $temp[0] = $start[1].".".$temp[0];
		    $temp[1] = $ende[1].".".$temp[1];
		  }
		  if($start[0] == $ende[0]) 
		  {
		    // gleicher Tag
		    $temp[1] = $ende[0].".".$temp[1];
		  }
		  else
		  {
		    // unterschiedlicher Tag
		    $temp[0] = $start[0].".".$temp[0];
		    $temp[1] = $ende[0].".".$temp[1];
		  }
		  $anzeigetag = $temp[0]." - ".$temp[1];
		}
		elseif($von && !$bis)
		{
		  // Endetag ist nicht gesetzt
		  $anzeigetag = date("d.m.Y",$von);
		}    
		else $anzeigetag = '';
		return $anzeigetag;
	}

}
