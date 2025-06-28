<?php
    class produit{

        private $id;
        private $nom;
        private $categorie;
        private $image;
        private $idprix;
        private $con;

        public function __construct($con){
            $this->con=$con;
        }

        //declararion des accesseurq 

        public function set_id($id) : void{$this->id=$id;}
        public function set_nom($nom) : void{$this->nom=$nom;}
        public function set_categorie($categorie) : void{$this->categorie=$categorie;}
        public function set_idprix($idprix) : void{$this->idprix=$idprix;}
        public function set_image($image) : void{$this->image=$image;}

        //declaration d'une methode 

        public function add_produit() :bool{
            $query ="INSERT INTO produit(nom_produit,image,id_categorie,id_prix) values(?,?,?,?)";
            $stmt =$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->image,$this->categorie,$this->idprix])){
                return true;
            }
            return false;
        }

        public function update_produit() :bool{
            $query="UPDATE produit set `nom_produit`=?,`image`=?,`id_categorie`=?,`id_prix`=? where id_produit=?";
            $stmt=$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->image,$this->categorie,$this->id])){
                return true;
            }
            return false;
        }

        public function get_produit() :array{
            $query= "select produit.nom_produit,produit.image,produit.id_produit,categorie.nom_categorie,categorie.id_categorie,prix.montant as id_prix from categorie,produit,prix where produit.id_categorie= categorie.id_categorie and produit.id_prix = prix.id_prix ";
            $stmt=$this->con->prepare($query);
            $stmt->execute([]);

            $data =[];

            while($dat=$stmt->fetch()){
                $data[]= $dat;
            }
            return $data;
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


        public function delete_produit(){
            $query="DELETE FROM produit where id_produit =?";
            $stmt=$this->con->prepare($query);
            $stmt->execute([$this->id]);
            
            $donnes =[];
            while($data =$stmt->fetch()){
                $donnes []=$data;
            }
            return $donnes;
        }

        public function get_produit_by_id($id){
            $query="SELECT * FROM produit WHERE id_produit =? ";
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
