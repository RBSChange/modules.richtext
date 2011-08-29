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

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postInsert($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function preUpdate($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postUpdate($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postSave($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @return void
	 */
//	protected function preDelete($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @return void
	 */
//	protected function preDeleteLocalized($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @return void
	 */
//	protected function postDelete($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @return void
	 */
//	protected function postDeleteLocalized($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @return boolean true if the document is publishable, false if it is not.
	 */
//	public function isPublishable($document)
//	{
//		$result = parent::isPublishable($document);
//		return $result;
//	}


	/**
	 * Methode à surcharger pour effectuer des post traitement apres le changement de status du document
	 * utiliser $document->getPublicationstatus() pour retrouver le nouveau status du document.
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param String $oldPublicationStatus
	 * @param array<"cause" => String, "modifiedPropertyNames" => array, "oldPropertyValues" => array> $params
	 * @return void
	 */
//	protected function publicationStatusChanged($document, $oldPublicationStatus, $params)
//	{
//	}

	/**
	 * Correction document is available via $args['correction'].
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param Array<String=>mixed> $args
	 */
//	protected function onCorrectionActivated($document, $args)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param String $tag
	 * @return void
	 */
//	public function tagAdded($document, $tag)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param String $tag
	 * @return void
	 */
//	public function tagRemoved($document, $tag)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $fromDocument
	 * @param f_persistentdocument_PersistentDocument $toDocument
	 * @param String $tag
	 * @return void
	 */
//	public function tagMovedFrom($fromDocument, $toDocument, $tag)
//	{
//	}

	/**
	 * @param f_persistentdocument_PersistentDocument $fromDocument
	 * @param richtext_persistentdocument_styledefinitionfortheme $toDocument
	 * @param String $tag
	 * @return void
	 */
//	public function tagMovedTo($fromDocument, $toDocument, $tag)
//	{
//	}

	/**
	 * Called before the moveToOperation starts. The method is executed INSIDE a
	 * transaction.
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param Integer $destId
	 */
//	protected function onMoveToStart($document, $destId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param Integer $destId
	 * @return void
	 */
//	protected function onDocumentMoved($document, $destId)
//	{
//	}

	/**
	 * this method is call before saving the duplicate document.
	 * If this method not override in the document service, the document isn't duplicable.
	 * An IllegalOperationException is so launched.
	 *
	 * @param richtext_persistentdocument_styledefinitionfortheme $newDocument
	 * @param richtext_persistentdocument_styledefinitionfortheme $originalDocument
	 * @param Integer $parentNodeId
	 *
	 * @throws IllegalOperationException
	 */
//	protected function preDuplicate($newDocument, $originalDocument, $parentNodeId)
//	{
//		throw new IllegalOperationException('This document cannot be duplicated.');
//	}

	/**
	 * this method is call after saving the duplicate document.
	 * $newDocument has an id affected.
	 * Traitment of the children of $originalDocument.
	 *
	 * @param richtext_persistentdocument_styledefinitionfortheme $newDocument
	 * @param richtext_persistentdocument_styledefinitionfortheme $originalDocument
	 * @param Integer $parentNodeId
	 *
	 * @throws IllegalOperationException
	 */
//	protected function postDuplicate($newDocument, $originalDocument, $parentNodeId)
//	{
//	}

	/**
	 * Returns the URL of the document if has no URL Rewriting rule.
	 *
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param string $lang
	 * @param array $parameters
	 * @return string
	 */
//	public function generateUrl($document, $lang, $parameters)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @return integer | null
	 */
//	public function getWebsiteId($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @return website_persistentdocument_page | null
	 */
//	public function getDisplayPage($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
//	public function getResume($document, $forModuleName, $allowedSections = null)
//	{
//		$resume = parent::getResume($document, $forModuleName, $allowedSections);
//		return $resume;
//	}

	/**
	 * @param richtext_persistentdocument_styledefinitionfortheme $document
	 * @param string $bockName
	 * @return array with entries 'module' and 'template'. 
	 */
//	public function getSolrserachResultItemTemplate($document, $bockName)
//	{
//		return array('module' => 'richtext', 'template' => 'Richtext-Inc-StyledefinitionforthemeResultDetail');
//	}
}