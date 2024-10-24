<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

echo '<script src="../modules/addons/RegistrationAlert/includes/html/js/ckeditor/ckeditor.js"></script>';

if($_REQUEST['success'])
{
  echo ' <script>$(document).ready( function(){ window.setTimeout( function(){ $(".alert").slideUp(); }, 2500);});</script>';
  echo '<div class="alert alert-success" style="font-size:14px"><strong><span class="fa fa-check"></span> '.$LANG['success'].'</strong> '.$LANG['successhelp'].'</div>';
}

echo '<form name="form" action="'.$modulelink.'&p=save" method="POST" class="form-horizontal">
        <div class="panel panel-default">
          <div class="panel-heading">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> '.$LANG['savechanges'].'</button>
            <button type="reset" class="btn btn-default btn-sm"><i class="fa fa-times"></i> '.$LANG['cancel'].'</button>
          </div>
          <div class="panel-body">

            <div class="form-group">
              <label class="col-md-3 control-label">'.$LANG['enablemodule'].'</label>
              <div class="col-md-6">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="Status" '; if($SETTINGS['Status']){echo 'checked';} echo '> '.$LANG['enablemodulehelp'].'
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">'.$LANG['allowedadmonsters'].'</label>
              <div class="col-md-6">';
      $result = Capsule::table('tbladmins')->orderBy('id', 'ASC')->get();
      foreach ($result as $data)
      {
         echo '<div class="checkbox">
                  <label>
                    <input type="checkbox" name="Admins[]" value="'.$data->id.'" '; if(is_array(unserialize($SETTINGS['Admins'])) && in_array($data->id, unserialize($SETTINGS['Admins']))){echo 'checked';} echo '> '.$data->firstname.' '.$data->lastname.'
                  </label>
                </div>';
      }
       echo '</div>
            </div>

          </div>
          <div class="panel-footer">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> '.$LANG['savechanges'].'</button>
            <button type="reset" class="btn btn-default btn-sm"><i class="fa fa-times"></i> '.$LANG['cancel'].'</button>
          </div>
        </div>
      </form>';

if($_REQUEST['p'] == 'save')
{
  Capsule::table('mod_registrationalertconfig')->where('setting', 'Status')->update(array('value' => $_REQUEST['Status']));
  Capsule::table('mod_registrationalertconfig')->where('setting', 'Admins')->update(array('value' => serialize($_REQUEST['Admins'])));

  header('Location: '.$modulelink.'&success=true');
  exit;
}
