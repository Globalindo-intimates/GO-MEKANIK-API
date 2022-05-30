<?php

namespace App\Controllers\API;

// defined('BASEPATH') or exit('No direct script access allowed');

use CodeIgniter\RESTful\ResourceController;
use App\Models\MachineBreakdownModel;
use App\Models\UserMekanikModel;
use App\Models\MekanikRepairingModel;
use App\Models\DeviceTokensModel;
use App\Models\ViewMachineRepairingModel;

//use App\Libraries\Notification;

class MekanikRepairing extends ResourceController{
    protected $machineBreakdownModel;
    protected $mekanikRepairingModel;
    protected $viewMekanikRepairingModel;
    protected $userMekanikModel;
    protected $devicesTokensModel;

    public function __construct()
    {
        $this->machineBreakdownModel = new MachineBreakdownModel();
        $this->mekanikRepairingModel = new MekanikRepairingModel();
        $this->userMekanikModel = new UserMekanikModel();
        $this->devicesTokensModel = new DeviceTokensModel();
        $this->viewMekanikRepairingModel = new ViewMachineRepairingModel();

    }

    public function simpanMekanikRepairing(){
        date_default_timezone_set("Asia/Jakarta");

        $idUser = $this->request->getVar('idUser');
        $idMachineBreakdown = $this->request->getVar('idMachineBreakdown');

        $dataMachineBreakdown = $this->machineBreakdownModel->getMachineBreakdown($idMachineBreakdown);

        // print_r($dataMachineBreakdown);

        $idMekanikMember = $this->userMekanikModel->getIdMekanikMember($idUser);

        $dataForMekanikRepairing = [
            'id_machine_breakdown' => $dataMachineBreakdown['id_machine_breakdown'],
            'id_mekanik_member' => $idMekanikMember,
            'line' => $dataMachineBreakdown['line'],
            'tgl' => date('Y-m-d'),
            'start_repairing' => date('H:i:s'),
            'end_repairing' => date('H:i:s', strtotime('00:00:00')),
            'barcode' => $dataMachineBreakdown['barcode_machine'],
            'status' => 'Repairing'
        ];

        // print_r($dataForMekanikRepairing);

        $result = $this->mekanikRepairingModel->simpanMekanikRepairing($dataForMekanikRepairing);

        if($result){
            return $this->respond([
                'status' => true,
                'data' => ['id' => $result]
            ],200);
        }else{
            return $this->respond([
                'status' => false,
                'data' => null
            ],400);            
        }
    }

    public function cancelMekanikRepairing(){
		$idMekanikRepairing = $this->request->getVar('idMekanikRepairing');
		$idUserMekanik = $this->request->getVar('idUserMekanik');
        $data = [
            'id_mekanik_repairing' => $idMekanikRepairing,
            'status' => 'Cancel'
        ];
        
        //$token = $this->devicesTokensModel->getToken($idUserMekanik);
        // echo print_r($token);

        $rst = $this->mekanikRepairingModel->cancelRepairing($data);

        if($rst){
            $result = $this->mekanikRepairingModel->getMekanikRepairing($data['id_mekanik_repairing']);
            if($result != null){
                $machineBreakdownRst = $this->machineBreakdownModel->getMachineBreakdown($result['id_machine_breakdown']);
                if($machineBreakdownRst != null){
                    $r = $this->machineBreakdownModel->updateToWaiting($result['id_machine_breakdown']);
                    if($r){
                        $title = "Order Canceled - " . $machineBreakdownRst['line'];
                        $message = "Order canceled by mechanic!";
                        $data = [
                            "Line" => $machineBreakdownRst['line'],
                            "Brand" => $machineBreakdownRst['machine_brand'],
                            "Tipe Mesin" => $machineBreakdownRst['machine_type'],
                            "Mesin SN" => $machineBreakdownRst['machine_sn'],
                            "Symptom" => $machineBreakdownRst['sympton']  
                        ];
                        
                        $tokens = $this->devicesTokensModel->getDevicesTokens();
        
                        $regIds = [];
                        foreach ($tokens as $t) {
                            //if($t['token'] != $token['token']){
                                array_push($regIds, $t['token']);
                            //}
                            // print_r($t['token']);
                        }
    
                        //$notif = new Notification();
                        //$rsp = $notif->sendNotification($regIds, $title, $message);
    
                        //return $this->respond(json_decode($rsp), 200, "");
						
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                            "registration_ids": ' . json_encode($regIds) . ',
                            "notification": {
                                "title": "' . $title . '",
                                "body": "' . $message . '"
                            },
                            "data": {
                                "Line": "' . $data['Line'] . '",
                                "Machine Brand": "' . $data['Brand'] . '",
                                "Machine Type": "' . $data['Tipe Mesin'] . '",
                                "Machine SN": "' . $data['Mesin SN'] . '",
                                "Symptom": "' . $data['Symptom'] . '"
                            }            
                        }',
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: key=AAAA8o0O1Wg:APA91bFVnYzfYuZ3OWu8uxrVoFW7Rk2SH7SDO5FnjhRpYFWex56uXPuk9E1SIA_iy3fnbTmh2kYk15jnon7kHcy6uMSjzlK1gbkx2PNUbkFe9yIepuMtDsqQxhEZvxH69lq-HRi-2x7t',
                                'Content-Type: application/json'
                            ),
                        ));
                
                        $response = curl_exec($curl);
                
                        curl_close($curl);
                        
                        // echo $response;                        
                        return $this->respond(json_decode($response));						
                    }
                }                
            }            
        }

        // return $this->respond([
        //     'status' => $rst
        // ]);
    }

    public function getTotalUserOrders(){
        $id = $this->request->getVar('idUserMekanik');
        $rst = $this->viewMekanikRepairingModel->totalUserOrders($id);

        return $this->respond(['data' => $rst]);
    }

    public function getTotalUserCanceledOrders(){
        $id = $this->request->getVar('idUserMekanik');
        $month = $this->request->getVar('month');
        $year = $this->request->getVar('year');
        $rst = $this->viewMekanikRepairingModel->totalUserCanceledOrders($id, $month, $year);

        return $this->respond(['data' => $rst]);
    }

    public function getUserCanceledOrders(){
        $id = $this->request->getVar('idUserMekanik');
        $month = $this->request->getVar('month');
		$year = $this->request->getVar('year');
        $rst = $this->viewMekanikRepairingModel->getUserCanceledOrders($id, $month, $year);

        // return $this->respond(['data' => $rst]);
        return $this->respond($rst, 200);
    }    

    public function getUserCanceledOrdersDetail(){
        $id = $this->request->getVar('id');
        $month = $this->request->getVar('month');
		$year = $this->request->getVar('year');

        $rst = $this->viewMekanikRepairingModel->userGetCanceledOrdersDetail($id, $month, $year);

        return $this->respond(['data' => $rst], 200);
    }

    public function getUserOrdersDetail(){
        $id = $this->request->getVar('id');
		$month = $this->request->getVar('month');
        $year = $this->request->getVar('year');
        $rst = $this->viewMekanikRepairingModel->userOrdersDetail($id, $month, $year);

        return $this->respond(['data' => $rst], 200);
    }

}