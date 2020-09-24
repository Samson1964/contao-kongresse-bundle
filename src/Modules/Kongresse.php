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
		$typen = unserialize($this->kongresse->typ);

		// Datensätze laden
		$objKongresse = \Database::getInstance()->prepare('SELECT * FROM tl_kongresse WHERE jahr >= ? AND jahr <= ? ORDER BY jahr DESC, datum DESC')
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
				if($objKongresse->file_broschuere)
				{
					$links .= '<a href="'.$this->replaceInsertTags('{{file::'.$objKongresse->file_broschuere.'}}').'">Kongressbroschüre</a>';
				}
				if($objKongresse->file_protokoll)
				{
					if($links) $links .= ' | ';
					$links .= '<a href="'.$this->replaceInsertTags('{{file::'.$objKongresse->file_protokoll.'}}').'">Protokoll</a>';
				}
				if($objKongresse->url)
				{
					if($links) $links .= ' | ';
					$links .= '<a href="'.$this->replaceInsertTags($objKongresse->url).'">Infoseite</a>';
				}

				// Extra-Links ermitteln
				$extra = unserialize($objKongresse->extra_links);
				if($extra)
				{
					if($links) $links .= '<br>';
					foreach($extra as $key => $value)
					{
						$links .= '<a href="'.$this->replaceInsertTags($value['url']).'">'.$value['text'].'</a>';
						if($key + 1 < count($extra)) $links .= ' | ';
					}
				}

				// Datensatz in Templatevariable schreiben
				$records[] = array
				(
					'jahr'  => $objKongresse->jahr,
					'ort'   => $objKongresse->ort,
					'datum' => $objKongresse->datum ? date('d.m.Y', $objKongresse->datum) : '',
					'info'  => $objKongresse->info,
					'links' => $links,
				);
			}
		}

		// Template füllen
		$objTemplate = new \FrontendTemplate($this->strTemplate);
		$this->Template->records = $records;
	}

}
