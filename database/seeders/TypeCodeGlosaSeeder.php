<?php

namespace Database\Seeders;

use App\Models\TypeCodeGlosa;
use Illuminate\Database\Seeder;

class TypeCodeGlosaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayData = [
            ['id' => 1, 'type_code' => '3047', 'name' => 'RESOLUCION 3047'],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $key => $value) {
            $data = new TypeCodeGlosa;
            $data->id = $value['id'];
            $data->type_code = $value['type_code'];
            $data->name = $value['name'];
            $data->save();
            $bar->advance();
        }

        $bar->finish(); // Finalizar la barra

    }
}
