<?php namespace App\Models;

use CodeIgniter\Model;

class MerkModel extends Model{
    protected $DBGroup = 'db2';

    protected $table = 'merk';
    protected $primaryKey = 'id_merk';
    protected $allowedFields = ['name'];


    
}