<?php

class AuthentificationManager {

    
    public function __construct()
    {
       
    }

    //Création des session de l'utilsiateur
    public function connectUser($account)
    {
        $_SESSION['user']=$account;
        $_SESSION['login']=$account->getLogin();
        $_SESSION['statut']=$account->getStatut();
    }

    //Retourn null si aucun utilisateur connecté
    public function isUserConnected()
    {
        if(key_exists('user',$_SESSION))
        {
            return $_SESSION['user'];
        }
        return null;
    }

    //Verifie si l'admin est connecté
    public function isAdminConnected()
    {
        $account=$this->isUserConnected();

        if($account!==null)
        {
            return ($account->getStatut()==='admin')? true : false;
        }
        return false;
    }

    public function verifUser($login)
    {
        if($login===$_SESSION['login'])
        {
            return true;
        }
        return false;
    }


    
    public function disconnectUser()
    {
        if($this->isUserConnected()!==null)
        {
            unset($_SESSION['user']);
            return true;
        }
        return false;
    }
}
?>