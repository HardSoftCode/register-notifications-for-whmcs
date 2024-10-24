<?php

use WHMCS\Database\Capsule;

$templateid = Capsule::table('tblemailtemplates')->where('name', 'Client Signup Notification')->where('language', '')->value('id');

echo '<link rel="stylesheet" href="../modules/addons/RegistrationAlert/includes/html/css/sky-mega-menu.css">
    <link rel="stylesheet" href="../modules/addons/RegistrationAlert/includes/html/css/sky-mega-menu-black.css">

		<!--[if lt IE 9]>
			<link rel="stylesheet" href="../modules/addons/RegistrationAlert/includes/html/css/sky-mega-menu-ie8.css">
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!--[if lt IE 10]>
			<script src="../modules/addons/RegistrationAlert/includes/html/js/jquery.placeholder.min.js"></script>
		<![endif]-->';

echo '<ul class="sky-mega-menu sky-mega-menu-anim-scale sky-mega-menu-response-to-icons">
        <li '; if($_REQUEST['a'] == '') { echo 'class="current"'; } echo '><a href="'.$modulelink.'"><i class="fa fa-home"></i>'.$LANG['home'].'</a></li>
        <li><a href="configemailtemplates.php?action=edit&id='.$templateid.'" target="_blank"><i class="fa fa-envelope"></i>'.$LANG['emailtemplate'].'</a></li>
				<li class="right"><a href="http://www.hardsoftcode.com/product/29/whmcs-registration-notifications/documentation" target="_blank"><i class="fa fa-question-circle"></i>'.$LANG['help'].'</a></li>
			</ul>
			<br>';
