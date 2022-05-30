<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserMekanikModel;
use App\Models\MekanikMemberModel;

class UserMekanik extends ResourceController{
    use ResponseTrait;

    public function index(){
        $model = new UserMekanikModel();
        $data = $model->findAll();

        return $this->respond($data, 200);
    }

    public function create(){
        $nik = $this->request->getPost('nik');
        $userName = $this->request->getPost('userName');
        $password = $this->request->getPost('password');

        //cari id_mekanik_member di table mekanik_member berdasarkan NIK
        $data['mekanikMember'] = $this->_getByNIK($nik);
        if($data['mekanikMember'] != null){
            //jika ada cari user_name di tabel user_mekanik
            $data['userMekanik'] = $this->_getByUserName($userName);
            if($data['userMekanik'] != null){
                $response = [
                    'value' => 2,
                    'message' => 'User Name sudah ada!'
                ];
            }else{
                $dataUserMekanik = [
                    'id_mekanik_member' => $data['mekanikMember'][0]->id_mekanik_member,
                    'nik' => $nik,
                    'user_name' => $userName,
                    'password' => md5($password),
                    'active' => 0
                ];
                $userMekanikModel = new UserMekanikModel();

                $save = $userMekanikModel->createUser($dataUserMekanik);
                if($save){
                    $msg = ['message' => 'Register user baru berhasil!'];
                    $response = [
                        'value' => 0,
                        'message' => 'Register user baru berhasil.'
                    ];                    
                }
            }
        }else{
            //jika null
            $response = [
                'value' => 1,
                'message' => "NIK tidak ada!"
            ];            
            
        }

        return $this->respond($response);

        // echo json_encode($data);
    }

    private function _getByNIK($nik){
        $mekanikMemberModel = new MekanikMemberModel();

        $data = $mekanikMemberModel->getWhere(['NIK' => $nik])->getResult();

        return $data;
    }

    private function _getByUserName($userName){
        $model = new UserMekanikModel();

        $data = $model->getWhere(['user_name' => $userName])->getResult();

        return $data;
    }

    public function update($id=null){
        $model = new UserMekanikModel();
        $json = $this->request->getJSON();
        if($json){
            $data = [
                'user_name' => $json->userName
            ];
        }else{
            $input = $this->request->getRawInput();
            $data = [
                'user_name'
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
        $model = new UserMekanikModel();
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
    
    public function signIn(){
        $userName = $this->request->getPost('userName');
        $password = $this->request->getPost('password');

        $model = new UserMekanikModel();

        $dataUserMekanik = $model->getWhere(['user_name' => $userName, 'password' => md5($password)])->getResult();
        if($dataUserMekanik){
            $response = [
                'value' => 1,
                'userName' => $userName,
                'messages' => 'Login berhasil.' 
            ];
        }else{
            $response = [
                'value' => 2,
                'userName' => '',
                'messages' => 'Login gagal, User Name dan atau Password salah!' 
            ];
        }
        return $this->respond($response);
    }

}