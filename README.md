# Work in progress.
# Do not use in production mode.

# Kohana - Hook

## How to create a hooker

First you will need to load all actions registered from you __construct() or before():

```php
Hook::instance()->add_actions_from_config('challenges');
```

And then anywhere you want to provide a hook in your methods:

```php
Hook::instance()->do_all_actions('class_hookname', array(
	'key_1' => 'value_1',
	'key_2' => 'value_2'
));
```


## How to use a hook

Register your actions in your config file config/hook.php

```php
return array(
	'YourClass'=> array(
		array( 
			'hook_name' => 'yourclass_hookname', 
			'class' => 'Myclass', 
			'method' => 'method_to_call', 
			'params' => array('one', 'two') 
		),
	)
);
```

Create your action (method) in you class

```php
class Myclass {

	/**
	 * Execute whaterver you need to do
	 *
	 * @param   array  $hookParams   The array provided with the method do_all_actions
	 * @param   array  $actionParam1 params[0] registered in config/hook.php
	 * @param   array  $actionParam2 params[1] registered in config/hook.php
	 * @return  Encrypt
	 */

	public function method_to_call($hookParams, $actionParam1, $actionParam2){
		// do what you need
	}

}
```


