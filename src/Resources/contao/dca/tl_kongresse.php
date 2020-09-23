<?php

/**
 * Tabelle tl_kongresse
 */
$GLOBALS['TL_DCA']['tl_kongresse'] = array
(

	// Konfiguration
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		),
	),

	// Datensätze auflisten
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array(),
			'flag'                    => 1,
			'panelLayout'             => 'filter;sort,search,limit',
		),
		'label' => array
		(
			// Das Feld aktiv wird vom label_callback überschrieben
			'fields'                  => array('aktiv','id','nachname','vorname','firma','plz','ort'),
			'showColumns'             => true,
			'format'                  => '%s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kongresse']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kongresse']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kongresse']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kongresse']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif',
				'attributes'          => 'style="margin-right:3px"'
			),
			'toggle' => array
			(
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_kongresse', 'toggleIcon')
			), 
		)
	),

	// Paletten
	'palettes' => array
	(
		'default'                     => '{kongresse_legende};{aktiv_legende},aktiv;{alias_legende},alias'
	),

	// Felder
	'fields' => array
	(
		'id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['id'],
			'sorting'                 => true,
			'search'                  => true,
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['tstamp'],
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'jahr' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['jahr'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'filter'                  => true,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>4, 'tl_class'=>'w50'),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		'ort' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['ort'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'search'                  => true,
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'datum' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['datum'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(11) NOT NULL default ''"
		), 
		'info' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['info'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'search'                  => false,
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'aktiv' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['aktiv'],
			'exclude'                 => true,
			'filter'                  => true,
			'default'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
	)
);

/**
 * Class tl_member_aktivicon
 */
class tl_kongresse extends Backend
{
    /**
     * Add an image to each record
     * @param array
     * @param string
     * @param DataContainer
     * @param array
     * @return string
     */
	public function addIcon($row, $label, DataContainer $dc, $args)
	{
		// Anzahl Einbindungen feststellen und Singular/Plural zuweisen
		$seiten = count(explode("\n",$row['links']))-1;
		($seiten == 1) ? ($wort = 'Seite') : ($wort = 'Seiten');

		if($row['aktiv'] && $row['links'])
		{
			// Adresse aktiv, ein oder mehrere Einbindungen
			$icon = 'bundles/contaoadressen/images/gruen.png';
			$title = 'Adresse eingebunden auf '.$seiten.' '.$wort;
		}
		elseif($row['aktiv'])
		{
			// Adresse aktiv, keine Einbindungen
			$icon = 'bundles/contaoadressen/images/gelb.png';
			$title = 'Adresse aktiv, aber nicht eingebunden';
		}
		elseif($row['links'])
		{
			// Adresse nicht aktiv, ein oder mehrere Einbindungen
			$icon = 'bundles/contaoadressen/images/gelb.png';
			$title = 'Adresse nicht aktiv, aber auf '.$seiten.' '.$wort.' eingebunden';
		}
		else
		{
			// Adresse nicht aktiv, keine Einbindungen
			$icon = 'bundles/contaoadressen/images/rot.png';
			$title = 'Adresse nicht aktiv und nicht eingebunden';
		}

		// Spalte 0 (aktiv) in Ausgabe überschreiben
		$args[0] = '<span href="" title="'.$title.'">'.Image::getHtml($icon,'').'</span>';

		// Modifizierte Zeile zurückgeben
		return $args;

	}

	/**
	 * Generiert automatisch ein Alias aus Vorname und Nachname
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize(\StringUtil::restoreBasicEntities($dc->activeRecord->nachname.'-'.$dc->activeRecord->vorname));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_kongresse WHERE alias=?")
								   ->execute($varValue);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	} 

	/**
	 * Return the link picker wizard
	 * @param \DataContainer
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		return ' <a href="contao/page.php?do='.Input::get('do').'&amp;table='.$dc->table.'&amp;field='.$dc->field.'&amp;value='.str_replace(array('{{link_url::', '}}'), '', $dc->value).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':768,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\'ctrl_'.$dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '').'\',\'self\':this});return false">' . Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}

	/**
	 * Trägt die Metdaten in "alt" und "caption" ein, wenn das Bild gewechselt wird und die Metafelder leer sind
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return mixed
	 */
	public function storeFileMetaInformation($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord->singleSRC != $varValue)
		{
			$objFile = \FilesModel::findByUuid($varValue);
			if ($objFile === null)
			{
				return $varValue;
			}

			$arrMeta = deserialize($objFile->meta);
			if (empty($arrMeta))
			{
				return $varValue;
			}

			$strLanguage = 'de';
			if (isset($arrMeta[$strLanguage]))
			{
				if (\Input::post('alt') == '' && !empty($arrMeta[$strLanguage]['title']))
				{
					\Input::setPost('alt', $arrMeta[$strLanguage]['title']);
				}

				if (\Input::post('caption') == '' && !empty($arrMeta[$strLanguage]['caption']))
				{
					\Input::setPost('caption', $arrMeta[$strLanguage]['caption']);
				}
			}

		}

		return $varValue;
	} 

}
