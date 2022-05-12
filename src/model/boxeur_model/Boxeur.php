<?php

class Boxeur {

    private $nom;
    private $prenom;
    private $age;
    private $poids;
    private $origine;
    private $champion;
    private $login;
    private $urlIMG;
    private $description;

    public function __construct($n,$p,$a,$poids,$o,$c,$login,$urlIMG)
    {
        $this->nom=$n;
        $this->prenom=$p;
        $this->age=$a;
        $this->poids=$poids;
        $this->origine=$o;
        $this->champion=$c;
        $this->login=$login;
        $this->urlIMG=$urlIMG;
        $this->description=$this->setDescription();
    }

    public function getAttribut($att)
    {
        switch($att)
        {
            case "nom":
                return $this->nom;
            case "prenom":
                return $this->prenom;
            case "age":
                return $this->age;
            case "poids":
                return $this->poids;
            default:
                return null;
        }
        
    }
    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }
    public function getAge()
    {
        return $this->age;
    }
    public function getPoids()
    {
        return $this->poids;
    }
    public function getOrigine()
    {
        return $this->origine;
    }
    public function getChampion()
    {
        return $this->champion;
    }
    public function getLogin()
    {
        return $this->login;
    }
    public function getURLimg()
    {
        return $this->urlIMG;
    }
    public function getDescription()
    {
        return $this->description;
    }

    public function setNom($n)
    {
        $this->nom=$n;
    }
    public function setPrenom($p)
    {
        $this->prenom=$p;
    }
    public function setAge($a)
    {
        $this->age=$a;
    }
    public function setPoids($p)
    {
        $this->poids=$p;
    }
    public function setOrigine($o)
    {
        $this->origine=$o;
    }
    public function setChampion($c)
    {
      $this->champion=$c;  
    }
    //Construction de la description
    public function setDescription()
    {

        $description=$this->getNom()." ".$this->getPrenom(). " est un boxeur ayant ".$this->getAge() ." ans.";
        $description.="Son poids est de ".$this->getPoids()." kg et combat dans la catégorie des ";
        
        //Selon le poids catégorie différente
        ($this->getPoids()<=61? $description.="poids plumes" : $description.="");
        ($this->getPoids()>61 && $this->getPoids()<=75? $description.="poids moyen" : $description.="");
        ($this->getPoids()>75 && $this->getPoids()<=81? $description.="poids mi-lourd" : $description.="");
        ($this->getPoids()>81? $description.="poids lourd" : $description.="");

        $description.=".Ce boxeur est ".$this->getOrigine()." et a suivis un entrainement acharné depuis son plus jeune âge.";
        ($this->getChampion()==='o'? $description.="Cela lui a permis d'être champion du monde dans sa discipline." : $description.="Malheuresement il n'a pas obtenu le titre de champion du monde..");
        return $description;
    }

}


?>