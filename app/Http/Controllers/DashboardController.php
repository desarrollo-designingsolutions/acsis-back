<?php

namespace App\Http\Controllers;

use App\Enums\Invoice\StatusInvoiceEnum;
use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    public function __construct(
        protected QueryController $queryController,
        protected InvoiceRepository $invoiceRepository,

    ) {}

    public function countAllData(Request $request)
    {
        try {
            $invoiceCountData = $this->invoiceRepository->countData($request->all());
            // $approvedVsGlosaData = $this->invoiceRepository->countApprovedVsGlosa($request->all());
            $inReviewVsPendingData = $this->invoiceRepository->countInReviewVsPending($request->all());
            $pendingPaymentsData = $this->invoiceRepository->countPendingPayments($request->all());

            $request['status'] = StatusInvoiceEnum::INVOICE_STATUS_008->value;
            $countPendingPaymentDataStatusPending = $this->invoiceRepository->countPendingPayments($request->all(),"Montos pendientes de radicación");
            $averageResponseTimeData = $this->invoiceRepository->countAverageResponseTime($request->all());
            $recoveredGlosasData = $this->invoiceRepository->countRecoveredGlosas($request->all());

            return response()->json([
                'code' => 200,
                'invoiceCountData' => $invoiceCountData,
                // 'approvedVsGlosaData' => $approvedVsGlosaData,
                'countPendingPaymentDataStatusPending' => $countPendingPaymentDataStatusPending,
                'pendingPaymentsData' => $pendingPaymentsData,
                'inReviewVsPendingData' => $inReviewVsPendingData,
                'averageResponseTimeData' => $averageResponseTimeData,
                'recoveredGlosasData' => $recoveredGlosasData,
            ]);
        } catch (Throwable $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage()]);
        }
    }

    public function getInvoiceTrend(Request $request)
    {
        $year = $request->input('year', date('Y')); // Por defecto, el año actual
        $company_id = $request->input('company_id');

        $trends = Invoice::selectRaw("DATE_FORMAT(invoice_date, '%Y-%m') as month")
            ->selectRaw('SUM(total) as total_amount')
            ->selectRaw('COUNT(*) as invoice_count')
            ->whereNotNull('invoice_date')
            ->whereYear('invoice_date', $year) // Filtrar por año
            ->where('company_id', $company_id)
            ->groupByRaw("DATE_FORMAT(invoice_date, '%Y-%m')")
            ->orderBy('month', 'asc')
            ->get();

        $months = [];
        $amounts = [];
        $counts = [];

        $monthNames = [
            '01' => 'Ene',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dic',
        ];

        // Inicializar los meses con 0 para asegurar que todos los meses aparezcan
        $allMonths = array_fill_keys(array_values($monthNames), 0);
        $allAmounts = array_fill_keys(array_values($monthNames), 0);
        $allCounts = array_fill_keys(array_values($monthNames), 0);

        foreach ($trends as $trend) {
            $month = substr($trend->month, 5, 2); // Obtener 'MM'
            $monthName = $monthNames[$month];
            $allAmounts[$monthName] = (float) $trend->total_amount;
            $allCounts[$monthName] = $trend->invoice_count;
        }

        // Convertir a arrays para la respuesta
        $months = array_keys($allMonths);
        $amounts = array_values($allAmounts);
        $counts = array_values($allCounts);

        return response()->json([
            'months' => $months,
            'amounts' => $amounts,
            'counts' => $counts,
        ]);
    }

    public function getStatusDistribution(Request $request)
    {
        $company_id = $request->input('company_id');
        
        // Obtener la distribución de facturas por estado
        $distribution = Invoice::selectRaw('status, COUNT(*) as count')
            ->whereNotNull('status') // Solo facturas con estado definido
            ->where('company_id', $company_id)
            ->groupBy('status')
            ->get();

        // Mapear los estados con sus descripciones y cantidades
        $labels = [];
        $counts = [];
        $total = 0;

        // Obtener las descripciones y colores del enum
        $statusMap = array_reduce(StatusInvoiceEnum::cases(), function ($carry, $case) {
            $carry[$case->value] = [
                'description' => $case->description(),
                'color' => $case->backgroundColor(),
            ];

            return $carry;
        }, []);

        foreach ($distribution as $item) {
            $value = collect($item);
            $statusValue = $value['status'];
            if (isset($statusMap[$statusValue])) {
                $labels[] = $statusMap[$statusValue]['description'];
                $counts[] = (int) $item->count;
                $colors[] = $statusMap[$statusValue]['color'];
                $total += (int) $item->count;
            }
        }

        return response()->json([
            'labels' => $labels,
            'counts' => $counts,
            'total' => $total,
        ]);
    }
}
