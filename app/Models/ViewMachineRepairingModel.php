<?php
namespace App\Models;

use Codeigniter\Model;

class ViewMachineRepairingModel extends Model{
    protected $DBGroup = 'default';
    protected $table="viewmachinerepairing";
    protected $allowedFields = [
        'id_mekanik_member',
        'id_user_mekanik',
        'NIK',
        'Nama',
        'tgl',
        'status'
    ];

    public function totalUserOrders($id){
        $rst = $this->where(['id_user_mekanik' => $id])->countAllResults();

        return $rst;

    }

    public function userOrdersDetail($id, $month, $year){
        $rst = $this->where(['id_user_mekanik' => $id, 'MONTH(tgl)' => $month, 'YEAR(tgl)' => $year])->findAll();

        return $rst;
    }
    
    public function totalUserCanceledOrders($id, $month, $year){
        $rst = $this->where([
				'id_user_mekanik' => $id, 
				'status' => 'Cancel', 
				'MONTH(tgl)' => $month,
				'YEAR(tgl)' => $year
			])->countAllResults();

        return $rst;
    }

    public function getUserCanceledOrders($id, $month, $year){
        $rst = $this->asArray()->select('COUNT(id_mekanik_member) as count_id, DAY(tgl) as day')
                    ->where(['id_user_mekanik' => $id, '`status`' => 'Cancel', 'MONTH(tgl)' => $month, 'YEAR(tgl)' => $year])
                    ->groupBy('DAY(tgl)')->findAll();

        return $rst;
    }

    public function userGetCanceledOrdersDetail($id, $month, $year){
        $rst = $this->asArray()
                    ->where(['id_user_mekanik' => $id, '`status`' => 'Cancel', 'MONTH(tgl)' => $month, 'YEAR(tgl)' => $year])->findAll();
        
        return $rst;
    }
}