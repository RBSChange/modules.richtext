<?php
/**
 * Class where to put your custom methods for document richtext_persistentdocument_styledefinitionfortheme
 * @package modules.richtext.persistentdocument
 */
class richtext_persistentdocument_styledefinitionfortheme extends richtext_persistentdocument_styledefinitionforthemebase 
{

	/**
	 * @param string $actionType
	 * @param array $formProperties
	 */
	public function addFormProperties($propertiesNames, &$formProperties)
	{	
		// In case of a new doc, set theme and definition to be able to get substitutions.
		if ($this->isNew())
		{
			$globalRequest = HttpController::getInstance()->getContext()->getRequest();
			if ($this->getTheme() === null && $globalRequest->hasParameter('themeId'))
			{
				$this->setTheme(theme_persistentdocument_theme::getInstanceById($globalRequest->getParameter('themeId')));
			}
			if ($this->getDefinition() === null && $globalRequest->hasParameter('definitionId'))
			{
				$this->setDefinition(richtext_persistentdocument_styledefinition::getInstanceById($globalRequest->getParameter('definitionId')));
			}
		}
		
		$definition = $this->getDefinition();
		$jsonSkinVars = array();
		$jsonSkinVars[] = array('value' => $definition->getCSSSelector(), 'label' => f_Locale::translateUI('&modules.richtext.document.styledefinition.Css-selector;'));
		if ($this->getTheme())
		{
			$themeName = $this->getTheme()->getCodename();
			foreach ($this->getVarsInfos() as $name => $varInfos)
			{
				$jsonSkinVars[] = array('value' => '/*@var '.$name.'*/', 'label' => f_Locale::translateUI('&modules.richtext.bo.doceditor.Skin-varLabel;') . ' ' . f_Locale::translateUI("&themes.$themeName.skin.$name;") . ' (' . $varInfos['ini'] . ')');
			}
		}
		else
		{
			Framework::info(__METHOD__ . ' no theme');
		}
		$formProperties['substitutions'] = $jsonSkinVars;
		
		$formProperties['globalcss'] = $definition->getCss();
	}
	
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