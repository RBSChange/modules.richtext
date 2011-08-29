<?php
/**
 * richtext_StyledefinitionforthemeService
 * @package modules.richtext
 */
class richtext_StyledefinitionforthemeService extends f_persistentdocument_DocumentService
{
	/**
	 * @var richtext_StyledefinitionforthemeService
	 */
	private static $instance;

	/**
	 * @return richtext_StyledefinitionforthemeService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return richtext_persistentdocument_styledefinitionfortheme
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_richtext/styledefinitionfortheme');
	}

	/**
	 * Create a query based on 'modules_richtext/styledefinitionfortheme' model.
	 * Return document that are instance of modules_richtext/styledefinitionfortheme,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_richtext/styledefinitionfortheme');
	}
	
	/**
	 * Create a query based on 'modules_richtext/styledefinitionfortheme' model.
	 * Only documents that are strictly instance of modules_richtext/styledefinitionfortheme
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_richtext/styledefinitionfortheme', false);
	}
	
	/**
	 * @param richtext_persistentdocument_styledefinition $definition
	 * @return richtext_persistentdocument_styledefinitionfortheme[]
	 */
	public function getByDefinition($definition)
	{
		return $this->createQuery()->add(Restrictions::eq('definition', $definition))->find();
	}
	
	/**
	 * @param theme_persistentdocument_theme $theme
	 * @return richtext_persistentdocument_styledefinitionfortheme[]
	 */
	public function getByTheme($theme)
	{
		return $this->createQuery()->add(Restrictions::eq('theme', $theme))->find();
	}
	
	/**
	 * @param richtext_persistentdocument_styledefinition $definition
	 * @return richtext_persistentdocument_styledefinitionfortheme[]
	 */
	public function getByThemeAndDefinition($theme, $definition)
	{
		return $this->createQuery()->add(Restrictions::eq('definition', $definition))->add(Restrictions::eq('theme', $theme))->findUnique();
	}
	
	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		// If there is no definition, set it with the parentNodeId.
		$definition = $document->getDefinition();
		if ($definition === null && $parentNodeId !== null)
		{
			$definition = richtext_persistentdocument_styledefinition::getInstanceById($parentNodeId);
			$document->setDefinition($definition);
		}
		
		// Check theme conflicts.
		$theme = $document->getTheme();
		$styledef = $this->getByThemeAndDefinition($theme, $definition);
		if ($styledef !== null && $styledef->getId() !== $document->getId())
		{
			throw new BaseException('There is already a specilisation for these definition and theme', 'modules.richtext.bo.general.defintion-and-theme-conflict', array('defintion' => $definition->getLabel(), 'theme' => $theme->getLabel()));
		}
		
		// Check CSS.
		try 
		{
			$sheet = new f_web_CSSStylesheet();
			$sheet->loadCSS($document->getCss());
		}
		catch (Exception $e)
		{
			throw new BaseException('CSS code is invalid: ' . $e->getMessage(), 'modules.richtext.bo.general.invalid-css', array('message' => $e->getMessage()));
		}
		
		// Generate label.
		$document->setLabel($definition->getLabel() . '/' . $theme->getLabel());
	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
	protected function preInsert($document, $parentNodeId)
	{
		$document->setInsertInTree(false);
	}
}