@extends('layouts.app')
@section('title', 'Báo cáo doanh thu')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $now = now();
    $hour = (int) $now->format('G');
    $user = Auth::user();

    $greeting = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
    $weekdayMap = [
        'Mon' => 'Thứ hai',
        'Tue' => 'Thứ ba',
        'Wed' => 'Thứ tư',
        'Thu' => 'Thứ năm',
        'Fri' => 'Thứ sáu',
        'Sat' => 'Thứ bảy',
        'Sun' => 'Chủ nhật',
    ];
    $weekdayVN = $weekdayMap[$now->format('D')] ?? '';
    $dateVN = $now->format('d/m/Y');
@endphp

<div class="ad-wrapper d-flex container-fluid">
    {{-- SIDEBAR --}}
    <aside class="ad-sidebar">
        <nav class="ad-menu">
            <h6>TỔNG QUAN</h6>
            <a class="ad-link {{ request()->routeIs('admin.form') ? 'active' : '' }}"
               href="{{ route('admin.form') }}">Bảng điều khiển</a>

            <h6>NGƯỜI DÙNG</h6>
            <a class="ad-link {{ request()->routeIs('admin.userManagement_main.form') ? 'active' : '' }}"
               href="{{ route('admin.userManagement_main.form') }}">Quản lý người dùng</a>

            <h6>KHUYẾN MÃI</h6>
            <a class="ad-link {{ request()->routeIs('admin.promotionManagement.form') ? 'active' : '' }}"
               href="{{ route('admin.promotionManagement.form') }}">Quản lý khuyến mãi</a>

            <h6>PHIM</h6>
            <a class="ad-link {{ request()->routeIs('admin.moviesManagement_main.form') ? 'active' : '' }}"
               href="{{ route('admin.moviesManagement_main.form') }}">Quản lý phim</a>

            <h6>PHÒNG CHIẾU</h6>
            <a class="ad-link {{ request()->routeIs('admin.movietheaterManagement.form') ? 'active' : '' }}"
               href="{{ route('admin.movietheaterManagement.form') }}">Quản lý phòng chiếu</a>

            <h6>SUẤT CHIẾU</h6>
            <a class="ad-link {{ request()->routeIs('admin.showtimeManagement.form') ? 'active' : '' }}"
               href="{{ route('admin.showtimeManagement.form') }}">Quản lý suất chiếu</a>

            <h6>BÁO CÁO</h6>
            <a class="ad-link {{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}"
               href="{{ route('admin.reports.revenue') }}">Doanh thu</a>

            <h6>TIN TỨC</h6>
            <a class="ad-link {{ request()->routeIs('admin.newsManagement.form') ? 'active' : '' }}"
               href="{{ route('admin.newsManagement.form') }}">Quản lý tin tức</a>
        </nav>
    </aside>

    {{-- MAIN --}}
    <main class="ad-main flex-grow-1">
        {{-- Greeting --}}
        <div class="ad-greeting card shadow-sm border-0 mb-4 w-100">
            <div class="card-body d-flex align-items-center gap-3 flex-wrap">
                <img src="{{ $user?->avatar ? asset('storage/' . $user->avatar) : asset('storage/pictures/dogavatar.jpg') }}"
                     class="rounded-circle me-3"
                     style="width:72px;height:72px;object-fit:cover;flex:0 0 72px;"
                     alt="avatar">

                <div class="me-auto min-w-0">
                    <h5 class="mb-1 text-truncate">
                        {{ $greeting }}, <span class="text-primary">{{ $user?->username ?? 'Admin' }}</span>
                    </h5>

                    <div class="text-muted small">
                        {{ $weekdayVN }}, {{ $dateVN }} • Chúc bạn làm việc hiệu quả!
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 ms-md-auto">
                    <a href="{{ url()->current() }}" class="btn btn-sm btn-light">Làm mới</a>
                </div>
            </div>
        </div>

        <div class="ad-page-title d-flex align-items-center justify-content-between mb-3">
            <h3 class="m-0">Báo cáo doanh thu</h3>
        </div>

        <div class="adm-revenue">
            <div class="row g-4">
                {{-- Chart doanh thu theo thời gian --}}
                <div class="col-12">
                    <div class="card-like revenue-card mb-3">
                        <div class="revenue-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1">Doanh thu theo thời gian</h5>
                                <div class="text-muted small">
                                    Dữ liệu từ các đơn hàng đã thanh toán
                                </div>
                            </div>

                            <div class="revenue-range-pills btn-group" role="group">
                                <button type="button" class="btn btn-sm revenue-range-pill active"
                                        data-range="7">7 ngày</button>
                                <button type="button" class="btn btn-sm revenue-range-pill"
                                        data-range="30">30 ngày</button>
                                <button type="button" class="btn btn-sm revenue-range-pill"
                                        data-range="365">1 năm</button>
                            </div>
                        </div>

                        <div class="chart-box mt-3">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Chart doanh thu theo từng phim --}}
                <div class="col-12">
                    <div class="card-like revenue-card">
                        <div class="revenue-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1">Doanh thu theo từng phim</h5>
                                <div class="text-muted small">
                                    Top phim mang lại doanh thu cao nhất
                                </div>
                            </div>
                        </div>

                        <div class="chart-box mt-3">
                            <canvas id="movieRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ===== CHART 1: Doanh thu theo thời gian =====
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            let revenueChart = new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: [],
                        borderColor: 'rgba(69, 74, 242, 0.9)',
                        backgroundColor: 'rgba(69, 74, 242, 0.12)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: { display: true, text: 'Ngày' },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Doanh thu (VNĐ)' },
                            ticks: {
                                callback: value => value.toLocaleString('vi-VN')
                            }
                        }
                    },
                    plugins: {
                        legend: { display: true },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + (ctx.parsed.y || 0).toLocaleString('vi-VN') + ' VNĐ'
                            }
                        }
                    }
                }
            });

            function loadRevenue(days) {
                $.ajax({
                    url: "{{ route('reports.revenue.ajax') }}",
                    type: "GET",
                    data: { days },
                    dataType: "json",
                    success: function (res) {
                        const labels = res.map(r => r.date);
                        const data   = res.map(r => parseFloat(r.total || 0));

                        revenueChart.data.labels = labels;
                        revenueChart.data.datasets[0].data = data;
                        revenueChart.update();
                    },
                    error: function () {
                        alert("Không tải được dữ liệu doanh thu tổng!");
                    }
                });
            }

            document.querySelectorAll('.revenue-range-pill').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.revenue-range-pill').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    loadRevenue(this.dataset.range);
                });
            });

            loadRevenue(7); // default

            // ===== CHART 2: Doanh thu theo từng phim =====
            const ctxMovie = document.getElementById('movieRevenueChart').getContext('2d');
            let movieRevenueChart = new Chart(ctxMovie, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: [],
                        backgroundColor: 'rgba(255, 159, 64, 0.75)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1.5,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: { display: true, text: 'Tên phim' },
                            ticks: {
                                maxRotation: 45,
                                callback: function (value) {
                                    const label = this.getLabelForValue(value);
                                    return label.length > 18 ? label.slice(0, 18) + '…' : label;
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Doanh thu (VNĐ)' },
                            ticks: {
                                callback: value => value.toLocaleString('vi-VN')
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + (ctx.parsed.y || 0).toLocaleString('vi-VN') + ' VNĐ'
                            }
                        }
                    }
                }
            });

            function loadMovieRevenue() {
                $.ajax({
                    url: "{{ route('reports.revenue.movie.ajax') }}",
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        const labels = res.map(r => r.title);
                        const data   = res.map(r => parseFloat(r.total || 0));

                        movieRevenueChart.data.labels = labels;
                        movieRevenueChart.data.datasets[0].data = data;
                        movieRevenueChart.update();
                    },
                    error: function () {
                        alert("Không tải được dữ liệu doanh thu theo phim!");
                    }
                });
            }

            loadMovieRevenue();
        });
    </script>
@endpush

@push('styles')
<style>
    .adm-revenue .revenue-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eaecf0;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        padding: 18px 20px 20px;
    }
    .adm-revenue .chart-box {
        position: relative;
        width: 100%;
        min-height: 360px;
    }
    .adm-revenue .revenue-card-header h5 {
        font-weight: 600;
    }

    .revenue-range-pills .revenue-range-pill {
        border-radius: 999px;
        padding-inline: 16px;
        padding-block: 6px;
        border: 1px solid #d4d4ff;
        background: #f3f4ff;
        color: #4338ca;
        font-weight: 500;
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.18);
        transition: all .15s ease;
    }
    .revenue-range-pills .revenue-range-pill:not(.active) {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #4b5563;
        box-shadow: none;
    }
    .revenue-range-pills .revenue-range-pill:hover {
        filter: brightness(0.97);
    }
</style>
@endpush
