<?php

    namespace App\Models;
    use MF\Model\Model;

    class DocumentType extends Model {
        private $id;
        private $documentType;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        //salvar
        public function save() {

            $query = "insert into tbdocumenttypes(documentType)values(:documentType)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':documentType', $this->__get('documentType'));
            $stmt->execute();

            return $this;
        }
        //validar se poder criar nova unidade
        public function validateRegistration() {
            $valido = true;

            if(strlen($this->__get('documentType')) < 3) {
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

        public function getAll() {

            $query = " 
            select * from tbdocumenttypes order by documentType";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } 
    }
?>