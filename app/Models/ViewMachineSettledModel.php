<?php 
namespace App\Models;

use  CodeIgniter\Model;

class ViewMachineSettledModel extends Model{
    protected $DBGroup = 'default';
    protected $table="viewmachinesettled";
    protected $allowedFields = [
        'id_mekanik_member','id_user_mekanik','NIK','Nama','start_repairing','date','status',
        'machine_brand', 'machine_type', 'type', 'machine_sn', 'sympton'
    ];
    protected $returnType = 'object';

    public function totalUserOrdersFinished($id, $month, $year){
        $rst = $this->where(['id_user_mekanik' => $id, 'MONTH(date)' => $month, 'YEAR(date)' => $year])->countAllResults();

        return $rst;
    }

    public function userOrdersFinished($id, $month, $year){
        // $rst = $this->asArray()->select('COUNT(date) as count_date')
        //             ->where(['id_user_mekanik' => $id])
        //             ->groupBy('date')->findAll();
        $rst = $this->asArray()->select('COUNT(id_user_mekanik) AS count_id, DAY(date) as day')
                    ->where(['id_user_mekanik' => $id, 'MONTH(date)' => $month, 'YEAR(date)' => $year])
                    ->groupBy('DAY(date)')->findAll();

        return $rst;        
    }

    public function userOrdersFinishedDetail($id, $month, $year){
        $rst = $this->asArray()->select("date, machine_brand, machine_type, machine_sn, sympton, TIMEDIFF(date,start_repairing) AS duration")
                    ->where(['id_user_mekanik' => $id, 'MONTH(date)' => $month, 'YEAR(date)' => $year])
                    ->findAll();
        return $rst;
    }
}


