<?php
namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MachineSettledModel;
use App\Models\ViewMachineSettledModel;
use App\Models\MachineBreakdownModel;
use App\Models\UserMekanikModel;
use App\Models\SpvModel;

use App\Libraries\Notification;
use App\Models\ViewMachineSettled;

class MachineSettled extends ResourceController{
    protected $machineBreakdownModel;
    protected $machineSetteldModel;
    protected $viewMachineSettledModel;
    protected $userMekanikModel;
    protected $spvModel;

    public function __construct(){
        $this->machineBreakdownModel = new MachineBreakdownModel();
        $this->machineSetteldModel = new MachineSettledModel();
        $this->viewMachineSettledModel = new ViewMachineSettledModel();
        $this->userMekanikModel = new UserMekanikModel();
        $this->spvModel = new SpvModel();
    }

    public function simpanMachineSettled(){
        date_default_timezone_set("Asia/Jakarta");

        $idUser = $this->request->getVar('idUser');
        $idMachineBreakdown = $this->request->getVar('idMachineBreakdown');
        $idMekanikRepairing = $this->request->getVar('idMekanikRepairing');
        $nik = $this->request->getVar('nik');
        $catatan = $this->request->getVar('catatan');

        $dataMachineBreakdown = $this->machineBreakdownModel->getMachineBreakdown($idMachineBreakdown);
        $idMekanikMember = $this->userMekanikModel->getIdMekanikMember($idUser);        
        $spvName = $this->spvModel->getByNIK($nik);

        $dataForMachineSetteld = [
            'id_mekanik_repairing' => $idMekanikRepairing,
            'id_machine_breakdown' => $idMachineBreakdown,
            'id_mekanik_member' => $idMekanikMember,
            'line' => $dataMachineBreakdown['line'],
            'spv_name' => $spvName,
            'status' => 'settled',
            'date' => date('Y-m-d H:i:s'),
            'barcode_machine' => $dataMachineBreakdown['barcode_machine'],
            'catatan' => $catatan
        ];

        $result = $this->machineSetteldModel->simpanMachineSettled($dataForMachineSetteld);
        if($result){
            return $this->respond([
                'status' => true,
            ],200);
        }else{
            return $this->respond([
                'status' => false,
            ],400);            
        }        

    }

    public function getTotalUserOrdersFinished(){
        $id = $this->request->getVar('idUserMekanik');
        $month = $this->request->getVar('month');
		$year = $this->request->getVar('year');
        $rst = $this->viewMachineSettledModel->totalUserOrdersFinished($id, $month, $year);

        return $this->respond(
            ['data' => $rst]
        );
    }

    public function getUserOrdersFinished(){
        $id = $this->request->getVar('idUserMekanik');
        $month = $this->request->getVar('month');
        $year = $this->request->getVar('year');
        $rst = $this->viewMachineSettledModel->userOrdersFinished($id, $month, $year);		
        
        return $this->respond($rst, 200);
    }

    public function getUserOrdersFinishedDetail(){
        $id = $this->request->getVar('id');
        $month = $this->request->getVar('month');
        $year = $this->request->getVar('year');
        $rst = $this->viewMachineSettledModel->userOrdersFinishedDetail($id, $month, $year);		
        
        return $this->respond(['data' => $rst], 200);
    }
}
