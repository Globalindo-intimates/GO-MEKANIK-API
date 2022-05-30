<?php namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model{
    protected $tabel = 'order1';
    protected $primaryKey = "id_order";
    protected $allowedFields = [
        'id_order', 
        'style', 
        'style_sam',
        'orc',
        'comm',
        'buyer',
        'so',
        'color',
        'qty',
        'etd',
        'to_cutting',
        'tgl_to_cutting',
        'exported_date',
        'exported_qty',
        'plan_export',
        'line_allocation1',
        'line_allocation2',
        'line_allocation3',
    ];
}