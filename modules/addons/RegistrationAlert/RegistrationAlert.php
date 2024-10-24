<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function RegistrationAlert_config()
{
  $configarray = array(
  "name" => "Registration Email Alert",
  "description" => "When new customers register on the website the admin may need to be informed about the registration, this module will do that by sending an email to the admin's email.",
  "version" => "2.3.0",
  "author" => "<a href=\"http://www.hardsoftcode.com\" target=\"_blank\">HSC</a>",
  "language" => "english",
  "fields" => array(
  "delete" => array ("FriendlyName" => "Delete Module DB", "Type" => "yesno", "Size" => "25", "Description" => "Tick this box to delete the module database on deactivating"),
  ));
  return $configarray;
}

function RegistrationAlert_activate()
{
  try
  {
    if(!Capsule::schema()->hasTable('mod_registrationalertconfig'))
    {
      Capsule::schema()->create('mod_registrationalertconfig', function ($table)
      {
        $table->text('setting');
        $table->text('value');
      });

      Capsule::table('mod_registrationalertconfig')->insert(array(
          array('setting' => 'Status', 'value' => 'on'),
          array('setting' => 'Admins', 'value' => ''),
      ));
    }

    if(Capsule::schema()->hasTable('tblemailtemplates'))
    {
      if(!Capsule::table('tblemailtemplates')->where('name', 'Client Signup Notification')->value('name'))
      {
        Capsule::table('tblemailtemplates')->insert(array(
          'type'    => 'admin',
          'name'    => 'Client Signup Notification',
          'subject' => 'Client Signup Notification',
          'message' => '<p>There is a new client has register today {$date} with the following details</p>Client ID: {$id}<br>Name: {$clientname}<br>Email: {$email}<br>Company: {$companyname}<br>Address 1: {$address1}<br>Address 2: {$address2}<br>City: {$city}<br>State: {$state}<br>Postcode: {$postcode}<br>Country: {$country}<br>Phone Number: {$phonenumber}<br>IP Address: {$ip}',
          'custom'  => '1',
        ));
      }
    }
  }
  catch (\Exception $e)
  {
    return array('status'=>'error','description'=>'Unable to create table mod_registrationalertconfig: ' .$e->getMessage());
  }

  return array('status'=>'success','description'=>'Module activated successfully. Click configuration to configure the module');
}

function RegistrationAlert_deactivate()
{
  $delete = Capsule::table('tbladdonmodules')->where('module', 'RegistrationAlert')->where('setting', 'delete')->first();

  if($delete->value)
  {
    try
    {
      Capsule::schema()->dropIfExists('mod_registrationalertconfig');
      Capsule::table('tblemailtemplates')->where('name', 'Client Signup Notification')->delete();
    }
    catch (\Exception $e)
    {
      return array('status'=>'error','description'=>'Unable to drop tables: ' .$e->getMessage());
    }
  }

  return array('status'=>'success','description'=>'Module deactivated successfully');
}

function RegistrationAlert_output($vars)
{
  $modulelink = $vars['modulelink'];
  $LANG       = $vars['_lang'];

  $SETTINGS = array();
  $results = Capsule::table('mod_registrationalertconfig')->get();
  foreach ($results as $result)
  {
    $setting = $result->setting;
    $value   = $result->value;
    $SETTINGS[$setting] = $value;
  }

  require(dirname( __FILE__ ).'/includes/pages/menu.php');

  if(file_exists(dirname( __FILE__ ).'/includes/pages/'.$_REQUEST['a'].'.php'))
  {
    require(dirname( __FILE__ ).'/includes/pages/'.$_REQUEST['a'].'.php');
  }
  else
  {
    require(dirname( __FILE__ ).'/includes/pages/home.php');
  }

}
