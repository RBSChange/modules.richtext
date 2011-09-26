<?php
/**
 * richtext_ImportAction
 * @package modules.richtext.actions
 */
class richtext_ImportAction extends change_JSONAction
{
	/**
	 * @return Boolean
	 */
	protected function isDocumentAction()
	{
		return false;
	}

	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$result = richtext_SystemstyledefinitionService::getInstance()->importFromRichtextXml();
		return $this->sendJSON($result);
	}
}