<?php
require_once "Account.php";

class AccountBuilder {

    //CHAMP  de constante pour le formulaire 
    const LOGIN_REF ="login";
    const PASSWORD_REF ="password";
    const NAME_REF ="name";


    private $data;
    private $error;

    public function __construct($data)
    {
        $this->data=$data;
        //Echapper les caractères
        foreach($this->data as $keys => $value)
        {
            $this->data[$keys]=htmlspecialchars($value,ENT_QUOTES);
        }
        $this->error=[];
    }
    public function getLoginRef()
    {
        return self::LOGIN_REF;
    }
    public function getPasswordRef()
    {
        return self::PASSWORD_REF;
    } 
    public function getNameRef()
    {
        return self::NAME_REF;
    }
    public function getDataRef($NomConstante)
    {
        if(key_exists($NomConstante,$this->data))
         {
            return $this->data[$NomConstante];
         }
         return '';
    }

     //Retourne l'erreur selon la constante
     public function getErrorConstante($NomConstante)
     {
         if(key_exists($NomConstante,$this->error))
         {
             return $this->error[$NomConstante];
         }
         return null;
     }
     //Test si data possède les clés de connexion d'un utilisateur
     public function KeyExistAccountConnexion()
    {
        if(key_exists(self::LOGIN_REF,$this->data) && key_exists(self::PASSWORD_REF,$this->data))
        {
            return true;
        }
        return false;
    }
    //Test si le data possède les clées de creation d'un utilisateur
    public function KeyExistAccountCreation()
    {
        if($this->KeyExistAccountConnexion() && key_exists(self::NAME_REF,$this->data))
        {
            return true;
        }
        return false;
    }

    //Creation compte avec le tableau data et mise à defaut du statut user
    public function createAccount() 
    {
        $account = new Account($this->data[self::LOGIN_REF],$this->data[self::PASSWORD_REF]
        ,$this->data[self::NAME_REF],"user");
        return $account;
    }

     //Permet de remplir l'indice d'error adequat par rapport à la constante
     public function ConstructErrorChar($constante)
     {
        
        if(mb_strlen($this->data[$constante],'UTF-8') >= 30)
            $this->error[$constante]=" Erreur le champ ".$constante ." doit contenir  moins de 30 caractères";
        
        if(mb_strlen($this->data[$constante],'UTF-8') <= 2)
            $this->error[$constante]=" Erreur le champ ".$constante ." doit contentir au moins 3 caractères";
        
        if($this->data[$constante]==="")
        {
            $this->error[$constante]="Erreur le champ ".$constante ." est vide ! \n";
        }

        if(preg_match('#[^a-zA-Z0-9]#', $this->data[$constante]))
        {
            $this->error[$constante]="Erreur le champ ".$constante ." comporte des caractères spéciaux ! \n";
        } 
     }

     public function isValidConnexion()
     {
         //Construction erreur
         $this->ConstructErrorChar(self::LOGIN_REF);
         $this->ConstructErrorChar(self::PASSWORD_REF);
         //Si tableau error vide le compte est valide 
        if($this->error===[])
        {
            return true;
        }
        return false;
         
     }
     public function isValidCreation()
     {
         //Construction erreur
         $this->ConstructErrorChar(self::LOGIN_REF);
         $this->ConstructErrorChar(self::PASSWORD_REF);
         //$this->ConstructErrorChar(self::NAME_REF);
         
         if(preg_match('#[^a-zA-Z- ]#', $this->data[self::NAME_REF]))
         {
             $this->error[self::NAME_REF]="Erreur le champ ".self::NAME_REF ." comporte des caractères spéciaux ou chiffre ! \n";
         }
        if(mb_strlen($this->data[self::NAME_REF],'UTF-8') >=20)
        {
            $this->error[self::NAME_REF]="Erreur le champ ".self::NAME_REF ." doit contenir moin de 20 caractères ! \n";
        }
        if(mb_strlen($this->data[self::NAME_REF],'UTF-8') <=2)
        {
            $this->error[self::NAME_REF]="Erreur le champ ".self::NAME_REF ." doit contenir au moin 3 caractères ! \n";
        }

         //Si tableau error vide le compte est valide 
        if($this->error===[])
        {
            return true;
        }
        return false;
     }

     public static function ModifAccountInstance(Account $account)
    {
        return new AccountBuilder(array(
            self::LOGIN_REF => $account->getLogin(),
            self::PASSWORD_REF => "",                     //PASSWORD VIDE CAR HASHER
            self::NAME_REF => $account->getNom(),  
        ));
    }

     public function updateAccount(Account $account)
     {
        if(key_exists(self::LOGIN_REF,$this->data))
        {
            $account->setLogin($this->data[self::LOGIN_REF]);
        }
        if(key_exists(self::PASSWORD_REF,$this->data))
        {
            $account->setMdp($this->data[self::PASSWORD_REF]);
        }
        if(key_exists(self::NAME_REF,$this->data))
        {
            $account->setNom($this->data[self::NAME_REF]);
        }
     }




}



?>