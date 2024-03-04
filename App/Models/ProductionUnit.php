<?php

    namespace App\Models;
    use MF\Model\Model;

    class ProductionUnit extends Model {
        private $id;
        private $productionUnit;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        //salvar
        public function save() {

            $query = "insert into tbproductionunits(productionUnit)values(:productionUnit)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':productionUnit', $this->__get('productionUnit'));
            $stmt->execute();

            return $this;
        }
        //validar se poder criar nova unidade
        public function validateRegistration() {
            $valido = true;

            if(strlen($this->__get('productionUnit')) < 3) {
                $valido = false;
            }

            return $valido;
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

        //Recuperar todos os usuários habilitados na unidade
        public function getAllUsersForProdUnit() {
            $query = "select count(*) as total_seguindo from usuarios_seguidores where id_usuario =:id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getAll() {

            $query = " 
            select * from tbproductionunits order by productionUnit";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } 
    }
?>