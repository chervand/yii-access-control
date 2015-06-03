<?php

/**
 * @author chervand <chervand@gmail.com>
 */
class AccessControlFilter extends CFilter
{
	/**
	 * Excluded items.
	 * @var array
	 */
	public $exclude = [''];
	/**
	 * HTTP error status code. Defaults to 403.
	 * @var int
	 */
	public $status = 403;
	/**
	 * HTTP error message. Defaults to "Forbidden".
	 * @var string
	 */
	public $message = 'Forbidden';
	/**
	 * Controller instance.
	 * @author andcherv
	 * @var
	 */
	private $_controller;
	/**
	 * Authorization item bizrule params.
	 * @author andcherv
	 * @var array
	 */
	private $_accessParams = [];


	/**
	 * @author andcherv
	 */
	public function init()
	{
		$this->_controller = Yii::app()->controller;
	}

	/**
	 * Checks the access to the action.
	 * @author andcherv
	 * @param CFilterChain $filterChain
	 * @return bool
	 * @throws CHttpException
	 * @see IAuthManager::checkAccess()
	 */
	protected function preFilter($filterChain)
	{
		$itemName = $this->getAuthItemName();
		if (!in_array($itemName, $this->exclude) && !$this->checkAccess($itemName))
			throw new CHttpException($this->status, $this->message);
		return true;
	}

	/**
	 * Returns authorization item name.
	 * @author andcherv
	 * @return string
	 * @see CAuthManager
	 */
	protected function getAuthItemName()
	{
		$authItemName = [];
		if (isset($this->_controller->module))
			$authItemName['module'] = $this->_controller->module->id;
		if (isset($this->_controller))
			$authItemName['controller'] = $this->_controller->id;
		if (isset($this->_controller->action))
			$authItemName['action'] = $this->_controller->action->id;
		return implode('/', $authItemName);
	}

	/**
	 * Checks current user's access to the $itemName given.
	 * @param $itemName
	 * @return bool
	 */
	protected function checkAccess($itemName)
	{
		if (Yii::app()->user instanceof CWebUser)
			return Yii::app()->user->checkAccess($itemName, $this->getAccessParams());
		return false;
	}

	/**
	 * Returns authorization item bizrule params array.
	 * @author andcherv
	 * @return mixed
	 * @see IAuthManager::checkAccess()
	 */
	protected function getAccessParams()
	{
		if (method_exists($this->_controller, 'accessParams'))
			$this->_accessParams = $this->_controller->accessParams();
		return $this->_accessParams;
	}
}
