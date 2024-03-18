<?php

namespace App\Controllers;

class Customer extends BaseController
{
    public function get()
    {
		$customerModel = new \App\Models\CustomerModel();
		$id = $this->request->getGet('customer_code');
		
		if($id)
			$data['customer'] = $customerModel->find($id);
		else
		{
			$data['customer'] = "No ID";
		}
		
        echo json_encode($data['customer']);
    }
}
