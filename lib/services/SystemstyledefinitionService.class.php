<?php
/**
 * richtext_SystemstyledefinitionService
 * @package modules.richtext
 */
class richtext_SystemstyledefinitionService extends richtext_StyledefinitionService
{
	/**
	 * @var richtext_SystemstyledefinitionService
	 */
	private static $instance;

	/**
	 * @return richtext_SystemstyledefinitionService
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
	 * @return richtext_persistentdocument_systemstyledefinition
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_richtext/systemstyledefinition');
	}

	/**
	 * Create a query based on 'modules_richtext/systemstyledefinition' model.
	 * Return document that are instance of modules_richtext/systemstyledefinition,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_richtext/systemstyledefinition');
	}
	
	/**
	 * Create a query based on 'modules_richtext/systemstyledefinition' model.
	 * Only documents that are strictly instance of modules_richtext/systemstyledefinition
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_richtext/systemstyledefinition', false);
	}
	
	/**
	 * Import definitions from richtext.xml. 
	 */
	public function importFromRichtextXml()
	{
		$buildPath = f_util_FileUtils::buildChangeBuildPath();
		
		$paths = FileResolver::getInstance()->setPackageName('modules_website')->setDirectory('config')->getPaths('richtext.xml');
		while (f_util_StringUtils::beginsWith(f_util_ArrayUtils::firstElement($paths), $buildPath))
		{
			array_shift($paths);
		}
		
		$path = f_util_ArrayUtils::firstElement($paths);
		if (!$path)
		{
			return;
		}
		
		$domDoc = new DOMDocument();
		if (!$domDoc->load($path))
		{
			Framework::error(__METHOD__ . ": $path is not a valid XML");
		}
		$styles = $domDoc->getElementsByTagName('style');
		foreach ($styles as $style)
		{
			$tagName = $style->getAttribute('tag');
			$tagClass = null;
			foreach ($style->getElementsByTagName('attribute') as $attrNode)
			{
				if ($attrNode->getAttribute('name') == 'class')
				{
					$tagClass = $attrNode->getAttribute('value');
				}
			}
			
			if (!$tagName || !$tagClass)
			{
				Framework::error(__METHOD__ . ": style has no 'tag' or 'class' attribute");
				continue;
			}
			
			$doc = $this->getByTagNameAndTagClass($tagName, $tagClass);
			if ($doc === null)
			{
				$doc = $this->getNewDocumentInstance();
				$doc->setTagName($tagName);
				$doc->setTagClass($tagClass);
			}
			elseif (!($doc instanceof richtext_persistentdocument_systemstyledefinition))
			{
				continue;
			}
			
			$isBlock = ($style->hasAttribute('block') && $style->getAttribute('block') == 'false') ? false : true; 
			$label = $style->hasAttribute('label') ? $style->getAttribute('label') : ($tagName . '.' . $tagClass);
			$doc->setIsBlock($isBlock);
			$doc->setLabel($label);	
			$doc->save(ModuleService::getInstance()->getSystemFolderId('richtext', 'website'));
		}
	}
	
	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
//	protected function preSave($document, $parentNodeId)
//	{
//		parent::preSave($document, $parentNodeId);
//
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function preInsert($document, $parentNodeId)
//	{
//		parent::preInsert($document, $parentNodeId);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postInsert($document, $parentNodeId)
//	{
//		parent::postInsert($document, $parentNodeId);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function preUpdate($document, $parentNodeId)
//	{
//		parent::preUpdate($document, $parentNodeId);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postUpdate($document, $parentNodeId)
//	{
//		parent::postUpdate($document, $parentNodeId);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postSave($document, $parentNodeId)
//	{
//		parent::postSave($document, $parentNodeId);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @return void
	 */
//	protected function preDelete($document)
//	{
//		parent::preDelete($document);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @return void
	 */
//	protected function preDeleteLocalized($document)
//	{
//		parent::preDeleteLocalized($document);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @return void
	 */
//	protected function postDelete($document)
//	{
//		parent::postDelete($document);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @return void
	 */
//	protected function postDeleteLocalized($document)
//	{
//		parent::postDeleteLocalized($document);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
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
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param String $oldPublicationStatus
	 * @param array<"cause" => String, "modifiedPropertyNames" => array, "oldPropertyValues" => array> $params
	 * @return void
	 */
//	protected function publicationStatusChanged($document, $oldPublicationStatus, $params)
//	{
//		parent::publicationStatusChanged($document, $oldPublicationStatus, $params);
//	}

	/**
	 * Correction document is available via $args['correction'].
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param Array<String=>mixed> $args
	 */
//	protected function onCorrectionActivated($document, $args)
//	{
//		parent::onCorrectionActivated($document, $args);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param String $tag
	 * @return void
	 */
//	public function tagAdded($document, $tag)
//	{
//		parent::tagAdded($document, $tag);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param String $tag
	 * @return void
	 */
//	public function tagRemoved($document, $tag)
//	{
//		parent::tagRemoved($document, $tag);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $fromDocument
	 * @param f_persistentdocument_PersistentDocument $toDocument
	 * @param String $tag
	 * @return void
	 */
//	public function tagMovedFrom($fromDocument, $toDocument, $tag)
//	{
//		parent::tagMovedFrom($fromDocument, $toDocument, $tag);
//	}

	/**
	 * @param f_persistentdocument_PersistentDocument $fromDocument
	 * @param richtext_persistentdocument_systemstyledefinition $toDocument
	 * @param String $tag
	 * @return void
	 */
//	public function tagMovedTo($fromDocument, $toDocument, $tag)
//	{
//		parent::tagMovedTo($fromDocument, $toDocument, $tag);
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
//		parent::onMoveToStart($document, $destId);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param Integer $destId
	 * @return void
	 */
//	protected function onDocumentMoved($document, $destId)
//	{
//		parent::onDocumentMoved($document, $destId);
//	}

	/**
	 * this method is call before saving the duplicate document.
	 * If this method not override in the document service, the document isn't duplicable.
	 * An IllegalOperationException is so launched.
	 *
	 * @param richtext_persistentdocument_systemstyledefinition $newDocument
	 * @param richtext_persistentdocument_systemstyledefinition $originalDocument
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
	 * @param richtext_persistentdocument_systemstyledefinition $newDocument
	 * @param richtext_persistentdocument_systemstyledefinition $originalDocument
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
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param string $lang
	 * @param array $parameters
	 * @return string
	 */
//	public function generateUrl($document, $lang, $parameters)
//	{
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @return integer | null
	 */
//	public function getWebsiteId($document)
//	{
//		return parent::getWebsiteId($document);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @return website_persistentdocument_page | null
	 */
//	public function getDisplayPage($document)
//	{
//		return parent::getDisplayPage($document);
//	}

	/**
	 * @param richtext_persistentdocument_systemstyledefinition $document
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
	 * @param richtext_persistentdocument_systemstyledefinition $document
	 * @param string $bockName
	 * @return array with entries 'module' and 'template'. 
	 */
//	public function getSolrserachResultItemTemplate($document, $bockName)
//	{
//		return array('module' => 'richtext', 'template' => 'Richtext-Inc-SystemstyledefinitionResultDetail');
//	}
}