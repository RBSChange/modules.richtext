<?php
/**
 * Class where to put your custom methods for document richtext_persistentdocument_styledefinition
 * @package modules.richtext.persistentdocument
 */
class richtext_persistentdocument_styledefinition extends richtext_persistentdocument_styledefinitionbase 
{
	/**
	 * @return string
	 */
	public function getTagTypeUILabel()
	{
		return LocaleService::getInstance()->transBO('m.richtext.document.styledefinition.type-' . ($this->getIsBlock() ? 'block' : 'inline'), array('ucf'));
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