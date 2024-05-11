@extends('layouts')
@section('title','Dashboard')
@section('content')
@if(auth()->check() && auth()->user()->role_id == '4')
<div class="content-wrapper">
    <br><br><br><br><br>
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1" style="font-size: 3rem;"><i class="fas fa-users"></i></span>
                <div class="info-box-content d-flex flex-column align-items-center">
                    <span class="info-box-text" style="font-size: 1.2rem;">Total Users</span>
                    <span class="info-box-number mb-0 text-center" style="font-size: 2rem;">{{ $totalUsers }}</span>
                </div>
            </div>
        </div>
        &nbsp&nbsp&nbsp
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1" style="font-size: 3rem;"><i class="fas fa-check"></i></span>
                <div class="info-box-content d-flex flex-column align-items-center">
                    <span class="info-box-text" style="font-size: 1.2rem;">Approved Restaurants</span>
                    <span class="info-box-number mb-0 text-center" style="font-size: 2rem;">{{ $approvedRestaurants }}</span>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1" style="font-size: 3rem;"><i class="fas fa-clock"></i></span>
                <div class="info-box-content d-flex flex-column align-items-center">
                    <span class="info-box-text" style="font-size: 1.2rem;">Restaurant Registration Requests</span>
                    <span class="info-box-number mb-0 text-center" style="font-size: 2rem;">{{ $pendingRequests }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(Auth::guard('restaurant')->check())
<div class="content-wrapper">
    <br>
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1" style="font-size: 3rem;"><i class="fas fa-users"></i></span>
                <div class="info-box-content d-flex flex-column align-items-center">
                    <span class="info-box-text" style="font-size: 1.2rem;">Reservation Request</span>
                    <span class="info-box-number mb-0 text-center" style="font-size: 2rem;">{{ $reservationRequests }}</span>
                </div>
            </div>
        </div>
        &nbsp&nbsp&nbsp
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1" style="font-size: 3rem;"><i class="fas fa-check"></i></span>
                <div class="info-box-content d-flex flex-column align-items-center">
                    <span class="info-box-text" style="font-size: 1.2rem;">Today's Approved Reservation</span>
                    <span class="info-box-number mb-0 text-center" style="font-size: 2rem;">{{ $todaysApprovedReservationsCount }}</span>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-lg-8 col-12 mx-auto">
        <div class="card border-primary">
            <div class="card-body">
                <canvas id="areaChart" style="height: 400px; width: 800px;"></canvas>
            </div>
        </div>
    </div>

</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('areaChart').getContext('2d');

    var reservationCounts = <?php echo json_encode($reservationCounts); ?>;

    var hours = Object.keys(reservationCounts);
    var counts = Object.values(reservationCounts);

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: hours.map(hour => hour.toString().padStart(2, '0') + ':00'),
            datasets: [{
                label: 'Today\'s Peak Hours for Reservations',
                data: counts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Number of Reservations'
                    },
                    ticks: {
                        beginAtZero: true
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Hour of the Day'
                    }
                }
            }
        }
    });
</script>

@endsection
