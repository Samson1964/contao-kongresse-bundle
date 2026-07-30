<?php

/**
 * Erweiterung von tl_module um die Einstellungen des Frontend-Moduls
 * "Kongresse & Ausschüsse".
 *
 * Die Felder align und space aus der ursprünglichen Palette sind entfallen,
 * weil es sie seit Contao 4 nicht mehr gibt. Stattdessen stehen jetzt die
 * üblichen Felder headline und customTpl zur Verfügung.
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'kongresse_select';
$GLOBALS['TL_DCA']['tl_module']['palettes']['kongresse'] = '{title_legend},name,headline,type;{kongresse_legend},kongresse_from,kongresse_to,kongresse_select;{template_legend:hide},customTpl;{expert_legend:hide},cssID';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['kongresse_select'] = 'kongresse_typ';

$GLOBALS['TL_DCA']['tl_module']['fields']['kongresse_from'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kongresse_from'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>false, 'rgxp'=>'digit', 'tl_class'=>'w50', 'maxlength'=>4),
	'sql'                     => "varchar(4) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['kongresse_to'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kongresse_to'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>false, 'rgxp'=>'digit', 'tl_class'=>'w50', 'maxlength'=>4),
	'sql'                     => "varchar(4) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['kongresse_typ'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kongresse_typ'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'options'                 => &$GLOBALS['TL_LANG']['tl_module']['kongresse_typen'],
	'eval'                    => array
	(
		'mandatory'           => false,
		'multiple'            => true,
		'tl_class'            => 'long clr'
	),
	'sql'                     => 'blob NULL'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['kongresse_select'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kongresse_select'],
	'exclude'                 => true,
	'filter'                  => true,
	'default'                 => false,
	'inputType'               => 'checkbox',
	'eval'                    => array
	(
		'submitOnChange'      => true,
		'isBoolean'           => true,
		'tl_class'            => 'clr'
	),
	'sql'                     => "char(1) NOT NULL default ''"
);
