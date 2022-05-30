<?php namespace App\Models;

use CodeIgniter\Model;

class UserMekanikModel extends Model{
    protected $dbGroup='default';

    protected $table = 'user_mekanik';
    protected $primaryKey = 'id_user_mekanik';
    protected $allowedFields = ['id_user_mekanik', 'id_mekanik_member','user_name', 'nik', 'password','active'];

    public function createUser($data){
        return $this->db->table($this->table)->insert($data);
    }   

    public function getIdMekanikMember($id){
        $result = $this->find($id);

        return $result['id_mekanik_member'];
    }
    
}