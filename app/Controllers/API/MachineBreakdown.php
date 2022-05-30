<?php
namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MachineBreakdownModel;
use App\Models\ViewMachinesBreakdownModel;

class MachineBreakdown extends ResourceController{
    protected $machineBreakdown;
	protected $viewMachineBreakdown;

    public function __construct()
    {
        $this->machineBreakdown = new MachineBreakdownModel();
		$this->viewMachineBreakdown = new ViewMachinesBreakdownModel();
    }

    public function index(){
        $status = $this->request->getVar('status');
        $rst = $this->viewMachineBreakdown->getMachinesBreakdown($status);
        if($rst){
            return $this->respond($rst, 200);
        }else{
            return $this->respond([
                'status' => false,
                'message' => 'Data kosong'
            ], 400);
        }
    }

    public function updateEndWaiting(){
        $id = $this->request->getVar('id');

        $result = $this->machineBreakdown->updateEndWaiting($id);

        return $this->respond(['status' => $result]);
    }

    public function getMachineBreakdownStatus(){
        $id = $this->request->getVar('id');

        $result = $this->viewMachineBreakdown->getMachineBreakdownStatus($id);

        return $this->respond(['data' => $result['status']]);
    }

}