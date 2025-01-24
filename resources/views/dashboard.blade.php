<!-- resources/views/dashboard/index.blade.php -->
@extends('layout')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2>Dashboard</h2>
        <div class="row">
            <div class="col-xxl-6 col-md-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">
                            Grafik Batang
                        </h4>
                    </div>
                    <!-- end card header -->
                    <div class="card-body pb-0">
                        <!-- Updated ID to match the second chart -->
                        <canvas id="customerStatusChart2" style="width: 100%; height: 400px;" data-colors='["--vz-primary", "--vz-success", "--vz-warning"]' dir="ltr"></canvas>
                    </div>
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->

            <div class="col-xxl-6 col-md-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">
                            Grafik Lingkaran
                        </h4>
                    </div>
                    <!-- end card header -->
                    <div class="card-body pb-0">
                        <!-- Updated ID to match the first chart -->
                        <canvas id="customerStatusChart1" style="width: 100%; height: 400px;" data-colors='["--vz-warning", "--vz-danger", "--vz-success"]' class="apex-charts" dir="ltr"></canvas>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>

        </div>


        <!-- First Chart (Pie Chart) -->
        <!-- <div class="row">
            <div class="col-md-6">
                <canvas id="customerStatusChart1"></canvas>
            </div>
        </div> -->

        <!-- Second Chart (Bar Chart) -->
        <!-- <div class="row">
            <div class="col-md-6">
                <canvas id="customerStatusChart2"></canvas>
            </div>
        </div> -->

    </div>
</div>

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Fetch data using AJAX and render the charts
        $.ajax({
            url: "{{ route('dashboard.data') }}",
            method: "GET",
            success: function(response) {
                // Render the first pie chart (Lingkaran)
                var ctx1 = document.getElementById('customerStatusChart1').getContext('2d');
                new Chart(ctx1, {
                    type: 'pie',
                    data: {
                        labels: ['New Customer', 'Loyal Customer'],
                        datasets: [{
                            // label: 'Customer Status',
                            data: [response.newCustomerCount, response.loyalCustomerCount],
                            backgroundColor: ['#FF5730', '#33FF57'],
                            borderColor: ['#FF5733', '#33FF57'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });

                // Render the second bar chart (Grafik Batang)
                var ctx2 = document.getElementById('customerStatusChart2').getContext('2d');
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['New Customer', 'Loyal Customer'],
                        datasets: [{
                            label: 'Customer Status',
                            data: [response.newCustomerCount, response.loyalCustomerCount],
                            backgroundColor: ['#FF5733', '#33FF57'],
                            borderColor: ['#FF5733', '#33FF57'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function() {
                alert('Error loading customer data');
            }
        });
    });
</script>

@endsection