<?php
/**
 * richtext_ApplyUpdatesAction
 * @package modules.richtext.actions
 */
class richtext_ApplyUpdatesAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$result = array();

		richtext_ModuleService::getInstance()->applyAndRebuild();

		return $this->sendJSON($result);
	}
}