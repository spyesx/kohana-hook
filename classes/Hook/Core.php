<?php defined('SYSPATH') or die('No direct script access.');

class Hook_Core {

	protected static $_instance = null;

	private $_actions = array();
	private $_returns = array();

	public static function instance()
	{
		if (!isset(Hook_Core::$_instance))
		{
			self::$_instance = new Hook_Core();
		}

		return self::$_instance;
	}

	public function add_action($hook_name, $Class, $method, array $params = array(), array $hook_params = array())
	{
		if(!isset($this->_actions[$hook_name]))
		{
			$this->_actions[$hook_name] = array();
		}

		$this->_actions[$hook_name][] = array(
			'class' => $Class,
			'method' => $method,
			'params' => array_merge($params, $hook_params),
		);
	}

	public function add_actions_from_config($hooker)
	{
		$actions = Kohana::$config->load('hooks.'.$hooker);

		if($actions)
		{
			foreach ($actions as $action)
			{
				$this->add_action(
					$action['hook_name'],
					$action['class'],
					$action['method'],
					( isset($action['params']) ? $action['params'] : array() )
				);
			}
		}
	}

	public function do_action($hook_name, $Class, $method, array $params = array())
	{
		if(!class_exists($Class))
		{
			throw new Kohana_Exception('The class '.$Class.' does not exist');
		}

		$Class = new $Class;

		if(!method_exists($Class, $method))
		{
			throw new Kohana_Exception('The method '.$method.' does not exist in the class '.$Class);
		}

		$this->_returns[$hook_name][] = call_user_func_array(array($Class, $method), $params);
	}

	public function do_all_actions($hook_name, array $hook_params = array())
	{
		if( !isset($this->_actions[$hook_name]) OR !is_array($hook_params) )
		{
			return;
		}

		foreach ($this->_actions[$hook_name] as $action)
		{
			$action['params']   = array_reverse($action['params'], true);
			$action['params']['hook_params'] = '$hook_params';
			$action['params']   = array_reverse($action['params'], true);

			$this->do_action($hook_name, $action['class'], $action['method'], $action['params']);
		}
	}

	public function get_returns($hook_name)
	{
		return isset($this->_returns[$hook_name]) ? $this->_returns[$hook_name] : null;
	}

	// TODO : develop has_action
	// TODO : develop remove_action
	// TODO : develop remove_all_actions


}
