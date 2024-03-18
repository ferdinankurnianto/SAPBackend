<?php
namespace App\Models;
use CodeIgniter\Model;

class TransactionModel extends Model {
	protected $table = 'transaction';
	protected $primaryKey = 'transaction_code';
	
    protected $returnType     = 'array';
	
	protected $allowedFields = ['transaction_code', 'customer_name', 'product_name', 'qty_out'];

    function addTransaction($data){
        $this->insert($data);
    }

    function editTransaction($id, $data){
        $this->update($id, $data);
    }

    function deleteTransaction($id){
        $this->delete($id);
    }

    function getTransaction($id){
        return $this->find($id);
    }

    public function product_name($id)
    {
        if($id!=''){
            $result = $this->select('product_name')
		    ->find($id);
        } else {
            $result = $this->select('product_name')
            ->findAll();
        }
        return $result;
    }

    function qty_out($id){
        if($id!=''){
            $result = $this->select('qty_out')
		    ->find($id);
        } else {
            $result = $this->select('qty_out')
            ->findAll();
        }
        return $result;
    }
}
?>