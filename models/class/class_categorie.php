<?php
    class fonction{
        private $id;
        private $nom;
        private $description;
        private $con;

        public function __construct($con){
            $this->con=$con;
        }

        //declararion des accesseurq 

        public function set_id($id) : void{$this->id=$id;}
        public function set_fonction($nom) : void{$this->nom=$nom;}
        public function set_description($description) : void{$this->description=$description;}

        //declaration d'une methode 

        public function add_categorie() : bool{
            $query ="INSERT INTO categorie(nom_categorie,`description`) values(?,?)";
            $stmt =$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->description])){  
                return true;
            }
            return false;
        }
        
        public function update_categorie() :bool{
            $query="UPDATE categorie set nom_categorie =?,`description`=? where id_categorie=?";
            $stmt=$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->description,$this->id])){
                return true;
            }
            return false;
        }

        public function get_categorie() :array{
            $query= "SELECT * FROM categorie";
            $stmt=$this->con->prepare($query);
            $stmt->execute([]);

            $data =[];

            while($dat=$stmt->fetch()){
                $data[]= $dat;
            }
            return $data;
        }

        public function delete_categorie(){
            $query="DELETE FROM categorie where id_categorie =?";
            $stmt=$this->con->prepare($query);
            $stmt->execute([$this->id]);
            $donnes =[];
            while($data =$stmt->fetch()){
                $donnes []=$data;
            }
            return $donnes;
        }

        public function get_categorie_by_id($id){
            $query="SELECT * FROM categorie WHERE id_categorie =? ";
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