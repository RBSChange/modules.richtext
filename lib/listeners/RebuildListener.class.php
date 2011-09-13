<?php
/**
 * @package modules.richtext.lib.listeners
 */
class richtext_RebuildListener
{
	public function onRebuildNode($sender, $params)
	{
		if (defined('NODE_NAME') && $params['method'] == 'rebuildFiles')
		{
			richtext_ModuleService::getInstance()->rebuildFiles();
		}
	}
}