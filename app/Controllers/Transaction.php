<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Transaction extends BaseController
{
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
		$this->db = \Config\Database::connect();
		$this->transactionModel = new \App\Models\TransactionModel();
		$this->customerModel = new \App\Models\CustomerModel();
		$this->productModel = new \App\Models\ProductModel();
    }

    public function index()
    {
		$data['transaction'] = $this->transactionModel->findall();
        $data['customer_name'] = $this->customerModel->customer_name($id='');
        $data['product_name'] = $this->productModel->product_name($id='');
        return view('transaction', $data);
    }

	public function get()
    {
		$id = $this->request->getGet('id');
		if($id)
			$data['transaction'] = $this->transactionModel->getTransaction($id);
		else
			$data['transaction'] = $this->transactionModel->findall();
		echo json_encode($data['transaction']);
	}
	
	public function getAll()
    {
		$list = $this->transactionModel->findall();
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
			'transaction_code' => $this->request->getPost('transaction_code'),
			'customer_name' => $this->request->getPost('customer_name'),
			'product_name' => $this->request->getPost('product_name'),
			'qty_out' => $this->request->getPost('qty_out'),
		];
		if(! $this->validateData($data, 'transaction')){
			$result['errors'] = $this->validator->getErrors();
			$result['status'] = 'validation';
			echo json_encode($result);
		} else {
			$this->db->transBegin();
			$this->transactionModel->addTransaction($data);
			$this->productModel->decreaseQty($data['product_name'], $data['qty_out']);

			$qty = $this->productModel->qty($data['product_name']);
			
			if($qty['qty']<0){
				$result['message'] = 'Insert Failed, Please make sure there is enough stock of '.$data['product_name'];
				$result['status'] = false;
				$this->db->transRollback();
			} elseif($this->db->transStatus() === false){
				$result['message'] = 'Insert Failed';
				$result['status'] = false;
				$this->db->transRollback();
			} else {
				$result['message'] = 'Insert Successfully';
				$result['status'] = true;
				$this->db->transCommit();
			}
			
			echo json_encode($result);
		}
	}

	public function edit(){
		$id = $this->request->getPost('id');
		$data =[
			'customer_name' => $this->request->getPost('customer_name'),
			'product_name' => $this->request->getPost('product_name'),
			'qty_out' => $this->request->getPost('qty_out'),
		];

		if(! $this->validateData($data, 'transaction')){
			$result['errors'] = $this->validator->getErrors();
			$result['status'] = 'validation';
			echo json_encode($result);
		} else {
			$qty_out = $this->transactionModel->qty_out($id);
			$diff = $qty_out['qty_out']-$data['qty_out'];
			$originalProduct = $this->transactionModel->product_name($id);
			
			$this->db->transBegin();
			$this->transactionModel->editTransaction($id, $data);
			if($originalProduct == $data['product_name']){
				$this->productModel->addQty($data['product_name'], $diff);
			} else {
				$this->productModel->addQty($originalProduct, $qty_out['qty_out']);
				$this->productModel->decreaseQty($data['product_name'], $data['qty_out']);
			}
			$qty = $this->productModel->qty($data['product_name']);

			if($qty['qty']<0){
				$result['message'] = 'Edit Failed, Please make sure there is enough stock of '.$data['product_name'];
				$result['status'] = false;
				$this->db->transRollback();
			}
			elseif($this->db->transStatus() === false){
				$result['message'] = 'Edit Failed';
				$result['status'] = false;
				$this->db->transRollback();
			} else {
				$result['message'] = 'Edit Successfully';
				$result['status'] = true;
				$this->db->transCommit();
			}

			echo json_encode($result);
		}
	}
	public function delete(){
		$id = $this->request->getPost('id');
		$qty_out = $this->transactionModel->qty_out($id);
		$product_name = $this->transactionModel->product_name($id);

		$this->db->transBegin();
		$this->transactionModel->deleteTransaction($id);
		$this->productModel->addQty($product_name['product_name'], $qty_out['qty_out']);

		if($this->db->transStatus() === false){
			$result['message'] = 'Delete Failed';
			$result['status'] = false;
			$this->db->transRollback();
		} else {
			$result['message'] = 'Delete Successfully';
			$result['status'] = true;
			$this->db->transCommit();
		}
		echo json_encode($result);
	}
}
