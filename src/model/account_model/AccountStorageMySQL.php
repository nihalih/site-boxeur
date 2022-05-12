<?php
    require_once "Account.php";
    require_once "AccountStorage.php";
    require_once('/users/22008897/private/mysql_config.php');

    class AccountStorageMySQL implements AccountStorage{
        private $bd;
        public function __construct($bd)
        {    
           $this->bd=$bd;
    
        }

        public function read($id)
        {
            $id=htmlspecialchars($id,ENT_QUOTES);
            $requete = "SELECT * FROM account WHERE id=:id"; // id non renseigné
            $stmt = $this->bd->prepare($requete);
            $data = array(":id"=>$id); 
            $stmt->execute($data);
            $tabAccount=$stmt->fetch(); 
            //Retourner null si la requet ne renvoi rien sinon renvoyer le compte en question
            return ($tabAccount===false? null : new Account($tabAccount['login'],$tabAccount['password'],$tabAccount['name'],
            $tabAccount['statut']));
        
        }


        //Renvoi un tableau des objets contenu dans la  base de donnée
        public function readAll(){
            $requete = "SELECT * FROM account"; 
            $stmt = $this->bd->prepare($requete);
            $stmt->execute();
            $tabAccount=$stmt->fetchAll(); // Stock tous les resultat de la requette dans tableau
            $AccoutTabRetour=array(); // stockage des comptes de la base 
            if($tabAccount!==false) 
            {
                foreach($tabAccount as $keys => $value)
                {
                    
                    $account=new Account($value['login'],$value['password'],$value['name'],$value['statut']);
                    $AccoutTabRetour+=[$value['id'] => $account];
                }
            }
            return $AccoutTabRetour;
    
    }

        //Authentification
        public function checkAuth($login,$password)
        {
            $login=htmlspecialchars($login,ENT_QUOTES);
            $password=htmlspecialchars($password,ENT_QUOTES);

            $requete = 'SELECT * FROM account WHERE login=:login';
            $stmt = $this->bd->prepare($requete);
            $data = array(":login"=>$login); 
            $stmt->execute($data);
            $tabAccount=$stmt->fetch();
            //Retourner null si requete n'a pas aboutis sinon
            if($tabAccount===false){
                return null;
            }
            //Verifier mot de passe valide et renvoi compte utilisateur
            if(password_verify($password,$tabAccount['password']))
            {
                return new Account($tabAccount['login'],$tabAccount['password'],$tabAccount['name'],
                $tabAccount['statut']);
            }
               
            return null;
    
        }

        //Création d'un compte dans base de donnée et retourne son id
        public function create(Account $a)
        {
            $requete = 'INSERT INTO account(login,password,name,statut) VALUES(:login,:password,:name,:statut)'; 
            // Prepare une requete pour la sécurité 
            $stmt = $this->bd->prepare($requete);
            $data = array(":login"=>$a->getLogin(),
            ":password"=>$a->getMdp(),
            ":name"=>$a->getNom(),
            ":statut"=>$a->getStatut()); 
            $stmt->execute($data); 
            return $this->bd->lastInsertId();     //RETOURN l'id de la dernière insertion
        }

        //Test si le login est valide dans la base
        public function LoginValid($login)
        {
            $login=htmlspecialchars($login,ENT_QUOTES);
            $requete = 'SELECT * FROM account WHERE login=:login';
            // Prepare une requete pour la sécurité 
            $stmt = $this->bd->prepare($requete);
            $data = array(":login"=>$login); 
            $stmt->execute($data);
            $tabAccount=$stmt->fetch();
            //Aucun login correspondant renvoyer true
            if($tabAccount===false){
                return true;
            }
            return false;
        }
        //Mise a jour compte dans la base
        public function update($id,Account $account)
        {
            $id=htmlspecialchars($id,ENT_QUOTES);
            //SI l'objet existe
            if ($this->read($id)!==null) {
                $requete = 'UPDATE account SET login=:login , password=:password,name=:name,statut=:statut
                WHERE id=:id';  
                $stmt = $this->bd->prepare($requete);
                $data = array(":login"=>$account->getLogin(),
                ":password"=>$account->getMdp(),
                ":name"=>$account->getNom(),
                ":statut"=>$account->getStatut(),
                ":id"=>$id);
                            
                $stmt->execute($data);          
                return true;
            }
            return false;
        }

        public function delete($id)
        {
            $id=htmlspecialchars($id,ENT_QUOTES);
            $requete = 'DELETE FROM account WHERE id=:id';  
            $stmt = $this->bd->prepare($requete);
            $data = array(":id"=>$id);
            $stmt->execute($data);      
        }

    }
?>