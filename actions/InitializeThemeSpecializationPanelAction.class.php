<?php
/**
 * richtext_InitializeThemeSpecializationPanelAction
 * @package modules.richtext.actions
 */
class richtext_InitializeThemeSpecializationPanelAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$result = array('nodes' => array(), 'themes' => array());
		
		$definition = $this->getDocumentInstanceFromRequest($request);
		$usedThemes = array();
		foreach (richtext_StyledefinitionforthemeService::getInstance()->getByDefinition($definition) as $specialization) 
		{
			/* @var $specialization richtext_persistentdocument_styledefinitionfortheme */
			$theme = $specialization->getTheme();
			$usedThemes[] = $theme->getId();
			$info = array(
				'id' => $specialization->getId(),
				'themeId' => $theme->getId(), 
				'theme' => $theme->getLabel(),
			);
			$result['nodes'][] = $info;
		}

		foreach (theme_ThemeService::getInstance()->createQuery()->add(Restrictions::notin('id', $usedThemes))->find() as $theme)
		{
			/* @var $theme theme_persistentdocument_theme */
			$result['themes'][] = array('id' => $theme->getId(), 'label' => $theme->getLabel());
		}

		return $this->sendJSON($result);
	}
}