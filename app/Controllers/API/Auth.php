<?php
namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AuthModel;

class Auth extends ResourceController{
    protected $auth;

    public function __construct()
    {
        $this->auth = new AuthModel();
    }

    public function getValidateUserName(){
        $result = $this->auth->getValidateUserName($this->request->getVar('userName'));

        if($result){
            return $this->respond([
                'status' => true,
                'message' => 'User Name terdaftar.',
                'data' => ['id' => $result]
            ], 200);
        }else{
            return $this->respond([
                'status' => false,
                'message' => 'User Name belum terdaftar! Daftar terlebih dahulu!'
            ], 400);
        }
    }

    public function getValidateUserNameRegister(){
        $result = $this->auth->getValidateUserNameRegister($this->request->getVar('userName'));

        if($result){
            return $this->respond([
                'status' => false,
                'message' => 'User Name sudah terdaftar. Silahkan pilih User Name lain',
                'data' => ['id' => $result]
            ], 400);
        }else{
            return $this->respond([
                'status' => true,
                'message' => 'Bisa menggunakan User Name ini.'
            ], 200);
        }
    }
    
    public function getValidateEmailRegister(){
        $result = $this->auth->getValidateEmailRegister($this->request->getVar('email'));

        if($result){
            return $this->respond([
                'status' => false,
                'message' => 'Email sudah terdaftar. Silahkan ganti dengan Email lain',
                'data' => ['id' => $result]
            ], 400);
        }else{
            return $this->respond([
                'status' => true,
                'message' => 'Bisa menggunakan Email ini.'
            ], 200);
        }
    }    

    public function getValidateNIK(){
        $rst = $this->auth->getValidateNIK(
            $this->request->getVar('nik')
        );

        if($rst){
            return $this->respond([
                'status' => true,
                'data' => $rst[0]->id_mekanik_member
            ], 200);
        }else{
            return $this->respond([
                'status' => false,
                'message' => 'NIK tidak ada!'
            ], 400);
        }
    }

    public function getValidateNIK2(){
        $rst = $this->auth->getValidateNIK2(
            $this->request->getVar('nik')
        );

        if($rst){
            return $this->respond([
                'status' => true,
                'data' => $rst['id_user_mekanik']
            ], 200);
        }else{
            return $this->respond([
                'status' => false,
                'message' => 'NIK tidak terdaftar!'
            ], 400);
        }
    }    

    public function login(){
        $data = [
            'user_name' => $this->request->getVar('userName'),
            'password' => $this->request->getVar('password'),
            'token' => $this->request->getVar('token')
        ];

        $result = $this->auth->login($data);
        if($result){
            if($result['active'] == 1){
                return $this->respond([
                    'status' => false,
                    'message' => 'User sedang aktif!'], 400);                
            }else if($result['active'] == 0){
                // $this->_setUserToActive($result['id_user_mekanik']);
                $rst = $this->auth->setUserToActive($result['id_user_mekanik'],$data['token']);
                if($rst){
                    return $this->respond([
                        'status' => true,
                        'message' => 'Login berhasil.',
                        'data' => ['id' => $result['id_user_mekanik'], 'nik' => $result['nik'],'token' => $result['token']]
                    ], 200);
                }                
            }
        }else{
            return $this->respond([
                'status' => false,
                'message' => 'User Name dan atau password salah!'], 400);            
        }
    }

    // public function register(){
    //     $dataMekanikMember = $this->auth->getValidateNIK($this->request->getVar('nik'));
    //     // print_r($dataMekanikMember[0]->id_mekanik_member);
    //     $data = [
    //         'id_mekanik_member' => $dataMekanikMember[0]->id_mekanik_member,
    //         'user_name' => $this->request->getVar('userName'),
    //         'nik' => $this->request->getVar('nik'),
    //         // 'email' => $this->request->getVar('email'),
    //         'password' => $this->request->getVar('password'),
    //         'active' => 0,
    //         'token' => $this->request->getVar('token')
    //     ];

    //     $rst = $this->auth->getValidateUserName($data['user_name']);
    //     if($rst != null){
    //         return $this->respond(
    //             [
    //                 'status' => false,
    //                 'message' => 'User Name sudah dipakai. Gunakan user name lain!'
    //             ],400
    //         );
    //     }else{
    //         $result = $this->auth->register($data);
    //         if($result){
    //             return $this->respond([
    //                 'status' => true,
    //                 'message' => 'Registrasi berhasil.',
    //                 'data' => ['id' => $result]
    //             ], 200);
    //         }else{
    //             return $this->respond([
    //                 'status' => false,
    //                 'message' => 'Register gagal!'], 400);            
    //         }
    //     }
    // }

    public function register(){
        $dataMekanikMember = $this->auth->getValidateNIK($this->request->getVar('nik'));
        if($dataMekanikMember){
            $dataUserName = $this->auth->getValidateUserName($this->request->getVar('userName'));
            if($dataUserName){
                return $this->respond(
                    [
                        'status' => false,
                        'message' => 'User Name sudah dipakai. Gunakan user name lain!'
                    ],400
                );                
            }else{
                $data = [
                    'id_mekanik_member' => $dataMekanikMember[0]->id_mekanik_member,
                    'user_name' => $this->request->getVar('userName'),
                    'nik' => $this->request->getVar('nik'),
                    // 'email' => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'),
                    'active' => 0,
                    'token' => $this->request->getVar('token')
                ];
                $result = $this->auth->register($data);
                if($result){
                    return $this->respond([
                        'status' => true,
                        'message' => 'Registrasi berhasil.',
                        'data' => ['id' => $result]
                    ], 200);  
                }else{
                    return $this->respond([
                        'status' => false,
                        'message' => 'Register gagal!'], 400);            
                }                
            }
        }else{
            return $this->respond([
                "status" => false,
                "message" => "NIK tidak ada!"
            ]);
        }

    }
    
    public function simpanPassword(){
        $id = $this->request->getVar('id');
        $password = $this->request->getVar('password');
        $data = [
            'id_user_mekanik' => $id,
            'password' => $password
        ];

        $result = $this->auth->simpanPassword($data);

        if($result){
            return $this->respond([
                'status' => true,
                'message' => 'Password berhasil dirubah.',
            ], 200);
        }else{
            return $this->respond([
                'status' => false,
                'message' => 'Password gagal dirubah!'], 400);            
        }
    }
    
    public function deactiveUser(){
        $data = [
            'id_user_mekanik' => $this->request->getVar('id'),
            'active' => 0
        ];

        $result = $this->auth->deactiveUser($data);
        $returnVal = [
            'status' => $result
        ];

        return $this->respond($returnVal);
    }
}