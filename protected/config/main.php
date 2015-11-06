<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Sword',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.utils.*',
		'application.controllers.*',
	),

	'aliases' => array(
        //'bootstrap' => realpath(__DIR__ . '/../extensions/bootstrap'), // change this if necessary
    ),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>"db",
		),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'showScriptName'=>false,
			'caseSensitive'=>true,
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

		'format'=>array(
        	'class'=>'application.components.Formatter',
        ),
        'emailUtils'=>array(
        	'class'=>'application.components.utils.EmailUtils',
        ),
		
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database


	
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=sword',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => array(
                'host' => 'smtp.gmail.com',
                'username' => 'flacogarbage@gmail.com',
                'password' => '****',
                'port' => '465',
                'encryption'=>'tls',
            ),
            'viewPath' => 'application.views.email',
            'logging' => true,
            'dryRun' => false
        ),


	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'TEMPLATE_NOTIFICATION_EMAIL'=>1,
		'TEMPLATE_REGISTER_EMAIL'=>2,
		'TEMPLATE_FORGET_EMAIL'=>3,
		'DATE_FORMAT'=>'Y-m-d',
		'URL_BASE'=>'http://localhost/sword/',
        'DEFAULT_NOTIFICATIONS_EMAIL'=>'flacogarbage@gmail.com',
        'DEFAULT_NOTIFICATIONS_EMAIL_NAME'=>'Sword Application',
	),
);

