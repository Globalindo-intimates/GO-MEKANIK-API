<?php namespace App\Models;

use CodeIgniter\Model;

class LineModel extends Model{
    protected $DBGroup = 'default';

    protected $table = 'line';
    protected $primaryKey = 'id_line';
    protected $allowedFields = ['id_line','name', 'description','shift','operators','head','id_spv','floor'];
}