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
			$tagClass = $style->getAttribute('class');
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
			
			$isBlock = ($style->getAttribute('block') == 'false') ? false : true; 
			$label = $style->hasAttribute('labeli18n') ? $style->getAttribute('labeli18n') : $style->getAttribute('label');
			$doc->setIsBlock($isBlock);
			$doc->setLabel($label);	
			$doc->save(ModuleService::getInstance()->getSystemFolderId('richtext', 'website'));
		}
	}
}