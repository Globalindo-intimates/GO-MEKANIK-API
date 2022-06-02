<?php
namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MachineBreakdownModel;
use App\Models\ViewMachinesBreakdownModel;
use CodeIgniter\API\ResponseTrait;

class Machinebreakdown extends ResourceController{
    protected $machineBreakdown;
	protected $viewMachineBreakdown;

    use ResponseTrait;

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

    // public function index(){
    //     // $status = $this->request->getVar('status');
    //     try{
    //         $rst = $this->viewMachineBreakdown->getAll();
    //         if($rst){
    //             return $this->respond($rst, 200);
    //         }
    //         return $this->respond([
    //             'status' => 'empty',
    //             'message' => 'Data kosong'
    //         ], 200);
    //     }catch(\Exception $e){
    //         die($e->getMessage());
    //     }
    // }    

    public function getByStatus($status){
        try{
            $rst = $this->viewMachineBreakdown->getByStatus($status);
            if($rst){
                return $this->respond($rst, 200);
            }else{
                return $this->respond([
                    'status' => 'empty',
                    'message' => 'Data kosong'
                ], 200);            
            }
        }catch(\Exception $e){
            die($e->getMessage());
        }
    }

}