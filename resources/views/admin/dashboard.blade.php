@extends('layouts.app')

@section('title', 'แดชบอร์ดผู้ดูแลระบบ | Raphiphat001')

@section('content')
<div class="container py-4">

    <!-- Admin Welcome Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger"><i class="fa-solid fa-user-shield me-1"></i> Admin Portal</span>
                <h3 class="fw-bold mb-0">แดชบอร์ดภาพรวมศูนย์บริการรถยนต์ Porsche</h3>
            </div>
            <p class="text-muted small mb-0">
                ผู้จัดการระบบ: <strong>นายรภิภัทร (Raphiphat001)</strong> | Service Operations Director
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.repairs.index') }}" class="btn btn-outline-dark btn-sm">
                <i class="fa-solid fa-list-check me-1"></i> จัดการใบแจ้งซ่อมทั้งหมด
            </a>
            <a href="{{ route('admin.catalogs.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-boxes-stacked me-1"></i> แคตตาล็อกอะไหล่
            </a>
        </div>
    </div>

    <!-- Executive Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white border-start border-4 border-danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">งานแจ้งซ่อมทั้งหมด</div>
                        <div class="fs-2 fw-bold text-dark">{{ $totalRepairs }}</div>
                        <div class="text-muted small">ทุกซีรีส์ Porsche</div>
                    </div>
                    <div class="p-3 bg-danger bg-opacity-10 rounded-circle text-danger">
                        <i class="fa-solid fa-car-side fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white border-start border-4 border-secondary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">รอดำเนินการ / รอมอบหมาย</div>
                        <div class="fs-2 fw-bold text-secondary">{{ $pendingRepairs }}</div>
                        <div class="text-danger small">ต้องมอบหมายช่าง</div>
                    </div>
                    <div class="p-3 bg-secondary bg-opacity-10 rounded-circle text-secondary">
                        <i class="fa-solid fa-clock fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">กำลังดำเนินการซ่อม</div>
                        <div class="fs-2 fw-bold text-warning">{{ $inProgressRepairs }}</div>
                        <div class="text-muted small">ช่างกำลังตรวจ/ซ่อม</div>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 rounded-circle text-warning">
                        <i class="fa-solid fa-wrench fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card border-0 shadow-sm p-3 bg-white border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">รายได้ซ่อมเสร็จสมบูรณ์</div>
                        <div class="fs-2 fw-bold text-success">{{ number_format($totalRevenue, 0) }} ฿</div>
                        <div class="text-muted small">ยอดรวมสุทธิ {{ $completedRepairs }} คัน</div>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 rounded-circle text-success">
                        <i class="fa-solid fa-coins fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row (Chart.js) -->
    <div class="row g-4 mb-4">
        <!-- Series Breakdown Doughnut Chart -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-pie text-danger me-2"></i>สัดส่วนงานซ่อมตามซีรีส์ Porsche</h6>
                </div>
                <div class="card-body p-4 d-flex align-items-center justify-content-center" style="min-height: 280px;">
                    <div style="width: 100%; max-width: 320px;">
                        <canvas id="seriesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technician Workload Bar Chart -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-column text-warning me-2"></i>ภาระงานของช่างเทคนิค (Technician Workload)</h6>
                    <span class="badge bg-warning text-dark">Thanasak 017 Lead</span>
                </div>
                <div class="card-body p-4" style="min-height: 280px;">
                    <canvas id="techChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Repairs Table with Quick Assign -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-ol text-danger me-2"></i>รายการแจ้งซ่อมล่าสุด 6 รายการ</h6>
            <a href="{{ route('admin.repairs.index') }}" class="btn btn-sm btn-outline-danger">ดูทั้งหมด &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">เลขที่ใบแจ้งซ่อม</th>
                        <th>ลูกค้า</th>
                        <th>รุ่นรถยนต์</th>
                        <th>ทะเบียน</th>
                        <th>ช่างผู้รับผิดชอบ</th>
                        <th>สถานะ</th>
                        <th>ยอดประเมิน</th>
                        <th class="text-end pe-3">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRepairs as $r)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('technician.repairs.show', $r->id) }}" class="fw-bold text-danger text-decoration-none">
                                    {{ $r->ticket_no }}
                                </a>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $r->customer?->name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $r->customer?->phone ?: '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $r->porscheModel?->model_name ?? $r->model_custom_name }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $r->color }} ({{ $r->car_year }})</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $r->license_plate }}</span>
                            </td>
                            <td>
                                @if($r->technician)
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-wrench me-1"></i>{{ $r->technician->name }}</span>
                                @else
                                    <span class="badge bg-danger">ยังไม่มอบหมาย</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $r->status_badge }}">{{ $r->status_text }}</span>
                            </td>
                            <td>
                                @if($r->net_total > 0)
                                    <strong>{{ number_format($r->net_total, 2) }} ฿</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('technician.repairs.show', $r->id) }}" class="btn btn-outline-dark btn-sm py-1 px-2" title="เปิดหน้าตรวจเช็ค">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.repairs.print', $r->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm py-1 px-2" title="พิมพ์ใบสั่งซ่อม">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Series Doughnut Chart
    const ctxSeries = document.getElementById('seriesChart');
    if (ctxSeries) {
        new Chart(ctxSeries, {
            type: 'doughnut',
            data: {
                labels: ['911', '718', 'Taycan', 'Cayenne', 'Macan', 'Panamera'],
                datasets: [{
                    data: [
                        {{ $seriesStats['911'] }},
                        {{ $seriesStats['718'] }},
                        {{ $seriesStats['Taycan'] }},
                        {{ $seriesStats['Cayenne'] }},
                        {{ $seriesStats['Macan'] }},
                        {{ $seriesStats['Panamera'] }}
                    ],
                    backgroundColor: [
                        '#d5001c', // 911 Porsche Red
                        '#ff9500', // 718 Orange
                        '#007aff', // Taycan Electric Blue
                        '#34c759', // Cayenne Green
                        '#af52de', // Macan Purple
                        '#c8a661'  // Panamera Gold
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // 2. Technician Workload Bar Chart
    const ctxTech = document.getElementById('techChart');
    if (ctxTech) {
        new Chart(ctxTech, {
            type: 'bar',
            data: {
                labels: {!! json_encode($technicians->pluck('name')) !!},
                datasets: [
                    {
                        label: 'งานที่กำลังทำ (Active Jobs)',
                        data: {!! json_encode($technicians->pluck('active_jobs')) !!},
                        backgroundColor: '#ff9500'
                    },
                    {
                        label: 'งานที่ซ่อมเสร็จแล้ว (Completed Jobs)',
                        data: {!! json_encode($technicians->pluck('done_jobs')) !!},
                        backgroundColor: '#34c759'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }
});
</script>
@endpush
