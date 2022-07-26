<?php
namespace App\Models;

use CodeIgniter\Model;

class SpvModel extends Model{
    protected $DBGroup = 'dbPR';
    protected $table = 'spv';
    protected $primaryKey = 'id_spv';
    protected $allowedFields = [
        'id_spv', 'name', 'shift', 'barcode', 'nik'
    ];

    public function getByNIK($nik){
        $result = $this->where('nik', $nik)->limit(1)->first();
        if($result){
            return $result['name'];
        }
        return false;
    }
}