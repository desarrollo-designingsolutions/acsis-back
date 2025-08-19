<?php

namespace Database\Seeders;

use App\Models\CodeGlosaAnswer;
use Illuminate\Database\Seeder;

class CodeGlosaAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrayData = [
            [
                'code' => '996',
                'name' => 'Glosa o devolución injustificada',
            ],
            [
                'code' => '997',
                'name' => 'No subsanada (Glosa o devolución totalmente aceptada)',
            ],
            [
                'code' => '998',
                'name' => 'Subsanada parcial (Glosa o devolución parcialmente aceptada)',
            ],
            [
                'code' => '999',
                'name' => 'Subsanada (Glosa o devolución no aceptada)',
            ],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $value) {
            $data = new CodeGlosaAnswer;
            $data->code = $value['code'];
            $data->name = $value['name'];
            $data->save();
            $bar->advance();
        }

        $bar->finish(); // Finalizar la barra
    }
}
