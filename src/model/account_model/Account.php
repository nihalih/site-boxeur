<?php 

    class Account {


        private $login;
        private $mdp;
        private $nom;
        private $statut;

        public function __construct($l,$m,$n,$s)
        {
            $this->login=$l;
            $this->mdp=password_hash($m, PASSWORD_BCRYPT);      
            $this->nom=$n;
            $this->statut=$s;
        }



        public function getNom()
        {
            return $this->nom;
        }
        public function getLogin()
        {
            return $this->login;
        }

        public function getMdp()
        {
            return $this->mdp;
        }

        public function getStatut()
        {
            return $this->statut;
        }
        public function setLogin($login)
        {
            $this->login=$login;
        }
        public function setMdp($mdp)
        {
            $this->mdp=password_hash($mdp,PASSWORD_BCRYPT);
        }
        public function setNom($nom)
        {
            $this->nom=$nom;
        }


    }


?>