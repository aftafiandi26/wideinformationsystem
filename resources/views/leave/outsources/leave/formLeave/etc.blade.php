@extends('layout')

@section('title')
    Etc Leave Form
@stop

@section('top')
    @include('assets_css_1')
    @include('assets_css_2')
    @include('asset_select2')

@stop

@section('navbar')
    @include('navbar_top')
    @include('navbar_left', [
        'c2' => 'active',
        'c16' => 'active',
    ])
@stop

@push('style')
    <style>
        .annual-leave-form .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .annual-leave-form .form-control[readonly] {
            background-color: #f8f9fa;
        }

        .annual-leave-form label {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        /* Required field styling - red color for labels */
        .annual-leave-form label[for="leave_date"],
        .annual-leave-form label[for="end_leave_date"],
        .annual-leave-form label[for="back_work"],
        .annual-leave-form label[for="perhitungan"],
        .annual-leave-form label[for="entitlement"],
        .annual-leave-form label[for="remaining"],
        .annual-leave-form label[for="provinces"],
        .annual-leave-form label[for="city"],
        .annual-leave-form label[for="head_of_department"],
        .annual-leave-form label[for="reason"] {
            color: red;
        }

        .annual-leave-form .btn {
            padding: 8px 16px;
            margin-right: 10px;
            border-radius: 4px;
        }

        .annual-leave-form .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .annual-leave-form .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .annual-leave-form .row {
            margin-bottom: 15px;
        }

        .annual-leave-form textarea {
            resize: vertical;
            min-height: 60px;
        }

        /* API Error Notification Styles - Removed */

        /* Modal Customization for Error */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
            font-size: 16px;
        }

        .modal-header .close {
            color: white;
            opacity: 0.8;
        }

        .modal-header .close:hover {
            opacity: 1;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
        }

        /* Validation States */
        .text-danger {
            color: #dc3545 !important;
            font-weight: 600;
        }

        .text-warning {
            color: #ffc107 !important;
            font-weight: 600;
        }

        .text-success {
            color: #28a745 !important;
            font-weight: 600;
        }

        /* Balance Validation Error Styles */
        .balance-validation-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-radius: 8px;
            padding: 0;
            border: none;
        }

        .balance-validation-error .error-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 8px 8px 0 0;
            font-size: 14px;
            font-weight: 600;
        }

        .balance-validation-error .error-header i {
            margin-right: 8px;
        }

        .balance-validation-error .error-body {
            padding: 16px;
        }

        .balance-validation-error .error-body p {
            margin-bottom: 12px;
            color: #495057;
            font-size: 14px;
        }

        .balance-validation-error .error-details {
            background: white;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .balance-validation-error .error-item {
            display: flex;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .balance-validation-error .error-item:last-child {
            border-bottom: none;
        }

        .balance-validation-error .error-item i {
            margin-right: 10px;
            font-size: 14px;
            width: 16px;
        }

        .balance-validation-error .error-item span {
            color: #495057;
            font-size: 13px;
        }

        .balance-validation-error .error-footer {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
        }

        .balance-validation-error .error-footer i {
            margin-right: 6px;
            color: #721c24;
        }

        .balance-validation-error .error-footer small {
            color: #721c24;
            font-weight: 500;
        }

        /* Balance Warning Notification Styles */
        .balance-warning-notification {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-radius: 8px;
            padding: 0;
            border: none;
        }

        .balance-warning-notification .warning-header {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
            padding: 12px 16px;
            border-radius: 8px 8px 0 0;
            font-size: 14px;
            font-weight: 600;
        }

        .balance-warning-notification .warning-header i {
            margin-right: 8px;
        }

        .balance-warning-notification .warning-body {
            padding: 16px;
        }

        .balance-warning-notification .warning-body p {
            margin-bottom: 12px;
            color: #495057;
            font-size: 14px;
        }

        .balance-warning-notification .warning-details {
            background: white;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .balance-warning-notification .warning-item {
            display: flex;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .balance-warning-notification .warning-item:last-child {
            border-bottom: none;
        }

        .balance-warning-notification .warning-item i {
            margin-right: 10px;
            font-size: 14px;
            width: 16px;
        }

        .balance-warning-notification .warning-item span {
            color: #495057;
            font-size: 13px;
        }

        .balance-warning-notification .warning-footer {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
        }

        .balance-warning-notification .warning-footer i {
            margin-right: 6px;
            color: #155724;
        }

        .balance-warning-notification .warning-footer small {
            color: #155724;
            font-weight: 500;
        }

        /* Disabled button styles */
        .btn.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Provinces Error Notification Styles */
        .provinces-error-notification {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-radius: 8px;
            padding: 0;
            border: none;
        }

        .provinces-error-notification .error-header {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
            padding: 12px 16px;
            border-radius: 8px 8px 0 0;
            font-size: 14px;
            font-weight: 600;
        }

        .provinces-error-notification .error-header i {
            margin-right: 8px;
        }

        .provinces-error-notification .error-body {
            padding: 16px;
        }

        .provinces-error-notification .error-body p {
            margin-bottom: 12px;
            color: #495057;
            font-size: 14px;
        }

        .provinces-error-notification .error-details {
            background: white;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .provinces-error-notification .error-item {
            display: flex;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .provinces-error-notification .error-item:last-child {
            border-bottom: none;
        }

        .provinces-error-notification .error-item i {
            margin-right: 10px;
            font-size: 14px;
            width: 16px;
        }

        .provinces-error-notification .error-item span {
            color: #495057;
            font-size: 13px;
        }

        .provinces-error-notification .error-footer {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
        }

        .provinces-error-notification .error-footer i {
            margin-right: 6px;
            color: #0c5460;
        }

        .provinces-error-notification .error-footer small {
            color: #0c5460;
            font-weight: 500;
        }

        /* Disabled city input styling */
        .annual-leave-form #city:disabled {
            background-color: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .annual-leave-form #city:disabled::placeholder {
            color: #adb5bd;
            font-style: italic;
        }

        /* Enabled city input styling */
        .annual-leave-form #city:not(:disabled) {
            background-color: #fff;
            color: #495057;
            cursor: text;
            opacity: 1;
        }
    </style>
@endpush

@section('body')

    <div class="annual-leave-form">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Form Etc Leave</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <form action="{{ route('outsource/leave/outsource/store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <label for="username">Request by</label>
                                    <input type="text" name="username" id="username" class="form-control"
                                        value="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" readonly>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" name="nik" id="nik" class="form-control"
                                        value="{{ Auth::user()->nik }}" readonly>
                                </div>
                                <div class="col-lg-2">
                                </div>
                                <div class="col-lg-2">
                                    <label for="leave_date">Start Leave Date</label>
                                    <input type="date" name="leave_date" id="leave_date" class="form-control" required>
                                </div>
                                <div class="col-lg-2">
                                    <label for="end_leave_date">End Leave Date</label>
                                    <input type="date" name="end_leave_date" id="end_leave_date" class="form-control"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="period">Period</label>
                                    <input type="text" name="period" id="period" class="form-control"
                                        value="{{ date('Y') }}" readonly>
                                </div>
                                <div class="col-lg-3">
                                    <label for="position">Position</label>
                                    <input type="text" name="position" id="position" class="form-control"
                                        value="{{ Auth::user()->position }}" readonly>
                                </div>
                                <div class="col-lg-2">
                                </div>

                                <div class="col-lg-2">
                                    <label for="back_work">Back to Work at</label>
                                    <input type="date" name="back_work" id="back_work" class="form-control" required>
                                </div>
                                <div class="col-lg-1">
                                    <label for="perhitungan">Day</label>
                                    <input type="text" name="perhitungan" id="perhitungan" class="form-control" readonly
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="join_date">Join Date</label>
                                    <input type="date" name="join_date" id="join_date" class="form-control"
                                        value="{{ Auth::user()->join_date }}" readonly>
                                </div>
                                <div class="col-lg-3">
                                    <label for="dept_category_name">Department</label>
                                    <input type="text" name="dept_category_name" id="dept_category_name"
                                        class="form-control" value="{{ Auth::user()->getDepartment() }}" readonly>
                                </div>
                                <div class="col-lg-2">
                                </div>
                                <div class="col-lg-2">
                                    <label for="leave_category_id">Leave Category</label>
                                    <select name="leave_category_id" id="leave_category_id" class="form-control" readonly>
                                        @foreach ($leaveCategory as $category)
                                            <option value="{{ $category->id }}">{{ $category->leave_category_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-lg-1">
                                    <label for="entitlement">Max Days</label>
                                    <input type="text" name="entitlement" id="entitlement" class="form-control"
                                        readonly value="" required>
                                </div>
                                <div class="col-lg-1">
                                    <label for="remaining">Remaining</label>
                                    <input type="text" name="remaining" id="remaining" class="form-control" readonly
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ Auth::user()->email }}" readonly>
                                </div>
                                <div class="col-lg-3">
                                    <label for="provinces">Province of Destination</label>
                                    <select name="provinces" id="provinces" class="form-control" required>
                                        <option value="">Loading Provinces...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="city">City of Destination</label>
                                    <input type="text" name="city" id="city" class="form-control" required
                                        disabled placeholder="Please select province first">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="head_of_department">Head of Department</label>
                                    <select name="head_of_department" id="head_of_department" class="form-control"
                                        required>
                                        <option value="">Select HOD</option>
                                        @foreach ($headDept as $head)
                                            <option value="{{ $head->username }}">
                                                {{ $head->first_name . ' ' . $head->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="reason">Reason</label>
                                    <textarea name="reason" id="reason" class="form-control" required placeholder="Please provide reason for leave"></textarea>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-info btn-sm" id="reviewBtn">
                                        <i class="fa fa-eye"></i> Review
                                    </button>
                                    <button type="submit" class="btn btn-success btn-sm" id="submitBtn"
                                        style="display: none;">
                                        <i class="fa fa-check"></i> Apply
                                    </button>
                                    <a href="{{ route('outsource/leave/outsource') }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('bottom')
    @include('assets_script_1')
    @include('assets_script_3')
    @include('asset_select2')
@stop

@push('js')
    <script>
        // Helper function to get and validate session data
        function getSessionData() {
            var formData = sessionStorage.getItem('leaveFormData');
            if (!formData) return null;

            try {
                var data = JSON.parse(formData);
                var now = Date.now();

                if (data.expires && now > data.expires) {
                    sessionStorage.removeItem('leaveFormData');
                    return null;
                }
                return data;
            } catch (e) {
                sessionStorage.removeItem('leaveFormData');
                return null;
            }
        }

        // Global notification function
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            $('.notification').remove();

            var alertClass = 'alert-info';
            if (type === 'error') alertClass = 'alert-danger';
            if (type === 'warning') alertClass = 'alert-warning';
            if (type === 'success') alertClass = 'alert-success';

            var notification = $('<div class="notification alert ' + alertClass +
                ' alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
                '<strong>' + (type === 'error' ? 'Error' : type === 'warning' ? 'Warning' : type ===
                    'success' ? 'Success' : 'Info') + ':</strong> ' + message +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>');

            $('body').append(notification);

            // Auto remove after 5 seconds
            setTimeout(() => notification.alert('close'), 5000);
        }

        // Function to check if session data is expired
        function checkSessionExpiry() {
            var data = getSessionData();
            if (!data) {
                showNotification('Form data has expired (4 hours). Please fill the form again.', 'warning');
                return false;
            }
            return true;
        }

        // Function to clear expired session data on page load
        function clearExpiredSessionData() {
            getSessionData(); // This will automatically clear expired data
        }

        // Function to show session expiry countdown
        function showSessionCountdown() {
            var data = getSessionData();
            if (data && data.expires) {
                var now = Date.now();
                var timeLeft = data.expires - now;

                if (timeLeft > 0) {
                    var minutes = Math.floor(timeLeft / (1000 * 60));
                    var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    if (minutes < 5) { // Show warning when less than 5 minutes left
                        showNotification('Session will expire in ' + minutes + 'm ' + seconds +
                            's (4 hours total). Please save your work.',
                            'warning');
                    }
                }
            }
        }

        $(document).ready(function() {
            // Toggle review button enable/disable
            function setReviewButtonDisabled(disabled, reason) {
                var btn = $('#reviewBtn');
                btn.prop('disabled', !!disabled);
                if (disabled) {
                    btn.addClass('disabled');
                    if (reason) btn.attr('title', reason);
                } else {
                    btn.removeClass('disabled');
                    btn.removeAttr('title');
                }
            }

            // Guard based on remaining UI (red) and presence of maxDays value
            function updateReviewBtnGuard() {
                var hasMaxDays = $('#entitlement').val() !== '' && $('#entitlement').val() !== null;
                var remainingIsRed = $('#remaining').hasClass('text-danger');
                if (hasMaxDays && remainingIsRed) {
                    setReviewButtonDisabled(true, 'Remaining exceeds limit');
                } else {
                    // Only enable if no other guard left it disabled
                    if (!$('form button[type="submit"]').prop('disabled')) {
                        setReviewButtonDisabled(false);
                    }
                }
            }

            // Load EtcLeaveServices categories (includes max_days per category)
            var etcLeaveCategories = {!! json_encode(isset($leaveBalanceCategories) ? $leaveBalanceCategories : []) !!};

            function getCategoryById(categoryId) {
                if (!categoryId) return null;
                for (var i = 0; i < etcLeaveCategories.length; i++) {
                    if (String(etcLeaveCategories[i].id) === String(categoryId)) {
                        return etcLeaveCategories[i];
                    }
                }
                return null;
            }

            function getMaxDaysByCategoryId(categoryId) {
                var cat = getCategoryById(categoryId);
                if (!cat) return null;
                return (cat.max_days === null || typeof cat.max_days === 'undefined') ? null : parseFloat(cat
                    .max_days);
            }
            // Clear expired session data on page load
            clearExpiredSessionData();

            // Clean up any existing event handlers to prevent multiple registrations
            $('#leave_date, #end_leave_date, #back_work, #provinces, #reviewBtn, form').off();

            // Check session expiry every minute
            setInterval(showSessionCountdown, 60000);

            // Function to convert text to title case
            function titleCase(str) {
                return str.replace(/\w\S*/g, txt => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
            }

            // Handle provinces change to load cities
            $('#provinces').off('change').on('change', function() {
                var selectedProvince = $(this).val();
                var provinceId = $(this).find('option:selected').data('id');

                if (provinceId) {
                    // Enable city input and load cities
                    $('#city').prop('disabled', false).attr('placeholder', 'Loading cities...');
                    loadCitiesFromService(provinceId);
                } else {
                    // Disable city input if no province selected
                    $('#city').prop('disabled', true).attr('placeholder', 'Please select province first');

                    // Clear city input/select
                    if ($('#city').is('select')) {
                        // If it's a select, convert back to input
                        $('#city').replaceWith(
                            '<input type="text" name="city" id="city" class="form-control" required disabled placeholder="Please select province first">'
                        );
                    } else {
                        $('#city').val('');
                    }
                }
            });

            // Function to load cities from service
            function loadCitiesFromService(provinceId) {
                // Use existing route for cities data
                var citiesUrl = "{{ route('leave/ecek', ['id' => ':id']) }}".replace(':id', provinceId);

                // Show loading state
                $('#city').val('Loading cities...').prop('disabled', true);

                $.ajax({
                    url: citiesUrl,
                    dataType: 'json',
                    type: 'GET',
                    timeout: 10000,
                    success: function(result) {
                        // Check if result has error
                        if (result && result.error) {
                            $('#city').val('').prop('disabled', true).attr('placeholder',
                                'Error loading cities');
                            showNotification('Error: ' + result.error, 'error');
                            return;
                        }

                        // If result is array of cities
                        if (Array.isArray(result) && result.length > 0) {
                            // Create dropdown options
                            var options = '<option value="">Select City</option>';
                            result.forEach(function(city) {
                                if (city && city.name) {
                                    var cityName = titleCase(city.name);
                                    options += '<option value="' + cityName + '">' + cityName +
                                        '</option>';
                                }
                            });

                            // Replace input with select and enable it
                            $('#city').replaceWith(
                                '<select name="city" id="city" class="form-control" required>' +
                                options + '</select>');

                        } else {
                            $('#city').val('').prop('disabled', true).attr('placeholder',
                                'No cities available');
                            showNotification('No cities available for this province', 'warning');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#city').val('').prop('disabled', true).attr('placeholder',
                            'Error loading cities');

                        // Show detailed error message
                        var errorMessage = 'Error loading cities: ';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage += xhr.responseJSON.error;
                        } else {
                            errorMessage += status + ' - ' + error;
                        }

                        showNotification(errorMessage, 'error');
                    }
                });
            }

            function fetchHolidaysFromAPI(year) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: '/outsource/holidays/' + year,
                        method: 'GET',
                        timeout: 10000, // 10 second timeout
                        success: function(response) {
                            // Check if API response is successful
                            if (response.success && response.data) {
                                var data = response.data;

                                // Filter only national holidays
                                var nationalHolidays = data.filter(function(holiday) {
                                    return holiday.is_national_holiday === true;
                                });

                                // Convert to our format (MM-DD)
                                var holidays = [];
                                nationalHolidays.forEach(function(holiday) {
                                    var date = new Date(holiday.holiday_date);
                                    var month = String(date.getMonth() + 1).padStart(2,
                                        '0');
                                    var day = String(date.getDate()).padStart(2, '0');
                                    holidays.push({
                                        date: month + '-' + day,
                                        name: holiday.holiday_name,
                                        fullDate: holiday.holiday_date
                                    });
                                });

                                // Holidays loaded successfully
                                resolve(holidays);
                            } else {
                                // Fallback to service holidays if API response is invalid
                                fetchHolidaysFromService(year).then(function(serviceHolidays) {
                                    resolve(serviceHolidays);
                                }).catch(function(serviceError) {
                                    // Final fallback to minimal static holidays
                                    var minimalHolidays = getMinimalStaticHolidays(
                                        year);
                                    resolve(minimalHolidays);
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Fallback to service holidays if API fails
                            fetchHolidaysFromService(year).then(function(serviceHolidays) {
                                resolve(serviceHolidays);
                            }).catch(function(serviceError) {
                                // Final fallback to minimal static holidays
                                var minimalHolidays = getMinimalStaticHolidays(year);
                                resolve(minimalHolidays);
                            });
                        }
                    });
                });
            }

            // Fetch holidays from service (fallback)
            function fetchHolidaysFromService(year) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: '/outsource/holidays/' + year,
                        method: 'GET',
                        timeout: 5000, // 5 second timeout
                        success: function(response) {
                            if (response.success && response.data) {
                                var data = response.data;

                                // Filter only national holidays
                                var nationalHolidays = data.filter(function(holiday) {
                                    return holiday.is_national_holiday === true;
                                });

                                // Convert to our format (MM-DD)
                                var holidays = [];
                                nationalHolidays.forEach(function(holiday) {
                                    var date = new Date(holiday.holiday_date);
                                    var month = String(date.getMonth() + 1).padStart(2,
                                        '0');
                                    var day = String(date.getDate()).padStart(2, '0');
                                    holidays.push({
                                        date: month + '-' + day,
                                        name: holiday.holiday_name,
                                        fullDate: holiday.holiday_date
                                    });
                                });

                                resolve(holidays);
                            } else {
                                reject(new Error(response.message || 'Service error'));
                            }
                        },
                        error: function(xhr, status, error) {
                            reject(new Error('Service request failed: ' + error));
                        }
                    });
                });
            }

            // Get holidays from service (replaces static data)
            function getStaticHolidays(year) {
                return fetchHolidaysFromService(year);
            }

            // Minimal static holidays as final fallback (only essential holidays)
            function getMinimalStaticHolidays(year) {
                var holidays = [];

                // Only essential fixed holidays
                var essentialHolidays = [{
                        date: '01-01',
                        name: 'Tahun Baru'
                    },
                    {
                        date: '08-17',
                        name: 'Hari Kemerdekaan RI'
                    },
                    {
                        date: '12-25',
                        name: 'Hari Natal'
                    }
                ];

                essentialHolidays.forEach(function(holiday) {
                    holidays.push({
                        date: holiday.date,
                        name: holiday.name,
                        fullDate: year + '-' + holiday.date
                    });
                });

                return holidays;
            }

            // Holiday API disabled - no error notification needed

            // Function to get next working day (+1 day from given date)
            function getNextWorkingDay(dateString) {
                var date = new Date(dateString);

                // Add 1 day
                date.setDate(date.getDate() + 1);

                // Format back to YYYY-MM-DD
                var year = date.getFullYear();
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');

                return year + '-' + month + '-' + day;
            }

            // Compute end date such that number of working days from start to end equals targetDays
            function getEndDateForWorkingDays(startDateString, targetDays) {
                if (!startDateString || !targetDays || targetDays <= 0) return startDateString;
                var current = new Date(startDateString);
                var counted = 0;
                // Ensure holidays for years are loaded
                var endGuard = 1000; // safety to avoid infinite loop
                while (counted < targetDays && endGuard-- > 0) {
                    var dow = current.getDay();
                    if (dow >= 1 && dow <= 5 && !isHoliday(current)) {
                        counted++;
                        if (counted === targetDays) break;
                    }
                    current.setDate(current.getDate() + 1);
                }
                var y = current.getFullYear();
                var m = String(current.getMonth() + 1).padStart(2, '0');
                var d = String(current.getDate()).padStart(2, '0');
                return y + '-' + m + '-' + d;
            }

            // Function to validate leave balance
            function validateLeaveBalance(requestedDays, availableBalance) {
                // Clear previous validation states
                $('#perhitungan').removeClass('text-danger text-warning text-success');
                $('#remaining').removeClass('text-danger text-warning text-success');

                // Check if requested days exceed available balance
                if (requestedDays > availableBalance) {
                    // Show error state
                    $('#perhitungan').addClass('text-danger');
                    $('#remaining').addClass('text-danger');

                    // Show validation error modal
                    showBalanceValidationError(requestedDays, availableBalance);

                    // Disable form submission
                    $('form button[type="submit"]').prop('disabled', true).addClass('disabled');
                    // Disable review button via guard
                    updateReviewBtnGuard();

                } else if (requestedDays === availableBalance) {
                    // Show warning state - using all available balance
                    $('#perhitungan').addClass('text-warning');
                    $('#remaining').addClass('text-warning');

                    // Show warning modal
                    showBalanceWarning(requestedDays, availableBalance);

                    // Allow form submission
                    $('form button[type="submit"]').prop('disabled', false).removeClass('disabled');
                    updateReviewBtnGuard();

                } else {
                    // Show success state - within balance
                    $('#perhitungan').addClass('text-success');
                    $('#remaining').addClass('text-success');

                    // Allow form submission
                    $('form button[type="submit"]').prop('disabled', false).removeClass('disabled');
                    updateReviewBtnGuard();
                }
            }

            // Function to show balance validation error
            function showBalanceValidationError(requested, available) {
                var errorHtml = '<div class="balance-validation-error">' +
                    '<div class="error-header">' +
                    '<i class="fa fa-times-circle text-danger"></i> ' +
                    '<strong>Insufficient Leave Balance</strong>' +
                    '</div>' +
                    '<div class="error-body">' +
                    '<p class="mb-3">You are requesting <strong>' + requested +
                    ' days</strong> but only have <strong>' + available + ' days</strong> available.</p>' +
                    '<div class="error-details">' +
                    '<div class="error-item">' +
                    '<i class="fa fa-calendar-check text-primary"></i> ' +
                    '<span>Requested: <strong>' + requested + ' days</strong></span>' +
                    '</div>' +
                    '<div class="error-item">' +
                    '<i class="fa fa-calendar-times text-danger"></i> ' +
                    '<span>Available: <strong>' + available + ' days</strong></span>' +
                    '</div>' +
                    '<div class="error-item">' +
                    '<i class="fa fa-calculator text-warning"></i> ' +
                    '<span>Shortage: <strong>' + (requested - available) + ' days</strong></span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="error-footer">' +
                    '<i class="fa fa-exclamation-triangle text-warning"></i> ' +
                    '<small>Please reduce the number of leave days or contact HR for assistance</small>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                showCustomModal('Leave Balance Error', errorHtml);
            }

            // Function to show balance warning (using all balance)
            function showBalanceWarning(requested, available) {
                var warningHtml = '<div class="balance-warning-notification">' +
                    '<div class="warning-header">' +
                    '<i class="fa fa-exclamation-triangle text-warning"></i> ' +
                    '<strong>Using All Available Balance</strong>' +
                    '</div>' +
                    '<div class="warning-body">' +
                    '<p class="mb-3">You are using all <strong>' + available +
                    ' days</strong> of your available leave balance.</p>' +
                    '<div class="warning-details">' +
                    '<div class="warning-item">' +
                    '<i class="fa fa-info-circle text-info"></i> ' +
                    '<span>This will leave you with <strong>0 days</strong> remaining</span>' +
                    '</div>' +
                    '<div class="warning-item">' +
                    '<i class="fa fa-calendar text-primary"></i> ' +
                    '<span>Make sure this is your intended leave period</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="warning-footer">' +
                    '<i class="fa fa-check-circle text-success"></i> ' +
                    '<small>You can proceed with this leave application</small>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                showCustomModal('Leave Balance Warning', warningHtml);
            }

            // Function to load provinces from static data
            function loadProvinces() {
                // Clear loading option
                $('#provinces').empty();
                $('#provinces').append('<option value="">Select Province</option>');

                // Load static provinces directly
                loadStaticProvinces();
            }

            // Function to load static provinces with structured data from service
            function loadStaticProvinces() {
                // Get provinces data from Laravel service
                var provincesData = {!! $provincesData !!};

                // Group provinces by region for better organization
                var regions = {};
                provincesData.forEach(function(province) {
                    if (!regions[province.region]) {
                        regions[province.region] = [];
                    }
                    regions[province.region].push(province);
                });

                // Add provinces grouped by region
                Object.keys(regions).forEach(function(regionName) {
                    // Add region header (optional)
                    if (Object.keys(regions).length > 1) {
                        $('#provinces').append('<optgroup label="' + regionName + '">');
                    }

                    // Add provinces in this region
                    regions[regionName].forEach(function(province) {
                        $('#provinces').append(
                            '<option value="' + province.name + '" data-id="' + province.id +
                            '" data-region="' + province.region + '">' +
                            province.name +
                            '</option>'
                        );
                    });

                    if (Object.keys(regions).length > 1) {
                        $('#provinces').append('</optgroup>');
                    }
                });

            }

            // Custom modal function
            function showCustomModal(title, content) {
                // Remove existing modal if any
                $('#customModal').remove();

                var modalHtml = '<div id="customModal" class="modal fade" tabindex="-1" role="dialog">' +
                    '<div class="modal-dialog modal-sm" role="document">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<h5 class="modal-title">' + title + '</h5>' +
                    '<button type="button" class="close" data-dismiss="modal">' +
                    '<span>&times;</span>' +
                    '</button>' +
                    '</div>' +
                    '<div class="modal-body">' + content + '</div>' +
                    '<div class="modal-footer">' +
                    '<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Got it!</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                $('body').append(modalHtml);
                $('#customModal').modal('show');
            }

            // Cache holidays in localStorage
            function getCachedHolidays(year) {
                var cacheKey = 'holidays_' + year;
                var cached = localStorage.getItem(cacheKey);

                if (cached) {
                    try {
                        var cachedData = JSON.parse(cached);
                        var cacheDate = new Date(cachedData.timestamp);
                        var now = new Date();

                        // Cache valid for 24 hours
                        if ((now - cacheDate) < (24 * 60 * 60 * 1000)) {
                            return cachedData.holidays;
                        }
                    } catch (e) {
                        // Cache corrupted, continue to fetch from API
                    }
                }

                // Fetch from API and cache
                fetchHolidaysFromAPI(year).then(function(holidays) {
                    if (holidays.length > 0) {
                        localStorage.setItem(cacheKey, JSON.stringify({
                            holidays: holidays,
                            timestamp: new Date().toISOString()
                        }));
                    }
                }).catch(function(error) {
                    // Silently handle cache errors
                });

                // Return empty array - no holidays will be excluded if API fails
                return [];
            }

            // Function to check if a date is a holiday (supports cross-year)
            function isHoliday(date) {
                var year = date.getFullYear();
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');
                var dateString = month + '-' + day;

                // Get holidays for the specific year
                var holidays = getCachedHolidays(year);
                return holidays.some(function(holiday) {
                    return holiday.date === dateString;
                });
            }

            // Function to get holiday information for date range (supports cross-year)
            function getHolidayInfo(startDate, endDate) {
                var holidays = [];
                var currentDate = new Date(startDate);
                var endDateObj = new Date(endDate);

                // Get all years in the date range
                var years = [];
                var year = currentDate.getFullYear();
                var endYear = endDateObj.getFullYear();

                for (var y = year; y <= endYear; y++) {
                    years.push(y);
                }

                // Get holidays for all years in range
                var allHolidays = [];
                years.forEach(function(year) {
                    var yearHolidays = getCachedHolidays(year);
                    allHolidays = allHolidays.concat(yearHolidays);
                });

                while (currentDate <= endDateObj) {
                    var month = String(currentDate.getMonth() + 1).padStart(2, '0');
                    var day = String(currentDate.getDate()).padStart(2, '0');
                    var dateString = month + '-' + day;
                    var currentYear = currentDate.getFullYear();

                    var holiday = allHolidays.find(function(h) {
                        return h.date === dateString;
                    });

                    if (holiday) {
                        holidays.push({
                            date: currentDate.toLocaleDateString('id-ID'),
                            name: holiday.name,
                            fullDate: holiday.fullDate,
                            year: currentYear
                        });
                    }

                    currentDate.setDate(currentDate.getDate() + 1);
                }

                return holidays;
            }

            // Function to calculate working days (Monday-Friday only, excluding holidays) - supports cross-year
            function calculateWorkingDays(startDate, endDate) {
                var workingDays = 0;
                var currentDate = new Date(startDate);
                var endDateObj = new Date(endDate);

                // Get all years in the date range for holiday loading
                var years = [];
                var year = currentDate.getFullYear();
                var endYear = endDateObj.getFullYear();

                for (var y = year; y <= endYear; y++) {
                    years.push(y);
                }

                // Pre-load holidays for all years in range
                years.forEach(function(year) {
                    getCachedHolidays(year);
                });

                while (currentDate <= endDateObj) {
                    var dayOfWeek = currentDate.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

                    // Count only Monday (1) to Friday (5) and exclude holidays
                    if (dayOfWeek >= 1 && dayOfWeek <= 5 && !isHoliday(currentDate)) {
                        workingDays++;
                    }

                    // Move to next day
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                return workingDays;
            }

            // Function to calculate days and update form (supports cross-year)
            function calculateDays() {
                var startDate = $('#leave_date').val();
                var endDate = $('#end_leave_date').val();
                var backWork = $('#back_work').val();
                var selectedCategoryId = $('#leave_category_id').val();
                var maxDaysAllowed = getMaxDaysByCategoryId(selectedCategoryId);

                if (startDate && endDate) {
                    var start = new Date(startDate);
                    var end = new Date(endDate);

                    // Check if end date is after start date
                    if (end >= start) {
                        // Check if this is a cross-year leave
                        var startYear = start.getFullYear();
                        var endYear = end.getFullYear();

                        if (startYear !== endYear) {
                            // Pre-load holidays for all years in range
                            var years = [];
                            for (var y = startYear; y <= endYear; y++) {
                                years.push(y);
                            }
                            preloadHolidaysForYears(years);
                        }

                        // Calculate working days only (Monday-Friday)
                        var daysDiff = calculateWorkingDays(start, end);

                        // Check if back_work is same as end_leave_date
                        if (backWork && backWork === endDate) {
                            // ONLY subtract 0.5 if end_leave_date is a working day (Monday-Friday)
                            var endDayOfWeek = end.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                            if (endDayOfWeek >= 1 && endDayOfWeek <= 5) { // If end_leave_date is a weekday
                                daysDiff = daysDiff - 0.5;
                            }
                        }

                        // Enforce max_days based on selected leave category
                        if (maxDaysAllowed !== null && daysDiff > maxDaysAllowed) {
                            setReviewButtonDisabled(true, 'Requested days exceed category limit');
                            // Auto-adjust end date to fit within maxDaysAllowed working days
                            var adjustedEnd = getEndDateForWorkingDays(startDate, maxDaysAllowed);
                            $('#end_leave_date').val(adjustedEnd);

                            // Recompute back to work based on adjusted end date
                            var nextWorking = getNextWorkingDay(adjustedEnd);
                            $('#back_work').val(nextWorking);

                            // Set day and remaining according to limit
                            $('#perhitungan').val(maxDaysAllowed);
                            var balanceForMax = parseFloat($('#entitlement').val()) || 0;
                            var remainingForMax = Math.max(balanceForMax - maxDaysAllowed, 0);
                            $('#remaining').val(remainingForMax);

                            showNotification('End date adjusted to meet the category limit of ' + maxDaysAllowed +
                                ' working days.', 'warning');
                            // Allow submit (dates are now valid)
                            $('form button[type="submit"]').prop('disabled', false).removeClass('disabled');
                            // Do not return; continue to show holidays and validations with adjusted dates
                        } else {
                            // Update total days
                            $('#perhitungan').val(daysDiff);

                            // Update Remaining to follow Day result (MaxDays - Day), capped at 0
                            if (maxDaysAllowed === null) {
                                // No limit category: leave remaining blank
                                $('#remaining').val('');
                            } else {
                                var remainingCalc = Math.max(maxDaysAllowed - daysDiff, 0);
                                $('#remaining').val(remainingCalc);
                            }
                            // Update review button based on remaining state
                            updateReviewBtnGuard();
                        }

                        // Validate against available max days if limited
                        var balance = parseFloat($('#entitlement').val()) || 0;

                        // Show holiday information
                        var holidays = getHolidayInfo(start, end);
                        if (holidays.length > 0) {
                            var holidayAlert = '📅 Holidays during Leave Period:\n\n';

                            // Group holidays by year if cross-year
                            if (startYear !== endYear) {
                                var holidaysByYear = {};
                                holidays.forEach(function(holiday) {
                                    if (!holidaysByYear[holiday.year]) {
                                        holidaysByYear[holiday.year] = [];
                                    }
                                    holidaysByYear[holiday.year].push(holiday);
                                });

                                Object.keys(holidaysByYear).forEach(function(year) {
                                    holidayAlert += '📆 ' + year + ':\n';
                                    holidaysByYear[year].forEach(function(holiday) {
                                        holidayAlert += '• ' + holiday.name + ' (' + holiday.date +
                                            ')\n';
                                    });
                                    holidayAlert += '\n';
                                });
                            } else {
                                holidays.forEach(function(holiday) {
                                    holidayAlert += '• ' + holiday.name + ' (' + holiday.date + ')\n';
                                });
                            }

                            holidayAlert += '✅ Holidays are not counted in leave calculations.';

                            // Show holiday info (only for few holidays to avoid spam)
                            if (holidays.length <= 5) {
                                showNotification(holidayAlert, 'info');
                            }
                        }

                        // Validate days against available balance (only if limited)
                        if (maxDaysAllowed !== null) {
                            validateLeaveBalance(daysDiff, balance);
                            // Enable or disable review button
                            setReviewButtonDisabled(daysDiff > balance, 'Requested days exceed category limit');
                        } else {
                            // No limit: ensure submit enabled
                            $('form button[type="submit"]').prop('disabled', false).removeClass('disabled');
                            setReviewButtonDisabled(false);
                        }
                    } else {
                        showNotification('End date must be after start date', 'warning');
                        $('#end_leave_date').val('');
                        $('#perhitungan').val('');
                        $('#remaining').val($('#entitlement').val());
                    }
                }
            }

            // Function to pre-load holidays for multiple years
            function preloadHolidaysForYears(years) {
                years.forEach(function(year) {
                    getCachedHolidays(year);
                });
            }

            // Load holidays for current year and next year on page load (for cross-year support)
            var currentYear = new Date().getFullYear();
            var nextYear = currentYear + 1;

            preloadHolidaysForYears([currentYear, nextYear]);

            // Load provinces from API
            loadProvinces();

            // Auto-fill date fields with cascade effect
            $('#leave_date').off('change').on('change', function() {
                var leaveDate = $(this).val();
                var endLeaveDate = $('#end_leave_date').val();
                var backWork = $('#back_work').val();

                // Auto-fill end_leave_date if empty
                if (leaveDate && !endLeaveDate) {
                    $('#end_leave_date').val(leaveDate);
                }

                // Auto-fill back_work if empty (next working day from leave_date)
                if (leaveDate && !backWork) {
                    var nextWorkingDay = getNextWorkingDay(leaveDate);
                    $('#back_work').val(nextWorkingDay);
                }

                // Calculate days
                calculateDays();
                updateReviewBtnGuard();
            });

            // Auto-fill back_work when end_leave_date changes
            $('#end_leave_date').off('change').on('change', function() {
                var endLeaveDate = $(this).val();

                if (endLeaveDate) {
                    // Calculate next working day (+1 day from end_leave_date)
                    var nextWorkingDay = getNextWorkingDay(endLeaveDate);

                    // Auto-fill back_work with next working day
                    $('#back_work').val(nextWorkingDay);
                }

                // Calculate days
                calculateDays();
                updateReviewBtnGuard();
            });

            // Also recalc remaining instantly when category changes (since MaxDays changes)
            $('#leave_category_id').on('change', function() {
                var selectedCategoryId = $('#leave_category_id').val();
                var maxDaysAllowed = getMaxDaysByCategoryId(selectedCategoryId);
                var currentDays = parseFloat($('#perhitungan').val()) || 0;
                if (maxDaysAllowed === null) {
                    $('#remaining').val('');
                } else {
                    $('#remaining').val(Math.max(maxDaysAllowed - currentDays, 0));
                }
            });

            // Auto calculate when back_work changes
            $('#back_work').off('change').on('change', function() {
                calculateDays();
                updateReviewBtnGuard();
            });

            // Reflect selected category meta (value, max_days) and set hidden input
            function updateSelectedCategoryMeta() {
                var selectedCategoryId = $('#leave_category_id').val();
                var cat = getCategoryById(selectedCategoryId);
                var metaEl = $('#leaveCategoryMeta');
                if (cat) {
                    $('#leave_category_value').val(cat.value || '');
                    var maxTxt = (cat.max_days === null || typeof cat.max_days === 'undefined') ? 'No limit' : (cat
                        .max_days + ' days');
                    metaEl.text('Value: ' + (cat.value || '-') + ' | Max days: ' + maxTxt);
                    // Reflect max days into entitlement input as requested
                    if (cat.max_days === null || typeof cat.max_days === 'undefined') {
                        $('#entitlement').val('');
                    } else {
                        $('#entitlement').val(cat.max_days);
                    }
                } else {
                    $('#leave_category_value').val('');
                    metaEl.text('');
                    $('#entitlement').val('');
                }
            }

            // Init and on-change hook for category select
            updateSelectedCategoryMeta();
            $('#leave_category_id').off('change').on('change', function() {
                updateSelectedCategoryMeta();
                // Recalculate in case limit changed by category
                calculateDays();
                var selectedCategoryId = $('#leave_category_id').val();
                var maxDaysAllowed = getMaxDaysByCategoryId(selectedCategoryId);
                var currentDays = parseFloat($('#perhitungan').val()) || 0;
                if (maxDaysAllowed !== null && currentDays > maxDaysAllowed) {
                    setReviewButtonDisabled(true, 'Requested days exceed category limit');
                } else {
                    setReviewButtonDisabled(false);
                }
                updateReviewBtnGuard();
            });

            // Form validation
            $('form').off('submit').on('submit', function(e) {
                var startDate = $('#leave_date').val();
                var endDate = $('#end_leave_date').val();
                var totalDays = $('#perhitungan').val();
                var selectedCategoryId = $('#leave_category_id').val();
                var maxDaysAllowed = getMaxDaysByCategoryId(selectedCategoryId);
                var reason = $('#reason').val();
                var province = $('#provinces').val();
                var city = $('#city').val();

                // Check if all required fields are filled
                if (!startDate || !endDate || !totalDays || !reason) {
                    e.preventDefault();
                    showNotification('Please fill in all required fields', 'warning');
                    return false;
                }

                // Check if province is selected
                if (!province) {
                    e.preventDefault();
                    showNotification('Please select a province first', 'warning');
                    return false;
                }

                // Check if city is selected (only if it's a dropdown)
                if ($('#city').is('select') && !city) {
                    e.preventDefault();
                    showNotification('Please select a city', 'warning');
                    return false;
                }

                // Check if city is filled (if it's a text input)
                if ($('#city').is('input') && !city) {
                    e.preventDefault();
                    showNotification('Please enter a city name', 'warning');
                    return false;
                }

                // Check if total days is valid
                if (totalDays <= 0) {
                    e.preventDefault();
                    showNotification('Total days must be greater than 0', 'warning');
                    return false;
                }

                // Check if requesting more days than available
                var balance = parseFloat($('#entitlement').val()) || 0;
                if (parseInt(totalDays) > balance) {
                    e.preventDefault();
                    showNotification('You cannot request more days than your available balance (' +
                        balance +
                        ' days)', 'error');
                    return false;
                }

                // Check against category max_days from EtcLeaveServices
                var totalDaysNum = parseFloat(totalDays);
                if (maxDaysAllowed !== null && totalDaysNum > maxDaysAllowed) {
                    e.preventDefault();
                    showNotification('Requested days exceed the category limit (' + maxDaysAllowed +
                        ' days). Please adjust your dates.', 'error');
                    return false;
                }

                // Confirm submission
                if (!confirm('Are you sure you want to submit this leave application?')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Set minimum date to today
            var today = new Date().toISOString().split('T')[0];
            $('#leave_date').attr('min', today);
            $('#end_leave_date').attr('min', today);
            $('#back_work').attr('min', today);

            // Handle review button
            $('#reviewBtn').off('click').on('click', function() {
                // Block when disabled
                if ($('#reviewBtn').prop('disabled')) {
                    showNotification('Requested days exceed category limit. Please adjust your dates.',
                        'warning');
                    return false;
                }
                if (validateFormForReview()) {
                    // Check if session data is still valid before saving
                    var sessionData = getSessionData();

                    if (sessionData) {
                        // Session exists, check if expired
                        if (checkSessionExpiry()) {
                            saveFormDataToSession();
                            window.location.href = '{{ route('outsource/leave/outsource/review') }}';
                        } else {
                            showNotification('Session expired (4 hours). Please fill the form again.',
                                'error');
                        }
                    } else {
                        // No existing session, save new data
                        saveFormDataToSession();
                        window.location.href = '{{ route('outsource/leave/outsource/review') }}';
                    }
                }
            });

            // Function to validate form for review
            function validateFormForReview() {
                var startDate = $('#leave_date').val();
                var endDate = $('#end_leave_date').val();
                var totalDays = $('#perhitungan').val();
                var reason = $('#reason').val();
                var province = $('#provinces').val();
                var city = $('#city').val();
                var hod = $('#head_of_department').val();

                // Check if all required fields are filled
                if (!startDate || !endDate || !totalDays || !reason || !province || !city || !hod) {
                    showNotification('Please fill in all required fields before reviewing', 'warning');
                    return false;
                }

                // Check if total days is valid
                if (totalDays <= 0) {
                    showNotification('Total days must be greater than 0', 'warning');
                    return false;
                }

                // Check if requesting more days than available
                var balance = parseFloat($('#entitlement').val()) || 0;
                if (parseInt(totalDays) > balance) {
                    showNotification('You cannot request more days than your available balance (' + balance +
                        ' days)', 'error');
                    return false;
                }

                return true;
            }

            // Function to save form data to session storage
            function saveFormDataToSession() {
                var formData = {
                    id: {{ auth()->user()->id }},
                    username: $('#username').val(),
                    nik: $('#nik').val(),
                    email: $('#email').val(),
                    position: $('#position').val(),
                    department: $('#dept_category_name').val(),
                    joinDate: $('#join_date').val(),
                    leaveDate: $('#leave_date').val(),
                    endLeaveDate: $('#end_leave_date').val(),
                    backWork: $('#back_work').val(),
                    period: $('#period').val(),
                    category: $('#leave_category_id').val(),
                    balance: $('#entitlement').val(),
                    remaining: $('#remaining').val(),
                    days: $('#perhitungan').val(),
                    province: $('#provinces').val(),
                    city: $('#city').val(),
                    hod: $('#head_of_department').val(),
                    reason: $('#reason').val(),
                    timestamp: Date.now(), // Add timestamp for expiry
                    expires: Date.now() + (4 * 60 * 60 * 1000) // 4 hours from now                    
                };

                try {
                    sessionStorage.setItem('leaveFormData', JSON.stringify(formData));
                } catch (error) {
                    showNotification('Error saving form data. Please try again.', 'error');
                }
            }

        });
    </script>
@endpush
