<?php 
class user{ 

        private $id;
        private $nom;
        private $email;
        private $tel;
        
        public function __construct(){} 
        
        public function setId($id){ $this->id = $id; }

        public function getId(){ return $this->id; }

        public function setNom($nom){ $this->nom = $nom; }

        public function getNom(){ return $this->nom; }

        public function setEmail($email){ $this->email = $email; }

        public function getEmail(){ return $this->email; }
        
        public function setTel($tel){ $this->tel = $tel; }

        public function getTel(){ return $this->tel; }
}