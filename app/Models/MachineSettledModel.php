<?php
namespace App\Models;
use CodeIgniter\Model;

class MachineSettledModel extends Model{
    protected $table='machine_settled';
    protected $primaryKey='id_machine_sttled';
    protected $allowedFields = [
        'id_machine_stteld',
        'id_mekanik_repairing',
        'id_machine_breakdown',
        'id_mekanik_member',
        'line',
        'spv_name',
        'status',
        'date',
        'barcode_machine',
        'catatan'
    ];

    public function simpanMachineSettled($data){
        $rst = $this->save($data);
        if($rst){
            return true;
        }else{
			return false;	
		}
        
    }
}