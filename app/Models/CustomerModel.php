<?php
namespace App\Models;
use CodeIgniter\Model;

class CustomerModel extends Model {
	protected $table = 'customer';
	protected $primaryKey = 'customer_code';
	
    protected $returnType     = 'array';
	
	protected $allowedFields = ['customer_code', 'customer_name', 'address', 'telp'];
	
    public function customer_name()
    {
        $result = $this->select('customer_name')
		->findAll();
        return $result;
    }
}
?>