<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiskonToTransactionTable extends Migration
{
    public function up()
    {
        $fields = [
            'diskon' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'total_harga' // Sesuaikan letak field setelah field total harga Anda
            ],
        ];
        $this->forge->addColumn('transaction', $fields); // Sesuaikan jika nama tabel Anda 'transaction' tanpa 's'
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', 'diskon');
    }
}