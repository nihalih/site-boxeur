<?php


class View {
    protected $menu;
    protected $TabURLMenu;
    protected $title;
    protected $content;
    protected $routeur;
    protected $feedback;

    public function __construct($r,$f)
    {
       $this->routeur=$r;
       $this->feedback=$f;
       $this->TabURLMenu= array("Accueil"=>$this->routeur->getindexURL(),
                        "Galerie boxeurs"=>$this->routeur->getListeURL(),
                        "Création compte"=>$this->routeur->getURLCreateAccount(),
                        "A propos"=>$this->routeur->getURLAbout(),
                        "Se connecter"=>$this->routeur->getURLAskConnexion()
);

       $this->menu=$this->MenuPage(); //Création menu

    }

    //Inclusion squelette de la vue
    public function render()
    {
        require_once "squeletteView.php";
    }


     ///Page accueil
     public function indexPage()
     {
         $this->title="Accueil";
         $this->content="<h1>Bienvenu ! </h1>"."\n";
         $this->content.="<p>Vous êtes sur la page d'accueil ! Veuillez créer un compte ou vous connecter.</p>"."\n";
         $this->content.="<p> Ce site permet d'ajouter vos boxeurs favoris et de consulter ceux ajouter par les différents membres du site. </p>"."\n";

     }

    //Page sur un objet
    public function makeBoxeurPage($boxeur,$id)
    {
        $this->title="Page sur le boxeur ".$boxeur->getNom() ." ".$boxeur->getPrenom();
        $this->content="<h1>$this->title</h1>"."\n";
        $this->content.="<div class ='boxeurPage'>"."\n";
        $this->content.="<img src =\"".$boxeur->getURLimg()."\" alt=\"".$boxeur->getNom()."\">"."\n";
        $this->content.="<p>".$boxeur->getDescription()."</p>"."\n";
        $this->content.="</div>"."\n";
    }



    //Page liste boxeur
    public function makeListPage($tableauBoxeur)
    {
        $this->title="Galerie des boxeurs";

        $this->content="<h1>$this->title</h1>"."\n";
        $this->content.=$this->getFormulaireRecherche();
        $this->content.=$this->getRadioTrie();
        $this->content.="<div class =galerie >"."\n";

        foreach($tableauBoxeur as $keys => $value)
        {
            $this->content.="<div class =grille>"."\n";
            $name=$value->getNom();
            $prenom=$value->getPrenom();
            $this->content.="<h3>$name"." ".$prenom."</h3>"."\n";
            $this->content.="<img src=\"".$value->getURLimg()."\" alt=\""." Image de $name"."\">"."\n";
            $this->content.="</div>"."\n";

        }
        $this->content.="</div>"."\n";



    }
    //Page resultant de la recherce
    public function makeRecherchePage($tableauBoxeur)
    {
        $this->makeListPage($tableauBoxeur);
        $this->title="Résultat de la recherche";
    }
    //Page resultant du trie
    public function makeTriePage($tableauBoxeur)
    {
        $this->makeListPage($tableauBoxeur);
        $this->title="Résultat du trie";
    }



    //Page formulaire de création d'un objet
    public function makeBoxeurCreationPage(BoxeurBuilder $BoxeurBuild)
    {
        //Création formulaire
        $this->title="Création boxeur";
        $this->content="<div class='formulaire'>"."\n";
        $this->content.="<h2> Créer un boxeur </h2>"."\n";
        $this->content.='<form action="'.$this->routeur->getBoxeurSaveURL().'" method="POST">'."\n";
        $this->content.=$this->getFormulaireBoxeur($BoxeurBuild);
        $this->content.="<button type='submit'>Créer </button>"."\n";
        $this->content.="</form>"."\n";
        $this->content.="</div>"."\n";

    }

    //Page confirmation de la supression d'un objet
    public function makeBoxeurDeletionPage($id)
    {
        $this->title="Suppression du boxeur ";
        $this->content="<h1>$this->title</h1>"."\n";
        $this->content.="<div class =avertissement>"."\n";
        $this->content.="<p>Etes vous sur de vouloir supprimer le boxeur ?</p>"."\n";
        $this->content.="</div>"."\n";
        $this->content.='<form action="'.$this->routeur->getBoxeurDeletionURL($id).'" method="POST">'."\n";
        $this->content.= "<button>Confirmer</button></form>"."\n";
    }

    //Affichage page confirmation suppression
    public function makeBoxeurDeletePage()
    {
        $this->title="Suppression effectuée";
        $this->content="<h1>$this->title</h1>"."\n";
        $this->content.="<p>Boxeur correctement supprimer ! </p>"."\n";
    }
    // Page modification d'objet par un formulaire
    public function makeBoxeurModifPage($id,BoxeurBuilder $BoxeurBuild)
    {
        $this->title="Modification du boxeur.";
        $this->content="<div class='formulaire'>"."\n";
        $this->content.="<h2> Modifier le boxeur </h2>"."\n";
        $this->content.='<form action="'.$this->routeur->getBoxeurModifSave($id).'" method="POST">'."\n";
        $this->content.=$this->getFormulaireBoxeur($BoxeurBuild);
        $this->content.= "<button>Modifier</button>"."\n";
        $this->content.="</form>"."\n";
        $this->content.="</div>"."\n";

    }

    //Page de connexion
    public function makeLoginFormPage(AccountBuilder $accountbuilder)
    {
        $this->title="Page de connexion.";
        $this->content="<div class='formulaire'>"."\n";
        $this->content.="<h2> Connexion </h2>"."\n";
        $this->content.='<form action="'.$this->routeur->getURLConnected().'" method="POST">'."\n";
        $this->content.=$this->getFormulaireAccount($accountbuilder,null);
        $this->content.="<button type='submit'>Se connecter</button>"."\n";
        $this->content.="</form>"."\n";
        $this->content.="</div>"."\n";

    }
    //Page déconnexion
    public function makeDeconnexionPage($name)
    {
        $this->title="Page de deconnexion.";
        $this->content="<h1>Déconnexion</h1>"."\n";
        $this->content.="<div class='avertissement'>"."\n";
        $this->content.="<h3>$name souhaitez vous vous déconnecter ? </h3>"."\n";
        $this->content.="</div>"."\n";
        $this->content.="<div class='buttondeco'>"."\n";
        $this->content.='<form action="'.$this->routeur->getURLConfirmDeconnexion().'" method="POST">'."\n";
        $this->content.="<button name='logout'>Se déconnecter</button></form>"."\n";
        $this->content.="</div>"."\n";
    }

    public function makeCreateAccountPage(AccountBuilder $accountBuiler)
    {
        $this->title="Page création de compte.";
        $this->content="<div class='formulaire'>"."\n";
        $this->content.="<h2> Création compte </h2>"."\n";
        $this->content.='<form action="'.$this->routeur->getURLSaveAccount().'" method="POST">'."\n";
        $this->content.=$this->getFormulaireAccount($accountBuiler,"yes");
        $this->content.="<button type='submit'>Créer un compte </button>"."\n";
        $this->content.="</form>"."\n";
        $this->content.="</div>"."\n";


    }
    public function makeAboutPage()
    {
        $this->title="A propos.";
        $this->content="<h1>$this->title</h1>"."\n";
        $this->content.="<h3> Choix du sujet sur les boxeurs </h3>"."\n";
        $this->content.="<p>Le sujet de la boxe en général est un sport que le binôme du <strong>GROUPE 11</strong> <strong>Ihannouba Nihal 22009099</strong> et <strong>Kermezian Axel 22008897</strong> affectionne particulièrement.</p>"."\n";
        $this->content.="<p>Nous avons opté pour un travail en cohésion , en nous concertant sur les différentes tâches à implémenter.</p>"."\n";
        $this->content.="<p>Les tâches ont été définis lors de l'implémentation de l'authentification en MVCR car le modèle MVCR ayant été etudié pour la partie précedente pouvait être à présent implémenté sur l'authentification.</p>"."\n";
        $this->content.="<p>Un membre était chargé du controlleur tandis que le second membre c'est chargé du modèle.</p>"."\n";
        $this->content.="<p> La base de donnée contient trois boxeurs possédant une image qui leur est propre. Les boxeurs créés par un utilisateur connecté reçois une image par default qui seras identique à tous les boxeurs créés.</p>"."\n";
        $this->content.="<p> Les pages du site ne sont pas adaptées à chaque format (nous avons essayés de faire au mieux sans redefinir les propriété des éléments selon la taille de la fenêtre).</p>"."\n";
        $this->content.="<p>Le but était d'éviter les collisions entre éléments au maximum,le site n'est donc pas totalement responsive. </p>"."\n";
        $this->content.="<h4>Options réalisées :</h4>"."\n";
        $this->content.="<ul>"."\n";
        $this->content.="<li> Recherche : barre de recherche au niveau de la galerie des boxeurs( permet de rechercher les boxeurs selon un mot ou une phrase contenu dans leur description).</li>"."\n";
        $this->content.="<li> Trie : Plusieurs bouttons radio pour trier la galerie des boxeurs selon leur Nom,prenom,age,poids"."\n"."(Peut être également implémenter selon l'ordre décroissant en changeant le min par max dans la fonction showListTrie du BoxeurController) "."\n"."</li>"."\n";
        $this->content.="<li> Partie admin : Gestion des comptes uniquement visible pour l'administrateur au sein de sa barre de menu. Permet de modifier ou supprimer les différents comptes (le compte admin inclus)."."\n";
        $this->content.=" Les comptes utilisateurs ne peuvent ni modifier ni supprimer leur propre compte."."\n"."Cela peut être modifier simplement par l'ajout d'une ligne de code au niveau du controleur dans les différentes méthodes liées à la gestion des comptes."."\n"."</li>"."\n";
        $this->content.="</ul>"."\n";
    }

    /////ERREUR PAGE /////
     //Page objet inconnu
     public function makeUnknownBoxeurPage()
     {
         $this->title="Erreur";
         $this->content="<h1>$this->title</h1>"."\n";
         $this->content.="<p>Boxeur inconnu !</p>"."\n";
     }
     //Page introuvable
     public function makeUnkownActionPage()
     {
         $this->title="Erreur";
         $this->content="<h1>$this->title</h1>"."\n";
         $this->content.="<p>La page demandée est introuvable.</p>"."\n";
     }
    //Page d'erreur personnalisable avec la variable $a
    public function errorPage($a)
    {
        $this->title="Error Page";
        $this->content="<h1>$this->title</h1>"."\n";
        $this->content.="<p> $a </p>"."\n";
    }
    //Permet d'afficher le contenu de la variable en mode debug
    public function makeDebugPage($variable) {
        $this->title = 'Debug';
        $this->content="<h1>$this->title</h1>"."\n";
        $this->content .= '<pre>'.htmlspecialchars(var_dump($variable, true)).'</pre>'."\n";
    }

    /////////////////////////////////////////////////////////////

    //Renvoi un Formulaire pour création et modification d'un boxeur
    public function getFormulaireBoxeur(BoxeurBuilder $BoxeurBuild )
    {

        //Récpérer les constante des champs pour le formulaire dans Boxeur builder
        $champ1=$BoxeurBuild::NAME_REF;
        $champ2=$BoxeurBuild::PRENOM_REF;
        $champ3=$BoxeurBuild::AGE_REF;
        $champ4=$BoxeurBuild::POIDS_REF;
        $champ5=$BoxeurBuild::ORIGINE_REF;
        $champ6=$BoxeurBuild::CHAMPION_REF;


        //Recupère les erreurs et ajoute la balise div
        $erreurNom=$this->errordiv($BoxeurBuild->getErrorConstante($champ1));
        $erreurPrenom=$this->errordiv($BoxeurBuild->getErrorConstante($champ2));
        $erreurAge=$this->errordiv($BoxeurBuild->getErrorConstante($champ3));
        $erreurPoids=$this->errordiv($BoxeurBuild->getErrorConstante($champ4));
        $erreurOrigine=$this->errordiv($BoxeurBuild->getErrorConstante($champ5));
        $erreurChampion=$this->errordiv($BoxeurBuild->getErrorConstante($champ6));

        // formulaire
        $tmp="<div class=textForm>"."\n";
        $tmp.='<p>'."\n".'<label>Nom : <input type="text" name='.$champ1.' value="'.$BoxeurBuild->getDataRef($champ1).'"/></label> '."\n".$erreurNom."\n".'</p>'."\n";
        $tmp.='<p>'."\n".'<label>Prenom : <input type="text" name='.$champ2.' value="'.$BoxeurBuild->getDataRef($champ2).'"/></label> '."\n".$erreurPrenom."\n".'</p>'."\n";
        $tmp.='<p>'."\n".'<label>Age : <input type="text" name='.$champ3.' value="'.$BoxeurBuild->getDataRef($champ3).'"/></label> '."\n".$erreurAge."\n".'</p>'."\n";
        $tmp.='<p>'."\n".'<label>Poids : <input type="text" name='.$champ4.' value="'.$BoxeurBuild->getDataRef($champ4).'"/></label> '."\n".$erreurPoids."\n".'</p>'."\n";
        $tmp.='<p>'."\n".'<label>Origine: <input type="text" name='.$champ5.' value="'.$BoxeurBuild->getDataRef($champ5).'"/></label> '."\n".$erreurOrigine."\n".'</p>'."\n";
        $tmp.='<p>'."\n".'<label>Champion (o/n) : <input type="text" name='.$champ6.' value="'.$BoxeurBuild->getDataRef($champ6).'"/></label> '."\n".$erreurChampion."\n".'</p>'."\n";
        $tmp.="</div>"."\n";
        return $tmp;
    }

    //Renvoi le formulaire de la création d'un compte ou connexion selon le deuxième paramètre
    public function getFormulaireAccount(AccountBuilder $accountbuilder,$connexionORcreation)
    {
        //Récpérer les constante des champs pour le formulaire dans Boxeur builder
        $champ1=$accountbuilder::LOGIN_REF;
        $champ2=$accountbuilder::PASSWORD_REF;
        $champ3=$accountbuilder::NAME_REF;
        //Recupère les erreurs
        $erreurLogin=$this->errordiv($accountbuilder->getErrorConstante($champ1));
        $erreurPassword=$this->errordiv($accountbuilder->getErrorConstante($champ2));
        $erreurName=$this->errordiv($accountbuilder->getErrorConstante($champ3));
        // formulaire
        $tmp="<div class=textForm>"."\n";
        $tmp.='<p>'."\n".'<label>Login : <input type="text" name='.$champ1.' value="'.$accountbuilder->getDataRef($champ1).'"/></label>'."\n".$erreurLogin."\n".'</p>'."\n";
        $tmp.='<p>'."\n".'<label>Mot de passe : <input type="password" name='.$champ2.' value="'.$accountbuilder->getDataRef($champ2).'"/></label>'."\n".$erreurPassword."\n".'</p>'."\n";
        if($connexionORcreation!==null)
        {
            $tmp.='<p>'."\n".'<label>Nom : <input type="text" name='.$champ3.' value="'.$accountbuilder->getDataRef($champ3).'"/></label>'."\n".$erreurName."\n".'</p>'."\n";

        }
        $tmp.="</div>"."\n";

        return $tmp;

    }
    //Label recherche
    public function getFormulaireRecherche()
    {
        $rechercheForm="<div class ='recherche'>"."\n";
        $rechercheForm.='<form action="'.$this->routeur->getURLRecherche().'" method="POST">'."\n";
        $rechercheForm.='<p>'."\n".'<label> <input type="text" name=recherche placeholder="rechercher" /></label>'."\n";
        $rechercheForm.="<button name='search'>Rechercher</button>"."\n"."</p>"."\n";
        $rechercheForm.="</form>"."\n";
        $rechercheForm.="</div>"."\n";
        return $rechercheForm;
    }
    //Radio button trie
    public function getRadioTrie()
    {
        $trie="<div class='trie'>"."\n";
        $trie.='<form action="'.$this->routeur->getURLTrie().'" method="POST">'."\n";
            $trie.="<fieldset>"."\n";
                $trie.="<legend>Trie des boxeurs selon :</legend>"."\n";
                $trie.='<p>'."\n".'<label><input type="radio" value="nom" name="trie" /> Nom</label>'."\n";
                $trie.='<label><input type="radio" value="prenom" name="trie" /> Prénom</label>'."\n";
                $trie.='<label><input type="radio" value="age" name="trie" /> Age</label>'."\n";
                $trie.='<label><input type="radio" value="poids" name="trie" /> Poids</label>'."\n".'</p>'."\n";
                $trie.='<button type=submit>Trier</button>'."\n";
            $trie.='</fieldset>'."\n";
        $trie.='</form>'."\n";
        $trie.="</div>"."\n";
        return $trie;
    }

    //Barre de menu
    public function MenuPage()
    {
        //Lien vers accueil,Liste objet ,Creation nouvelle objet
        $menu="<nav class ='menu'> "."\n";
        $menu.="<ul>"."\n";
        foreach($this->TabURLMenu as $champ => $valueURL)
        {
            $menu.="<li><a href =\"$valueURL \">$champ</a></li>"."\n";
        }
        $menu.="</ul>"."\n"."</nav>"."\n";
        return $menu;
    }


    //////************* Post-Redirect-Get ************//////
    public function displayBoxeurCreationSuccess($id)
    {
        $this->routeur->POSTredirect($this->routeur->getBoxeurURL($id), $this->positiveFeedBack("Le boxeur a bien été créé !"));

    }
    public function displayBoxeurCreationFailure()
    {
        $this->routeur->POSTredirect($this->routeur->getBoxeurCreationURL(),$this->negativeFeedBack("Erreurs dans le formulaire."));
    }

    public function displayBoxeurDeletePage()
    {
        $this->routeur->POSTredirect($this->routeur->getListeURL(), $this->positiveFeedBack("Le boxeur a bien été supprimée !"));

    }
    public function displayBoxeurModificationPage($id)
    {
        $this->routeur->POSTredirect($this->routeur->getBoxeurURL($id), $this->positiveFeedBack("Le boxeur a bien été modifié!"));
    }
    public function displayBoxeurNoModificationPage($id)
    {
        $this->routeur->POSTredirect($this->routeur->getBoxeurModifURL($id), $this->negativeFeedBack("Erreur dans le formulaire !"));

    }
    public function displayAccountConnexion($name)
    {
        $this->routeur->POSTredirect($this->routeur->getindexURL(),$this->positiveFeedBack("Connecté en tant que $name !"));
    }
    public function displayAccountNoConnexion()
    {
        $this->routeur->POSTredirect($this->routeur->getURLAskConnexion(),$this->negativeFeedBack("Erreur de connnexion ! "));
    }
    public function displayAccountDeconnexion()
    {
        $this->routeur->POSTredirect($this->routeur->getURLAskConnexion(), $this->positiveFeedBack("Deconnexion réussi !"));

    }
    public function displayAccountCreationSucces()
    {
        $this->routeur->POSTredirect($this->routeur->getURLAskConnexion(),$this->positiveFeedBack("Compte créé ! Veuillez désormais vous connecter. "));
    }
    public function displayAccountCreationFailure()
    {
        $this->routeur->POSTredirect($this->routeur->getURLCreateAccount(), $this->negativeFeedBack("Erreurs dans le formulaire."));

    }
    public function displayAccountCreationFailureLogin($login)
    {
        $this->routeur->POSTredirect($this->routeur->getURLCreateAccount(), $this->negativeFeedBack("Erreur le login ".$login." n'est pas disponible !"));

    }

    public function displayRechercheFailure()
    {
        $this->routeur->POSTredirect($this->routeur->getListeURL(), $this->negativeFeedBack("Aucun boxeur ne correspond à la recherche !"));

    }
    public function displayTrieFailure()
    {
        $this->routeur->POSTredirect($this->routeur->getListeURL(), $this->negativeFeedBack("Erreur ! Aucun argument n'a été séléctionné dans le trie des boxeurs. "));

    }

    public function positiveFeedBack($content)
    {
        return "<div class=postiveFeedback>"."\n"."<p>".$content."</p>"."\n"."</div>"."\n";
    }
    public function negativeFeedBack($content)
    {
        return "<div class=negativeFeedback>"."\n"."<p>".$content."</p>"."\n"."</div>"."\n";
    }
    public function errordiv($content)
    {
        return "<span class=erreur>".$content."</span>"."\n";
    }

}


?>
