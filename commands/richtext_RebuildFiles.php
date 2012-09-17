<?php
/**
 * commands_richtext_RebuildFiles
 * @package modules.richtext.command
 */
class commands_richtext_RebuildFiles extends c_ChangescriptCommand
{
	/**
	 * @return string
	 * For exemple "<moduleName> <name>"
	 */
	public function getUsage()
	{
		return "";
	}

	/**
	 * @return string
	 * For exemple "initialize a document"
	 */
	public function getDescription()
	{
		return "rebuild xml and css files";
	}
	
	/**
	 * @see c_ChangescriptCommand::getEvents()
	 */
	public function getEvents()
	{
		return array(
			array('target' => 'compile-all'),
		);
	}

	/**
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	public function _execute($params, $options)
	{
		$this->message("== Rebuild xml and css files for richtext ==");

		$this->loadFramework();
		richtext_ModuleService::getInstance()->rebuildFiles();

		$this->quitOk("Command successfully executed");
	}
}