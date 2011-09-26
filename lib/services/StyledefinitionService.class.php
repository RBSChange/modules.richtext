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
	 * @param string $tagName
	 * @return boolean
	 */
	protected function isTagNameAllowed($tagName)
	{
		return !in_array($tagName, array('div'));
	}
	
	/**
	 * @param richtext_persistentdocument_styledefinition $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		// Check forbidden tag names.
		$tagName = $document->getTagName();
		if (!$this->isTagNameAllowed($tagName))
		{
			throw new BaseException('This tag name is forbidden', 'modules.richtext.bo.general.forbidden-tagname', array('tagName' => $tagName));
		}
		
		// Check tag and class conflicts.
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
}