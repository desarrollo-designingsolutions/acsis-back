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
                'id' => 1,
                'name' => 'Proveedor',
            ],
            [
                'id' => 2,
                'name' => 'Contratista',
            ],
            [
                'id' => 3,
                'name' => 'Crítico',
            ],
            [
                'id' => 4,
                'name' => 'Comunitario',
            ],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $key => $value) {
            $data = TypeEntity::find($value['id']);
            if (! $data) {
                $data = new TypeEntity;
            }
            $data->id = $value['id'];
            $data->company_id = Constants::COMPANY_UUID;
            $data->name = $value['name'];
            $data->is_active = 1;
            $data->save();
        }

        $bar->finish(); // Finalizar la barra
    }
}
