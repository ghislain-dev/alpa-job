<?php
    class user{

        private $id;
        private $nom;
        private $postnom;
        private $prenom;
        private $pwd;
        private $idfonction;
        private $email;
        private $numero;
        private $image;
        private $con;

        public function __construct($con){
            $this->con=$con;
        }

        //declararion des accesseurq 

        public function set_id($id) : void{$this->id=$id;}
        public function set_nom($nom) : void{$this->nom=$nom;}
        public function set_postnom($postnom) : void{$this->postnom=$postnom;}
        public function set_prenom($prenom) : void{$this->prenom=$prenom;}
        public function set_pwd($pwd) : void{$this->pwd=$pwd;}
        public function set_idfonction($idfonction) : void{$this->idfonction=$idfonction;}
        public function set_email($email) : void{$this->email=$email;}
        public function set_numero($numero) : void{$this->numero=$numero;}
        public function set_image($image) : void{$this->image=$image;}

        //declaration d'une methode 

        public function add_user() :bool{
            $query ="INSERT INTO utilisateur(nom,postnom,prenom,pwd,id_fonction,email,image,numero) values(?,?,?,?,?,?,?,?)";
            $stmt =$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->postnom,$this->prenom,$this->pwd,$this->idfonction,$this->email,$this->image,$this->numero])){
                return true;
            }
            return false;
        }

        public function update_user() :bool{
            $query="UPDATE utilisateur set nom =?,`postnom`=? ,`prenom`=?,`pwd`=?,`id_fonction`=?,`email`=?, `numero`=? where id_utilisateur=?";
            $stmt=$this->con->prepare($query);
            if($stmt->execute([$this->nom,$this->postnom,$this->prenom,$this->pwd,$this->idfonction,$this->email,$this->numero,$this->id])){
                return true;
            }
            return false;
        }

        public function get_user() :array{
            $query= "select utilisateur.nom,utilisateur.postnom,utilisateur.prenom,fonction.nom_fonction,utilisateur.image,utilisateur.email,utilisateur.numero,utilisateur.id_utilisateur from fonction,utilisateur where utilisateur.id_fonction= fonction.id_fonction";
            $stmt=$this->con->prepare($query);
            $stmt->execute([]);

            $data =[];

            while($dat=$stmt->fetch()){
                $data[]= $dat;
            }
            return $data;
        }

         public function verifierConnexion($email, $motdepasse) {
            $sql = "SELECT * FROM utilisateur WHERE email = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($motdepasse, $user['mot_de_passe'])) {
                return $user;
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

           public function delete_user() {
            try {
                $sql = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
                $stmt = $this->con->prepare($sql);
                return $stmt->execute([$this->id]);
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
                return false;
            }
            }

        public function get_user_by_id($id){
            $query="SELECT * FROM utilisateur WHERE id_utilisateur =? ";
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
