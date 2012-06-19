<?php
/**
 * richtext_StyledefinitionScriptDocumentElement
 * @package modules.richtext.persistentdocument.import
 */
class richtext_StyledefinitionScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return richtext_persistentdocument_styledefinition
	 */
	protected function initPersistentDocument()
	{
		return richtext_StyledefinitionService::getInstance()->getNewDocumentInstance();
	}
	
	/**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_richtext/styledefinition');
	}
}