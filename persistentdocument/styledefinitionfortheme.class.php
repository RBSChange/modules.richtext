<?php
/**
 * Class where to put your custom methods for document richtext_persistentdocument_styledefinitionfortheme
 * @package modules.richtext.persistentdocument
 */
class richtext_persistentdocument_styledefinitionfortheme extends richtext_persistentdocument_styledefinitionforthemebase 
{
	/**
	 * @var array
	 */
	private $varsInfos;
	
	/**
	 * @return array
	 */
	private function getVarsInfos()
	{
		if ($this->varsInfos === null)
		{
			if ($this->getTheme())
			{
				$variablesPath = f_util_FileUtils::buildChangeBuildPath('themes', $this->getTheme()->getCodename(), 'variables.ser');
				if (!is_readable($variablesPath))
				{
					throw new Exception('theme no compiled properly: ' . $this->getCodename());
				}
				$this->varsInfos = unserialize(file_get_contents($variablesPath));
			}
			else
			{
				$this->varsInfos = array();
			}
		}
		return $this->varsInfos;
	}
}