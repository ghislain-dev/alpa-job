<?php
    class fonction{
        private $id;
        private $fonction;
        private $description;
        private $con;

        public function __construct($con){
            $this->con=$con;
        }

        //declararion des accesseurq 

        public function set_id($id) : void{$this->id=$id;}
        public function set_fonction($fonction) : void{$this->fonction=$fonction;}
        public function set_description($description) : void{$this->description=$description;}

        //declaration d'une methode 

        public function add_fonction() : bool{
            $query ="INSERT INTO fonction(nom_fonction,`description`) values(?,?)";
            $stmt =$this->con->prepare($query);
            if($stmt->execute([$this->fonction,$this->description])){
                return true;
            }
            return false;
        }
        
        public function update_fonction() :bool{
            $query="UPDATE fonction set nom_fonction =?,`description`=? where id_fonction=?";
            $stmt=$this->con->prepare($query);
            if($stmt->execute([$this->fonction,$this->description,$this->id])){
                return true;
            }
            return false;
        }

        public function get_fonction() :array{
            $query= "SELECT * FROM fonction";
            $stmt=$this->con->prepare($query);
            $stmt->execute([]);

            $data =[];

            while($dat=$stmt->fetch()){
                $data[]= $dat;
            }
            return $data;
        }

        public function delete_fonction(){
            $query="DELETE FROM fonction where id_fonction =?";
            $stmt=$this->con->prepare($query);
            $stmt->execute([$this->id]);
            
            $donnes =[];
            while($data =$stmt->fetch()){
                $donnes []=$data;
            }
            return $donnes;
        }

        public function get_fonction_by_id($id){
            $query="SELECT * FROM fonction WHERE id_fonction =? ";
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