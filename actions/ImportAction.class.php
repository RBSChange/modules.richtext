<?php
/**
 * richtext_ImportAction
 * @package modules.richtext.actions
 */
class richtext_ImportAction extends f_action_BaseJSONAction
{
	/**
	 * @return Boolean
	 */
	protected function isDocumentAction()
	{
		return false;
	}

	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$result = richtext_SystemstyledefinitionService::getInstance()->importFromRichtextXml();
		return $this->sendJSON($result);
	}
}