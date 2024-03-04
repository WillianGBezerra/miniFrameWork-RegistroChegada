<?php

    namespace App\Models;
    use MF\Model\Model;

    class User extends Model {
        private $id;
        private $name;
        private $email;
        private $password;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        //salvar
        public function save() {

            $query = "insert into tbusers(name, email, password)values(:name, :email, :password)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':name', $this->__get('name'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':password', $this->__get('password'));
            $stmt->execute();

            return $this;
        }
        //validar se poder criar novo usuário
        public function validateRegistration() {
            $valido = true;

            if(strlen($this->__get('name')) < 3) {
                $valido = false;
            }

            
            if(strlen($this->__get('email')) < 3) {
                $valido = false;
            }

            
            if(strlen($this->__get('password')) < 3) {
                $valido = false;
            }

            return $valido;
        }
        //recuperar usuário por email
        public function getUserForEmail() {
            $query = "select name, email from tbusers where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function authenticate() {

            $query = "select id, name, email from tbusers where email = :email and password = :password";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':password', $this->__get('password'));

            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($user['id'] != '' && $user['name'] != '') {
                $this->__set('id', $user['id']);
                $this->__set('name', $user['name']); 
            }
            return $this;
        }

        public function getAll() {
            $query = "
            select
             u.id, u.name, u.email, 
             ( select count(*) 
                from usuarios_seguidores as us 
               where us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id) as seguindo_sn 
            from 
             usuarios as u
            where 
             u.name like :name and u.id != :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':name', '%'.$this->__get('name').'%');
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        //Recuperar dados do usuário autenticado
        public function getInfoUser() {
            $query = "select name from tbusers where id =:id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        //Recuperar total de tweets do usuário autenticado
        public function getTotalInvoices() {
            $query = "select count(*) as totalInvoices from tbinvoices where userId =:id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getTotalNotPedingInvoices() {
            $query = "select count(*) as totalNotPedingInvoices from tbinvoices where userId =:id and statusId = 2";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
    }
?>