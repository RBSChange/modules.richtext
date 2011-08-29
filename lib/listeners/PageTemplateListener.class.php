<?php
/**
 * @package modules.richtext.lib.listeners
 */
class richtext_PageTemplateListener
{
	public function onPersistentDocumentUpdated($sender, $params)
	{
		$document = $params["document"];
		if ($document instanceof theme_persistentdocument_pagetemplate)
		{
			richtext_ModuleService::getInstance()->applyToPageTemplate($document);
		}
	}
}