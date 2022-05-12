<?php 

class BoxeurBuilder{

    //CHAMP  de constante pour le formulaire 
    const NAME_REF ="nom";
    const PRENOM_REF ="prenom";
    const AGE_REF ="age";
    const POIDS_REF="poids";
    const ORIGINE_REF="origine";
    const CHAMPION_REF="champion";


    private $data;
    private $error;


    public function __construct(array $data)
    {
        $this->data=$data;
        //Echapper les caractères
        foreach($this->data as $keys => $value)
        {
            $this->data[$keys]=htmlspecialchars($value,ENT_QUOTES);
        }
        $this->error=[];
    }

    public function getData()
    {
        return $this->data;
    }
    public function getError()
    {
        return $this->error;
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
    
    
     public function KeyExistBoxeur()
     {
        if(key_exists(self::NAME_REF,$this->data) && key_exists(self::PRENOM_REF,$this->data) && key_exists(self::AGE_REF,$this->data)
        && key_exists(self::POIDS_REF,$this->data)&& key_exists(self::ORIGINE_REF,$this->data) && key_exists(self::CHAMPION_REF,$this->data))
        {
            return true;
        }
        return false;
     }
     // créer et retourne nouveau boxeur
    public function createBoxeur($login) 
    {
        $nom=htmlspecialchars($this->data[self::NAME_REF],ENT_QUOTES);
        $prenom=htmlspecialchars($this->data[self::PRENOM_REF],ENT_QUOTES);
        $age=htmlspecialchars($this->data[self::AGE_REF],ENT_QUOTES);
        $poids=htmlspecialchars($this->data[self::POIDS_REF],ENT_QUOTES);
        $origine=htmlspecialchars($this->data[self::ORIGINE_REF],ENT_QUOTES);
        $champion=htmlspecialchars($this->data[self::CHAMPION_REF],ENT_QUOTES);
        $login=htmlspecialchars($login,ENT_QUOTES);
        $boxeur = new Boxeur($nom,$prenom,$age,$poids,$origine,$champion,$login,"img/basicBoxeur.PNG");
        return $boxeur;
    }

   //Permet de remplir l'indice d'error adequat par rapport à la constante
   public function ConstructErrorChar($constante)
   {
       if($this->data[$constante]==="")
       {
           $this->error[$constante]="Erreur le champ ".$constante ." est vide ! \n";
       }
       if(mb_strlen($this->data[$constante],'UTF-8') >= 30)
           $this->error[$constante]=" Erreur le champ ".$constante ." doit faire moins de 30 caractères";

       if(preg_match('#[^a-zA-Z]#', $this->data[$constante]))
       {
           $this->error[$constante]="Erreur le champ ".$constante ." comporte des caractères spéciaux ou chiffre ! \n";
       } 
   }
    //Construit les erreurs pour un entier
    public function ConstructErrorInt($constante)
    {
        if(preg_match('/[^0-9]/', $this->data[$constante]) || $this->data[$constante]==="" || $this->data[$constante]<0)
        {
                $this->error[$constante]="Erreur ! Le champ " .$constante." doit être un chiffre positif ! \n";
        }
        if($this->data[$constante]>=200)
        {
            $this->error[$constante]="Erreur ! Le champ ".$constante ." doit être inférieur à 200";
        }

    }
    //Objet valide
    public function isValid()
    {
        //Construction des erreurs
        /////////////////ERREUR TYPE STRING ///////////////////
        $this->ConstructErrorChar(self::NAME_REF);
        $this->ConstructErrorChar(self::PRENOM_REF);
        $this->ConstructErrorChar(self::ORIGINE_REF);
         //POUR LE CHAMPION SEULEMENT DEUX POSSIBILITE o ou n 
        if($this->data[self::CHAMPION_REF]!=="o" && $this->data[self::CHAMPION_REF]!=="n")
        {
            $this->error[self::CHAMPION_REF]="Erreur ! Veuillez entrer o pour oui et n pour non";
        }

        /////ERREUR TYPE INT ///////////////
        $this->ConstructErrorInt(self::AGE_REF);
        $this->ConstructErrorInt(self::POIDS_REF);

        //Si tableau error vide le boxeur est valide 
        if($this->error===[])
        {
            return true;
        }
        return false;
    }

    //Renvoi une nouvelle instance de boxeur contenant le boxeur sous forme de tableau en parametre
    public static function ModifBoxeurInstance(Boxeur $boxeur)
    {
        return new BoxeurBuilder(array(
            self::NAME_REF => $boxeur->getNom(),
            self::PRENOM_REF => $boxeur->getPrenom(),
            self::AGE_REF => $boxeur->getAge(),
            self::POIDS_REF => $boxeur->getPoids(),
            self::ORIGINE_REF => $boxeur->getOrigine(),
            self::CHAMPION_REF => $boxeur->getChampion(),

        ));
    }

    public function updateBoxeur(Boxeur $boxeur)
        {
            //SI la clé nom existe modifier le nom du boxeur
            if(key_exists(self::NAME_REF,$this->data))
            {
                $boxeur->setNom($this->data[self::NAME_REF]);
            }
            //Clé prenom 
            if(key_exists(self::PRENOM_REF,$this->data))
            {
                $boxeur->setPrenom($this->data[self::PRENOM_REF]);
            }
            //Clé age 
            if(key_exists(self::AGE_REF,$this->data))
            {
                $boxeur->setAge($this->data[self::AGE_REF]);
            }
            //Clé poids
            if(key_exists(self::POIDS_REF,$this->data))
            {
                $boxeur->setPoids($this->data[self::POIDS_REF]);
            }
            //Clé origine
            if(key_exists(self::ORIGINE_REF,$this->data))
            {
                $boxeur->setOrigine($this->data[self::ORIGINE_REF]);
            }
            //Clé champion
            if(key_exists(self::AGE_REF,$this->data))
            {
                $boxeur->setChampion($this->data[self::CHAMPION_REF]);
            }
            
        }


}


?>