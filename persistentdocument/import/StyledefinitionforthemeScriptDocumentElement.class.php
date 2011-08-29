<?php
/**
 * richtext_StyledefinitionforthemeScriptDocumentElement
 * @package modules.richtext.persistentdocument.import
 */
class richtext_StyledefinitionforthemeScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return richtext_persistentdocument_styledefinitionfortheme
     */
    protected function initPersistentDocument()
    {
    	return richtext_StyledefinitionforthemeService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_richtext/styledefinitionfortheme');
	}
}