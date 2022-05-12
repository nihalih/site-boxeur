<?php


    class AdminView extends UserView {

        public function __construct($r,$f,$a)
        {
            parent::__construct($r,$f,$a);
            $this->menu=$this->MenuPage(); //Création menu
        }

        public function MenuPage()
        {
            //Lien vers accueil,Liste objet ,Creation nouvelle objet
            $menu="<nav class ='menu'>"."\n"."<ul>"."\n";
            $menu.="<li><a href =\"".$this->routeur->getindexURL()."\">Accueil</a></li>"."\n";
            $menu.="<li><a href =\"".$this->routeur->getListeURL()."\">Galerie boxeurs</a></li>"."\n";
            $menu.="<li><a href =\"".$this->routeur->getBoxeurCreationURL()."\">Création d'un boxeur</a></li>"."\n";
            $menu.="<li><a href =\"".$this->routeur->getURLgestionAccount()."\">Gestion compte</a></li>"."\n";
            $menu.="<li><a href =\"".$this->routeur->getURLAskDeconnexion()."\">Se deconnecter</a></li>"."\n";
            $menu.="</ul>"."\n"."</nav>"."\n"; 
            return $menu;
        } 

        //Page liste des comptes
        public function makeListAccountPage($tableauCompte)
        {
            $this->title="Page gestion";
            $this->content="<h2>Gestion des comptes</h2>"."\n";
            $this->content.="<div class=galerie>"."\n";
            foreach($tableauCompte as $keys => $value)
            {
                $this->content.="<div class =grille>"."\n";
                $name=$value->getNom();
                $url = $this->routeur->getAccountURL($keys);
                $this->content.="<a href =\"$url\">"."\n";
                $this->content.="<h3>$name</h3>"."\n";
                $this->content.='<img src="img/avatar.PNG" alt="Avatar image">'."\n".'</a>'."\n";
                $this->content.="</div>"."\n";
    
            }
            $this->content.="</div>"."\n";
            


        }

        public function displayAccountDeletePageAdmin($nom)
        {
            $this->routeur->POSTredirect($this->routeur->getindexURL(), $this->positiveFeedBack("Le compte administrateur de ".$nom." a bien été supprimée !"));
 
        }

       
    }

?>