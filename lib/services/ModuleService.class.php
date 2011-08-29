<?php
/**
 * @package modules.richtext.lib.services
 */
class richtext_ModuleService extends ModuleBaseService
{
	/**
	 * Singleton
	 * @var richtext_ModuleService
	 */
	private static $instance = null;

	/**
	 * @return richtext_ModuleService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * Apply to all pagetemplates and rebuild richtext.xml and css.
	 */
	public function applyAndRebuild()
	{
		$this->applyToPageTemplates();
		$this->rebuildRichtextXml();
		$this->rebuildCss();		
	}
	
	/**
	 * Rebuild the richtext.xml file in build.
	 */
	public function rebuildRichtextXml()
	{
		$output = new XMLWriter();
		$output->openMemory();
		$output->startDocument('1.0', 'UTF-8');
		$output->startElement('styles');
		foreach (richtext_StyledefinitionService::getInstance()->createQuery()->find() as $definition)
		{
			if ($definition instanceof richtext_persistentdocument_systemstyledefinition)
			{
				continue;
			}
			
			/* @var $definition richtext_persistentdocument_styledefinition */
			$output->startElement('style');
			$output->writeAttribute('tag', $definition->getTagName());
			$output->writeAttribute('label', $definition->getLabelForBlocksXml());
			$output->writeAttribute('block', $definition->getIsBlock() ? 'true' : 'false');
			$output->startElement('attribute');
			$output->writeAttribute('name', 'class');
			$output->writeAttribute('value', $definition->getTagClass());
			$output->endElement();
			$output->endElement();
		}
		$output->endElement();
		$output->endDocument();
		$contents = $output->outputMemory(true);

		$dirPath = f_util_FileUtils::buildChangeBuildPath('modules', 'generic', 'config');
		f_util_FileUtils::mkdir($dirPath);
		$filePath = f_util_FileUtils::buildChangeBuildPath('modules', 'generic', 'config', 'richtext.xml');
		f_util_FileUtils::write($filePath, $contents, f_util_FileUtils::OVERRIDE);
		
		// Clear caches.
		f_util_FileUtils::unlink(f_util_FileUtils::buildCachePath('cleanXHTMLFragment.xsl'));
	}

	/**
	 * Rebuild the css file in build.
	 */
	public function rebuildCss()
	{
		$this->rebuildGlobalCss(false);
		$this->rebuildThemesCss(false);
		
		// Clear caches.
		CacheService::getInstance()->clearCssCache();
	}
		
	/**
	 * Rebuild the css file in build.
	 * @param boolean $clearCache
	 */
	public function rebuildGlobalCss($clearCache = true)
	{
		$defaultCSS = '/* Included by rixhtext_<themeCodename>.css */';
				
		foreach (richtext_StyledefinitionService::getInstance()->createQuery()->find() as $definition)
		{
			if ($definition instanceof richtext_persistentdocument_systemstyledefinition)
			{
				continue;
			}
			
			/* @var $definition richtext_persistentdocument_styledefinition */
			if ($definition->getCss())
			{
				if (Framework::inDevelopmentMode())
				{
					$defaultCSS .= PHP_EOL . PHP_EOL . '/* CSS for ' . $definition->getCSSSelector() . ' - ' . $definition->getLabel() . ' (' . $definition->getId() . ') */';
				}
				$defaultCSS .=  PHP_EOL . $definition->getCss();
			}
			else if (Framework::inDevelopmentMode())
			{
				$defaultCSS .= PHP_EOL . PHP_EOL . '/* NO CSS for ' . $definition->getCSSSelector() . ' - ' . $definition->getLabel() . ' (' . $definition->getId() . ') */';
			}
		}
			
		$dirPath = f_util_FileUtils::buildChangeBuildPath('modules', 'richtext', 'style');
		f_util_FileUtils::mkdir($dirPath);
		$filePath = f_util_FileUtils::buildChangeBuildPath('modules', 'richtext', 'style', 'richtext.css');
		f_util_FileUtils::write($filePath, $defaultCSS, f_util_FileUtils::OVERRIDE);
		
		// Clear caches.
		if ($clearCache)
		{
			CacheService::getInstance()->clearCssCache();
		}
	}
	
	/**
	 * Rebuild the css files for all themes in build.
	 * @param boolean $clearCache
	 */
	public function rebuildThemesCss($clearCache = true)
	{
		foreach (theme_ThemeService::getInstance()->createQuery()->find() as $theme)	
		{
			$this->rebuildThemeCss($theme, false);
		}
		
		// Clear caches.
		if ($clearCache)
		{
			CacheService::getInstance()->clearCssCache();
		}
	}
	
	/**
	 * Rebuild the css file for a given theme in build.
	 * @param theme_persistentdocument_theme $theme
	 * @param boolean $clearCache
	 */
	public function rebuildThemeCss($theme, $clearCache = true)
	{
		$contents = '@import url(/modules/richtext/style/richtext.css);';
		
		$codeName = $theme->getCodename();
		foreach (richtext_StyledefinitionforthemeService::getInstance()->getByTheme($theme) as $specilization)
		{
			/* @var $specilization richtext_persistentdocument_styledefinitionfortheme */
			$definition = $specilization->getDefinition();
			if ($specilization->getCss())
			{
				if (Framework::inDevelopmentMode())
				{
					$contents .= PHP_EOL . PHP_EOL . '/* Specialized CSS for ' . $definition->getCSSSelector() . ' - ' . $definition->getLabel() . ' (' . $definition->getId() . '/' . $specilization->getId() . ') */';
				}
				$contents .= PHP_EOL . $specilization->getCss();
			}
			else if (Framework::inDevelopmentMode())
			{
				$contents .= PHP_EOL . PHP_EOL . '/* NO Specialized CSS for ' . $definition->getCSSSelector() . ' - ' . $definition->getLabel() . ' (' . $definition->getId() . '/' . $specilization->getId() . ') */';
			}
		}
			
		$dirPath = f_util_FileUtils::buildChangeBuildPath('modules', 'richtext', 'style');
		f_util_FileUtils::mkdir($dirPath);
		$filePath = f_util_FileUtils::buildChangeBuildPath('modules', 'richtext', 'style', 'richtext_' . $codeName . '.css');
		f_util_FileUtils::write($filePath, $contents, f_util_FileUtils::OVERRIDE);
		
		// Clear caches.
		if ($clearCache)
		{
			CacheService::getInstance()->clearCssCache();
		}
	}
	
	/**
	 * Adds the css in all page templates.
	 */
	public function applyToPageTemplates()
	{
		foreach (theme_PagetemplateService::getInstance()->createQuery()->find() as $template)
		{
			/* @var $template theme_persistentdocument_pagetemplate */
			$this->applyToPageTemplate($template);
		}
	}
	
	/**
	 * Adds the css in a given page template.
	 * @param theme_persistentdocument_pagetemplate $template
	 */
	public function applyToPageTemplate($template)
	{
		Framework::fatal(__METHOD__ . ' ' . $template->getId());
		$theme = f_util_ArrayUtils::firstElement($template->getThemeArrayInverse());
		$css = 'modules.richtext.richtext' . ($theme ? ('_' . $theme->getCodename()) : '');
		Framework::fatal(__METHOD__ . ' ' . $css);
		if (!$template->getCssscreen())
		{
			$template->setCssscreen($css);
		}
		elseif (!f_util_StringUtils::contains($template->getCssscreen(), $css))
		{
			$template->setCssscreen($template->getCssscreen() . ',' . $css);
		}
		$template->save();
	}
}