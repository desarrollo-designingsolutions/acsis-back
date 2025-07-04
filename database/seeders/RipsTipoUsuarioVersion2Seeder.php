<?php

namespace Database\Seeders;

use App\Models\RipsTipoUsuarioVersion2;
use Illuminate\Database\Seeder;

class RipsTipoUsuarioVersion2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $arrayData = [
            ['id' => '1', 'codigo' => '01', 'nombre' => 'Contributivo cotizante', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '2', 'codigo' => '02', 'nombre' => 'Contributivo beneficiario', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '3', 'codigo' => '03', 'nombre' => 'Contributivo adicional', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '4', 'codigo' => '04', 'nombre' => 'Subsidiado', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '5', 'codigo' => '05', 'nombre' => 'No afiliado', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '6', 'codigo' => '06', 'nombre' => 'Especial o Excepción cotizante', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '7', 'codigo' => '07', 'nombre' => 'Especial o Excepción beneficiario', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '8', 'codigo' => '08', 'nombre' => 'Personas privadas de la libertad a cargo del Fondo Nacional de Salud', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '9', 'codigo' => '09', 'nombre' => 'Tomador / Amparado ARL', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '10', 'codigo' => '10', 'nombre' => 'Tomador / Amparado SOAT', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '11', 'codigo' => '11', 'nombre' => 'Tomador / Amparado Planes  voluntarios de salud', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '12', 'codigo' => '12', 'nombre' => 'Particular', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
            ['id' => '13', 'codigo' => '13', 'nombre' => 'Especial o Exepcion no cotizante Ley 352 de 1997', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-06-16 04:04:43 PM', 'isPublicPrivate' => null],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $value) {
            $data = new RipsTipoUsuarioVersion2;
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
