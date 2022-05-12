<?php 
require_once "view/View.php";
require_once "view/UserView.php";
require_once "view/AdminView.php";
require_once "control/BoxeurController.php";
require_once "control/AccountController.php";
require_once "model/account_model/AuthentificationManager.php";

class Router {
    private $vue;
    private $controlerBoxeur;
    private $controlerAccount;
    private $autorisedAction;
    public function __construct()
    {
        $this->autorisedAction=array();
    }

    // URL page d'accueil
    public function getindexURL()
    {
        return ".";
    }
    //URL de l'objet correspondant à l'identifiant
    public function getBoxeurURL($id)
    {
        return ".?boxeur=$id";
    }
    //URL de creation 
    public function getBoxeurCreationURL()
    {
        return ".?action=nouveauBoxeur";
    }

    //URL de sauvegarde d'une création
    public function getBoxeurSaveURL()
    {
        return ".?action=sauverNouveauBoxeur";
    }
    //URL de la liste des objets
    public function getListeURL()
    {
        return ".?action=galerie";
    }
    //URL confirmation supprésion d'un objet par son id
    public function getBoxeurAskDeletionURL($id)
    {
        return ".?boxeur=$id&amp;action=AskSupprimerBoxeur";
    }
    //URL qui supprime l'objet correspondant à l'id
    public function getBoxeurDeletionURL($id)
    {
        return ".?boxeur=$id&amp;action=ConfirmationSupprimerBoxeur";
    }

    //URL modification
    public function getBoxeurModifURL($id)
    {
        return ".?boxeur=$id&amp;action=modifierBoxeur";
    }

    //URL appliquer les modification au boxeur
    public function getBoxeurModifSave($id)
    {
        return ".?boxeur=$id&amp;action=SaveModifBoxeur";

    }
    public function getURLAbout()
    {
        return ".?action=about";
    }
    public function getAccountURL($id)
    {
        return ".?account=$id";
    }
    //Url page de connexion
    public function getURLAskConnexion()
    {
        return ".?action=connexion";
    }
    //Url page user connecté
    public function getURLConnected()
    {
        return ".?action=isconnected";
    }
    //Url page user deconnecté
    public function getURLAskDeconnexion()
    {
        return ".?action=deconnexion";
    }
    //Url confirmation deconnexion
    public function getURLConfirmDeconnexion()
    {
        return ".?action=confirmDeconnecion";
    }

    public function getURLCreateAccount()
    {
        return ".?action=newAccount";
    }
    public function getURLSaveAccount()
    {
        return ".?action=SaveAccount";
    }
    public function getURLRecherche()
    {
        return ".?action=recherche";
    }
    public function getURLTrie()
    {
        return ".?action=trie";
    }
    public function getURLgestionAccount()
    {
        return ".?action=GestionAccount";
    }
    //Modification compte
    public function getAccountModifURL($id)
    {
        return ".?account=$id&amp;action=ModifAccount";
    }
    //Modification compte appliqué
    public function getAccountModifSave($id)
    {
        return ".?account=$id&amp;action=SaveModifAccount";

    }
    public function getAccountAskDeletionURL($id)
    {
        return ".?account=$id&amp;action=DeleteAccount";

    }
    public function getAccountDeletionURL($id)
    {
        return ".?account=$id&amp;action=ConfirmDeleteAccount";

    }

    // post redirect 
    public function POSTredirect($url, $feedback) {
		$_SESSION['feedback'] = $feedback;
		header("Location: ".htmlspecialchars_decode($url), true, 303);
		die;
	}

    public function main($BoxeurStorage,$AccountStorage)
    {
        //Nom de session et demarre la session
        session_name("Session-Site-Boxeur");
        session_start();

        //Recupérer feedback si existe
        $feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
        $_SESSION['feedback'] = '';

        //Vue privé 
        if(key_exists("user",$_SESSION))
        {
            if($_SESSION['statut']==='admin')
            {
                $this->vue=new AdminView($this,$feedback,$_SESSION['user']);
            }
            else {
                $this->vue=new UserView($this,$feedback,$_SESSION['user']);
            }
            
        }
        else {
            $this->vue=new View($this,$feedback);
        }
        $AuthManager=new AuthentificationManager();
        
        $this->controlerBoxeur=new BoxeurController($this->vue,$BoxeurStorage,$AuthManager);
        $this->controlerAccount=new AccountController($this->vue,$AccountStorage,$AuthManager);
        

        $boxeurId=key_exists('boxeur', $_GET) ? $_GET['boxeur'] : null;
        $accountId=key_exists('account', $_GET) ? $_GET['account'] : null;
        $action = key_exists('action', $_GET) ? $_GET['action'] : null;

        if($action===null)
        {
            $action =($boxeurId===null)? "accueil" : "voir";
            $action=($action!=="voir" && $accountId!==null)? "voirAccount" : $action;
        }
        
        try {
            //Utilisateur connecté
            if(key_exists('user',$_SESSION))
            {

                
                switch($action){
                case "voir":
                    if($boxeurId===null){
                        $this->vue->makeUnknownBoxeurPage();
                    }
                    else {
                        $this->controlerBoxeur->showInformation($boxeurId);
                    }
                    break;
                case "voirAccount":
                    if($accountId===null)
                    {
                        $this->vue->makeUnknownPage(); 
                    }
                    else {
                        $this->controlerAccount->showAccountInformation($accountId);
                    }
                    break;
                case "nouveauBoxeur":
                    $this->controlerBoxeur->newBoxeur();
                    break;

                case "sauverNouveauBoxeur":
                    $this->controlerBoxeur->saveNewBoxeur($_POST);
                    break;

                case "AskSupprimerBoxeur":
                    if ($boxeurId === null) {
                        $this->vue->makeUnkownActionPage();
                    } 
                    else {
                        $this->controlerBoxeur->askBoxeurDeletion($boxeurId);
                    }
                    break;
        
                case "ConfirmationSupprimerBoxeur":
                    if ($boxeurId === null) {
                        $this->vue->makeUnkownActionPage();
                    } 
                    else {
                        $this->controlerBoxeur->deleteBoxeur($boxeurId);
                    }
                    break;
        
                case "modifierBoxeur":
                    if ($boxeurId === null) {
                        $this->vue->makeUnkownActionPage();
                    } 
                    else {
                        $this->controlerBoxeur->askBoxeurModif($boxeurId);
                    }
                    break;
        
                case "SaveModifBoxeur":
                    if ($boxeurId === null) {
                        $this->vue->makeUnkownActionPage();
                    } 
                    else {
                        $this->controlerBoxeur->saveBoxeurModification($boxeurId, $_POST);
                    }
                    break;
                 
                case "galerie":
                    $this->controlerBoxeur->showList();
                    break;

                case "recherche":
                    $this->controlerBoxeur->showListRecherche($_POST);
                    break;

                case "trie":
                    $this->controlerBoxeur->showListTrie($_POST);
                    break;
                    
                case "accueil":
                    $this->vue->indexPage();
                    break;
                    
                case "about":
                    $this->vue->makeAboutPage();
                    break;

                case "ModifAccount":
                    if($accountId===null)
                    {
                        $this->vue->makeUnkownActionPage();
                    }
                    else {
                        $this->controlerAccount->askAccountModif($accountId);
                    }
                    break;
                
                case "SaveModifAccount":
                    if($accountId===null)
                    {
                        $this->vue->makeUnkownActionPage();
                    }
                    else {
                        $this->controlerAccount->saveAccountModification($accountId,$_POST);
                    }
                    break;

                case "DeleteAccount":
                    if($accountId===null)
                    {
                        $this->vue->makeUnkownActionPage();
                    }
                    else {
                        $this->controlerAccount->askAccountDeletion($accountId);
                    }
                    break;

                case "ConfirmDeleteAccount":
                    if($accountId===null)
                    {
                        $this->vue->makeUnkownActionPage();
                    }
                    else {
                        $this->controlerAccount->deleteAccount($accountId);

                    }
                    break;
                
                case "deconnexion":
                    $this->controlerAccount->askDeconnectionUser();
                    break;
                    
                case "confirmDeconnecion":
                    $this->controlerAccount->deconnectUser();
                    break;
                
                case "GestionAccount":
                    $this->controlerAccount->showListAccount();
                    break;
                
                default:
                    
                    $this->vue->makeUnkownActionPage();
                    break;
            }
        }
            else{
                switch($action){
                
                case "accueil":
                    $this->vue->indexPage();
                    break;

                case "galerie":
                    $this->controlerBoxeur->showList();
                    break;

                case "recherche":
                    $this->controlerBoxeur->showListRecherche($_POST);
                    break;
                    
                case "trie":
                    $this->controlerBoxeur->showListTrie($_POST);
                    break;

                case "connexion":
                    $this->controlerAccount->newConnexion();
                    break;

                case "isconnected":
                    $this->controlerAccount->connectUser($_POST);
                    break;

                case "newAccount":
                    $this->controlerAccount->newAccount();
                    break;

                case "SaveAccount":
                    $this->controlerAccount->saveNewAccount($_POST);
                    break;
                case "about":
                    $this->vue->makeAboutPage();
                    break;
    

                default:
                        /* L'internaute a demandé une action non prévue. */
                    $this->vue->makeUnkownActionPage();
                    break;
            }
        }
        } catch (Exception $e) {
            /* Si on arrive ici, il s'est passé quelque chose d'imprévu
              * (par exemple un problème de base de données) */
            $this->vue->errorPage($e);
        }
    
        /* Enfin, on affiche la page préparée */
        $this->vue->render();
            
        
        }

}