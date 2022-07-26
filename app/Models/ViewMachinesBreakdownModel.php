<?php
namespace App\Models;
use CodeIgniter\Model;

class ViewMachinesBreakdownModel extends Model{
    protected $DBGroup = 'default';    
    protected $table = 'viewmachinesbreakdown';
    protected $primaryKey = 'id_machine_breakdown';
    protected $allowedFields = [
        'id_machine_breakdown',
        'line',
        'tgl_breakdown',
        'machine_brand',
        'machine_type',
        'type',
        'machine_sn',
        'sympton',
        'status',
        'date_start_waiting',
        'date_end_waiting',
        'floor',
        'barcode_machine'
    ];

    public function getMachinesBreakdown($status){
        if($status == "all"){
            $rst = $this->findAll();
            return $rst;
        }else{
            $rst = $this->select(
                "id_machine_breakdown, line, tgl_breakdown, 
                machine_brand, machine_type, type, machine_sn,
                sympton, status, date_start_waiting, date_end_waiting, floor"
            )
            ->orderBy('id_machine_breakdown', 'DESC')
            ->where('status', $status)->findAll();

            return $rst;
        }
    }

    public function getMachineBreakdown($id){
        $rst = $this->find((int)$id);

        return $rst;
    }

    public function updateEndWaiting($id){
        $dataForUpdate = [
            'id_machine_breakdown' => $id,
            'status' => 'Repairing...'
        ];

        $rst = $this->save($dataForUpdate);
        if($rst){
            return TRUE;
        }
        return FALSE;

    }

    public function getMachineBreakdownStatus($id){
        $result = $this->find($id);
        // $result = $this->where('id_machine_breakdown', $id)->findAll();

        return $result;
    }

    // public function updateToWaiting($id){
    //     $dataForUpdate = [
    //         'id_machine_breakdown' => $id,
    //         'status' => 'Waiting...'
    //     ];

    //     $rst = $this->save($dataForUpdate);
    //     if($rst){
    //         return TRUE;
    //     }
    //     return FALSE;        
    // }

    public function getAll(){
        $rst = $this->findAll();
        return $rst;
    }

    public function getByStatus($status){
        $rst = $this->select(
            "id_machine_breakdown, line, tgl_breakdown, 
            machine_brand, machine_type, type, machine_sn,
            sympton, status, date_start_waiting, date_end_waiting, floor"
        )
        ->orderBy('id_machine_breakdown', 'DESC')
        ->where('status', $status)->findAll();

        return $rst;        
    }
}