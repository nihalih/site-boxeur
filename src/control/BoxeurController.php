<?php 
require_once "model/boxeur_model/Boxeur.php";
require_once "model/boxeur_model/BoxeurBuilder.php";
require_once "model/boxeur_model/BoxeurStorageMySQL.php";

class BoxeurController {

    private $view;
    private $boxeurStore; 
    private $currentBoxeurBuilder;
    private $modifBoxeurBuilder;
    private $AuthManager;

    public function __construct($view,$boxeurStore,$auth)
    {
        $this->view=$view;
        $this->boxeurStore = $boxeurStore;
        $this->AuthManager=$auth;
        $this->currentBoxeurBuilder = key_exists('currentNewBoxeur', $_SESSION) ? $_SESSION['currentNewBoxeur'] : null;
		$this->modifBoxeurBuilder = key_exists('modifBoxeur', $_SESSION) ? $_SESSION['modifBoxeur'] : array();
    }

    //Lorsque plus de référence sur la classe stockage dans les différentes session
    public function __destruct() {
		$_SESSION['currentNewBoxeur'] = $this->currentBoxeurBuilder;
        $_SESSION['modifBoxeur'] = $this->modifBoxeurBuilder;
	}
    

    //Affiche le boxeur selon l'id en parametre 
    public function showInformation($id) {
        $boxeur=$this->boxeurStore->read($id);
        // SI non null erreur 
        if($boxeur!==null ) 
        {
            //Vérifie si l'utilisateur actuel est connecté ou l'admin
            if($this->AuthManager->verifUser($boxeur->getLogin()) || 
            $this->AuthManager->isAdminConnected())
            {
                $this->view->makePrivateBoxeurPage($boxeur,$id); 
            }
            else {
            $this->view->makeBoxeurPage($boxeur,$id); 
            }
        }
        else {
            $this->view->makeUnknownBoxeurPage();
        }
    }

   
    //Affiche galerie boxeur
    public function showList()
    {
        $boxeurTab=$this->boxeurStore->readAll();
        $this->view->makeListPage($boxeurTab);
        
    }
    //Galerie boxeur selon une recherche
    public function showListRecherche($data)
    {
        $boxeurTab=$this->boxeurStore->readAll();
        if(key_exists("recherche",$data) && $data["recherche"]!=="")
        {
            //Tableau qui retourne les boxeur correspondant à la recherhce
            $tabBoxeurRecherche=array();
            $recherche=htmlspecialchars($data["recherche"],ENT_QUOTES);
            foreach($boxeurTab as $keys => $boxeur)
            {
                //Recherche occurance
                if(strpos(strtolower($boxeur->getDescription()),strtolower($recherche))!==false){
                    $tabBoxeurRecherche[$keys]=$boxeur;
                }
            }

            //METTRE FEEDBACK SI TABLEAU VIDE AVEC PAGE PAR DEFAULT
            if(sizeof($tabBoxeurRecherche)===0)
            {
                $this->view->displayRechercheFailure();
            }
            else {
                $this->view->makeRecherchePage($tabBoxeurRecherche);
            }
        }
        else {
            $this->view->displayRechercheFailure();

        }
    }

    //Galerie boxeur triée
    public function showListTrie($data)
    {
        $boxeurTab=$this->boxeurStore->readAll();
        if(key_exists("trie",$data) && $data["trie"]!=="")
        {
            //Tableau tri l'attribut spécifié , tab retour les boxeur triés
            $tabBoxeurTri=array();
            $tabRetour=array();
            $trie=htmlspecialchars($data["trie"],ENT_QUOTES);
            

            //Création d'un tableau avec indice id du boxeur et valeur de l'attribut recherché
            foreach($boxeurTab as $id => $boxeur)
            {
                $attributTri=strtolower($boxeur->getAttribut($trie));
                $tabBoxeurTri[$id]=$attributTri;
            }

            //Récupération du boxeur selon l'attribut le plus petit et la clé du boxeur
            foreach($tabBoxeurTri as $value)
            {
                $idMin=array_keys($tabBoxeurTri, min($tabBoxeurTri));
                $tabRetour[$idMin[0]]=$this->boxeurStore->read($idMin[0]);
                unset($tabBoxeurTri[$idMin[0]]); // Supprimer pour ne pas retomber dessus 
            }
            $this->view->makeTriePage($tabRetour);
            
        }
        else {
            $this->view->displayTrieFailure();

        }

    }

 


    
    //Renvoi le formulaire de création d'un boxeur basic
    public function newBoxeur() {
		/* Affichage du formulaire de création*/
        //Recuperer le boxeur builder si existe en session
        if($this->currentBoxeurBuilder===null)
        {
            $this->currentBoxeurBuilder = new BoxeurBuilder([]);
        }   
        
        $this->view->makeBoxeurCreationPage($this->currentBoxeurBuilder);
    	}

    // Affichage sauvegarder boxeur dans base
    public function saveNewBoxeur(array $data)
    {
        $boxeurBuild=new BoxeurBuilder($data); // Boxeur en cours de manipulation
        //Si toutes les clé de data sont celle correspondante au boxeurbuilder
        if($boxeurBuild->KeyExistBoxeur()===true)
        {
            //Si les champs sont remplis correctement créer nouveau boxeur
            if($boxeurBuild->isValid())
            {
                $boxeur=$boxeurBuild->createBoxeur($_SESSION['login']); // Créé un boxeur
                $id=$this->boxeurStore->create($boxeur); // ajoute nouveau boxeur dans la base et retourne id
                $this->currentBoxeurBuilder = null;
                $this->view->displayBoxeurCreationSuccess($id);
            }
            //Sinon afficher le formulaire et les erreurs commises
            else {
                $this->currentBoxeurBuilder=$boxeurBuild;
                $this->view->displayBoxeurCreationFailure();
            
            }
        }
        else {
            $this->view->makeUnkownActionPage();
        }
        
    }

    //Affiche une page erreur si l'id de supprésion de l'objet nexiste pas sinon envoi sur le page confirmation
    public function askBoxeurDeletion($id)
    {
        $boxeur=$this->boxeurStore->read($id);
       
        if($boxeur!==null && ($boxeur->getLogin()===$_SESSION['login'] || $_SESSION['statut']==='admin'))
        {
            $this->view->makeBoxeurDeletionPage($id);
        }
        else {
            $this->view->makeUnkownActionPage();
        }
       
    }

    //Supprimer definitivement le boxeur
    public function deleteBoxeur($id)
    {
        $boxeur=$this->boxeurStore->read($id);
        if($boxeur!==null && ($boxeur->getLogin()===$_SESSION['login'] || $_SESSION['statut']==='admin'))
        {
            $this->boxeurStore->delete($id);
            $this->view->displayBoxeurDeletePage();
        }
        else {
            $this->view->makeUnkownActionPage();

        }
        
    }

    //Element modifier
    public function askBoxeurModif($id)
    {
        // ID du boxeur present dans la SESSION de modification remplire le formulaire avec
        if (key_exists($id, $this->modifBoxeurBuilder)) {
            //Page formulaire
            $this->view->makeBoxeurModifPage($id, $this->modifBoxeurBuilder[$id]);
            return;
        }
        
        //Sinon recuperer boxeur correspondant dans la base 
        $boxeur=$this->boxeurStore->read($id); 
        if($boxeur!==null && ($boxeur->getLogin()===$_SESSION['login'] || $_SESSION['statut']==='admin')) {
             // boxeurbuilder correspondant au boxeur
            $boxeurbuild= BoxeurBuilder::ModifBoxeurInstance($boxeur); 
            $this->view->makeBoxeurModifPage($id,$boxeurbuild);
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }

    //Sauvegarde modification boxeur
    public function saveBoxeurModification($id,array $data)
    {
        //Récupérer 
        $boxeur=$this->boxeurStore->read($id);
       
        if($boxeur!==null && ($boxeur->getLogin()===$_SESSION['login'] ||
         $_SESSION['statut']==='admin')) {
            $boxeurBuild=new BoxeurBuilder($data);
            if($boxeurBuild->isValid())
            {
                $boxeurBuild->updateBoxeur($boxeur); // Mettre a jour le boxeur du builder
                $ok=$this->boxeurStore->update($id,$boxeur); // Met a jour le boxeur dans la base et retourne boolean pour la mise a jour ou non
                
                //Si non mise a jour retourner exeption
                if (!$ok)
                {
                    throw new Exception("Erreur de mise à jour ");
                }
                unset($this->modifBoxeurBuilder[$id]);
                $this->view->displayBoxeurModificationPage($id); // Afficher page du boxeur

            }
            else {
                $this->modifBoxeurBuilder[$id]=$boxeurBuild;
                $this->view->displayBoxeurNoModificationPage($id); // Si erreur rafficher page modif
            }
        }
        else {
            $this->view->makeUnkownActionPage();
        }
    }

   
}

?>