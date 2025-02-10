<?php
include_once ('users/passwordRecovery.php');
include_once ('users/controlPanel.php');
include_once ('users/singup.php');
include_once ('users/login.php');
include_once ('users/changePassword.php');
include_once ('users/disconnected.php');
include_once ('users/loginMultiple.php');
include_once ('users/tiposDeServicio.php');

class UserInWeb {
    static public function initUserInWeb($requests){
        $text = $requests['text'];
        $isUserInWeb = false;
        if( $text == 'user-login' ||
            $text == 'user-singup' ||
            $text == 'user-passwordRecovery' ||
            $text == 'user-controlPanel' ||
            $text == 'user-changePassword' ||
            $text == 'user-disconnected' ||
            $text == 'user-loginMultiple' ||
            $text == 'user-tiposDeServicio'
        ){
            $isUserInWeb = true;
        }

        if($text == 'user-login'){ Login::initLogin($requests); }
        if($text == 'user-singup'){ Singup::initSingup($requests); }
        if($text == 'user-passwordRecovery'){ PasswordRecovery::initPasswordRecovery($requests); }
        if($text == 'user-controlPanel'){ ControlPanel::initControlPanel($requests); }
        if($text == 'user-changePassword'){ ChangePassword::initChangePassword($requests); }
        if($text == 'user-disconnected'){ Disconnected::initDisconnected($requests); }
        if($text == 'user-loginMultiple'){ LoginMultiple::initLogin($requests); }
        if($text == 'user-tiposDeServicio'){ TiposDeServicio::initTiposDeServicio(); }

        return $isUserInWeb;
    }
}

