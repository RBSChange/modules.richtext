<?php
/**
 * commands_richtext_RebuildFiles
 * @package modules.richtext.command
 */
class commands_richtext_RebuildFiles extends commands_AbstractChangeCommand
{
	/**
	 * @return String
	 * @example "<moduleName> <name>"
	 */
	public function getUsage()
	{
		return "";
	}

	/**
	 * @return String
	 * @example "initialize a document"
	 */
	public function getDescription()
	{
		return "rebuild xml and css files";
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	public function _execute($params, $options)
	{
		$this->message("== RebuildFiles ==");

		richtext_ModuleService::getInstance()->rebuildFiles();

		$this->quitOk("Command successfully executed");
	}
}