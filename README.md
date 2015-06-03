# chervand/yii-access-control

## AuthManager configuration

Proper authManager component should be configured (CPhpAuthManger or CDbAuthManager)

### CDbAuthManager (Example)

Apply migrations or create DB tables as described in [CDbAuthManager](http://www.yiiframework.com/doc/api/1.1/CDbAuthManager) documentation.

```php
    ...
	'components' => [
	    ...
		'authManager' => [
			'class' => 'CDbAuthManager',
			'connectionID' => 'db',
			'itemTable' => 'auth_item',
			'itemChildTable' => 'auth_item_child',
			'assignmentTable' => 'auth_assignment',
			'defaultRoles' => ['guest', 'user', 'support', 'moder', 'admin'],
		],
		...
    ],
    ...
```

## Filter configuration

Import a filter in config or in controller

```php
    ...
	'import' => [
	    ...
		'vendor.chervand.yii-access-control.components.*',
		...
	],
	...
```

### Controller

- exclude - Excluded routes. Will not be filtered.
- message - 403 error message.

```php
class Controller extends CController
{
    ...
	public function filters()
	{
		return [
		    ...
			[
				'AccessControlFilter',
				'message' => Yii::t('app', 'You are not authorized to perform this action.'),
				'exclude' => [
				    'site/index',
                    'site/error'
                ]
			],
			...
		];
	}
	...
}
```

## Creating Rules
