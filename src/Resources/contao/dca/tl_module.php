<?php
/**
 * Avatar for Contao Open Source CMS
 *
 * Copyright (C) 2013 Kirsten Roschanski
 * Copyright (C) 2013 Tristan Lins <http://bit3.de>
 *
 * @package    Avatar
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Add palette to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'kongresse_select';
$GLOBALS['TL_DCA']['tl_module']['palettes']['kongresse'] = '{title_legend},name,type;{kongresse_legend},kongresse_from,kongresse_to,kongresse_select;{expert_legend:hide},cssID,align,space';
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
		'includeBlankOption'  => true,
		'multiple'            => true,
		'tl_class'            => 'long clr'
	),
	'sql'                     => 'blob NULL' 
); 

$GLOBALS['TL_DCA']['tl_module']['fields']['kongresse_select'] = array
(
	'exclude'                 => true,
	'filter'                  => true,
	'default'                 => false,
	'inputType'               => 'checkbox',
	'eval'                    => array
	(
		'submitOnChange'      => true,
		'tl_class'            => 'clr'
	),
	'sql'                     => "char(1) NOT NULL default ''"
);

