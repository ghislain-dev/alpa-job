<?php
    class prix{
        private $id;
        private $montant;
      
        private $con;

        public function __construct($con){
            $this->con=$con;
        }

        //declararion des accesseurq 

        public function set_id($id) : void{$this->id=$id;}
        public function set_montant($montant) : void{$this->montant=$montant;}
        

        //declaration d'une methode 

        public function add_prix() : bool{
            $query ="INSERT INTO prix(montant) values(?)";
            $stmt =$this->con->prepare($query);
            if($stmt->execute([$this->montant])){
                return true;
            }
            return false;
        }
        
        public function update_prix() :bool{
            $query="UPDATE prix set montant =? where id_prix=?";
            $stmt=$this->con->prepare($query);
            if($stmt->execute([$this->montant,$this->id])){
                return true;
            }
            return false;
        }

        public function get_prix() :array{
            $query= "SELECT * FROM prix";
            $stmt=$this->con->prepare($query);
            $stmt->execute([]);

            $data =[];

            while($dat=$stmt->fetch()){
                $data[]= $dat;
            }
            return $data;
        }

        public function delete_prix(){
            $query="DELETE FROM prix where id_prix =?";
            $stmt=$this->con->prepare($query);
            $stmt->execute([$this->id]);
            
            $donnes =[];
            while($data =$stmt->fetch()){
                $donnes []=$data;
            }
            return $donnes;
        }

        public function get_prix_by_id($id){
            $query="SELECT * FROM prix WHERE id_prix =? ";
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