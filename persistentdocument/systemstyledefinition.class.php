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
		$ls = LocaleService::getInstance();
		$label = parent::getLabel();
		if ($ls->isKey($label))
		{
			return $ls->trans($label);
		}
		return $label;
	}
}