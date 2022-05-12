<?php
require_once "View.php";

class UserView extends View {

    private $account;

    public function __construct($r,$f,$a)
    {
        parent::__construct($r,$f);
        $this->account=$a;
        $this->menu=$this->MenuPage(); //Création menu
    }

    public function MenuPage()
    {
        //Lien vers accueil,Liste objet ,Creation nouvelle objet
        $menu="<nav class ='menu'>"."\n"."<ul>"."\n";
        $menu.="<li><a href =\"".$this->routeur->getindexURL()."\">Accueil</a></li>"."\n";
        $menu.="<li><a href =\"".$this->routeur->getListeURL()."\">Galerie boxeurs</a></li>"."\n";
        $menu.="<li><a href =\"".$this->routeur->getBoxeurCreationURL()."\">Création d'un boxeur</a></li>"."\n";
        $menu.="<li><a href =\"".$this->routeur->getURLAskDeconnexion()."\">Se deconnecter</a></li>"."\n";
        $menu.="</ul>"."\n"."</nav>"; 
        return $menu;
    } 
    public function indexPage()
    {
        $this->title="Accueil";
        $this->content="<h1>Bienvenue ".$this->account->getNom()."</h1>"."\n";
        $this->content.="<p>Vous êtes sur la page d'accueil et vous avez le statut ".$this->account->getStatut().".</p>"."\n";
        $this->content.="<p> Ce site permet d'ajouter vos boxeurs favoris et de consulter ceux ajouté par les différents utilisateurs du site. </p>"."\n";
    }

   
    public function makeListPage($tableauBoxeur)
    {
        $this->title="Galerie des boxeurs";
        $this->content="<h1>$this->title</h1>"."\n";
        $this->content.=$this->getFormulaireRecherche();
        $this->content.= $this->getRadioTrie();
        $this->content.="<h2> Découvre les caractéristiques de ton boxeur préféré ! </h2>"."\n";
        $this->content.="<div class =galerie >"."\n";
        
        foreach($tableauBoxeur as $keys => $value)
        {
            $this->content.="<div class =grille>"."\n";
            $name=$value->getNom();
            $prenom=$value->getPrenom();
            $url = $this->routeur->getBoxeurURL($keys);
            $this->content.="<a href =\"$url\">"."\n";
            $this->content.="<h3>$name"." ".$prenom."</h3>"."\n";
            $this->content.="<img src=\"".$value->getURLimg()."\" alt=\""." Image de $name"."\">"."\n"."</a>"."\n";
            $this->content.="</div>"."\n";

        }
        $this->content.="</div>"."\n";
        
    }
    //Page boxeur avec accès au modif et delete
    public function makePrivateBoxeurPage($boxeur,$id)
    {
        $this->makeBoxeurPage($boxeur,$id);
        $this->content.="<div class ='modifandsup'>"."\n";
        $this->content.="<ul>"."\n";
        $this->content.="<li> <a href =\"".$this->routeur->getBoxeurModifURL($id)."\">Modifier ".$boxeur->getNom()."</a> </li>"."\n";
        $this->content.="<li> <a href =\"".$this->routeur->getBoxeurAskDeletionURL($id)."\">Supprimer ".$boxeur->getNom()."</a> </li>"."\n";
        $this->content.="</ul>"."\n";
        $this->content.="</div>"."\n";
    }

    //Page utilisateur
    public function makeAccountPage($account,$id)
    {
        $this->title="Account page of user ".$account->getLogin();
        $this->content="<h2>Profil de l'utilisateur ". $account->getNom()."</h2>"."\n";
        $this->content.="<div class ='modifandsup'>"."\n";
        $this->content.="<ul>"."\n";
        $this->content.="<li> <a href =\"".$this->routeur->getAccountModifURL($id)."\">Modifier le compte de ".$account->getNom()."</a> </li>"."\n";
        $this->content.="<li> <a href =\"".$this->routeur->getAccountAskDeletionURL($id)."\">Supprimer le compte ".$account->getNom()."</a> </li>"."\n";
        $this->content.="</ul>"."\n";
        $this->content.="</div>"."\n";

    }
    //Page modification du compte
    public function makeAccountModifPage($id,AccountBuilder $AccountBuilder)
    {
        $this->title="Modification du compte.";
        $this->content="<div class='formulaire'>"."\n";
        $this->content.="<h2> Modifier le compte </h2>"."\n";
        $this->content.='<form action="'.$this->routeur->getAccountModifSave($id).'" method="POST">'."\n";
        $this->content.=$this->getFormulaireAccount($AccountBuilder,"modif");
        $this->content.= "<button>Modifier</button>"."\n";
        $this->content.="</form>"."\n";
        $this->content.="</div>"."\n";
    }
    //Page confirmation de la supression d'un compte
    public function makeAcountDeletionPage($id)
    {
        $this->title="Suppression du compte ";
        $this->content="<h1>$this->title</h1>"."\n";
        $this->content.="<div class =avertissement>"."\n";
        $this->content.="<p>Etes vous sur de vouloir supprimer le compte ?</p>"."\n";
        $this->content.="</div>"."\n";
        $this->content.='<form action="'.$this->routeur->getAccountDeletionURL($id).'" method="POST">'."\n";
        $this->content.= "<button>Confirmer</button>"."\n"."</form>"."\n";
    }

/////Redirection///////
/////////////////////////////////////////////////////////
    public function displayAccountModificationPage($id)
    {
        $this->routeur->POSTredirect($this->routeur->getAccountURL($id), $this->positiveFeedBack("Le compte a bien été modifié!"));
        
    }
    public function displayAccountNoModificationPage($id)
    {
        $this->routeur->POSTredirect($this->routeur->getAccountModifURL($id), $this->negativeFeedBack("Erreur dans le formulaire !"));

    }
    public function displayAccountDeletePage($nom)
    {
        $this->routeur->POSTredirect($this->routeur->getURLgestionAccount(), $this->positiveFeedBack("Le compte ".$nom." a bien été supprimée !"));

    }
    


}

?>