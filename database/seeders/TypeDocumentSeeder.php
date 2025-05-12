<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use App\Models\TypeDocument;
use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'name' => 'CC',
            ],
            [
                'name' => 'CE',
            ],
            [
                'name' => 'Pasaporte',
            ],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $key => $value) {
            $data = new TypeDocument;
            $data->company_id = Constants::COMPANY_UUID;
            $data->name = $value['name'];
            $data->save();
            $bar->advance();
        }

        $bar->finish(); // Finalizar la barra
    }
}
