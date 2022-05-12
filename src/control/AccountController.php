<?php
require_once "model/account_model/Account.php";
require_once "model/account_model/AccountBuilder.php";
require_once "model/account_model/AccountStorageMySQL.php";

class AccountController {

    private $view;
    private $accountStore; 
    private $currentAccountBuilder;
    private $currentNewAccountBuilder;
    private $modifAccountBuilder;
    private $AuthManager;
    public function __construct($view,$accountStore,$auth)
    {
        $this->view=$view;
        $this->accountStore = $accountStore;
        $this->AuthManager=$auth;
        $this->currentAccountBuilder = key_exists('currentAccount', $_SESSION) ? $_SESSION['currentAccount'] : null;
		$this->currentNewAccountBuilder = key_exists('newAccount', $_SESSION) ? $_SESSION['newAccount'] : null;
        $this->modifAccountBuilder = key_exists('modifAccount',$_SESSION) ? $_SESSION['modifAccount'] : array();
    }

    //Lorsque plus de référence sur la classe stockage dans les différentes session
    public function __destruct() {
		$_SESSION['currentAccount'] = $this->currentAccountBuilder;
        $_SESSION['newAccount'] = $this->currentNewAccountBuilder;
        $_SESSION['modifAccount']=$this->modifAccountBuilder;

    }
    
    
    

    public function newAccount()
    {
        //Recuperer l'account builder si existe en session
        if($this->currentNewAccountBuilder===null)
        {
            $this->currentNewAccountBuilder = new AccountBuilder([]);
        }   
        $this->view->makeCreateAccountPage($this->currentNewAccountBuilder);
    }
    public function newConnexion()
    {
         //Recuperer l'account builder si existe en session
         if($this->currentAccountBuilder===null)
         {
             $this->currentAccountBuilder = new AccountBuilder([]);
         }
         $this->view->makeLoginFormPage($this->currentAccountBuilder);
    }


    

    //Connexion utilisateur si présent dans la base
    public function connectUser(array $data)
    {
        $accountBuilder=new AccountBuilder($data);

        if($accountBuilder->KeyExistAccountConnexion()===true)
        {
            if($accountBuilder->isValidConnexion())
            {
                $account=$this->accountStore->checkAuth($accountBuilder->getDataRef($accountBuilder::LOGIN_REF),
                $accountBuilder->getDataRef($accountBuilder::PASSWORD_REF));
                if($account!==null)
                {
                    $this->currentAccountBuilder=null;
                    $this->AuthManager->connectUser($account); // Création des sessions pour le user
                    $this->view->displayAccountConnexion($account->getNom());
                }
                else 
                {
                    $this->currentAccountBuilder=$accountBuilder;
                    $this->view->displayAccountNoConnexion();
                }
            }
            else {
                $this->currentAccountBuilder=$accountBuilder;
                $this->view->displayAccountNoConnexion();
            }
        }
        else {
            $this->view->makeUnkownActionPage();
        }
        
    }

    public function saveNewAccount(array $data)
    {
        $accoutBuild=new AccountBuilder($data); 
        //Si toutes les clé de data sont celle correspondante au accoutbuild data
        if($accoutBuild->KeyExistAccountCreation()===true)
        {
            //Si les champs sont remplis correctement créer nouveau compte
            if($accoutBuild->isValidCreation())
            {
                $account=$accoutBuild->createAccount(); 
                //SI login saisie jamais utilisé création du compte en base de donnée
                if($this->accountStore->LoginValid($account->getLogin()))
                {
                    $this->accountStore->create($account); 
                    $this->currentNewAccountBuilder = null;
                    $this->view->displayAccountCreationSucces();
                }
                //Login déja existant
                else {
                    $this->currentNewAccountBuilder=$accoutBuild;
                    $this->view->displayAccountCreationFailureLogin($account->getLogin());
                }
                
            }
            //Sinon afficher le formulaire et les erreurs commises
            else {
                $this->currentNewAccountBuilder=$accoutBuild;
                $this->view->displayAccountCreationFailure();
            
            }
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }
    
    //Affiche information de l'utilisateur connecté
    public function showAccountInformation($id){
        $account=$this->accountStore->read($id);
        // Compte qui existe dans la base et verifier que l'admin est bien connecté(l'utilisateur n'est pas autoriser à modifier son compte)
        if($account!==null && $this->AuthManager->isAdminConnected())
        {
            $this->view->makeAccountPage($account,$id); 
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }

    //Affiche la liste des comptes pour l'administrateur
    public function showListAccount()
    {
        if($this->AuthManager->isAdminConnected())
        {
            $accountTab=$this->accountStore->readAll();
            $this->view->makeListAccountPage($accountTab);
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }
    
    //Demande modif account
    public function askAccountModif($id)
    {
         // ID du compte present dans la SESSION de modification remplire le formulaire avec
         if (key_exists($id, $this->modifAccountBuilder)) {

            $this->view->makeAccountModifPage($id, $this->modifAccountBuilder[$id]);
            return;
        }
        
        //Sinon recuperer compte correspondant dans la base 
        $account=$this->accountStore->read($id);
        //Seulement l'admin peut modifier le compte 
        if($account!==null && $this->AuthManager->isAdminConnected()) {
            $accountBuild= AccountBuilder::ModifAccountInstance($account); 
            $this->view->makeAccountModifPage($id,$accountBuild);
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }

    //Sauvegarde le compte modifé
    public function saveAccountModification($id,array $data)
    {
        $account=$this->accountStore->read($id);
        if($account!==null && $this->AuthManager->isAdminConnected())
        {
            $accountBuild=new AccountBuilder($data);
            if($accountBuild->isValidCreation())
            {
                $accountBuild->updateAccount($account);
                $ok=$this->accountStore->update($id,$account);
                //Si pb mise a jour retourner exeption
                if (!$ok)
                {
                    throw new Exception("Erreur de mise à jour ");
                }
                unset($this->modifAccountBuilder[$id]);
                $this->view->displayAccountModificationPage($id);
            }
            else {
                $this->modifAccountBuilder[$id]=$accountBuild;
                $this->view->displayAccountNoModificationPage($id); // Si erreur rafficher page modif

            }
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }

    //Demande suppresion compte
    public function askAccountDeletion($id)
    {
        $account=$this->accountStore->read($id);
        if($account!==null && $this->AuthManager->isAdminConnected())
        {
            $this->view->makeAcountDeletionPage($id);
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }

    //Suppression du compte
    public function deleteAccount($id)
    {
        $account=$this->accountStore->read($id);
        if($account!==null && $this->AuthManager->isAdminConnected())
        {
            $this->accountStore->delete($id);
            //Supprésion du compte admin entraine la déconnexion et un feedback adapté
            if($account->getStatut()==="admin")
            {
               unset($_SESSION['user']);
               $this->view->displayAccountDeletePageAdmin($account->getNom());
            }
            else {
            $this->view->displayAccountDeletePage($account->getNom());
            }
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }

    //Demande deconnexion utilisateur connecté
    public function askDeconnectionUser()
    {
        $account=$this->AuthManager->isUserConnected();
        if($account!==null)
        {
            $this->view->makeDeconnexionPage($account->getNom());

        }
        //Retour à l'accueil 
        else {
            $this->view->indexPage("");
        }


    }

    //Deconnecte l'utilisateur
    public function deconnectUser()
    {
        if($this->AuthManager->disconnectUser())
        {
            $this->view->displayAccountDeconnexion();
        }
    }

    



}