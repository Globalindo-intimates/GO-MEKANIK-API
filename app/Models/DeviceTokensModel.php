<?php

namespace App\Models;

use CodeIgniter\Model;

class DeviceTokensModel extends Model{
    protected $table = 'user_mekanik';
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

    public function getDevicesTokens(){
        $rst = $this->select('token')->findAll();
        return $rst;
    }

    public function getToken($idUserMekanik){
        $rst = $this->find($idUserMekanik);

        return $rst;
    }
}