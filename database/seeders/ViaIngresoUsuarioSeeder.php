<?php

namespace Database\Seeders;

use App\Models\ViaIngresoUsuario;
use Illuminate\Database\Seeder;

class ViaIngresoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $arrayData = [
            ['id' => '1', 'codigo' => '01', 'nombre' => 'Demanda espontánea', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'NO', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '2', 'codigo' => '02', 'nombre' => 'Derivado de consulta externa', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '3', 'codigo' => '03', 'nombre' => 'Derivado de urgencias', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '4', 'codigo' => '04', 'nombre' => 'Derivado de hospitalización', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'NO', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '5', 'codigo' => '05', 'nombre' => 'Derivado de sala de cirugía', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '6', 'codigo' => '06', 'nombre' => 'Derivado de sala de partos', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '7', 'codigo' => '07', 'nombre' => 'Recién nacido en la institución', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '8', 'codigo' => '08', 'nombre' => 'Recién nacido en otra institución', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '9', 'codigo' => '09', 'nombre' => 'Derivado o referido de hospitalización domiciliaria', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '10', 'codigo' => '10', 'nombre' => 'Derivado de atención domiciliaria', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '11', 'codigo' => '11', 'nombre' => 'Derivado de telemedicina', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '12', 'codigo' => '12', 'nombre' => 'Derivado de jornada de salud', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '13', 'codigo' => '13', 'nombre' => 'Referido de otra institución', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
            ['id' => '14', 'codigo' => '14', 'nombre' => 'Contrarreferido de otra institución', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'NA', 'extra_II' => 'SI', 'extra_III' => 'NA', 'extra_IV' => 'SI', 'extra_V' => 'NA', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:17:45 PM', 'isPublicPrivate' => null],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $key => $value) {
            $data = new ViaIngresoUsuario;
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
            $data->fecha_actualizacion = $value['fecha_actualizacion'];
            $data->isPublicPrivate = $value['isPublicPrivate'];

            $data->save();
            $bar->advance();
        }
        $bar->finish(); // Finalizar la barra
    }
}
