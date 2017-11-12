<?php
 
 /**
 * Contao Open Source CMS - ContentBlocks extension
 *
 * Copyright (c) 2016 Arne Stappen (aGoat)
 *
 *
 * @package   contentblocks
 * @author    Arne Stappen <http://agoat.de>
 * @license	  LGPL-3.0+
 */


 
/**
 * Table tl_elements
 */
$GLOBALS['TL_DCA']['tl_elements'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'ptable'                      => 'tl_theme',
		'ctable'                      => array('tl_pattern'),
		'onload_callback' => array
		(
			//array('tl_elements', 'checkPermission'),
			array('tl_elements', 'showAlreadyUsedHint')
		),
		'onsubmit_callback'			  => array
		(
			array('tl_elements', 'generateAlias')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'alias' => 'index',
				'pid,invisible,sorting' => 'index'				
			)
		)
	),
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'headerFields'            => array('name', 'author', 'tstamp'),
			'panelLayout'             => 'filter;search,limit',
			'child_record_callback'   => array('tl_elements', 'listElements')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_elements']['edit'],
				'href'                => 'table=tl_pattern',
				'icon'                => 'edit.svg',
				'button_callback'     => array('tl_elements', 'elementButtons')
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elements']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.svg',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elements']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg',
				'button_callback'     => array('tl_elements', 'elementButtons')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elements']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elements']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_elements', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_elements']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
		'__selector__'			=> array('type'),
		'default'				=> '{type_legend},type',
		'group'					=> '{type_legend},type;{group_legend},title',
		'element'				=> '{type_legend},type;{element_legend},title,description,singleSRC;{template_legend},template;{default_legend},defaultType;{invisible_legend},invisible'
	),
	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager', 'table'=>'tl_theme'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elements']['type'],
			'default'                 => 'element',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options'       	 	 => array('group', 'element'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_elements_type'],
			'eval'                    => array('chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elements']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_elements', 'checkTitle')
			),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'alias' => array
		(
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "varchar(64) COLLATE utf8_bin NOT NULL default ''",
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elements']['description'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>256, 'tl_class'=>'long clr'),
			'sql'                     => "varchar(256) NOT NULL default ''"
		),
		'template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elements']['template'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'default'				  => 'ce_simple',
			'flag'                    => 11,
			'options_callback'        => array('tl_elements', 'getContentElementTemplates'),
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elements']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'extensions'=>'jpg,png,gif', 'tl_class'=>'w50 clr'),
			'sql'                     => "binary(16) NULL"
		),
		'defaultType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elements']['defaultType'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'save_callback'   		  => array(array('tl_elements', 'setDefaultType')),
			'sql'                     => "char(1) NOT NULL default '0'"
		),
		'invisible' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_elements']['invisible'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);



/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Arne Stappen (aGoat) <https://github.com/agoat>
 */
class tl_elements extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Add the type of content pattern
	 *
	 * @param array $arrRow
	 *
	 * @return string
	 */
	public function listElements($arrRow)
	{
		switch ($arrRow['type'])
		{
			case 'group':
				return '<div class="cte_type ctb_group">' . $arrRow['title'] . '</div>';

			case 'element':
				$key = $arrRow['invisible'] ? 'unpublished' : 'published';
				$strDefault = ($arrRow['defaultType']) ? '<span style="color:#b3b3b3;padding-left:8px">(Standard element)</span>' : '';
				if ($arrRow['singleSRC'])
				{
					$objImg = \FilesModel::findByUuid($arrRow['singleSRC']);
					$strBackground = ' url(' . $objImg->path . ')';
				}
	
				return '<div class="cte_type ctb_element ' . $key . '" style="position: relative; margin: 0; padding: 0 0 0 80px; line-height: 41px; pointer-events: none;"><div class="cte_img" style="position: absolute; left: 6px; top: 0px; width: 60px; height: 40px; border: 1px solid #ccc;  background: #eee' . $strBackground .  ';background-size: 60px 40px;"></div>' . $arrRow['title'] . $strDefault . '</div>';
		}
	}
	

	public function setDefaultType ($varValue, DataContainer $dc)
	{
		$db = Database::getInstance();
		
		if ($varValue)
		{
			// there can be only one default element
			$db->prepare("UPDATE tl_elements SET defaultType='' WHERE NOT id=? AND pid=?")
			   ->execute($dc->activeRecord->id, $dc->activeRecord->pid);
		}
		
		return $varValue;
	}

	
	public function checkTitle ($varValue, DataContainer $dc)
	{
		$db = Database::getInstance();

		$objTitle = $db->prepare("SELECT id FROM tl_elements WHERE NOT id=? AND pid=? AND title=?")
					   ->execute($dc->activeRecord->id, $dc->activeRecord->pid, $varValue);

		if ($objTitle->numRows > 0)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}
		
		return $varValue;
	}
	
	
	public function generateAlias (DataContainer $dc)
	{
		$db = Database::getInstance();

		// Generate alias from theme name and title
		$alias = \StringUtil::generateAlias(\ThemeModel::findById($dc->activeRecord->pid)->name . '-' . $dc->activeRecord->title);
		
		if ($alias != $dc->activeRecord->alias)
		{
			// Save alias to database
			$db->prepare("UPDATE tl_elements SET alias=? WHERE id=?")
			   ->execute($alias, $dc->activeRecord->id);
		
			if ($dc->activeRecord->alias)
			{
				// Also change the type (=alias) in tl_content table
				$db->prepare("UPDATE tl_content SET type=? WHERE type=?")
				   ->execute($alias, $dc->activeRecord->alias);
			}
		}
	}

	
	/**
	 * Return all content block templates as array
	 *
	 * @return array
	 */
	public function getContentElementTemplates(DataContainer $dc)
	{
		$arrTemplates = array();

		// Get the default templates
		foreach (\TemplateLoader::getPrefixedFiles('ce_') as $strTemplate)
		{
			$arrTemplates[$strTemplate][] = 'root';
		}

		$arrCustomized = glob(TL_ROOT . '/templates/ce_*');

		// Add the customized templates
		if (is_array($arrCustomized))
		{
			foreach ($arrCustomized as $strFile)
			{
				$strTemplate = basename($strFile, strrchr($strFile, '.'));
				$arrTemplates[$strTemplate][] = $GLOBALS['TL_LANG']['MSC']['global'];
			}
		}

		// Add the customized theme templates
		$theme = \ThemeModel::findById($dc->activeRecord->pid);
		
		$arrCustomized = glob(TL_ROOT . '/' . $theme->templates . '/' . 'ce_*');
		if (is_array($arrCustomized))
		{
			foreach ($arrCustomized as $strFile)
			{
				$strTemplate = basename($strFile, strrchr($strFile, '.'));
				$arrTemplates[$strTemplate][] = $theme->name;
			}
		}

		// Show the template sources
		foreach ($arrTemplates as $k=>$v)
		{
			$v = array_filter($v, function($a) {
				return $a != 'root';
			});
			if (empty($v))
			{
				$arrTemplates[$k] = $k;
			}
			else
			{
				$arrTemplates[$k] = $k . ' (' . implode(', ', $v) . ')';
			}
		}

		// Sort the template names
		ksort($arrTemplates);
		
		return $arrTemplates;
	}


	/**
	 * Show a hint if the content block is already in use
	 */
	public function showAlreadyUsedHint(DataContainer $dc)
	{
		if ($_POST || \Input::get('act') != 'edit')
		{
			return;
		}

		// Return if the user cannot access the layout module (see #6190)
		if (!$this->User->hasAccess('themes', 'modules') || !$this->User->hasAccess('layout', 'themes'))
		{
			return;
		}

		// Check if the content block is in use
		$objElement = \ElementsModel::findById($dc->id);
		
		if (\ContentModel::countBy('type', $objElement->alias) > 0)
		{
			\Message::addInfo($GLOBALS['TL_LANG']['MSC']['elementInUse']);
		}
	}

    
	/**
	 * Return the pattern edit button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function elementButtons($row, $href, $label, $title, $icon, $attributes)
	{
		switch ($row['type'])
		{
			case 'group':
				return '';
			
			case 'element':
				return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.\StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ';
		}
		
	}	

	
	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if ($row['type'] == 'group')
		{
			return '';
		}

		if (strlen(\Input::get('tid')))
		{
			$this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}
		
		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_elements::invisible', 'alexf'))
		{
			return '';
		}
		
		$href .= '&amp;id='.\Input::get('id').'&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];
		
		if ($row['invisible'])
		{
			$icon = 'invisible.svg';
		}
		
		return '<a href="'.$this->addToUrl($href).'" title="'.\StringUtil::specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label, 'data-state="' . ($row['invisible'] ? 0 : 1) . '"').'</a> ';
	}	

	
	/**
	 * Toggle the visibility of an element
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		$db = Database::getInstance();
		
		// Set the ID and action
		\Input::setGet('id', $intId);
		\Input::setGet('act', 'toggle');
		
		if ($dc)
		{
			$dc->id = $intId; // see #8043
		}
		
		if (!$this->User->isAdmin)
		{
			return;
		}		
		
		// Check the field access
		if (!$this->User->hasAccess('tl_elements::invisible', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish content block element ID "'.$intId.'"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	
		// The onload_callbacks vary depending on the dynamic parent table (see #4894)
		if (is_array($GLOBALS['TL_DCA']['tl_elements']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_elements']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}(($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$callback(($dc ?: $this));
				}
			}
		}
		
		// Check permissions to publish
		if (!$this->User->hasAccess('tl_elements::invisible', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide content block element ID "'.$intId.'"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		
		$objVersions = new Versions('tl_elements', $intId);
		$objVersions->initialize();
		
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_elements']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_elements']['fields']['invisible']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}
		
		// Update the database
		$db->prepare("UPDATE tl_elements SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
		   ->execute($intId);
					   
		$objVersions->create();
		$this->log('A new version of record "tl_elements.id='.$intId.'" has been created'.$this->getParentEntries('tl_elements', $intId), __METHOD__, TL_GENERAL);
	}	
}
