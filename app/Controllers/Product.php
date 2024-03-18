<?php

namespace App\Controllers;

class Customer extends BaseController
{
    public function get()
    {
		$productModel = new \App\Models\ProductModel();
		$id = $this->request->getGet('product_code');
		
		if($id)
			$data['product'] = $productModel->find($id);
		else
		{
			$data['product'] = "No ID";
		}
		
        echo json_encode($data['product']);
    }
}
