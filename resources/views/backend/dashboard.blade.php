@extends('backend.layouts.master')

@section('pageTitle')
    Dashboard
@endsection

@section('extraStyle')
    <style>
        .popup-logo {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .popup-content {
            text-align: center;
            animation: fadeInOut 4s ease-in-out infinite;
            /* Loop the fadeInOut animation */
        }

        .popup-content img {
            max-width: 50vw;
            max-height: 50vh;
            object-fit: contain;
        }

        /* Continuous fade-in and fade-out effect */
        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: scale(0.8);
            }
        }

        .calendar-move-today {
            background-color: transparent;
            color: black;
        }

        .calendar-move-today.active {
            background-color: #135de6;
            color: white;
        }
    </style>
@endsection

@section('pageContent')
    <section>
        <div class="row">
            @if (session('show_popup'))
                <div id="popup-logo" class="popup-logo">
                    <div class="popup-content">
                        <img src="{{ asset('images/Hi-Tech_Water_Logo.png') }}" alt="Logo">
                    </div>
                </div>
            @endif
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
                                <button type="button" id="today" class="calendar-btn calendar-move-today active">
                                    {{ __('Today') }}
                                </button>
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
        @if (session('show_popup'))
            // Show the popup logo with animation (e.g., fade-in and fade-out)
            window.addEventListener('DOMContentLoaded', (event) => {
                const popupLogo = document.getElementById('popup-logo');
                popupLogo.style.display = 'flex';

                setTimeout(() => {
                    popupLogo.style.display = 'none';
                }, 3500);
            });
        @endif
        $(document).ready(function() {
            // Set current date for date inputs
            var currentDate = new Date().toISOString().split('T')[0];
            $('input[type="date"]').val(currentDate);
            var ticketLabel = "{{ __('Tickets') }}";
            // Data from Laravel passed as JSON
            var monthlyTicketData = @json($monthlyData);

            // Line chart data for tickets opened per month
            var monthlyData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: ticketLabel,
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
        var monthNames = [
            "{{ __('January') }}", "{{ __('February') }}", "{{ __('March') }}", "{{ __('April') }}",
            "{{ __('May') }}", "{{ __('June') }}",
            "{{ __('July') }}", "{{ __('August') }}", "{{ __('September') }}", "{{ __('October') }}",
            "{{ __('November') }}", "{{ __('December') }}"
        ];

        function updateMonthYear(calendar) {
            const currentDate = calendar.getDate();
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const monthName = monthNames[month]; // Use translated month name

            $('#year-month').text(`${monthName} ${year}`);
        }

        $(document).ready(function() {
            let today = new Date();
            let currentDate = new Date();

            function updateTodayButton() {
                let formattedToday = today.toISOString().split("T")[0];
                let formattedCurrent = currentDate.toISOString().split("T")[0];

                if (formattedToday === formattedCurrent) {
                    $("#today").addClass("active");
                } else {
                    $("#today").removeClass("active");
                }
            }

            $("#today").on("click", function() {
                currentDate = new Date();
                updateTodayButton();
            });

            $("#btn-left").on("click", function() {
                currentDate.setDate(currentDate.getDate() - 1);
                updateTodayButton();
            });

            $("#btn-right").on("click", function() {
                currentDate.setDate(currentDate.getDate() + 1);
                updateTodayButton();
            });

            updateTodayButton();
        });
    </script>
@endsection
