<?php

namespace Database\Seeders;

use App\Models\InsuranceStatus;
use Illuminate\Database\Seeder;

class InsuranceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'code' => '1',
                'name' => 'Asegurado',
            ],
            [
                'code' => '2',
                'name' => 'No asegurado',
            ],
            [
                'code' => '3',
                'name' => 'Vehículo fantasma',
            ],
            [
                'code' => '4',
                'name' => 'Póliza falsa',
            ],
            [
                'code' => '5',
                'name' => 'Vehículo en fuga',
            ],
            [
                'code' => '6',
                'name' => 'Asegurado D.2497',
            ],
            [
                'code' => '7',
                'name' => 'No asegurado - Propietario Indeterminado',
            ],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $value) {
            $data = new InsuranceStatus;
            $data->code = $value['code'];
            $data->name = $value['name'];
            $data->save();
            $bar->advance();
        }

        $bar->finish(); // Finalizar la barra
    }
}
