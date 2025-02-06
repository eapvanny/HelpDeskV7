@extends('backend.layouts.master')

@section('pageTitle')
    Dashboard
@endsection

@section('extraStyle')
<style>
    
</style>
@endsection

@section('pageContent')
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="date">
                    <input class="select2" type="date">
                    <input class="select2" type="date">
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="color: grey">
                    <h5>Open tickets</h5>
                    <h3 id="open-tickets">0</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="color: grey">
                    <h5>Pending tickets</h5>
                    <h3 id="pending-tickets">0</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="color: grey">
                    <h5>Solved tickets</h5>
                    <h3 id="solved-tickets">0</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="color: grey">
                    <h5>Without assign agent</h5>
                    <h3 id="unassigned-tickets">0</h3>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="chart-container">
                <span style="font-weight: bold">Opened tickets this year</span>
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </section>
@endsection

@section('extraScript')
<script src="{{ asset('js/chart.js') }}"></script>

<script>
   $(document).ready(function() {
    // Set current date for date inputs
    var currentDate = new Date().toISOString().split('T')[0];
    $('input[type="date"]').val(currentDate);  

    // Line chart data for the months of the year
    var monthlyData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 
        datasets: [{
            label: 'Opened Tickets',
            borderColor: '#4299e1',
            backgroundColor: 'rgba(30, 143, 255, 0.46)',
            data: [30, 45, 35, 50, 60, 70],  
            fill: true,
            tension: 0.4
        }]
    };

    var ctx = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: monthlyData,
        options: {
            responsive: true,
            maintainAspectRatio: false, // Make sure the chart is responsive
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        // text: 'Months'
                    }
                },
                y: {
                    title: {
                        display: true,
                    },
                    beginAtZero: true
                }
            }
        }
    });
});

</script>

@endsection
