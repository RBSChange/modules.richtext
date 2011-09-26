<?php
/**
 * richtext_ApplyUpdatesAction
 * @package modules.richtext.actions
 */
class richtext_ApplyUpdatesAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$result = array();

		richtext_ModuleService::getInstance()->applyAndRebuild();

		return $this->sendJSON($result);
	}
}