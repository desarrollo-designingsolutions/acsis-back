<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use App\Models\TypeEntity;
use Illuminate\Database\Seeder;

class TypeEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'name' => 'Eps',
            ],
            [
                'name' => 'Asegurdora',
            ],
            [
                'name' => 'Arl',
            ],
            [
                'name' => 'Otras entidades',
            ],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $key => $value) {
            $data = new TypeEntity;
            $data->company_id = Constants::COMPANY_UUID;
            $data->name = $value['name'];
            $data->is_active = 1;
            $data->save();
            $bar->advance();
        }

        $bar->finish(); // Finalizar la barra
    }
}
