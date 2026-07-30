<?php

/**
 * Tabelle tl_kongresse
 *
 * Die Lesezugriffe auf $GLOBALS['TL_LANG'] sind mit `?? null` abgesichert,
 * weil der DcaLoader die Sprachdateien noch nicht geladen hat, wenn die DCA
 * beispielsweise über contao:migrate eingelesen wird.
 */

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_kongresse'] = array
(

	// Konfiguration
	'config' => array
	(
		'dataContainer'               => DC_Table::class,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id'   => 'primary',
				'jahr' => 'index'
			)
		),
	),

	// Datensätze auflisten
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('datum_von', 'jahr'),
			'flag'                    => 12,
			'panelLayout'             => 'filter;sort,search,limit',
		),
		'label' => array
		(
			'fields'                  => array('jahr', 'ort', 'datum_von', 'datum_bis', 'typ', 'online'),
			'showColumns'             => true,
			'format'                  => '%s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'href'                => 'act=copy',
				'icon'                => 'copy.svg',
			),
			'delete' => array
			(
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
			),
			// Ein-/Ausblenden über den Contao-eigenen Toggler; dafür ist am Feld
			// "aktiv" zusätzlich 'toggle' => true gesetzt.
			'toggle' => array
			(
				'href'                => 'act=toggle&amp;field=aktiv',
				'icon'                => 'visible.svg'
			),
			'show' => array
			(
				'href'                => 'act=show',
				'icon'                => 'show.svg',
				'attributes'          => 'style="margin-right:3px"'
			),
		)
	),

	// Paletten
	'palettes' => array
	(
		'default'                     => '{congress_legend},typ,online,jahr,ort,datum_von,datum_bis,info;{files_legend},file_broschuere,file_protokoll,url,newWindow;{extra_legend},extra_links;{aktiv_legend},aktiv'
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
		'typ' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['typ'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => &$GLOBALS['TL_LANG']['tl_kongresse']['typen'],
			'eval'                    => array
			(
				'multiple'            => false,
				'tl_class'            => 'w50',
				'includeBlankOption'  => true
			),
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
		'online' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['online'],
			'exclude'                 => true,
			'filter'                  => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'mandatory'           => false,
				'tl_class'            => 'w50 m12',
				'isBoolean'           => true,
			),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'jahr' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['jahr'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 12,
			'filter'                  => true,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>4, 'tl_class'=>'w50 clr'),
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
		'datum_von' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['datum_von'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'flag'                    => 6,
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard clr'),
			'sql'                     => "varchar(11) NOT NULL default ''"
		),
		'datum_bis' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['datum_bis'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'flag'                    => 6,
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
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'long clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'file_broschuere' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['file_broschuere'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array
			(
				'filesOnly'           => true,
				'fieldType'           => 'radio',
				'mandatory'           => false,
				'tl_class'            => 'w50'
			),
			'sql'                     => "binary(16) NULL"
		),
		'file_protokoll' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['file_protokoll'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array
			(
				'filesOnly'           => true,
				'fieldType'           => 'radio',
				'mandatory'           => false,
				'tl_class'            => 'w50'
			),
			'sql'                     => "binary(16) NULL"
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'mandatory'           => false,
				'rgxp'                => 'url',
				'decodeEntities'      => true,
				'maxlength'           => 255,
				'dcaPicker'           => true,
				'addWizardClass'      => false,
				'tl_class'            => 'w50 clr'
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'newWindow' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['newWindow'],
			'exclude'                 => true,
			'filter'                  => true,
			'default'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'mandatory'           => false,
				'tl_class'            => 'w50 m12',
				'isBoolean'           => true,
			),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'extra_links' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['extra_links'],
			'exclude'                 => true,
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array
			(
				'tl_class'            => 'long clr',
				'buttonPos'           => 'middle',
				'buttons'             => array
				(
					'copy'            => 'system/themes/flexible/icons/copy.svg',
					'delete'          => 'system/themes/flexible/icons/delete.svg',
					'move'            => 'system/themes/flexible/icons/move.svg',
					'up'              => 'system/themes/flexible/icons/up.svg',
					'down'            => 'system/themes/flexible/icons/down.svg'
				),
				'columnFields'        => array
				(
					'url' => array
					(
						'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['extra_links_url'],
						'exclude'                 => true,
						'search'                  => true,
						'inputType'               => 'text',
						'eval'                    => array
						(
							'mandatory'           => false,
							'rgxp'                => 'url',
							'decodeEntities'      => true,
							'maxlength'           => 255,
							'dcaPicker'           => true,
							'addWizardClass'      => false,
							'style'               => 'width:90%;'
						),
					),
					'text' => array
					(
						'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['extra_links_text'],
						'exclude'                 => true,
						'inputType'               => 'text',
						'eval'                    => array
						(
							'maxlength'           => 255,
							'style'               => 'width:90%;'
						),
					),
					'newWindow' => array
					(
						'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['extra_links_newWindow'],
						'exclude'                 => true,
						'default'                 => true,
						'inputType'               => 'checkbox',
						'eval'                    => array
						(
							'mandatory'           => false,
							'isBoolean'           => true,
						),
					),
				)
			),
			'sql'                     => "blob NULL"
		),
		// Der Vorgabewert '1' dient zugleich als Merkmal für die Migration
		// AktivDefaultMigration, die beim Update auf 2.0.0 die vorhandenen
		// Datensätze einmalig veröffentlicht.
		'aktiv' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['aktiv'],
			'exclude'                 => true,
			'filter'                  => true,
			'default'                 => '1',
			'inputType'               => 'checkbox',
			'toggle'                  => true,
			'eval'                    => array('doNotCopy'=>true, 'isBoolean'=>true),
			'sql'                     => "char(1) NOT NULL default '1'"
		),
	)
);
