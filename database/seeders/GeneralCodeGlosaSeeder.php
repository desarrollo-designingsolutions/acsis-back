<?php

namespace Database\Seeders;

use App\Models\GeneralCodeGlosa;
use Illuminate\Database\Seeder;

class GeneralCodeGlosaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayData = [
            ['id' => 1, 'type_code_glosa_id' => 1, 'general_code' => '1', 'description' => 'facturación', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'type_code_glosa_id' => 1, 'general_code' => '2', 'description' => 'tarifas', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'type_code_glosa_id' => 1, 'general_code' => '3', 'description' => 'soportes', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'type_code_glosa_id' => 1, 'general_code' => '4', 'description' => 'autorizaciones', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'type_code_glosa_id' => 1, 'general_code' => '5', 'description' => 'cobertura', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'type_code_glosa_id' => 1, 'general_code' => '6', 'description' => 'pertinencia', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'type_code_glosa_id' => 1, 'general_code' => '8', 'description' => 'devoluciones', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'type_code_glosa_id' => 1, 'general_code' => '9', 'description' => 'respuestas a glosas o devoluciones', 'created_at' => now(), 'updated_at' => now()],

            ['id' => 10, 'type_code_glosa_id' => 2, 'general_code' => 'FA', 'description' => 'FACTURACIÓN', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'type_code_glosa_id' => 2, 'general_code' => 'TA', 'description' => 'TARIFAS', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'type_code_glosa_id' => 2, 'general_code' => 'SO', 'description' => 'SOPORTES', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'type_code_glosa_id' => 2, 'general_code' => 'AU', 'description' => 'AUTORIZACIONES', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'type_code_glosa_id' => 2, 'general_code' => 'CO', 'description' => 'COBERTURA', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'type_code_glosa_id' => 2, 'general_code' => 'CL', 'description' => 'CALIDAD', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'type_code_glosa_id' => 2, 'general_code' => 'SA', 'description' => 'SEGUIMIENTO A LOS ACUERDOS', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'type_code_glosa_id' => 2, 'general_code' => 'RE', 'description' => 'GLOSA O DEVOLUCIÓN', 'created_at' => now(), 'updated_at' => now()],

        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $key => $value) {
            $data = GeneralCodeGlosa::find($value['id']);
            if (! $data) {
                $data = new GeneralCodeGlosa;
            }
            $data->id = $value['id'];
            $data->type_code_glosa_id = $value['type_code_glosa_id'];
            $data->general_code = $value['general_code'];
            $data->description = $value['description'];
            $data->save();
            $bar->advance();
        }

        $bar->finish(); // Finalizar la barra

    }
}
