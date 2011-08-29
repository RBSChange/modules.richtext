<?php
/**
 * Class where to put your custom methods for document richtext_persistentdocument_systemstyledefinition
 * @package modules.richtext.persistentdocument
 */
class richtext_persistentdocument_systemstyledefinition extends richtext_persistentdocument_systemstyledefinitionbase 
{
	/**
	 * @return string
	 */
	public function getLabel()
	{
		$label = parent::getLabel();
		if (f_Locale::isLocaleKey($label))
		{
			return f_Locale::translateUI($label);
		}
		return $label;
	}
	
	/**
	 * @return string
	 */
	public function getLabelForBlocksXml()
	{
		return str_replace('&modules.', '&ampmodules.', parent::getLabel());
	}
}