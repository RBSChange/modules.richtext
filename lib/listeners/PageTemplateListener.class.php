<?php
/**
 * @package modules.richtext.lib.listeners
 */
class richtext_PageTemplateListener
{
	public function onPersistentDocumentUpdated($sender, $params)
	{
		$document = $params["document"];
		if ($document instanceof theme_persistentdocument_pagetemplate && !($document instanceof theme_persistentdocument_pagetemplatedeclination))
		{
			richtext_ModuleService::getInstance()->applyToPageTemplate($document);
		}
	}
}