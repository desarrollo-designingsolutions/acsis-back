<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceSoat;
use App\Models\MedicalConsultation;
use App\Models\Service;
use App\Services\ExcelService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

use function Psl\PseudoRandom\float;

class prueba1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $excelService = new ExcelService;
        $sheet = null;

        try {
            $sheet = $excelService
                ->getSpreadsheetFromExcel(database_path('db/prueba_2_acssis.xlsx'))
                ->getSheetByName('Sheet1')
                ->toArray();
        } catch (Exception $e) {
            // $this->error('Error al leer el excel');
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            // $this->error('Error al obtener la hoja');
        }

        if ($sheet) {
            // Inicializar la barra de progreso
            $this->command->info('Starting Seed Data ...');
            $bar = $this->command->getOutput()->createProgressBar(count($sheet));

            \Log::info('count data:', [count($sheet)]);
            \Log::info('Sheet data:', [$sheet]);
            unset($sheet[0]);
            foreach ($sheet as $key => $dataSheet) {
                $company_id = $dataSheet[0];

                $invoiceSoat = InvoiceSoat::create([
                    'company_id' => $company_id,
                    'policy_number' => 0000000,
                    'accident_date' => 0000000,
                    'start_date' => 0000000,
                    'end_date' => 0000000,
                ]);

                $invoice = Invoice::create(
                    [
                        'company_id' => $company_id,
                        'service_vendor_id' => $dataSheet[1],
                        'entity_id' => $dataSheet[2],
                        'patient_id' => $dataSheet[3],
                        'tipo_nota_id' => $dataSheet[4],
                        'type' => $dataSheet[5],
                        'invoice_number' => $dataSheet[9],
                        'radication_date' => $dataSheet[12] ? Carbon::parse($dataSheet[12])->format('Y-m-d') : null,
                        'radication_number' => $dataSheet[10],
                        'typeable_type' => "App\Models\InvoiceSoat",
                        'typeable_id' => $invoiceSoat->id,
                        'total' => floatval($dataSheet[11]),
                        'status' => "INVOICE_STATUS_002",
                        'status_xml' => "INVOICE_STATUS_XML_001",

                    ]
                );

                $medicalConsultation = MedicalConsultation::create([
                    'codConsulta_id' => "0196f441-97b1-71d5-8689-92a1a605b77b",
                    'vrServicio' => $dataSheet[11],
                ]);

                Service::create(
                    [
                        'company_id' => $company_id,
                        'invoice_id' => $invoice->id,
                        'type' => "SERVICE_TYPE_001",
                        'codigo_servicio' => "000000",
                        'nombre_servicio' => "GENERICO",
                        'serviceable_type' => "App\Models\MedicalConsultation",
                        'serviceable_id' => $medicalConsultation->id,
                        'total_value' => floatval($dataSheet[11]),
                    ]
                );
                \Log::info(' key:', [$key + 1]);
                $bar->advance();
            }
            $bar->finish(); // Finalizar la barra
        }
    }
}
