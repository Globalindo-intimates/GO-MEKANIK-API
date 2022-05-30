<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MerkModel;

class Merk extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new MerkModel();
        $data = $model->findAll();

        return $this->respond($data, 200);
    }

    public function show($id = null)
    {
        $model = new MerkModel();
        $data = $model->getWhere(['id_merk' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found With ID ' . $id);
        }
    }

    public function create()
    {
        $model = new MerkModel();
        $data = [
            'name' => $this->request->getPost('merk_name')
        ];
        $data = json_decode(file_get_contents("php://input"));
        $model->insert($data);
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Data Saved'
            ]
        ];

        return $this->respondCreated($data, 201);
    }

    public function update($id = null)
    {
        $model = new MerkModel();
        $json = $this->request->getJSON();
        if ($json) {
            $data = [
                'name' => $json->name
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'name' => $input['merk_name']
            ];
        }
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $model = new MerkModel();
        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found With ID ' . $id);
        }
    }
}
