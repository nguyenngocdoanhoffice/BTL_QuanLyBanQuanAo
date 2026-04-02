<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SalesReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class SalesReportController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $this->resolveFilters($request);
        $data = $this->buildReportData($filters);

        return view('admin.reports.sales', $data);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $filters = $this->resolveFilters($request);
        $data = $this->buildReportData($filters);
        $series = $data['chartData'];
        [$fromDate, $toDate, $periodLabel] = $this->resolvePeriodRange($filters['period'], $filters['reportDate']);
        $fileName = 'bao-cao-ban-hang-' . $filters['period'] . '-' . $filters['reportDate']->format('Ymd_His') . '.csv';

        SalesReport::create([
            'report_type' => $filters['period'],
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'total_revenue' => $data['selectedRevenue'],
            'total_sold' => $data['selectedSold'],
            'total_stock' => $data['totalStock'],
            'export_format' => 'excel',
            'export_file_name' => $fileName,
            'generated_by' => Auth::id(),
        ]);

        return response()->streamDownload(function () use ($data, $series, $periodLabel) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Bao cao ban hang']);
            fputcsv($handle, ['Loai bao cao', $periodLabel]);
            fputcsv($handle, ['Ngay xuat', now()->format('d/m/Y H:i:s')]);
            fputcsv($handle, []);

            fputcsv($handle, ['Tong quan doanh thu']);
            fputcsv($handle, ['Doanh thu da chon', (float) $data['selectedRevenue']]);
            fputcsv($handle, []);

            fputcsv($handle, ['So luong da ban']);
            fputcsv($handle, ['Da ban da chon', (int) $data['selectedSold']]);
            fputcsv($handle, ['Tong da ban', (int) $data['totalSold']]);
            fputcsv($handle, ['Tong ton kho', (int) $data['totalStock']]);
            fputcsv($handle, []);

            fputcsv($handle, ['Doanh thu chi tiet theo ky']);
            fputcsv($handle, ['Ky', 'Doanh thu']);
            foreach (($series['labels'] ?? []) as $index => $label) {
                fputcsv($handle, [$label, (float) (($series['values'] ?? [])[$index] ?? 0)]);
            }
            fputcsv($handle, []);

            fputcsv($handle, ['Bao cao theo san pham']);
            fputcsv($handle, ['Ten san pham', 'SKU', 'Da ban', 'Ton kho hien tai', 'Ton kho theo size']);
            foreach ($data['productReports'] as $product) {
                fputcsv($handle, [
                    $product->title,
                    $product->sku,
                    (int) ($product->sold_quantity ?? 0),
                    (int) ($product->stock_remaining ?? 0),
                    (string) ($product->size_stock_label ?? ''),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function buildReportData(array $filters): array
    {
        [$startDate, $endDate, $selectedLabel] = $this->resolvePeriodRange($filters['period'], $filters['reportDate']);

        $selectedRevenue = $this->calculateRevenue($startDate, $endDate);
        $selectedSold = $this->calculateSoldQuantity($startDate, $endDate);
        $totalSold = $this->calculateTotalSoldQuantity();

        $productReports = $this->buildProductReports($startDate, $endDate);
        $totalStock = (int) Inventory::sum('quantity');

        $chartData = match ($filters['period']) {
            'month' => $this->buildMonthRevenueSeries($filters['reportDate']),
            'year' => $this->buildYearRevenueSeries($filters['reportDate']),
            default => $this->buildDayRevenueSeries($filters['reportDate']),
        };

        [$chartMin, $chartMax, $chartStep] = $this->resolveChartAxis($filters['period']);

        return [
            'selectedPeriod' => $filters['period'],
            'selectedPeriodLabel' => $selectedLabel,
            'selectedPeriodInput' => $this->formatReportInput($filters['period'], $filters['reportDate']),
            'selectedRevenue' => $selectedRevenue,
            'selectedSold' => $selectedSold,
            'totalSold' => $totalSold,
            'totalStock' => $totalStock,
            'productReports' => $productReports,
            'chartData' => $chartData,
            'chartMin' => $chartMin,
            'chartMax' => $chartMax,
            'chartStep' => $chartStep,
        ];
    }

    private function calculateRevenue(Carbon $startDate, Carbon $endDate): float
    {
        return (float) Order::query()
            ->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');
    }

    private function calculateSoldQuantity(Carbon $startDate, Carbon $endDate): int
    {
        return (int) OrderItem::query()
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('status', Order::STATUS_COMPLETED)
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum('quantity');
    }

    private function calculateTotalSoldQuantity(): int
    {
        return (int) OrderItem::query()
            ->whereHas('order', fn ($query) => $query->where('status', Order::STATUS_COMPLETED))
            ->sum('quantity');
    }

    private function buildProductReports(Carbon $startDate, Carbon $endDate)
    {
        $products = Product::query()
            ->with(['inventories' => function ($query) {
                $query->orderByRaw('CASE WHEN size IS NULL THEN 1 ELSE 0 END')->orderBy('size');
            }])
            ->withSum([
                'orderItems as sold_quantity' => function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', fn ($orderQuery) => $orderQuery->where('status', Order::STATUS_COMPLETED)->whereBetween('created_at', [$startDate, $endDate]));
                },
            ], 'quantity')
            ->orderByDesc('sold_quantity')
            ->take(12)
            ->get();

        $products->each(fn ($product) => $this->attachStockMeta($product));

        return $products;
    }

    private function attachStockMeta($product): void
    {
        $inventoriesWithSize = $product->inventories->filter(fn ($inventory) => filled($inventory->size));
        $currentStock = (int) $product->inventories->sum('quantity');

        if ($currentStock <= 0) {
            $currentStock = (int) ($product->stock_quantity ?? 0);
        }

        $product->stock_remaining = $currentStock;

        if ($inventoriesWithSize->isNotEmpty()) {
            $product->size_stock_label = $inventoriesWithSize
                ->map(fn ($inventory) => $inventory->size . ': ' . number_format((int) $inventory->quantity))
                ->implode(' | ');
            return;
        }

        if (! empty($product->size_options)) {
            $sizes = collect($product->size_options)->filter()->values();
            $perSize = $sizes->isNotEmpty() ? intdiv($currentStock, $sizes->count()) : 0;
            $remainder = $sizes->isNotEmpty() ? $currentStock % $sizes->count() : 0;

            $product->size_stock_label = $sizes
                ->map(function ($size, $index) use ($perSize, $remainder) {
                    $quantity = $perSize + ($index < $remainder ? 1 : 0);
                    return $size . ': ' . number_format($quantity);
                })
                ->implode(' | ');
            return;
        }

        $product->size_stock_label = 'Free-size: ' . number_format($currentStock);
    }

    private function resolveChartAxis(string $period): array
    {
        return match ($period) {
            'month', 'year' => [0, 20_000_000, 4_000_000],
            default => [0, null, null],
        };
    }

    private function resolveFilters(Request $request): array
    {
        $period = $request->string('period')->toString();
        if (! in_array($period, ['day', 'month', 'year'], true)) {
            $period = 'day';
        }

        return [
            'period' => $period,
            'reportDate' => $this->resolveReportDate($period, $request->string('report_date')->toString()),
        ];
    }

    private function resolveReportDate(string $period, string $inputValue): Carbon
    {
        try {
            return match ($period) {
                'month' => filled($inputValue)
                    ? Carbon::createFromFormat('Y-m', $inputValue)->startOfMonth()
                    : Carbon::now()->startOfMonth(),
                'year' => filled($inputValue)
                    ? Carbon::createFromFormat('Y', $inputValue)->startOfYear()
                    : Carbon::now()->startOfYear(),
                default => filled($inputValue)
                    ? Carbon::createFromFormat('Y-m-d', $inputValue)->startOfDay()
                    : Carbon::today()->startOfDay(),
            };
        } catch (\Throwable) {
            return match ($period) {
                'month' => Carbon::now()->startOfMonth(),
                'year' => Carbon::now()->startOfYear(),
                default => Carbon::today()->startOfDay(),
            };
        }
    }

    private function resolvePeriodRange(string $period, Carbon $reportDate): array
    {
        if ($period === 'month') {
            return [
                $reportDate->copy()->startOfMonth(),
                $reportDate->copy()->endOfMonth(),
                'Tháng ' . $reportDate->format('m/Y'),
            ];
        }

        if ($period === 'year') {
            return [
                $reportDate->copy()->startOfYear(),
                $reportDate->copy()->endOfYear(),
                'Năm ' . $reportDate->format('Y'),
            ];
        }

        return [
            $reportDate->copy()->startOfDay(),
            $reportDate->copy()->endOfDay(),
            'Ngày ' . $reportDate->format('d/m/Y'),
        ];
    }

    private function formatReportInput(string $period, Carbon $reportDate): string
    {
        return match ($period) {
            'month' => $reportDate->format('Y-m'),
            'year' => $reportDate->format('Y'),
            default => $reportDate->format('Y-m-d'),
        };
    }

    private function buildDayRevenueSeries(Carbon $day): array
    {
        $rows = Order::query()
            ->selectRaw('HOUR(created_at) as period_key, SUM(total) as total_revenue')
            ->where('status', Order::STATUS_COMPLETED)
            ->whereDate('created_at', $day)
            ->groupBy('period_key')
            ->pluck('total_revenue', 'period_key');

        $labels = collect(range(0, 23))->map(fn ($hour) => str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':00');
        $values = $labels->map(function ($label, $index) use ($rows) {
            return (float) ($rows[$index] ?? 0);
        });

        return [
            'title' => 'Doanh thu theo ngày (24 giờ)',
            'labels' => $labels->values()->all(),
            'values' => $values->values()->all(),
        ];
    }

    private function buildMonthRevenueSeries(Carbon $monthStart): array
    {
        $monthStart = $monthStart->copy()->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $rows = Order::query()
            ->selectRaw('DAY(created_at) as period_key, SUM(total) as total_revenue')
            ->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->groupBy('period_key')
            ->pluck('total_revenue', 'period_key');

        $labels = collect(range(1, $monthStart->daysInMonth))->map(fn ($day) => (string) $day);
        $values = $labels->map(function ($label) use ($rows) {
            $day = (int) $label;

            return (float) ($rows[$day] ?? 0);
        });

        return [
            'title' => 'Doanh thu theo tháng (từng ngày)',
            'labels' => $labels->values()->all(),
            'values' => $values->values()->all(),
        ];
    }

    private function buildYearRevenueSeries(Carbon $yearStart): array
    {
        $yearStart = $yearStart->copy()->startOfYear();
        $yearEnd = $yearStart->copy()->endOfYear();

        $rows = Order::query()
            ->selectRaw('MONTH(created_at) as period_key, SUM(total) as total_revenue')
            ->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('created_at', [$yearStart, $yearEnd])
            ->groupBy('period_key')
            ->pluck('total_revenue', 'period_key');

        $labels = collect(range(1, 12))->map(fn ($month) => 'Th' . $month);
        $values = $labels->map(function ($label, $index) use ($rows) {
            $month = $index + 1;

            return (float) ($rows[$month] ?? 0);
        });

        return [
            'title' => 'Doanh thu theo năm (từng tháng)',
            'labels' => $labels->values()->all(),
            'values' => $values->values()->all(),
        ];
    }
}
