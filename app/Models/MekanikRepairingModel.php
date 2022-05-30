<?php
namespace App\Models;
use CodeIgniter\Model;

class MekanikRepairingModel extends Model{
    // protected $DBGroup = 'default';
    protected $table = 'mekanik_repairing';
    protected $primaryKey = 'id_mekanik_repairing';
    protected $allowedFields = [
        'id_mekanik_repairing',
        'id_machine_breakdown',
        'id_mekanik_member',
        'line',
        'tgl',
        'start_repairing',
        'end_repairing',
        'barcode',
        'date_start_repairing',
        'date_end_repairing',
        'status'
    ];

    public function simpanMekanikRepairing($data){
        $rst = $this->insert($data);
        return $rst;
        // if($rst){
        //     return true;
        // }
        // return false;
    }

    public function cancelRepairing($data){
        $rst = $this->save($data);
        return $rst;
    }

    public function getMekanikRepairing($id){
        $rst = $this->find((int)$id);

        return $rst;
    }
}