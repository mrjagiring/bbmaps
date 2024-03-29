<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Maps Generator Web Application',
	'theme'=>'bootstrap',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.components.*',
		'application.helper.*',
		'application.models.*',
		'application.modules.user.models.*',
		'application.modules.user.components.*',
		'application.modules.rights.*',
		'application.modules.rights.components.*',
	),

	'modules'=>array(
		'gii'=>array(
			'generatorPaths'=>array(
				'bootstrap.gii',
			),
			'class'=>'system.gii.GiiModule',
			'password'=>'jagiring',
		),

		'user'=>array(
			'tableUsers' => 'tbl_users',
			'tableProfiles' => 'tbl_profiles',
			'tableProfileFields' => 'tbl_profiles_fields',
		),

		'rights'=>array(
			'install'=>false,
		),

		'importcsv'=>array(
			'path'=>'upload/importcsv/',
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			'class'=>'RWebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>array('/user/login'),
		),
		'authManager'=>array(
			'class'=>'RDbAuthManager',
			'connectionID'=>'db',
			'defaultRoles'=>array('Authenticated', 'Guest'),
		),
		'bootstrap'=>array(
			'class'=>'bootstrap.components.Bootstrap',
		),
		'phpThumb'=>array(
			'class'=>'ext.EPhpThumb.EPhpThumb',
		),
		'file'=>array(
			'class'=>'application.extensions.file.CFile',
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		// uncomment the following to use a MySQL database
		*/

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=bbmaps',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'samson2612',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',

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
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'samson@sinaga.or.id',
	),
);
