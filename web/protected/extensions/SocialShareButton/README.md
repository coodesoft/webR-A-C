SocialShareButton
=================

Yii extension for all social media share buttons like - facebook, twitter, linkedin, googleplus and pinterest.


Requirements
============

Tested with Yii 1.1.14 & Latest Version


Installation
============

- Download the latest release package
- Unpack it in /protected/extensions/ folder


Usage
=====

Paste the code into your main.php page or also you can use this code as per your requirement.

~~~
$this->widget('application.extensions.SocialShareButton.SocialShareButton', array(
		'style'=>'horizontal',
        'networks' => array('facebook','googleplus','linkedin','twitter','pinterest'),
		'data_via'=>'rohisuthar', //twitter username (for twitter only, if exists else leave empty)
));
~~~


Download
========

https://github.com/rohitsuthar/SocialShareButton


Usual parameters to be adjusted
===============================

- style: share button styles (string: vertical or horizontal).
- social media: social network which you must have (facebook, twitter, googleplus, linkedin, pinterest.);
- data_via: You can specify your twitter username (string: rohisuthar)
