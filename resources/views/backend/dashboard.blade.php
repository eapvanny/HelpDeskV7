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
                    <h5>{{ __('Problem Tickets') }}</h5>
                    <h3 id="problem-tickets">{{ __($openTickets) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="color: grey">
                    <h5>{{ __('Pending Tickets') }}</h5>
                    <h3 id="pending-tickets">{{ $pendingTickets }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="color: grey">
                    <h5>{{ __('Resolved Tickets') }}</h5>
                    <h3 id="solved-tickets">{{ $resolvedTickets }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="color: grey">
                    <h5>{{ __('Closed Tickets') }}</h5>
                    <h3 id="closed-tickets">{{ $closedTickets }}</h3>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="card mt-3">
                    <div class="media  d-flex justify-content-between ">
                        <div class="media-body">
                            <p class="mb-0 text-black mb-2" style="font-weight: bold">{{ __('Calendar') }}</p>
                            <span id="menu-navi">
                                <button type="button" id="today"
                                    class="calendar-btn calendar-move-today">{{ __('Today') }}</button>
                                <button type="button" class="calendar-btn calendar-move-day">
                                    <i id="btn-left" class="calendar-icon ic-arrow-line-left"></i>
                                </button>
                                <button type="button" class="calendar-btn calendar-move-day">
                                    <i id="btn-right" class="calendar-icon ic-arrow-line-right"></i>
                                </button>
                            </span>
                            <span id="year-month" class="calendar-render-range"></span>
                        </div>
                    </div>
                    <div id="calendar" style="height: 380px;"></div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="card mt-3 ticket-chat">
                    <div class="chart-container">
                        <span style="font-weight: bold">{{ __('All tickets in this year') }}</span>
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
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

            // Data from Laravel passed as JSON
            var monthlyTicketData = @json($monthlyData);

            // Line chart data for tickets opened per month
            var monthlyData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Opened Tickets',
                    borderColor: '#4299e1',
                    backgroundColor: 'rgba(30, 143, 255, 0.46)',
                    data: monthlyTicketData,
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
                    maintainAspectRatio: false, // Ensure responsiveness
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
                                //text: 'Months
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                //text: 'Tickets Opened'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
        $(document).ready(function() {
            const calendar = new Calendar('#calendar', {
                defaultView: 'month',
                isReadOnly: true,
                template: {
                    time(event) {
                        const {
                            start,
                            end,
                            title
                        } = event;
                        return `<span><i class="fa-solid fa-flag"></i> ${start} ~ ${end} ${title}</span>`;
                    },
                    allday(event) {
                        return `<span><i class="fa-solid fa-flag"></i> ${event.title}</span>`;
                    },
                },
                calendars: [{
                        id: 'school_event',
                        name: 'School Event',
                        backgroundColor: '#33bcff',
                        color: '#ffffff',
                    },
                    {
                        id: 'holiday',
                        name: 'Holiday',
                        backgroundColor: '#FF0000',
                        color: '#ffffff',
                    },
                    {
                        id: 'examination',
                        name: 'Examination',
                        backgroundColor: '#FFA500',
                        color: '#ffffff',
                    },
                ],
            });

            calendar.render();
            loadAcademicCalendar(function(response_data) {
                var event_arr = [];
                $.each(response_data, function(i, value) {
                    event_arr.push({
                        id: 'event_' + value.id,
                        calendarId: value.event_type,
                        title: value.title,
                        body: value.description,
                        isAllday: true,
                        start: value.date_from,
                        end: value.date_upto,
                        category: 'allday',
                    });
                });
                calendar.createEvents(event_arr);
            });

            $('#btn-left').on('click', function() {
                calendar.prev();
                updateMonthYear(calendar);
            });

            $('#btn-right').on('click', function() {
                calendar.next();
                updateMonthYear(calendar);
            });

            $('#today').on('click', function() {
                calendar.today();
                const currentDate = new Date();
                updateMonthYear(calendar, currentDate);
            });

            updateMonthYear(calendar);
        });

        // Load Academic Calendar function
        function loadAcademicCalendar(callback) {
            $.ajax({
                type: "GET",
                url: "/ajax/academic_calendar/search",
                data: {},
                dataType: 'json',
                async: true,
                success: function(response) {
                    if (response.status === true) {
                        if (callback !== undefined) {
                            callback(response.data);
                        }
                    } else {
                        alert('Something Went Wrong!');
                    }
                }
            });
        }

        // Update the Month and Year in the header
        function updateMonthYear(calendar) {
            const currentDate = calendar.getDate();
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const monthNames = [
                "January", "February", "March", "April", "May", "June", "July", "August",
                "September", "October", "November", "December"
            ];
            const monthName = monthNames[month];

            $('#year-month').text(`${monthName} ${year}`);
        }
    </script>
@endsection
