<?php

namespace Database\Seeders;

use App\Models\Sexo;
use Illuminate\Database\Seeder;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $arrayData = [
            ['id' => '1', 'codigo' => 'H', 'nombre' => 'Hombre', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => '1', 'extra_II' => '01', 'extra_III' => 'M', 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_Actualizacion' => '2021-08-13 03:14:01 PM', 'isPublicPrivate' => 'False'],
            ['id' => '2', 'codigo' => 'I', 'nombre' => 'Indeterminado o Intersexual', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => '3', 'extra_II' => '03', 'extra_III' => 'I', 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_Actualizacion' => '2021-08-13 02:44:41 PM', 'isPublicPrivate' => 'False'],
            ['id' => '3', 'codigo' => 'M', 'nombre' => 'Mujer', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => '2', 'extra_II' => '02', 'extra_III' => 'F', 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_Actualizacion' => '2021-08-13 03:14:12 PM', 'isPublicPrivate' => 'False'],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $value) {
            $data = new Sexo;

            $data->codigo = $value['codigo'];
            $data->nombre = $value['nombre'];
            $data->descripcion = $value['descripcion'];
            $data->habilitado = $value['habilitado'];
            $data->aplicacion = $value['aplicacion'];
            $data->isStandardGEL = $value['isStandardGEL'];
            $data->isStandardMSPS = $value['isStandardMSPS'];
            $data->extra_I = $value['extra_I'];
            $data->extra_II = $value['extra_II'];
            $data->extra_III = $value['extra_III'];
            $data->extra_IV = $value['extra_IV'];
            $data->extra_V = $value['extra_V'];
            $data->extra_VI = $value['extra_VI'];
            $data->extra_VII = $value['extra_VII'];
            $data->extra_VIII = $value['extra_VIII'];
            $data->extra_IX = $value['extra_IX'];
            $data->extra_X = $value['extra_X'];
            $data->valorRegistro = $value['valorRegistro'];
            $data->usuarioResponsable = $value['usuarioResponsable'];
            $data->fecha_Actualizacion = $value['fecha_Actualizacion'];
            $data->isPublicPrivate = $value['isPublicPrivate'];

            $data->save();
            $bar->advance();
        }

        $bar->finish(); // Finalizar la barra

    }
}
