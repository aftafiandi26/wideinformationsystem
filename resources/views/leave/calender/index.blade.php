@extends('layout')

@section('title')
    Calender of Leave
@stop

@section('top')
    @include('assets_css_1')
    @include('assets_css_2')
@stop

@section('navbar')
    @include('navbar_top')
    @include('navbar_left', [
        'c2' => 'active',
    ])
@stop

@section('style')
    <style>
        .fc .fc-col-header-cell-cushion {
            display: inline-block;
            padding: 2px 4px;
        }

        .fc .fc-col-header-cell-cushion {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }

        .modal-custom {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content-custom {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 5px;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 24px;
            font-weight: bold;
            color: #aaa;
        }

        .modal-close:hover {
            color: #000;
        }

        .event-details {
            margin-top: 15px;
        }

        .event-details p {
            margin: 5px 0;
        }
    </style>
@stop

@section('body')
    <!-- JavaScript Libraries -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.5/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.5/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.5/index.global.min.js'></script>

    <script>
        // Global variables
        let calendar;
        let globalEventsData = {};
        const modalId = 'calendar-modal-custom';

        document.addEventListener('DOMContentLoaded', function() {
            initializeCalendar();
            createModal();
        });

        function initializeCalendar() {
            const calendarEl = document.getElementById('calendar');

            if (!calendarEl) {
                console.error('Calendar element not found');
                showError('Calendar element not found');
                return;
            }

            calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                timeZone: 'Asia/Jakarta',
                headerToolbar: {
                    left: 'dayGridMonth,timeGridWeek,timeGridDay',
                    center: 'title',
                    right: 'prev,next today'
                },
                initialView: 'dayGridMonth',
                contentHeight: 600,
                aspectRatio: 2,
                events: {
                    url: '{{ route('leave/calender/data') }}',
                    method: 'GET',
                    extraParams: {
                        '_token': '{{ csrf_token() }}',
                        'cache': new Date().getTime()
                    },
                    success: function(response) {
                        hideLoading();
                        console.log('Events loaded successfully');

                        // Store events data globally
                        response.forEach(event => {
                            if (event.extendedProps?.originalData) {
                                globalEventsData[event.id] = event.extendedProps.originalData;
                            }
                        });
                    },
                    failure: function(error) {
                        hideLoading();
                        showError('Failed to load calendar data');
                        console.error('Events loading failed:', error);
                    }
                },
                dayMaxEvents: true, // Aktifkan fitur more link
                dayMaxEventRows: 3, // Maksimal 3 baris event yang ditampilkan
                moreLinkClick: 'popover', // Tampilkan popover ketika diklik

                // Custom popover content
                moreLinkContent: function(args) {
                    return '+ ' + args.num + ' more';
                },
                loading: function(isLoading) {
                    if (isLoading) {
                        showLoading();
                    } else {
                        hideLoading();
                    }
                },
                eventClick: function(info) {
                    handleEventClick(info);
                },
                eventDidMount: function(info) {
                    // Store original data in DOM for backup
                    if (info.event.extendedProps?.originalData) {
                        info.el.dataset.original = JSON.stringify(info.event.extendedProps.originalData);
                    }
                }
            });

            calendar.render();
            console.log('Calendar initialized successfully');
        }

        function handleEventClick(info) {
            console.log('Event clicked: successfully');

            let originalData = null;

            // Try to get data from multiple sources
            if (info.event.extendedProps?.originalData) {
                originalData = info.event.extendedProps.originalData;
            } else if (globalEventsData[info.event.id]) {
                originalData = globalEventsData[info.event.id];
            } else if (info.el.dataset.original) {
                try {
                    originalData = JSON.parse(info.el.dataset.original);
                } catch (e) {
                    console.error('Error parsing DOM data:', e);
                }
            }

            if (originalData) {
                showEventDetails(originalData, info.event);
            } else {
                // Fallback: show basic event info
                showBasicEventInfo(info.event);
            }

            console.log(info.event.extendedProps);

            info.jsEvent.preventDefault();
        }

        function showEventDetails(originalData, event) {
            const duration = calculateDuration(originalData.leave_date_original, originalData.back_work_original);

            const modalContent = `
                <h4>📅 Detail Cuti</h4>
                <div class="event-details">
                    <p><strong>👤 Nama Karyawan:</strong> ${originalData.username || 'N/A'}</p>
                    <p><strong>🏷️ Kategori Cuti:</strong> ${originalData.leave_category_name || 'N/A'}</p>
                    <p><strong>📅 Tanggal Mulai:</strong> ${formatDateDisplay(originalData.leave_date_original)}</p>
                    <p><strong>📅 Tanggal Selesai:</strong> ${formatDateDisplay(originalData.back_work_original)}</p>
                    <p><strong>⏰ Durasi:</strong> ${duration} hari</p>
                    <p><strong>🆔 ID Cuti:</strong> ${event.id}</p>
                    <p><strong>👤 User ID:</strong> ${originalData.user_id || 'N/A'}</p>
                </div>
            `;

            showModal(modalContent);
        }

        function showBasicEventInfo(event) {
            const startDate = event.start ? formatCalendarDate(event.start) : 'N/A';
            const endDate = event.end ? formatCalendarDate(event.end) : 'N/A';

            const modalContent = `
                <h4>📅 Informasi Cuti</h4>
                <div class="event-details">
                    <p><strong>📝 Judul:</strong> ${event.title || 'N/A'}</p>
                    <p><strong>📅 Tanggal Mulai:</strong> ${startDate}</p>
                    <p><strong>📅 Tanggal Selesai:</strong> ${endDate}</p>
                    <p><strong>🆔 ID:</strong> ${event.id}</p>
                    <p style="color: orange;">⚠️ Data detail lengkap tidak tersedia</p>
                </div>
            `;

            showModal(modalContent);
        }

        function formatDateDisplay(dateString) {
            if (!dateString) return 'N/A';

            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch (e) {
                return dateString;
            }
        }

        function formatCalendarDate(date) {
            if (!date) return 'N/A';
            return date.toLocaleDateString('id-ID');
        }

        function calculateDuration(startDate, endDate) {
            if (!startDate || !endDate) return 'N/A';

            try {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                return diffDays;
            } catch (e) {
                return 'N/A';
            }
        }

        function createModal() {
            if (document.getElementById(modalId)) return;

            const modal = document.createElement('div');
            modal.id = modalId;
            modal.className = 'modal-custom';
            modal.innerHTML = `
                <div class="modal-content-custom">
                    <span class="modal-close">&times;</span>
                    <div class="modal-body"></div>
                </div>
            `;

            document.body.appendChild(modal);

            // Add event listeners
            const closeBtn = modal.querySelector('.modal-close');
            closeBtn.addEventListener('click', () => hideModal());

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    hideModal();
                }
            });
        }

        function showModal(content) {
            const modal = document.getElementById(modalId);
            const modalBody = modal.querySelector('.modal-body');

            modalBody.innerHTML = content;
            modal.style.display = 'block';

            // Add close event to modal content
            const closeBtn = modal.querySelector('.modal-close');
            if (closeBtn) {
                closeBtn.onclick = () => hideModal();
            }
        }

        function hideModal() {
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }

        function showLoading() {
            const loadingEl = document.getElementById('calendar-loading');
            const errorEl = document.getElementById('calendar-error');

            if (loadingEl) loadingEl.style.display = 'block';
            if (errorEl) errorEl.style.display = 'none';
        }

        function hideLoading() {
            const loadingEl = document.getElementById('calendar-loading');
            if (loadingEl) loadingEl.style.display = 'none';
        }

        function showError(message) {
            const errorEl = document.getElementById('calendar-error');
            if (errorEl) {
                errorEl.innerHTML = `${message}. <button onclick="location.reload()">Coba lagi</button>`;
                errorEl.style.display = 'block';
            }
        }

        // Keyboard support for modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideModal();
            }
        });

        // Utility function to refresh calendar
        window.refreshCalendar = function() {
            if (calendar) {
                calendar.refetchEvents();
                showLoading();
            }
        };
    </script>

    <!-- HTML Structure -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Calendar of Leave</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="calendar-loading" style="display: none; text-align: center; padding: 20px;">
                <div class="spinner"></div>
                <p>Memuat data kalender...</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="calendar-error" style="display: none; text-align: center; padding: 20px; color: red;">
                Gagal memuat data. <button onclick="location.reload()">Coba lagi</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('leave/summary/employes/index') }}" class="btn btn-default btn-sm"
                style="margin-bottom: 10px;">
                <i class="fa fa-table"></i> Table View
            </a>
            <button onclick="refreshCalendar()" class="btn btn-primary btn-sm" style="margin-bottom: 10px;">
                <i class="fa fa-refresh"></i> Refresh Calendar
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="calendar"></div>
        </div>
    </div>
@stop

@section('bottom')
    @include('assets_script_1')
    @include('assets_script_2')
    @include('assets_script_7')
@stop

@section('js')
    <!-- Additional JavaScript if needed -->
@stop
