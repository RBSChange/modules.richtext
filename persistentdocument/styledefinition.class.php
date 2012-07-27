<?php
/**
 * Class where to put your custom methods for document richtext_persistentdocument_styledefinition
 * @package modules.richtext.persistentdocument
 */
class richtext_persistentdocument_styledefinition extends richtext_persistentdocument_styledefinitionbase 
{
	/**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
		$nodeAttributes['tagType'] = $this->getTagTypeUILabel();
	}

	/**
	 * @return string
	 */
	public function getTagTypeUILabel()
	{
		return f_Locale::translateUI('&modules.richtext.document.styledefinition.Type-' . ($this->getIsBlock() ? 'block' : 'inline') . ';');
	}
	
	/**
	 * @return string
	 */
	public function getCSSSelector()
	{
		return $this->getTagName() . '.' . $this->getTagClass();
	}
	
	/**
	 * @return string
	 */
	public function getLabelForBlocksXml()
	{
		return $this->getLabel();
	}
}