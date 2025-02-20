<!-- resources/views/dashboard/index.blade.php -->
@extends('layout')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Good Morning, {{Session::get('username')}}!</h4>
                        <p class="text-muted mb-0">Here's what's happening with your store today.</p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <form action="javascript:void(0);">
                            <div class="row g-3 mb-0 align-items-center">
                                <div class="col-sm-auto">
                                    <div class="input-group">
                                        <input type="text" id="currentDate" class="form-control border-0 dash-filter-picker shadow" readonly>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>

                </div><!-- end card header -->
            </div>
            <!--end col-->
        </div>


        <div class="row">
            <div class="col-xxl-6 col-md-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">
                            Customer Status Overview
                        </h4>

                    </div>
                    <!-- end card header -->
                    <div class="card-body pb-0">
                        <div id="customerStatusChart2" style="width: 100%; height: 430px;"></div> <!-- Bar Chart -->

                    </div>
                </div>
                <!-- end card -->
            </div>

            <!-- end col -->

            <div class="col-xxl-6 col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Users</p>
                                        <h2 class="mt-4 ff-secondary fw-semibold">
                                            <span class="counter-value" data-target="">0</span> user
                                        </h2>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-primary rounded-circle fs-2">
                                                <i data-feather="users"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Customers</p>
                                        <h3 class="mt-4 ff-secondary fw-semibold"><span class="counter-customer" data-target-customer="0">0</span> Customer</h3>

                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-danger rounded-circle fs-2">
                                                <i data-feather="shopping-bag"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    Customers
                                </h5>
                            </div>
                            <div class="card-body">
                                <table id="customersTable" class="table table-bordered dt-responsive nowrap table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div><!-- end card body -->
                        </div> <!-- end card-->
                    </div>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

<script>
    $(document).ready(function() {
        // Fetch data using AJAX
        $.ajax({
            url: "{{ route('dashboard.data') }}",
            method: "GET",
            success: function(response) {
                console.log(response); // Debugging untuk melihat apakah data diterima

                // Render Pie Chart
                // Highcharts.chart('customerStatusChart1', {
                //     chart: {
                //         type: 'pie'
                //     },
                //     title: {
                //         text: 'Customer Status Distribution'
                //     },
                //     tooltip: {
                //         pointFormat: '{series.name}: <b>{point.y}</b>'
                //     },
                //     accessibility: {
                //         point: {
                //             valueSuffix: ''
                //         }
                //     },
                //     plotOptions: {
                //         pie: {
                //             allowPointSelect: true,
                //             cursor: 'pointer',
                //             dataLabels: {
                //                 enabled: true,
                //                 format: '<b>{point.name}</b>: {point.y}'
                //             }
                //         }
                //     },
                //     series: [{
                //         name: 'Customers',
                //         colorByPoint: true,
                //         data: [{
                //                 name: 'New Customer',
                //                 y: response.newCustomerCount,
                //                 color: '#FF5733'
                //             },
                //             {
                //                 name: 'Loyal Customer',
                //                 y: response.loyalCustomerCount,
                //                 color: '#33FF57'
                //             }
                //         ]
                //     }]
                // });

                // Render Bar Chart
                Highcharts.chart('customerStatusChart2', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Customer Status',

                    },
                    xAxis: {
                        categories: ['New Customer', 'Loyal Customer'],
                        title: {
                            text: null
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Total Customers',
                            align: 'high'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    tooltip: {
                        valueSuffix: ''
                    },
                    plotOptions: {
                        column: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Customers',
                        data: [response.newCustomerCount, response.loyalCustomerCount],
                        colorByPoint: true
                    }]
                });
            },
            error: function(xhr, status, error) {
                console.error("Error:", status, error);
                alert('Error loading customer data');
            }
        });


        function updateDate() {
            const jakartaTimeZone = 'Asia/Jakarta';
            const now = new Date();
            const options = {
                weekday: 'long', // Day of the week (e.g., Monday)
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            };

            // Create a new Date object for Jakarta time
            const jakartaDate = new Intl.DateTimeFormat('id-ID', {
                ...options,
                timeZone: jakartaTimeZone,
            }).format(now);

            // Set the date in the input field
            document.getElementById('currentDate').value = jakartaDate;
        }

        function updateUserCount() {
            // Ambil data dari API
            fetch('/total-users')
                .then(response => response.json())
                .then(data => {
                    // Cek jika respons sukses dan memiliki total_users
                    if (data.success && data.total_users) {
                        // Ambil elemen counter-value dan ganti data-target
                        const counterValue = document.querySelector('.counter-value');
                        const totalUsers = data.total_users;

                        // Ubah data-target dengan total user
                        counterValue.setAttribute('data-target', totalUsers);

                        // Menampilkan total user
                        counterValue.innerText = totalUsers;
                    }
                })
                .catch(error => console.error('Error fetching total users:', error));
        }

        function updateCustomerCount() {
            // Ambil data dari API
            fetch('/total-customers')
                .then(response => response.json())
                .then(data => {
                    // Cek jika respons sukses dan memiliki total_customers
                    if (data.success && data.total_customers) {
                        // Ambil elemen counter-customer dan ganti data-target-customer
                        const counterValue = document.querySelector('.counter-customer');
                        const totalCustomers = data.total_customers;

                        // Ubah data-target-customer dengan total customers
                        counterValue.setAttribute('data-target-customer', totalCustomers);

                        // Menampilkan total customers
                        counterValue.innerText = totalCustomers;
                    }
                })
                .catch(error => console.error('Error fetching total customers:', error));
        }

        var table = $('#customersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('customers.data') }}",
            scrollY: '200px', // Set the fixed height for the table, you can adjust this value
            searching: false,
            scrollCollapse: true, // Allow table to collapse if fewer rows
            paging: false, // Disable pagination since scrolling will handle it
            columns: [{
                    data: null, // This column will display the auto-incrementing number
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Auto-incrementing number (row index + 1)
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'status'
                }
            ]
        });


        // Call updateDate function once to set the initial value
        updateDate();
        updateUserCount();
        updateCustomerCount();
    });
</script>


@endsection