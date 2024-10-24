<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Config\Setting;
use WHMCS\User\Admin;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

add_hook('ClientAdd', 685425, function($vars)
{
  global $remote_ip;

  $SETTINGS = array();
  $results = Capsule::table('mod_registrationalertconfig')->get();
  foreach ($results as $result)
  {
    $setting = $result->setting;
    $value   = $result->value;
    $SETTINGS[$setting] = $value;
  }

  if(!$_SESSION['adminid'] && $SETTINGS['Status'] && is_array(unserialize($SETTINGS['Admins'])))
  {
    $mergefields['ip']          = '<a href="http://www.geoiptool.com/en/?IP='.$remote_ip.'" target="_blank">'.$remote_ip.'</a>';
    $mergefields['clientname']  = $vars["firstname"].' '.$vars["lastname"];
    $mergefields['email']       = $vars["email"];
    $mergefields['companyname'] = $vars["companyname"];
    $mergefields['address1']    = $vars['address1'];
    $mergefields['address2']    = $vars['address2'];
    $mergefields['city']        = $vars['city'];
    $mergefields['state']       = $vars['state'];
    $mergefields['postcode']    = $vars['postcode'];
    $mergefields['country']     = $vars['country'];
    $mergefields['phonenumber'] = $vars['phonenumber'];
    $mergefields['id']          = $vars['client_id'];

    foreach(Admin::where('disabled', 0)->get() as $adminData)
    {
      if(in_array($adminData->id, unserialize($SETTINGS['Admins'])))
      {
        sendAdminMessage('Client Signup Notification', $mergefields, 'system', 0, $adminData->id);
      }
    }
  }
});
