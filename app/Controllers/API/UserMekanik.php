<?php
namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ViewUserMekanikModel;

class UserMekanik extends ResourceController{
    protected $viewUserMekanikModel;

    public function __construct()
    {
        $this->viewUserMekanikModel = new ViewUserMekanikModel();
    }

    public function getById(){
        $id = $this->request->getVar('id');

        $rst = $this->viewUserMekanikModel->getById($id);

        return $this->respond($rst, 200);
    }
}