<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        [, , $periodLabel] = $this->resolvePeriodRange($filters['period'], $filters['reportDate']);
        $fileName = 'bao-cao-ban-hang-' . $filters['period'] . '-' . $filters['reportDate']->format('Ymd_His') . '.xls';

        return response()->streamDownload(function () use ($data, $series, $periodLabel) {
            $borderStyle = 'border:1px solid #111;padding:6px;';
            $headerStyle = $borderStyle . 'font-weight:bold;background:#f1f5f9;';

            echo '<html><head><meta charset="UTF-8"></head><body>';

            echo '<table style="border-collapse:collapse;width:100%;">';
            echo '<tr><td style="' . $headerStyle . '" colspan="2">Báo cáo bán hàng</td></tr>';
            echo '<tr><td style="' . $borderStyle . '">Loại báo cáo</td><td style="' . $borderStyle . '">' . e($periodLabel) . '</td></tr>';
            echo '<tr><td style="' . $borderStyle . '">Ngày xuất</td><td style="' . $borderStyle . '">' . now()->format('d/m/Y H:i:s') . '</td></tr>';
            echo '</table><br>';

            echo '<table style="border-collapse:collapse;width:100%;">';
            echo '<tr><td style="' . $headerStyle . '" colspan="2">Tổng quan doanh thu</td></tr>';
            echo '<tr><td style="' . $borderStyle . '">Doanh thu đã chọn</td><td style="' . $borderStyle . '">' . number_format((float) $data['selectedRevenue'], 0, ',', '.') . ' đ</td></tr>';
            echo '</table><br>';

            echo '<table style="border-collapse:collapse;width:100%;">';
            echo '<tr><td style="' . $headerStyle . '" colspan="2">Số lượng đã bán</td></tr>';
            echo '<tr><td style="' . $borderStyle . '">Đã bán đã chọn</td><td style="' . $borderStyle . '">' . (int) $data['selectedSold'] . '</td></tr>';
            echo '<tr><td style="' . $borderStyle . '">Tổng đã bán</td><td style="' . $borderStyle . '">' . (int) $data['totalSold'] . '</td></tr>';
            echo '<tr><td style="' . $borderStyle . '">Tổng tồn kho</td><td style="' . $borderStyle . '">' . (int) $data['totalStock'] . '</td></tr>';
            echo '</table><br>';

            echo '<table style="border-collapse:collapse;width:100%;">';
            echo '<tr><td style="' . $headerStyle . '" colspan="2">Doanh thu chi tiết theo kỳ</td></tr>';
            echo '<tr><td style="' . $headerStyle . '">Kỳ</td><td style="' . $headerStyle . '">Doanh thu</td></tr>';
            foreach (($series['labels'] ?? []) as $index => $label) {
                $value = (float) (($series['values'] ?? [])[$index] ?? 0);
                echo '<tr>';
                echo '<td style="' . $borderStyle . '">' . e($label) . '</td>';
                echo '<td style="' . $borderStyle . '">' . number_format($value, 0, ',', '.') . ' đ</td>';
                echo '</tr>';
            }
            echo '</table><br>';

            echo '<table style="border-collapse:collapse;width:100%;">';
            echo '<tr><td style="' . $headerStyle . '" colspan="5">Báo cáo theo sản phẩm</td></tr>';
            echo '<tr>';
            echo '<td style="' . $headerStyle . '">Tên sản phẩm</td>';
            echo '<td style="' . $headerStyle . '">SKU</td>';
            echo '<td style="' . $headerStyle . '">Đã bán</td>';
            echo '<td style="' . $headerStyle . '">Tồn kho hiện tại</td>';
            echo '<td style="' . $headerStyle . '">Tồn kho theo size</td>';
            echo '</tr>';
            foreach ($data['productReports'] as $product) {
                echo '<tr>';
                echo '<td style="' . $borderStyle . '">' . e($product->title) . '</td>';
                echo '<td style="' . $borderStyle . '">' . e($product->sku) . '</td>';
                echo '<td style="' . $borderStyle . '">' . (int) ($product->sold_quantity ?? 0) . '</td>';
                echo '<td style="' . $borderStyle . '">' . (int) ($product->stock_remaining ?? 0) . '</td>';
                echo '<td style="' . $borderStyle . '">' . e((string) ($product->size_stock_label ?? '')) . '</td>';
                echo '</tr>';
            }
            echo '</table>';

            echo '</body></html>';
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
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

        [$chartMin, $chartMax, $chartStep] = $this->resolveChartAxis($filters['period'], $chartData);

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

    private function resolveChartAxis(string $period, array $chartData): array
    {
        if (! in_array($period, ['month', 'year'], true)) {
            return [0, null, null];
        }

        $step = $period === 'month' ? 5_000_000 : 50_000_000;
        $baseMax = $step * 4;
        $values = collect($chartData['values'] ?? [])->map(fn ($value) => (float) $value);
        $maxValue = (float) ($values->max() ?? 0);
        $scaledMax = $maxValue > 0 ? (int) (ceil($maxValue / $step) * $step) : 0;

        return [0, max($baseMax, $scaledMax), $step];
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
