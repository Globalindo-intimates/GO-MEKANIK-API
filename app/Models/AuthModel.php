<?php
namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model{
    protected $table = 'user_mekanik';
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    protected $primaryKey = 'id_user_mekanik';
    protected $allowedFields = [
        'id_user_mekanik', 
        'id_mekanik_member',
        'user_name',
        'nik',
        // 'email',
        'password',
        'active',
        'token'
    ];

    public function getValidateUserName($userName){
        $result = $this->where('user_name', $userName)->limit(1)->first();

        if($result){
            return $result['id_user_mekanik'];
        }

        return false;
    }

    public function getValidateUserNameRegister($userName){
        $result = $this->where('user_name', $userName)->limit(1)->first();

        if($result){
            return $result['id_user_mekanik'];
        }

        return false;
    }
    
    public function getValidateEmailRegister($email){
        $result = $this->where('email', $email)->limit(1)->first();

        if($result){
            return $result['id_user_mekanik'];
        }

        return false;
    }    

    public function getValidateNIK($nik){
        $db = \Config\Database::connect();
        $builder = $db->table('mekanik_member');

        $result = $builder->where('NIK', $nik)->get()->getResult();
        // $result = $builder->where('NIK', $nik)->limit(1)->first();

        if($result){
            return $result;
        }

        return false;        
    }

    public function getValidateNIK2($nik){
        $result = $this->where('NIK', $nik)->limit(1)->first();

        if($result){
            return $result;
        }

        return false;        
    }

    public function login($data){
        $result = $this->where('user_name', $data['user_name'])->limit(1)->first();

        if($result){
            if(password_verify($data['password'], $result['password'])){
                //return $result['id_user_mekanik'];
                return $result;
            }else{
                return FALSE;
            }

            return FALSE;
        }

    }

    public function setUserToActive($id, $token){
        $data = [
            'id_user_mekanik' => $id,
            'active' => 1,
            'token' => $token
        ];

        $rst = $this->save($data);
        if($rst){
            return TRUE;
        }
        return FALSE;

    }

    protected function hashPassword(array $data){
        if(!isset($data['data']['password'])){
            return $data;
        }
        
        $options = ['cost' => 10];
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT, $options);

        return $data;
    }

    public function register($data){
        $result = $this->insert($data);
        if($result > 0){
            return $result;
            // echo $result;
            // $query = $this->find($result);

            // return $query['id_user_mekanik'];
        }
        return FALSE;
    }
    
    // public function simpanPassword($id, $data){
        // $result = $this->update(['id_user_mekanik' => $data['id']], $data['pass']);
        // $rst = $this->update(['id_user_mekanik' => $id], $data);
        // $result = $this->save($data);
        
        // return $rst;

        // return $this->db->table($this->table)->update($data, ['id_user_mekanik' => $id]);
    // }

    public function simpanPassword($data){
        $rst = $this->save($data);
        if($rst){
            return TRUE;
        }
        return FALSE;
    }

    public function deactiveUser($data){
        $rst = $this->save($data);
        if($rst){
            return TRUE;
        }
        return FALSE;
    }
}