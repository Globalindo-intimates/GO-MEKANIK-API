<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\OrderModel;

class Order extends ResourceController{
    use ResponseTrait;

    public function index(){
        $model = new OrderModel();
        $data = $model->findAll();

        return $this->respond($data, 200);
    }
}