<?php
namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SpvModel;

class Spv extends ResourceController{
    protected $spvModel;

    public function __construct()
    {
        $this->spvModel = new SpvModel();
    }

    public function getByNIK(){
        $nik = $this->request->getVar('nik');

        $rst = $this->spvModel->getByNIK($nik);
        if($rst){
            return $this->respond([
                'status' => true,
                'message' => ''
            ], 200);
        }else{
            return $this->respond([
                'status' => false,
                'message' => 'NIK tidak ada!'
            ], 400);
        }

    }
}

