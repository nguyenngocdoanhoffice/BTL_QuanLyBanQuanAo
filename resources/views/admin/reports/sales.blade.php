@extends('layouts.admin')

@section('title', 'Báo cáo bán hàng')
@section('header', 'Báo cáo bán hàng')

@section('content')
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold">Bộ lọc báo cáo</h2>
                <p class="text-sm text-slate-500">Chọn đúng ngày, tháng hoặc năm để xem doanh thu theo mốc đó.</p>
            </div>

            <form method="GET" action="{{ route('admin.reports.sales') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[220px,220px,auto,auto] lg:items-end">
                <div>
                    <label for="report-period" class="mb-2 block text-sm font-medium text-slate-600">Loại báo cáo</label>
                    <select id="report-period" name="period" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700" data-report-period>
                        <option value="day" @selected($selectedPeriod === 'day')>Theo ngày</option>
                        <option value="month" @selected($selectedPeriod === 'month')>Theo tháng</option>
                        <option value="year" @selected($selectedPeriod === 'year')>Theo năm</option>
                    </select>
                </div>

                <div>
                    <label for="report-date-day" class="mb-2 block text-sm font-medium text-slate-600">Ngày báo cáo</label>
                    <input id="report-date-day" type="date" name="report_date" value="{{ $selectedPeriod === 'day' ? $selectedPeriodInput : now()->toDateString() }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700" data-report-date="day">
                    <input id="report-date-month" type="month" name="report_date" value="{{ $selectedPeriod === 'month' ? $selectedPeriodInput : now()->format('Y-m') }}" class="hidden w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700" data-report-date="month" disabled>
                    <input id="report-date-year" type="number" name="report_date" value="{{ $selectedPeriod === 'year' ? $selectedPeriodInput : now()->format('Y') }}" min="2000" max="2100" class="hidden w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700" data-report-date="year" disabled>
                </div>

                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Xem báo cáo</button>
                <button type="submit" formaction="{{ route('admin.reports.sales.export') }}" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Xuất Excel</button>
            </form>
        </div>
    </section>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Doanh thu {{ $selectedPeriodLabel }}</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($selectedRevenue, 0, ',', '.') }} đ</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Số lượng đã bán {{ $selectedPeriodLabel }}</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($selectedSold) }} sản phẩm</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Tổng đã bán</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($totalSold) }} sản phẩm</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Kho còn lại</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($totalStock) }} sản phẩm</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Biểu đồ doanh thu</h2>
                    <p class="text-sm text-slate-500" data-chart-title>{{ $chartData['title'] }}</p>
                </div>
            </div>
            <div class="mt-5 h-80">
                <canvas id="revenue-bar-chart" data-chart-period="{{ $selectedPeriod }}"></canvas>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Số lượng đã bán</h2>
            <dl class="mt-5 space-y-4">
                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <dt class="text-sm text-slate-500">{{ $selectedPeriodLabel }}</dt>
                    <dd class="text-lg font-semibold">{{ number_format($selectedSold) }} sản phẩm</dd>
                </div>
                <div class="flex items-center justify-between rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                    <dt class="text-sm text-emerald-700">Tổng đã bán (đơn hoàn thành)</dt>
                    <dd class="text-lg font-semibold text-emerald-700">{{ number_format($totalSold) }} sản phẩm</dd>
                </div>
            </dl>
        </section>
    </div>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Báo cáo theo sản phẩm</h2>
            <span class="text-sm text-slate-500">Top 12 theo số lượng bán</span>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500">
                        <th class="py-3 font-medium">Sản phẩm</th>
                        <th class="py-3 font-medium">SKU</th>
                        <th class="py-3 font-medium">Đã bán</th>
                        <th class="py-3 font-medium">Tồn kho hiện tại</th>
                        <th class="py-3 font-medium">Tồn theo size</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($productReports as $product)
                        <tr>
                            <td class="py-4 font-semibold">{{ $product->title }}</td>
                            <td class="py-4">{{ $product->sku }}</td>
                            <td class="py-4">{{ number_format((int) ($product->sold_quantity ?? 0)) }}</td>
                            <td class="py-4">{{ number_format((int) ($product->stock_remaining ?? 0)) }}</td>
                            <td class="py-4 text-xs text-slate-600">{{ $product->size_stock_label ?: 'Chưa có dữ liệu size' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500">Chưa có dữ liệu báo cáo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const canvas = document.getElementById('revenue-bar-chart');
            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            const chartData = @json($chartData);
            const chartMin = @json($chartMin);
            const chartMax = @json($chartMax);
            const chartStep = @json($chartStep);
            const period = canvas.dataset.chartPeriod || 'day';
            const periodSelect = document.querySelector('[data-report-period]');
            const chartTitle = document.querySelector('[data-chart-title]');
            const reportDateFields = {
                day: document.querySelector('[data-report-date="day"]'),
                month: document.querySelector('[data-report-date="month"]'),
                year: document.querySelector('[data-report-date="year"]'),
            };

            const syncReportDateField = () => {
                if (!periodSelect) {
                    return;
                }

                Object.entries(reportDateFields).forEach(([key, field]) => {
                    if (!field) {
                        return;
                    }

                    const isActive = periodSelect.value === key;
                    field.hidden = !isActive;
                    field.classList.toggle('hidden', !isActive);
                    field.disabled = !isActive;
                });
            };

            syncReportDateField();

            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Doanh thu',
                        data: chartData.values,
                        backgroundColor: chartData.values.map(() => '#0f172a'),
                        borderRadius: 12,
                        maxBarThickness: period === 'day' ? 72 : 32,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const value = Number(context.parsed.y || 0);
                                    return `${value.toLocaleString('vi-VN')} đ`;
                                },
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: chartMin ?? undefined,
                            max: chartMax ?? undefined,
                            ticks: {
                                stepSize: chartStep ?? undefined,
                                callback: (value) => {
                                    if (Number(value) === 0) {
                                        return '';
                                    }
                                    return `${Number(value).toLocaleString('vi-VN')} đ`;
                                },
                            },
                        },
                    },
                },
            });

            window.__salesReportChart = chart;
            window.__salesReportChartData = chartData;

            if (periodSelect) {
                periodSelect.addEventListener('change', syncReportDateField);
            }

            Object.values(reportDateFields).forEach((field) => {
                if (!field) {
                    return;
                }

                field.addEventListener('change', () => {
                    const form = periodSelect?.form;
                    if (form && typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    }
                });
            });

            if (chartTitle) {
                chartTitle.textContent = chartData.title;
            }
        })();
    </script>
@endpush
