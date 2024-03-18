<?php
namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model {
	protected $table = 'product';
	protected $primaryKey = 'product_name';
	
    protected $returnType     = 'array';
	
	protected $allowedFields = ['product_code', 'product_name', 'qty'];

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

    public function qty($id)
    {
        if($id!=''){
            $result = $this->select('qty')
		    ->find($id);
        } else {
            $result = $this->select('qty')
            ->findAll();
        }
        return $result;
    }

    public function decreaseQty($name, $data){
        $this->set('qty', 'qty-'.$data, false)
            ->where('product_name', $name)
            ->update();
    }

    public function addQty($name, $data){
        $this->set('qty', 'qty+'.$data, false)
            ->where('product_name', $name)
            ->update();
    }
}
?>