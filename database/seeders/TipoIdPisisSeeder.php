<?php

namespace Database\Seeders;

use App\Models\TipoIdPisis;
use Illuminate\Database\Seeder;

class TipoIdPisisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $arrayData = [
            ['id' => '1', 'codigo' => 'AS', 'nombre' => 'Adulto sin identificacion', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => 'AS', 'extra_III' => null, 'extra_IV' => 'AS', 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '2', 'codigo' => 'CC', 'nombre' => 'Cedula de Ciudadania', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'CC', 'extra_II' => 'CC', 'extra_III' => 'CC', 'extra_IV' => 'CC', 'extra_V' => 'CC', 'extra_VI' => 'CC', 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => '18:06', 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2022-12-14 06:07:06 PM', 'isPublicPrivate' => 'False', 'created_at' => null, 'updated_at' => null],
            ['id' => '3', 'codigo' => 'CD', 'nombre' => 'Carnet Diplomatico', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'CD', 'extra_II' => null, 'extra_III' => null, 'extra_IV' => 'CD', 'extra_V' => 'CD', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '4', 'codigo' => 'CE', 'nombre' => 'Cedula de Extranjeria', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'CE', 'extra_II' => 'CE', 'extra_III' => 'CE', 'extra_IV' => 'CE', 'extra_V' => 'CE', 'extra_VI' => 'CE', 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '5', 'codigo' => 'CN', 'nombre' => 'Certificado de nacido vivo', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => 'TI', 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-31 12:03:26 PM', 'isPublicPrivate' => 'False', 'created_at' => null, 'updated_at' => null],
            ['id' => '6', 'codigo' => 'DE', 'nombre' => 'Documento extranjero', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-31 05:28:16 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '7', 'codigo' => 'MS', 'nombre' => 'Menor sin identificacion', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => 'MS ', 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '8', 'codigo' => 'NI', 'nombre' => 'Número de identificación tributario NIT', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => 'NI', 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '9', 'codigo' => 'NV', 'nombre' => 'Certificado nacido vivo', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '10', 'codigo' => 'PA', 'nombre' => 'Pasaporte', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'PA', 'extra_II' => 'PA', 'extra_III' => null, 'extra_IV' => 'PA', 'extra_V' => 'PA', 'extra_VI' => 'PA', 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '11', 'codigo' => 'PE', 'nombre' => 'Permiso Especial de Permanencia', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => 'PE', 'extra_II' => 'PE', 'extra_III' => 'PA', 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '12', 'codigo' => 'PT', 'nombre' => 'Permiso por protección temporal', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => 'PT', 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-09-16 02:15:04 PM', 'isPublicPrivate' => 'False', 'created_at' => null, 'updated_at' => null],
            ['id' => '13', 'codigo' => 'RC', 'nombre' => 'Registro civil', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => 'RC', 'extra_V' => 'RC', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '14', 'codigo' => 'SC', 'nombre' => 'Salvoconducto', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => 'SC', 'extra_V' => 'SC', 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '15', 'codigo' => 'SI', 'nombre' => 'Sin identificación', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => null, 'extra_VI' => null, 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-29 08:41:35 PM', 'isPublicPrivate' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => '16', 'codigo' => 'TI', 'nombre' => 'Tarjeta de Identidad', 'descripcion' => null, 'habilitado' => 'SI', 'aplicacion' => null, 'isStandardGEL' => 'False', 'isStandardMSPS' => 'False', 'extra_I' => null, 'extra_II' => null, 'extra_III' => null, 'extra_IV' => null, 'extra_V' => 'TI', 'extra_VI' => 'TI', 'extra_VII' => null, 'extra_VIII' => null, 'extra_IX' => null, 'extra_X' => null, 'valorRegistro' => null, 'usuarioResponsable' => null, 'fecha_actualizacion' => '2021-05-31 12:04:27 PM', 'isPublicPrivate' => 'False', 'created_at' => null, 'updated_at' => null],
        ];

        // Inicializar la barra de progreso
        $this->command->info('Starting Seed Data ...');
        $bar = $this->command->getOutput()->createProgressBar(count($arrayData));

        foreach ($arrayData as $key => $value) {
            $data = new TipoIdPisis;
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
