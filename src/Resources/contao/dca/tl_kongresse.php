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
			'fields'                  => array('jahr'),
			'flag'                    => 12,
			'panelLayout'             => 'filter;sort,search,limit',
		),
		'label' => array
		(
			'fields'                  => array('jahr','ort','datum_von','datum_bis','typ'),
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
		'default'                     => '{congress_legend},typ,jahr,ort,datum_von,datum_bis,info;{files_legend},file_broschuere,file_protokoll,url,newWindow;{extra_legend},extra_links;{aktiv_legend},aktiv'
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
			'flag'                    => 5,
			'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard clr'),
			'sql'                     => "varchar(11) NOT NULL default ''"
		),
		'datum_bis' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kongresse']['datum_bis'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'flag'                    => 5,
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
				'tl_class'            => 'w50',
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

	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		$this->import('BackendUser', 'User');

		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 0));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_kongresse::aktiv', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];

		if (!$row['aktiv'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	public function toggleVisibility($intId, $blnPublished)
	{
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_kongresse::aktiv', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_kongresse toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_kongresse', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_kongresse']['fields']['aktiv']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_kongresse']['fields']['aktiv']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_kongresse SET tstamp=". time() .", aktiv='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
		     ->execute($intId);
		$this->createNewVersion('tl_kongresse', $intId);
	}

}
