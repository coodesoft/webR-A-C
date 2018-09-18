<?php
require('dbConfig.php');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

Yii::setPathOfAlias('commons', dirname(__FILE__).'/../../../commons');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'     => 'Rosario al costo',
    'theme'    => 'rac',
    'language' => 'es',

    // preloading 'log' component
    'preload'=>['log'],

    // autoloading model and component classes
    'import'=>[
        'application.models.*',
        'application.components.*',

        'application.modules.user.models.*',
        'application.modules.user.components.*',

        'application.modules.SocialConnect.*',
        'application.modules.SocialConnect.components.*',

        'ext.*',
        'ext.helpers.*',
        'commons.extensions.*',
        'commons.models.*',
    ],

    'modules'=>array(
        'SocialConnect',
        // uncomment the following to enable the Gii tool
        'gii'=>[
            'class'=>'system.gii.GiiModule',
            'password'=>'123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>['127.0.0.1','::1'],
        ],
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
            'registrationUrl' => ['/user/registration'],

            # recovery password path
            'recoveryUrl' => ['/user/recovery'],

            # login form path
            'loginUrl' => ['/user/login'],

            # page after login
            'returnUrl' => ['/user/profile'],

            # page after logout
            'returnLogoutUrl' => ['/user/login'],
        ),

    ),

    // application components
    'components'=>array(
        'user'=>[
          // enable cookie-based authentication
          'allowAutoLogin' => true,
          'class'          => 'WebUser',
          'loginUrl'       => ['/user/login']
        ],
        'social' => [
          'class' => 'SocialModule',
          ],
        // uncomment the following to enable URLs in path-format
        'urlManager'=>[
            'urlFormat'=>'path',
            'showScriptName' => false,
            'rules'=>[
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id1:\d+>/<id2:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ],
        ],

        'db'=>getDBparams(),

        'errorHandler'=>[
            'errorAction'=>'site/error',
        ],
        'log'=>[
            'class'=>'CLogRouter',
            'routes'=>[
                [
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ],
            ],
        ],

        'session' => [
          'timeout' => 2592000, // 30 dias
        ],
        'mail' => [
            'class' => 'application.extensions.yii-mail.YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => [
                'host'       => 'mail.rosarioalcosto.com.ar',
                'username'   => 'mailing@rosarioalcosto.com.ar',
                'password'   => 'R0s4r104lc0st0',
                'port'       => '25',
                'encryption' => '',//vacio
            ],
            'viewPath' => 'application.views.mail',
            'logging'  => true,
            'dryRun'   => false
        ],
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    //TODO:: Parametrizar mas info en esta seccion
    'params'=>[
        'mode'=>'prod', //test or production
        //TEST PARAMS
        'test' => [
            'email' => [
                'password' => 'R0s4r104lc0st0',
                'username' =>'no-reply@rosarioalcosto.com.ar',
                'host'=> 'mail.rosarioalcosto.com.ar',
                'port' => 587,
                'name'=> 'Rosario Al Costo - TEST',
                'debug'=>0,
                ],
            'URL' => 'http://rac.geneos.com.ar/web',
            'contactoEmail' => 'ventas@rosarioalcosto.com.ar',
            ],
        //PRODUCTION PARAMS
        'prod' => [
            'email' => [
                'password' => 'R0s4r104lc0st0',
                'username' =>'no-reply@rosarioalcosto.com.ar',
                'host'=> 'mail.rosarioalcosto.com.ar',
                'name'=> 'Rosario Al Costo',
                'port' => 587,
                'debug' => 0
                ],
            'URL' => 'http://rosarioalcosto.com/web',
            'contactoEmail' => 'ventas@rosarioalcosto.com.ar',
            ],
    ],
);
