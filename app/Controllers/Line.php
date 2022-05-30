<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\LineModel;

class Line extends ResourceController{
    use ResponseTrait;

    public function index(){
        $model = new LineModel();
        $data = $model->findAll();

        return $this->respond($data, 200);
    }

    public function create()
    {
        $model = new LineModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'shift' => $this->request->getPost('shift'),
            'operators' => $this->request->getPost('operators'),
            'head' => $this->request->getPost('head'),
            'id_spv' => $this->request->getPost('id_spv'),
            'floor' => $this->request->getPost('floor')
        ];

        // $data = json_decode(file_get_contents("php://input"));
        $model->insert($data);
        $response = [
            'status' => 201,
            'error' => null,
            'mesagges' => [
                'success' => 'Data Saved'
            ]
        ];

        return $this->respondCreated($data, 201);
    }

    public function update($id = null){
        $model = new LineModel();
        $json = $this->request->getJSON();
        if($json){
            $data = [
                'name' => $json->name,
                'description' => $json->description,
                'shift' => $json->shift,
                'operators' => $json->operators,
                'head' => $json->head,
                'id_spv' => $json->id_spv,
                'floor' => $json->floor                
            ];
        }else{
            $input = $this->request->getRawInput();
            $data = [
                'name' => $input['name'],
                'description' => $input['description'],
                'shift' => $input['shift'],
                'operators' => $input['operators'],
                'head' => $input['head'],
                'id_spv' => $input['id_spv'],
                'floor' => $input['floor']  
            ];
            $model->update($id, $data);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data updated'
                ]
                ];

            return $this->respond($response);
        }
    }

    public function delete($id = null){
        $model = new LineModel();
        $data = $model->find($id);
        if($data){
            $model->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data deleted'
                ]
                ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No data found with id: ' . $id);
        }
    }
}