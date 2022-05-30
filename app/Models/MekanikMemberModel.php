<?php namespace App\Models;

use CodeIgniter\Model;

class MekanikMemberModel extends Model{
    protected $DBGroup = 'default';

    protected $table = 'mekanik_member';
    protected $primaryKey = 'id_mekanik_member';
    protected $allowedFields = ['id_mekanik_member', 'NIK', 'Nama', 'Inisial', 'Bagian', 'Shift', 'nomor_telepon', 'barcode'];


}