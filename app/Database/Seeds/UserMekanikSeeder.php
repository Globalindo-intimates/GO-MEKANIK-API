<?php
namespace App\Database\Seeds;

class UserMekanikSeeder extends \CodeIgniter\Database\Seeder{
    public function run(){
        $options = [
            'cost' => 10
        ];

        $data = [
            'id_mekanik_member' => 2,
            'user_name' => 'Anang',
            'nik' => 308009,
            'password' => password_hash('Anang123', PASSWORD_DEFAULT, $options),
            'active' => 0
        ];

        $this->db->table('user_mekanik')->insert($data);
    }
}