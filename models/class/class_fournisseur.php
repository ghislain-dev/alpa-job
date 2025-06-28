<?php
    class fournisseur {
        private $id;
        private $nom;
        private $numero;
        private $email;
        private $con;

        public function __construct($con){
            $this->con=$con;
        }

        //declararion des accesseurq 

        public function set_id($id) : void{$this->id=$id;}
        public function set_nom($nom) : void{$this->nom=$nom;}
        public function set_numero($numero) : void{$this->numero=$numero;}
        public function set_email($email) : void{$this->email=$email;}

        //declaration d'une methode 

        public function add_fournisseur() : bool{
            $query ="INSERT INTO fournisseur(`noms`, `numero`, `email`) values(?,?,?)";
            $stmt =$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->numero,$this->email])){
                return true;
            }
            return false;
        }
        
        public function update_fournisseur() :bool{
            $query="UPDATE fournisseur set noms =?,`numero`=?,`email`=?  where id_fournisseur=?";
            $stmt=$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->numero,$this->email,$this->id])){
                return true;
            }
            return false;
        }

        public function get_fournisseur() :array{
            $query= "SELECT * FROM fournisseur";
            $stmt=$this->con->prepare($query);
            $stmt->execute([]);

            $data =[];

            while($dat=$stmt->fetch()){
                $data[]= $dat;
            }
            return $data;
        }

        public function delete_fournisseur(){
            $query="DELETE FROM fournisseur where id_fonction =?";
            $stmt=$this->con->prepare($query);
            $stmt->execute([$this->id]);
            
            $donnes =[];
            while($data =$stmt->fetch()){
                $donnes []=$data;
            }
            return $donnes;
        }

        public function get_fournisseur_by_id($id){
            $query="SELECT * FROM fournisseur WHERE id_fournisseur =? ";
            $stmt =$this->con->prepare($query);
            $stmt->execute([$id]);
    
            $donnes =[];
            while($data =$stmt->fetch()){
                $donnes [] =$data;
            }
            return $donnes;
        }
    }
?>