<?php

namespace App\Controllers;

class Transaction extends BaseController
{
    public function index()
    {
		$transactionModel = new \App\Models\TransactionModel();
		$customerModel = new \App\Models\CustomerModel();
		$productModel = new \App\Models\ProductModel();
		$data['transaction'] = $transactionModel->findall();
        $data['customer_name'] = $customerModel->customer_name();
        $data['product_name'] = $productModel->product_name();
        return view('transaction', $data);
    }
	
	public function getAll()
    {
		$transactionModel = new \App\Models\TransactionModel();
		$list = $transactionModel->findall();
		$data = array();
        foreach ($list as $transaction) {
            $row = array();
			$row[] = $transaction['transaction_code'];
            $row[] = $transaction['customer_name'];
            $row[] = $transaction['product_name'];
            $row[] = $transaction['qty_out'];
 
            $data[] = $row;
        }
		$output = array(
                        "data" => $data,
                );
		echo json_encode($output);
    }
	
	public function add(){
		$data =[
			'transaction_code' => $this->request->getPost('trasaction_code'),
			'customer_name' => $this->request->getPost('customer_name'),
			'product_name' => $this->request->getPost('product_name'),
			'qty_out' => $this->request->getPost('qty_out'),
		];
		$model = new \App\Models\CustomerModel();
		
		if ($model->insert($data)){
			$result['message'] = 'Insert Successfully';
			$result['status'] = true;
		} else {
			$result['message'] = 'Insert Failed';
			$result['status'] = false;
		}
		echo json_encode($result);
	}
	public function edit(){
		$model = new \App\Models\TransactionModel();
		
		$id = $this->request->getPost('transaction_code');
		$data =[
			'transaction_code' => $this->request->getPost('trasaction_code'),
			'customer_name' => $this->request->getPost('customer_name'),
			'product_name' => $this->request->getPost('product_name'),
			'qty_out' => $this->request->getPost('qty_out'),
		];
		if ($model->update($id, $data)){
			$result['message'] = 'Edit Successfully';
			$result['status'] = true;
		} else {
			$result['message'] = 'Edit Failed';
			$result['status'] = false;
		}	
		echo json_encode($result);
	}
	public function delete(){
		$id = $this->request->getPost('transaction_code');
		$model = new \App\Models\TransactionModel();
		if ($model->delete($id)){
			$result['message'] = 'Delete Successfully';
			$result['status'] = true;
		} else {
			$result['message'] = 'Delete Failed';
			$result['status'] = false;
		}
		echo json_encode($result);
	}
}
