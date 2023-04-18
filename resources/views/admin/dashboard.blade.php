@extends('layouts.admin')
@section('content')
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary shadow-sm"><i class="fa-solid fa-shop"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('Shops') }}</span>
                    <span class="info-box-number">
                        {{ $shopsCount }}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger shadow-sm"><i class="fas fa-thumbs-up"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('Rating') }}</span>
                    <span class="info-box-number">{{ $averageRating }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success shadow-sm"><i class="fas fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('Orders') }}</span>
                    <span class="info-box-number">{{ $ordersCount }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning shadow-sm"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('Unique Clients') }}</span>
                    <span class="info-box-number">{{ $uniqueClientsCount }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Monthly Recap Report') }}</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">
                                <strong>{{ __('Sales :dateFrom - :dateTo', ['dateFrom' => $salesDateFrom, 'dateTo' => $salesDateTo]) }}</strong>
                            </p>

                            <div class="chart">
                                <!-- Sales Chart Canvas -->
                                <canvas id="salesChart" style="height: 180px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /assets/admin/card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Main row -->
    <div class="row">
        <!-- Start col -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <!-- USERS LIST -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Workers') }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <ul class="users-list clearfix">
                                @foreach($workers as $worker)
                                    <li>
                                        <img src="{{ $worker->avatar }}" alt="User Image">
                                        <a class="users-list-name" href="#">{{ $worker->name }}</a>
                                        <span class="users-list-date">{{ \Carbon\Carbon::now()->sub(\Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $worker->created_at))->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!--/.card -->
                </div>
                <div class="col-md-6">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">{{ __('Latest Orders') }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Order name') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->id }}</a></td>
                                        <td>{{ $order->service->name }}</td>
                                        <td><span class="badge {{ ($order->status == \App\Models\Order::STATUS_PAID || $order->status == \App\Models\Order::STATUS_FULFILLED) ? 'bg-success' : 'bg-danger' }}">{{ $order->statusText }}</span></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('scripts')
    <script>
        (function () {
            'use strict'
            function getRandomRgb() {
                const num = Math.round(0xffffff * Math.random());
                const r = num >> 16;
                const g = num >> 8 & 255;
                const b = num & 255;

                return 'rgb(' + r + ', ' + g + ', ' + b + ')';
            }

            // Get context with querySelector - using Chart.js .getContext('2d') method.
            const salesChartCanvas = document.querySelector('#salesChart').getContext('2d');

            const salesChartData = {
                datasets: [
                    @foreach ($shops as $shop)
                    {
                        label: '{{ $shop['company_name'] }}',
                        backgroundColor: getRandomRgb(),
                        borderColor: 'rgba(60,141,188,0.8)',
                        fill: true,
                        pointRadius: 0,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: @json($shop['sales']),
                    },
                    @endforeach
                ],
            };

            const salesChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                tension: 0.4,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    xAxes: {
                        gridLines: {
                            display: false,
                        },
                    },
                    yAxes: {
                        gridLines: {
                            display: false,
                        },
                    },
                },
            };

            // This will get the first returned node in the js collection.
            const salesChart = new Chart(salesChartCanvas, {
                type: 'line',
                data: salesChartData,
                options: salesChartOptions,
            });
        })()
    </script>
@endsection
