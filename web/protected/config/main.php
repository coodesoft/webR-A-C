<?php
require('dbConfig.php');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

Yii::setPathOfAlias('commons', dirname(__FILE__).'/../../../commons');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Rosario al costo',
    'theme'=>'rac',
    'language'=>'es',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',

        'ext.*',
        'ext.helpers.*',
        'commons.extensions.*',
        'commons.models.*'
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool

        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
        ),

        'user'=>array(
            'tableUsers' => 'users',
            'tableProfiles' => 'profiles',
            'tableProfileFields' => 'profiles_fields',

            # encrypting method (php hash function)
            'hash' => 'md5',

            # send activation email
            'sendActivationMail' => true,

            # allow access for non-activated users
            'loginNotActiv' => false,

            # activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => false,

            # automatically login from registration
            'autoLogin' => true,

            # registration path
            'registrationUrl' => array('/user/registration'),

            # recovery password path
            'recoveryUrl' => array('/user/recovery'),

            # login form path
            'loginUrl' => array('/user/login'),

            # page after login
            'returnUrl' => array('/user/profile'),

            # page after logout
            'returnLogoutUrl' => array('/user/login'),
        ),

    ),

    // application components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
            'class' => 'WebUser',
            'loginUrl'=>array('/user/login')
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName' => false,
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id1:\d+>/<id2:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),

        /*'db'=>array(
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
        ),*/
        // uncomment the following to use a MySQL database

        'db'=>getDBparams(),

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
                array(
                    'class'=>'PEmailLogRoute',
                    'levels'=>'error, warning',
                    'except' => 'exception.CHttpException.404',
                    'emails'=>'errors@mailinator.com',
                ),
                */
            ),
        ),

        'session' => array (
            'timeout' => 2592000, // 30 dias
        ),

        'mail' => array(
            'class' => 'application.extensions.yii-mail.YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => array(
                'host' => 'mail.rosarioalcosto.com.ar',
                'username' => 'mailing@rosarioalcosto.com.ar',
                'password' => 'R0s4r104lc0st0',
                'port' => '25',
                'encryption' => '',//vacio
            ),
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'mailing@rosarioalcosto.com.ar',
        'contactoEmail'=>'mailing@rosarioalcosto.com.ar',
        'fbUrl' => '',
        'twUrl' => '',
    ),
);
