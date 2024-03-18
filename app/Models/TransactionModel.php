<?php
namespace App\Models;
use CodeIgniter\Model;

class TransactionModel extends Model {
	protected $table = 'transaction';
	protected $primaryKey = 'transaction_code';
	
    protected $returnType     = 'array';
	
	protected $allowedFields = ['transaction_code', 'customer_name', 'product_name', 'qty_out'];

    function addTransaction($data){
        

    }
}
?>