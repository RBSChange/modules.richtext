<?php
/**
 * richtext_patch_0400
 * @package modules.richtext
 */
class richtext_patch_0400 extends change_Patch
{
	/**
	 * @return array
	 */
	public function getPreCommandList()
	{
		return array(
			array('disable-site'),
		);
	}
	
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		// Clean built files.
		$path = f_util_FileUtils::buildChangeBuildPath('modules', 'generic', 'config', 'richtext.xml');
		if (file_exists($path))
		{
			f_util_FileUtils::unlink($path);
		}

		// Clean override/modules/uixul/style/cRichtextField.css.
		$path = f_util_FileUtils::buildOverridePath('modules', 'uixul', 'style', 'cRichtextField.css');
		if ($path !== null && file_exists($path))
		{
			$originalContent = file_get_contents($path);
			$toDelete = '@import url(/modules/richtext/style/richtext.css);';
			if (strpos($originalContent, $toDelete) !== false)
			{
				f_util_FileUtils::writeAndCreateContainer($path, str_replace($toDelete, '', $originalContent), f_util_FileUtils::OVERRIDE);
			}
		}
		
		// Fix labels of stystem style definitions.
		richtext_SystemstyledefinitionService::getInstance()->importFromRichtextXml();
	}
	
	/**
	 * @return array
	 */
	public function getPostCommandList()
	{
		return array(
			array('clear-documentscache'),
			array('enable-site'),
		);
	}
	
	/**
	 * @return string
	 */
	public function getExecutionOrderKey()
	{
		return '2011-09-23 09:26:06';
	}
		
	/**
	 * @return string
	 */
	public function getBasePath()
	{
		return dirname(__FILE__);
	}
	
    /**
     * @return false
     */
	public function isCodePatch()
	{
		return false;
	}
}