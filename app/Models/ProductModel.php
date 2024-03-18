<?php
namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model {
	protected $table = 'product';
	protected $primaryKey = 'product_code';
	
    protected $returnType     = 'array';
	
	protected $allowedFields = ['product_code', 'product_name', 'qty'];

	public function product_name()
    {
        $result = $this->select('product_name')
		->findAll();
        return $result;
    }
}
?>