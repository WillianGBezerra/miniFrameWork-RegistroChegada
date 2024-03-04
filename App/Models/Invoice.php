<?php

    namespace App\Models;
    use MF\Model\Model;

    class Invoice extends Model {
        private $id;
        private $invoice;
        private $invoiceKey;
        private $emissionDate;
        private $observation;
        private $productionUnitId;
        private $userId;
        private $documenttypeId;
        private $statusId;
        

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        //salvar
        public function save() {

            $query = "insert into tbInvoices(invoice, invoiceKey, emissionDate, observation, productionUnitId, userId, documenttypeId,statusId)values(:invoice,:invoiceKey, :emissionDate, :observation, :productionUnitId, :userId, :documenttypeId,:statusId)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':invoice', $this->__get('invoice'));
            $stmt->bindValue(':invoiceKey', $this->__get('invoiceKey'));
            $stmt->bindValue(':emissionDate', $this->__get('emissionDate'));
            $stmt->bindValue(':observation', $this->__get('observation'));
            $stmt->bindValue(':productionUnitId', $this->__get('productionUnitId'));
            $stmt->bindValue(':userId', $this->__get('userId'));
            $stmt->bindValue(':documenttypeId', $this->__get('documenttypeId'));
            $stmt->bindValue(':statusId', $this->__get('statusId'));
            $stmt->execute();

            return $this;
        }
        //validar se poder criar nova unidade
        public function validateRegistration() {
            $valido = true;

            if(!is_int($this->__get('productionUnitId'))) {
                return $valido;
            }
            if(!is_int($this->__get('userId'))) {
                return $valido;
            }
            if(!is_int($this->__get('documenttypeId'))) {
                return $valido;
            }
            if(strlen($this->__get('invoiceKey')) < 3) {
                $valido = false;
            }
            if(strlen($this->__get('emissionDate')) < 3) {
                $valido = false;
            }
            if(strlen($this->__get('observation')) < 3) {
                $valido = false;
            }
        }

        public function delete() {

            $query = "delete from tbinvoices where id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return true;
        } 

       
        
        public function getAll() {

            $query = " 
            select 
             i.id AS idInvoice, 
             i.invoice,
             i.invoiceKey,
             i.userId, 
             i.observation,
             i.productionUnitId,
             i.documenttypeId,
             i.statusId,
             u.name, 
             s.status,
             p.id,
             p.productionUnit AS fazenda,
             pfu.userForUnitId,
             pfu.productionUnitForUserId,
             DATE_FORMAT(i.timestamp, '%d/%m/%Y %H:%i') as timestamp,
             DATE_FORMAT(i.emissionDate, '%d/%m/%Y %H:%i') as emissao 
            from 
            tbinvoices as i 
             left join tbusers as u on (i.userId = u.id)
             left join tbproductionunitforuser as pfu on (i.userId = pfu.userForUnitId)
             left join tbstatus as s on (i.statusId = s.id)
             left join tbproductionunits as p on (i.productionUnitId = p.id)
            where 
            userId = :userId
            or i.userId in (select name from tbusers where userId = :userId) AND
            userId = :userId
            or i.userId in (select productionUnitForUserId from tbproductionunits where userId = :userId) AND
            userId = :userId
            or i.userId in (select productionUnit from tbproductionunits where productionUnitId = :productionUnitId)
            group by i.id
            order by 
            i.emissionDate asc";
 
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':userId', $this->__get('userId'));
            $stmt->bindValue(':userId', $this->__get('userId'));
            $stmt->bindValue(':productionUnitId', $this->__get('productionUnitId'));
            
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } 

        //Recuperar todos os invoices do usuario autenticado.
        public function getCountAllPending() {
            $query = "select count(*) as totalPending from tbinvoices, tbproductionunitforuser WHERE statusId = 2 AND userId = tbproductionunitforuser.userForUnitId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':statusId', $this->__get(2));
            $stmt->bindValue(':userId', $this->__get('userId'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
    }
?>