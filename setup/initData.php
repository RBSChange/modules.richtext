<?php
/**
 * @package modules.richtext
 */
class richtext_Setup extends object_InitDataSetup
{
	public function install()
	{
		// $this->executeModuleScript('init.xml');
			
		richtext_SystemstyledefinitionService::getInstance()->importFromRichtextXml();
	}
}