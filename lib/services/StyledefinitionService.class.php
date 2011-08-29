<?php
/**
 * richtext_StyledefinitionService
 * @package modules.richtext
 */
class richtext_StyledefinitionService extends f_persistentdocument_DocumentService
{
	/**
	 * @var richtext_StyledefinitionService
	 */
	private static $instance;

	/**
	 * @return richtext_StyledefinitionService
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
	 * @return richtext_persistentdocument_styledefinition
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_richtext/styledefinition');
	}

	/**
	 * Create a query based on 'modules_richtext/styledefinition' model.
	 * Return document that are instance of modules_richtext/styledefinition,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_richtext/styledefinition');
	}
	
	/**
	 * Create a query based on 'modules_richtext/styledefinition' model.
	 * Only documents that are strictly instance of modules_richtext/styledefinition
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_richtext/styledefinition', false);
	}
	
	/**
	 * @param string $tagName
	 * @param string $tagClass
	 */
	public function getByTagNameAndTagClass($tagName, $tagClass)
	{
		// As if this method is called from a sub class, the query should always be created on richtext_StyledefinitionService.
		$service = richtext_StyledefinitionService::getInstance();
		return $service->createQuery()->add(Restrictions::eq('tagName', $tagName))->add(Restrictions::eq('tagClass', $tagClass))->findUnique();
	}
		
	/**
	 * @param XMLWriter $output
	 * @param f_persistentdocument_PersistentDocument $document
	 */
	private function writeDocument($output, $document)
	{
		$model = $document->getPersistentModel();
		$output->startElement('nodeitem');
		$output->writeAttribute('id', $document->getId());
		$output->writeAttribute('label', $document->getTreeNodeLabel());
		$output->writeAttribute('model', str_replace('/', '_', $model->getName()));
		$output->endElement();
	}
	
	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		// Check tag and class conflicts.
		$tagName = $document->getTagName();
		$tagClass = $document->getTagClass();
		$styledef = $this->getByTagNameAndTagClass($tagName, $document->getTagClass());
		if ($styledef !== null && $styledef->getId() !== $document->getId())
		{
			throw new BaseException('There is already a definition for these tag name and class', 'modules.richtext.bo.general.tagname-and-class-conflict', array('tagName' => $tagName, 'tagClass' => $tagClass));
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
	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function preInsert($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postInsert($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function preUpdate($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postUpdate($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postSave($document, $parentNodeId)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @return void
	 */
//	protected function preDelete($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @return void
	 */
//	protected function preDeleteLocalized($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @return void
	 */
//	protected function postDelete($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @return void
	 */
//	protected function postDeleteLocalized($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
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
	 * @param richtext_persistentdocument_styledefinition $document
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
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param String $tag
	 * @return void
	 */
//	public function tagAdded($document, $tag)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param String $tag
	 * @return void
	 */
//	public function tagRemoved($document, $tag)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $fromDocument
	 * @param f_persistentdocument_PersistentDocument $toDocument
	 * @param String $tag
	 * @return void
	 */
//	public function tagMovedFrom($fromDocument, $toDocument, $tag)
//	{
//	}

	/**
	 * @param f_persistentdocument_PersistentDocument $fromDocument
	 * @param richtext_persistentdocument_styledefinition $toDocument
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
	 * @param richtext_persistentdocument_styledefinition $document
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
	 * @param richtext_persistentdocument_styledefinition $newDocument
	 * @param richtext_persistentdocument_styledefinition $originalDocument
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
	 * @param richtext_persistentdocument_styledefinition $newDocument
	 * @param richtext_persistentdocument_styledefinition $originalDocument
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
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param string $lang
	 * @param array $parameters
	 * @return string
	 */
//	public function generateUrl($document, $lang, $parameters)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @return integer | null
	 */
//	public function getWebsiteId($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @return website_persistentdocument_page | null
	 */
//	public function getDisplayPage($document)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{
		$resume = parent::getResume($document, $forModuleName, $allowedSections);
		
		$resume['properties']['tagType'] = $document->getTagTypeUILabel();
		$resume['properties']['tagName'] = $document->getTagName();
		$resume['properties']['tagClass'] = $document->getTagClass();
		
		return $resume;
	}

	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param string $bockName
	 * @return array with entries 'module' and 'template'. 
	 */
//	public function getSolrserachResultItemTemplate($document, $bockName)
//	{
//		return array('module' => 'richtext', 'template' => 'Richtext-Inc-StyledefinitionResultDetail');
//	}
}