<?php
/**
 * richtext_SystemstyledefinitionScriptDocumentElement
 * @package modules.richtext.persistentdocument.import
 */
class richtext_SystemstyledefinitionScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return richtext_persistentdocument_systemstyledefinition
     */
    protected function initPersistentDocument()
    {
    	return richtext_SystemstyledefinitionService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_richtext/systemstyledefinition');
	}
}