<?php
    class class_client{

        private $id;
        private $nom;
        private $pwd;
        private $email;
        private $numero;
        private $image;
        private $genre;
        private $con;

        public function __construct($con){
            $this->con=$con;
        }

        //declararion des accesseurq 

        public function set_id($id) : void{$this->id=$id;}
        public function set_nom($nom) : void{$this->nom=$nom;}

        public function set_pwd($pwd) : void{$this->pwd=$pwd;}
        public function set_genre($genre) : void{$this->genre=$genre;}
        public function set_email($email) : void{$this->email=$email;}
        public function set_numero($numero) : void{$this->numero=$numero;}
        public function set_image($image) : void{$this->image=$image;}

        //declaration d'une methode 

        public function add_clients() :bool{
            $query ="INSERT INTO client (nom, email, pwd, photo, numero, genre) values(?,?,?,?,?,?)";
            $stmt =$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->email,$this->pwd,$this->image,$this->numero,$this->genre])){
                return true;
            }
            return false;
        }

        public function update_clients() :bool{
            $query="UPDATE client set nom =?,`email`=?,`pwd`=?,`photo`=?, `numero`=?, genre= ? where id_client=?";
            $stmt=$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->email,$this->pwd,$this->image,$this->numero,$this->genre,$this->id])){
                return true;
            }
            return false;
        }

        public function get_clients() :array{
            $query= "select * from client";
            $stmt=$this->con->prepare($query);
            $stmt->execute([]);

            $data =[];

            while($dat=$stmt->fetch()){
                $data[]= $dat;
            }
            return $data;
        }

    


        public function delete_client(){
            $query="DELETE FROM client where id_client =?";
            $stmt=$this->con->prepare($query);
            $stmt->execute([$this->id]);
            
            $donnes =[];
            while($data =$stmt->fetch()){
                $donnes []=$data;
            }
            return $donnes;
        }

        public function get_client_by_id($id){
            $query="SELECT * FROM client WHERE id_clients =? ";
            $stmt =$this->con->prepare($query);
            $stmt->execute([$id]);
    
            $donnes =[];
            while($data =$stmt->fetch()){
                $donnes [] =$data;
            }
            return $donnes;
        }

        public function getClientById($id_client) {
            $sql = "SELECT `id_client`, `nom`, `email`, `numero`, `genre` FROM `client` WHERE id_client = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id_client]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

       


    }
?>
