<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use App\Models\TypeVendor;
use Illuminate\Database\Seeder;

class TypeVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'name' => 'Proveedor',
            ],
            [
                'name' => 'Contratista',
            ],
            [
                'name' => 'Crítico',
            ],
            [
                'name' => 'Comunitario',
            ],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $value) {
            $data = new TypeVendor();
            $data->name = $value['name'];
            $data->company_id = Constants::COMPANY_UUID;
            $data->save();
        }

        $bar->finish(); // Finalizar la barra
    }
}
