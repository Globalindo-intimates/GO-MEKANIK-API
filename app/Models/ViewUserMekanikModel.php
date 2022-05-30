<?php

namespace App\Models;

use CodeIgniter\Model;

class ViewUserMekanikModel extends Model
{
    protected $dbGroup = 'default';

    protected $table = 'viewusermekanik';
    // protected $primaryKey = 'id_user_mekanik';
    protected $allowedFields = ['id_user_mekanik', 'NIK', 'Nama', 'Inisial', 'Bagian', 'Shift'];

    public function getById($id)
    {
        $rst = $this->asArray()->select('Nama, Bagian, Shift')
                    ->where(['id_user_mekanik' => $id])->find();
        
        return $rst;
    }
}
