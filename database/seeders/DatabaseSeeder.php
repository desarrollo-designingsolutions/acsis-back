<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // paquete para el seeder de paises,estados y ciudades https://github.com/nnjeim/world
        // 1. composer require nnjeim/world
        // 2. php artisan world:install => (si no funciona ejecutar las de abajo)
        // 3. php artisan vendor:publish --tag=world
        // 4. php artisan migrate
        // 5. php artisan db:seed --class=WorldSeeder

        $this->call([
            WorldSeeder::class,
            MenuSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            TypeVendorSeeder::class,
            TypeEntitySeeder::class,
            TypeDocumentSeeder::class,

            TypeCodeGlosaSeeder::class,
            GeneralCodeGlosaSeeder::class,
            CodeGlosaSeeder::class,
            CupsRipsSeeder::class,

            TipoNotaSeeder::class,
            TipoIdPisisSeeder::class,
            RipsTipoUsuarioVersion2Seeder::class,
            SexoSeeder::class,
            PaisSeeder::class,
            MunicipioSeeder::class,
            ZonaVersion2Seeder::class,

            ModalidadAtencionSeeder::class,
            GrupoServicioSeeder::class,
            ServicioSeeder::class,
            RipsFinalidadConsultaVersion2Seeder::class,
            RipsCausaExternaVersion2Seeder::class,
            Cie10Seeder::class,
            RipsTipoDiagnosticoPrincipalVersion2Seeder::class,
            ConceptoRecaudoSeeder::class,
            ViaIngresoUsuarioSeeder::class,
            TipoMedicamentoPosVersion2Seeder::class,
            UmmSeeder::class,
            TipoOtrosServiciosSeeder::class,

            CondicionyDestinoUsuarioEgresoSeeder::class,
            IpsNoRepsSeeder::class,

            // Correr Manualmente
            // IpsCodHabilitacionSeeder::class,

            InsuranceStatusSeeder::class,

        ]);

        $client = new ClientRepository;

        $client->createPasswordGrantClient(null, 'Laravel Personal Grant Client', 'https://localhost');
        $client->createPersonalAccessClient(null, 'Laravel Password Access Client', 'https://localhost');
    }
}
