<?php

    require_once "BoxeurStorage.php";
    require_once "Boxeur.php";
    require_once('/users/22008897/private/mysql_config.php');
    
    class BoxeurStorageMySQL implements BoxeurStorage 
    {
        private $bd;
        public function __construct($bd)
        {    
           $this->bd=$bd;
    
        }


    //Renvoi un boxeur de la bd corespondant à l'id si existe sinon null
    public function read($id){
        $id=htmlspecialchars($id,ENT_QUOTES);
        $requete = "SELECT * FROM boxeur WHERE id=:id"; // id non renseigné
        // Prepare une requete pour la sécurité 
        $stmt = $this->bd->prepare($requete);
        // Remplissage des parametre du tableua  de la requete pour le id
        $data = array(":id"=>$id); // Remplis les éléments manquant de la requete
        $stmt->execute($data);
        $tabBoxeur=$stmt->fetch(); // Stock tous les resultat de la requette dans tableau
        //Retourner null si la requet ne renvoi rien sinon créer un nouveau boxeur
        return ($tabBoxeur===false? null : new Boxeur($tabBoxeur['nom'],$tabBoxeur['prenom'],$tabBoxeur['age'],
        $tabBoxeur['poids'],$tabBoxeur['origine'],$tabBoxeur['champion'],$tabBoxeur['login'],$tabBoxeur['img']));
    }

    //Renvoi un tableau des objets contenu dans la  base de donnée
    public function readAll(){
        $requete = "SELECT * FROM boxeur"; 
        $stmt = $this->bd->prepare($requete);
        $stmt->execute();
        $tabBoxeur=$stmt->fetchAll(); // Stock tous les resultat de la requette dans tableau
        $BoxeurtabRetour=array(); // stock les boxeurs de la base
        if($tabBoxeur!==false) // Si retour de fetch all
        {
            foreach($tabBoxeur as $keys => $value)
            {
                //Construction d'un boxeur et du tableau des boxeurs 
                $boxeur=new Boxeur($value['nom'],$value['prenom'],$value['age'],$value['poids'],$value['origine'],$value['champion'],$value['login'],$value['img']);
                $BoxeurtabRetour+=[$value['id'] => $boxeur];
            }
        }
        return $BoxeurtabRetour;
    
    }

     //Crée un nouveau boxeur dans la base et retourne son id
     public function create(Boxeur $b){
        
        $requete = 'INSERT INTO boxeur(nom,prenom,age,poids,origine,champion,login,img) VALUES(:nom,:prenom,:age,:poids,:origine,:champion,:login,:img)'; 
        $stmt = $this->bd->prepare($requete);
        // Remplissage des parametres pour la requete
        $data = array(":nom"=>$b->getNom(),
        ":prenom"=>$b->getPrenom(),
        ":age"=>$b->getAge(),
        ":poids"=>$b->getPoids(),
        ":origine"=>$b->getOrigine(),
        ":champion"=>$b->getChampion(),
        ":login"=>$b->getLogin(),
        ":img"=>"img/basicBoxeur.PNG"); 
        $stmt->execute($data);      
        return $this->bd->lastInsertId(); // Retourn l'id du dernier objet ajouté
    }

    //Supprime l'objet ayant l'id en parametre dans la base de donné
    public function delete($id){
        $id=htmlspecialchars($id,ENT_QUOTES);
        $requete = 'DELETE FROM boxeur WHERE id=:id';  
        $stmt = $this->bd->prepare($requete);
        $data = array(":id"=>$id);
        $stmt->execute($data);          
    }
     //Met a jour l'objet dans la base et retourn vrai si celui ci a été mis a jour 
     public function update($id, Boxeur $b) {
        $id=htmlspecialchars($id,ENT_QUOTES);
        //SI l'objet existe
		if ($this->read($id)!==null) {
            $requete = 'UPDATE boxeur SET nom=:nom , prenom=:prenom,age=:age,poids=:poids,origine=:origine,champion=:champion
             WHERE id=:id';  
            $stmt = $this->bd->prepare($requete);
            $data = array(":nom"=>$b->getNom(),
            ":prenom"=>$b->getPrenom(),
            ":age"=>$b->getAge(),
            ":poids"=>$b->getPoids(),
            ":origine"=>$b->getOrigine(),
            ":champion"=>$b->getChampion(),
            ":id"=>$id);
                          
            $stmt->execute($data);          
			return true;
		}
		return false;
	}



    }

?>
