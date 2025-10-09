@extends('layout')

@section('title')
    Leave Application Review
@stop

@section('top')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .review-container {
            /* background: #f8f9fa; */
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .review-section {
            background: whitesmoke;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .review-section h4 {
            color: #2c3e50;
            border-bottom: 1px solid #3498db;
            padding-bottom: 6px;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .review-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-label {
            font-weight: 500;
            color: #6c757d;
            min-width: 80px;
            font-size: 13px;
        }

        .review-value {
            color: #2c3e50;
            text-align: right;
            flex: 1;
            font-size: 13px;
            font-weight: 500;
        }

        .review-highlight {
            background: #e8f5e8;
            border-left: 4px solid #27ae60;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .review-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .review-error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .btn-review {
            padding: 12px 24px;
            margin: 5px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-confirm {
            background: #27ae60;
            color: white;
            border: none;
        }

        .btn-confirm:hover {
            background: #229954;
            color: white;
            text-decoration: none;
        }

        .btn-edit {
            background: #3498db;
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background: #2980b9;
            color: white;
            text-decoration: none;
        }

        .btn-cancel {
            background: #95a5a6;
            color: white;
            border: none;
        }

        .btn-cancel:hover {
            background: #7f8c8d;
            color: white;
            text-decoration: none;
        }

        .balance-info {
            background: linear-gradient(135deg, #3e4e96 0%, #21379b 100%);
            color: white;
            border-radius: 4px;
            padding: 10px;
            margin: 8px 0;
        }

        .balance-info h5 {
            margin: 0 0 6px 0;
            font-weight: 600;
            font-size: 14px;
        }

        .balance-item {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .balance-label {
            font-weight: 500;
            font-size: 12px;
        }

        .balance-value {
            font-weight: 600;
            font-size: 12px;
        }

        .date-range {
            background: #f8f9fa;
            border-radius: 3px;
            padding: 8px;
            margin: 6px 0;
        }

        .date-range .date-item {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .date-label {
            font-weight: 500;
            color: #6c757d;
            font-size: 12px;
        }

        .date-value {
            font-weight: 600;
            color: #495057;
            font-size: 12px;
        }

        .summary-card {
            background: linear-gradient(135deg, #3e4e96 0%, #21379b 100%);
            color: white;
            border-radius: 6px;
            padding: 12px;
            margin: 10px 0;
            text-align: center;
        }

        .summary-card h3 {
            margin: 0 0 6px 0;
            font-weight: 700;
            font-size: 16px;
        }

        .summary-card .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 13px;
        }

        .summary-label {
            font-weight: 500;
        }

        .summary-value {
            font-weight: 700;
        }

        .action-buttons {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .action-buttons .btn {
            margin: 0 5px;
            padding: 6px 12px;
            font-size: 13px;
        }

        /* Ultra compact spacing */
        .review-section {
            margin-bottom: 8px;
        }

        .review-section:last-child {
            margin-bottom: 0;
        }

        /* Ultra compact grid */
        @media (max-width: 768px) {

            .col-md-8,
            .col-md-4 {
                margin-bottom: 10px;
            }

            .col-sm-6 {
                margin-bottom: 8px;
            }

            .review-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 3px 0;
            }

            .review-value {
                text-align: left;
                margin-top: 2px;
            }

            .balance-item,
            .date-item {
                flex-direction: column;
                margin: 2px 0;
            }

            .action-buttons .btn {
                display: block;
                margin: 6px auto;
                width: 160px;
                padding: 5px 10px;
                font-size: 12px;
            }

            .summary-card {
                padding: 10px;
            }

            .summary-card h3 {
                font-size: 14px;
            }

            .summary-card .summary-item {
                font-size: 12px;
                margin: 2px 0;
            }
        }

        @media (max-width: 576px) {
            .review-container {
                padding: 8px;
            }

            .review-section {
                padding: 8px;
            }

            .balance-info {
                padding: 8px;
            }

            .action-buttons {
                padding: 8px;
                margin: 10px 0;
            }

            .review-label {
                min-width: 60px;
                font-size: 12px;
            }

            .review-value {
                font-size: 12px;
            }
        }
    </style>
@endpush

@section('body')
    <div class="review-container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">
                    <i class="fa fa-check-circle text-success"></i>
                    Leave Application Review
                </h2>
            </div>
        </div>

        <!-- Ultra Compact Summary -->
        <div class="summary-card">
            <h3><i class="fa fa-calendar-check"></i> Leave Application</h3>
            <div class="summary-item">
                <span class="summary-label">Days:</span>
                <span class="summary-value" id="summaryDays">0 days</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Period:</span>
                <span class="summary-value" id="summaryPeriod">-</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Balance:</span>
                <span class="summary-value" id="summaryRemaining">0 days</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Leave Category:</span>
                <span class="summary-value" id="summaryLeaveCategory">-</span>
            </div>
        </div>

        <!-- Ultra Compact Layout -->
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
                <!-- Combined Applicant & Leave Info -->
                <div class="review-section">
                    <h4><i class="fa fa-user"></i> Applicant & Leave Details</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="review-item">
                                <span class="review-label">Name:</span>
                                <span class="review-value" id="reviewName">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">Position:</span>
                                <span class="review-value" id="reviewPosition">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">Department:</span>
                                <span class="review-value" id="reviewDepartment">-</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="date-range">
                                <div class="date-item">
                                    <span class="date-label">Start:</span>
                                    <span class="date-value" id="reviewStartDate">-</span>
                                </div>
                                <div class="date-item">
                                    <span class="date-label">End:</span>
                                    <span class="date-value" id="reviewEndDate">-</span>
                                </div>
                                <div class="date-item">
                                    <span class="date-label">Back:</span>
                                    <span class="date-value" id="reviewBackWork">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Combined Destination & Reason -->
                <div class="review-section">
                    <h4><i class="fa fa-map-marker-alt"></i> Destination & Reason</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="review-item">
                                <span class="review-label">Province:</span>
                                <span class="review-value" id="reviewProvince">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">City:</span>
                                <span class="review-value" id="reviewCity">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">HOD:</span>
                                <span class="review-value" id="reviewHod">-</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="review-item">
                                <span class="review-label">Reason:</span>
                                <span class="review-value" id="reviewReason"
                                    style="text-align: left; white-space: pre-wrap; font-style: italic;">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance Sidebar -->
            <div class="col-md-4">
                <div class="balance-info">
                    <h5><i class="fa fa-wallet"></i> Balance</h5>
                    <div class="balance-item">
                        <span class="balance-label">Current:</span>
                        <span class="balance-value" id="reviewBalance">0 days</span>
                    </div>
                    <div class="balance-item">
                        <span class="balance-label">Requested:</span>
                        <span class="balance-value" id="reviewRequested">0 days</span>
                    </div>
                    <div class="balance-item">
                        <span class="balance-label">Remaining:</span>
                        <span class="balance-value" id="reviewRemaining">0 days</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validation Messages -->
        <div id="validationMessages"></div>

        <!-- Action Buttons -->
        <div class="action-buttons">

            <form action="{{ route('outsource/leave/outsource/store') }}" method="post" id="leaveForm">
                {{ csrf_field() }}
                <!-- Hidden fields will be populated by JavaScript -->
                <input type="hidden" name="username" id="formUsername">
                <input type="hidden" name="nik" id="formNik">
                <input type="hidden" name="email" id="formEmail">
                <input type="hidden" name="position" id="formPosition">
                <input type="hidden" name="dept_category_name" id="formDepartment">
                <input type="hidden" name="join_date" id="formJoinDate">
                <input type="hidden" name="leave_date" id="formLeaveDate">
                <input type="hidden" name="end_leave_date" id="formEndLeaveDate">
                <input type="hidden" name="back_work" id="formBackWork">
                <input type="hidden" name="period" id="formPeriod">
                <input type="hidden" name="leave_category_id" id="formLeaveCategory">
                <input type="hidden" name="entitlement" id="formEntitlement">
                <input type="hidden" name="remaining" id="formRemaining">
                <input type="hidden" name="perhitungan" id="formPerhitungan">
                <input type="hidden" name="provinces" id="formProvinces">
                <input type="hidden" name="city" id="formCity">
                <input type="hidden" name="head_of_department" id="formHod">
                <input type="hidden" name="reason" id="formReason">
                <input type="hidden" name="emp_stat" value="outsource">
            </form>

            <button type="button" class="btn btn-confirm btn-review" id="submitBtn">
                <i class="fa fa-check"></i> Confirm & Submit Application
            </button>

            <a href="javascript:history.back()" class="btn btn-edit btn-review">
                <i class="fa fa-edit"></i> Edit Application
            </a>
            <a href="{{ route('outsource/leave/outsource') }}" class="btn btn-cancel btn-review">
                <i class="fa fa-times"></i> Cancel
            </a>
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
        // Notification system
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

        // Function to get category name by ID (hardcoded for now)
        function getCategoryName(categoryId) {
            var categories = {
                '1': 'Annual Leave',
                '2': 'Sick Leave',
                '3': 'Personal Leave',
                '4': 'Emergency Leave'
            };
            return categories[categoryId] || 'Unknown Category';
        }

        // Function to get department name (hardcoded for now)
        function getDepartmentName() {
            return 'Department Name'; // Will be replaced with actual data
        }

        $(document).ready(function() {
            // Get form data from session storage or URL parameters
            var formData = getFormData();

            // Populate review with form data
            populateReview(formData);

            // Populate hidden form fields with sessionStorage data
            populateFormFields(formData);

            // Validate form data
            validateFormData(formData);

            // Handle submit button click
            $('#submitBtn').on('click', function() {
                if (!validateFormData(formData)) {
                    showNotification('Please fill in all required fields', 'warning');
                    return false;
                }

                // Submit the form programmatically
                $('#leaveForm').submit();
            });
        });

        function getFormData() {
            // Try to get data from session storage first
            var storedData = sessionStorage.getItem('leaveFormData');
            if (storedData) {
                return JSON.parse(storedData);
            }

            // Fallback to URL parameters
            var urlParams = new URLSearchParams(window.location.search);
            return {
                username: urlParams.get('username') || '{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}',
                nik: urlParams.get('nik') || '{{ Auth::user()->nik }}',
                email: urlParams.get('email') || '{{ Auth::user()->email }}',
                position: urlParams.get('position') || '{{ Auth::user()->position }}',
                department: urlParams.get('department') || 'Department ID: {{ Auth::user()->dept_category_id }}',
                joinDate: urlParams.get('joinDate') || '{{ Auth::user()->join_date }}',
                leaveDate: urlParams.get('leaveDate') || '',
                endLeaveDate: urlParams.get('endLeaveDate') || '',
                backWork: urlParams.get('backWork') || '',
                period: urlParams.get('period') || '{{ date('Y') }}',
                category: urlParams.get('category') || '1',
                balance: urlParams.get('balance') || '0',
                remaining: urlParams.get('remaining') || '0',
                days: urlParams.get('days') || '0',
                province: urlParams.get('province') || '',
                city: urlParams.get('city') || '',
                hod: urlParams.get('hod') || '',
                reason: urlParams.get('reason') || ''
            };
        }

        function populateReview(data) {
            // Applicant Information
            $('#reviewName').text(data.username || '-');
            $('#reviewNik').text(data.nik || '-');
            $('#reviewEmail').text(data.email || '-');
            $('#reviewPosition').text(data.position || '-');
            $('#reviewDepartment').text(data.department || 'Department ID: {{ Auth::user()->dept_category_id }}');
            $('#reviewJoinDate').text(formatDate(data.joinDate) || '-');

            // Leave Details - Get category name from database
            var categoryName = getCategoryName(data.category || '1');
            $('#reviewCategory').text(categoryName);
            $('#reviewPeriod').text(data.period || '-');
            $('#reviewStartDate').text(formatDate(data.leaveDate) || '-');
            $('#reviewEndDate').text(formatDate(data.endLeaveDate) || '-');
            $('#reviewBackWork').text(formatDate(data.backWork) || '-');

            // Balance Information
            $('#reviewBalance').text(data.balance + ' days');
            $('#reviewRequested').text(data.days + ' days');
            $('#reviewRemaining').text(data.remaining + ' days');

            // Destination Information
            $('#reviewProvince').text(data.province || '-');
            $('#reviewCity').text(data.city || '-');

            // Approval Information
            $('#reviewHod').text(data.hod || '-');

            // Reason
            $('#reviewReason').text(data.reason || '-');

            // Summary
            $('#summaryDays').text(data.days + ' days');
            $('#summaryPeriod').text(formatDateRange(data.leaveDate, data.endLeaveDate));
            $('#summaryRemaining').text(data.remaining + ' days');
            // Summary - Get category name from database
            var summaryCategoryName = getCategoryName(data.category || '1');
            $('#summaryLeaveCategory').text(summaryCategoryName);
        }

        function populateFormFields(data) {
            // Populate hidden form fields with sessionStorage data
            $('#formUsername').val(data.username || '');
            $('#formNik').val(data.nik || '');
            $('#formEmail').val(data.email || '');
            $('#formPosition').val(data.position || '');
            $('#formDepartment').val(data.department || '');
            $('#formJoinDate').val(data.joinDate || '');
            $('#formLeaveDate').val(data.leaveDate || '');
            $('#formEndLeaveDate').val(data.endLeaveDate || '');
            $('#formBackWork').val(data.backWork || '');
            $('#formPeriod').val(data.period || '');
            $('#formEntitlement').val(data.balance || '');
            $('#formRemaining').val(data.remaining || '');
            $('#formPerhitungan').val(data.days || '');
            $('#formProvinces').val(data.province || '');
            $('#formCity').val(data.city || '');
            $('#formHod').val(data.hod || '');
            $('#formReason').val(data.reason || '');
            $('#formLeaveCategory').val(data.category || '');
        }

        function validateFormData(data) {
            var isValid = true;
            var messages = [];

            // Check required fields
            if (!data.leaveDate) {
                messages.push('Start date is required');
                isValid = false;
            }

            if (!data.endLeaveDate) {
                messages.push('End date is required');
                isValid = false;
            }

            if (!data.reason) {
                messages.push('Reason is required');
                isValid = false;
            }

            if (!data.province) {
                messages.push('Province is required');
                isValid = false;
            }

            if (!data.city) {
                messages.push('City is required');
                isValid = false;
            }

            if (!data.hod) {
                messages.push('Head of Department is required');
                isValid = false;
            }

            // Check date logic
            if (data.leaveDate && data.endLeaveDate) {
                var startDate = new Date(data.leaveDate);
                var endDate = new Date(data.endLeaveDate);

                if (endDate < startDate) {
                    messages.push('End date must be after start date');
                    isValid = false;
                }
            }

            // Check balance
            var balance = parseFloat(data.balance) || 0;
            var requested = parseFloat(data.days) || 0;

            if (requested > balance) {
                messages.push('Requested days (' + requested + ') cannot exceed available balance (' + balance + ')');
                isValid = false;
            }

            // Display validation messages
            displayValidationMessages(messages, isValid);

            return isValid;
        }

        function displayValidationMessages(messages, isValid) {
            var container = $('#validationMessages');
            container.empty();

            if (messages.length > 0) {
                var alertClass = isValid ? 'review-warning' : 'review-error';
                var alertIcon = isValid ? 'fa-exclamation-triangle' : 'fa-times-circle';
                var alertTitle = isValid ? 'Warning' : 'Error';

                var html = '<div class="' + alertClass + '">';
                html += '<h5><i class="fa ' + alertIcon + '"></i> ' + alertTitle + '</h5>';
                html += '<ul>';
                messages.forEach(function(message) {
                    html += '<li>' + message + '</li>';
                });
                html += '</ul>';
                html += '</div>';

                container.html(html);
            }
        }

        function submitLeaveApplication(data) {
            // Show loading state
            $('#confirmSubmit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            // Create form data
            var formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('username', data.username);
            formData.append('nik', data.nik);
            formData.append('email', data.email);
            formData.append('position', data.position);
            formData.append('dept_category_name', data.department);
            formData.append('join_date', data.joinDate);
            formData.append('leave_date', data.leaveDate);
            formData.append('end_leave_date', data.endLeaveDate);
            formData.append('back_work', data.backWork);
            formData.append('period', data.period);
            formData.append('leave_category_id', data.category); // Annual
            formData.append('entitlement', data.balance);
            formData.append('remaining', data.remaining);
            formData.append('perhitungan', data.days);
            formData.append('provinces', data.province);
            formData.append('city', data.city);
            formData.append('head_of_department', data.hod);
            formData.append('reason', data.reason);
            formData.append('emp_stat', 'outsource');

            // Submit form
            $.ajax({
                url: '{{ route('outsource/leave/outsource/store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if (response.success) {
                        alert('Leave application submitted successfully!');
                        window.location.href = '{{ route('outsource/leave/outsource') }}';
                    } else {
                        alert('Error: ' + response.message);
                        $('#confirmSubmit').prop('disabled', false).html(
                            '<i class="fa fa-check"></i> Confirm & Submit Application');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error submitting application: ' + error);
                    $('#confirmSubmit').prop('disabled', false).html(
                        '<i class="fa fa-check"></i> Confirm & Submit Application');
                }
            });
        }

        function formatDate(dateString) {
            if (!dateString) return '-';

            var date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function formatDateRange(startDate, endDate) {
            if (!startDate || !endDate) return '-';

            var start = new Date(startDate);
            var end = new Date(endDate);

            return start.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }) +
                ' - ' +
                end.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
        }
    </script>
@endpush
