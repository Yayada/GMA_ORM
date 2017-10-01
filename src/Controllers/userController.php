<?php 
    require __DIR__."/../../config/orm/vueAll.php";
    require __DIR__."/../../config/orm/model.php";
    require __DIR__."/../vues/user/allusersvue.php";  
    require __DIR__."/../Entities/user.php";

    class userController{

        private $model;
        
        public function __construct(){ $this->model = Model::getInstance(); }
        
        public function adduser($user){ 
            $data = $this->model->save($user); 
        }
        
        public function getAllusers(){ 
            $v = new AllusersVue(); 
            $data = $this->model->findAll(new user()); 
            $v->allusers($data); 
            $v->afficher(); 
        }
        
        public function getuser($id){
            $data = $this->model->findAll(new user(), $id);
        } 
        
        public function removeuser($id){ 
            $data = $this->model->findAll(new user(), $id);
        }
    }

