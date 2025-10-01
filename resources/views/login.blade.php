<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="{{ asset('assets/iconic2.png') }}">
    <title>Login - WIS</title>
    @include('assets_css_1')
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500&family=Russo+One&display=swap" rel="stylesheet">

<style type="text/css">
    * {
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #3298f8 0%, #b3cbf3 100%);
        background-attachment: fixed;
        margin: 0;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        -webkit-font-smoothing: antialiased;
        position: relative;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 80%, rgba(30, 60, 114, 0.4) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(42, 82, 152, 0.3) 0%, transparent 50%);
        z-index: 1;
    }

    .container {
        position: relative;
        z-index: 2;
    }

    ::selection {
        background: rgba(102, 126, 234, 0.3);
    }

    .modern-login-card {
        background: rgba(255, 255, 255, 0.40);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow:
            0 10px 20px rgba(0, 0, 0, 0.1),
            0 0 0 1px rgba(255, 255, 255, 0.2);
        padding: 40px;
        max-width: 400px;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .modern-login-card:hover {
        transform: translateY(-5px);
        box-shadow:
            0 30px 60px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.3);
    }

    .panel-body {
        margin-top: 0;
    }

    .layout-login {
        margin-top: 0%;
    }

    .img-logo {
        width: 100%;
        height: 100%;
        display: flex;
        margin-left: auto;
        margin-right: auto;
        margin-top: -40%;
        margin-bottom: -60px;
    }

    /* Responsive untuk tablet */
    @media (max-width: 768px) {
        .img-logo {
            width: 90%;
            height: auto;
            margin-top: -30%;
            margin-bottom: -50px;
        }
    }

    /* Responsive untuk mobile */
    @media (max-width: 480px) {
        .img-logo {
            width: 90%;
            height: auto;
            margin-top: -25%;
            margin-bottom: -40px;
        }
    }

    /* Responsive untuk mobile kecil */
    @media (max-width: 360px) {
        .img-logo {
            width: 90%;
            height: auto;
            margin-top: -20%;
            margin-bottom: -30px;
        }
    }

    /* Responsive body untuk mobile */
    @media (max-width: 768px) {
        body {
            padding: 10px;
            align-items: center;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 5px;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
    }

    .modern-input-group {
        position: relative;
        margin-bottom: 25px;

    }

    .modern-input-group .input-group {
        display: flex;
        align-items: stretch;
        width: 100%;
    }

    .modern-input-group .input-group-addon {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white;
        border-radius: 12px 0 0 12px;
        padding: 15px 18px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        height: auto;

    }

    .modern-input-group .input-group-addon-eye {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white;
        padding: 15px 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        height: auto;
        cursor: pointer;
        min-width: 35px;
        width: 35px;
        border-radius: 0 12px 12px 0;
    }

    .modern-input-group .input-group-addon-eye:hover {
        background: linear-gradient(135deg, #2a5298, #3d6bb3);
        transform: scale(1.05);
    }

    .modern-input-group .input-group-addon-eye i {
        font-size: 14px;
    }

    .modern-input-group .form-control {
        border: 2px solid rgba(30, 60, 114, 0.3);
        border-left: none;
        border-right: none;
        border-radius: 0;
        padding: 15px 20px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        min-height: 50px;
        height: auto;
        line-height: 1.4;
        position: relative;
        border-radius: 0 12px 12px 0;
    }

    /* Eye icon animations */
    .input-group-addon-eye {
        animation: eyeBlink 3s infinite, gentlePulse 2s infinite;
    }

    @keyframes eyeBlink {

        0%,
        90%,
        100% {
            transform: scale(1);
        }

        5% {
            transform: scale(0.8);
        }

        10% {
            transform: scale(1);
        }
    }

    @keyframes gentlePulse {

        0%,
        100% {
            box-shadow: 0 2px 8px rgba(30, 60, 114, 0.2);
        }

        50% {
            box-shadow: 0 2px 12px rgba(30, 60, 114, 0.3);
        }
    }

    .modern-input-group .form-control:focus {
        border-color: #1e3c72;
        box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.2);
        background: white;
        outline: none;
        transform: scale(1.02);
        transition: all 0.3s ease;
    }

    .modern-input-group .form-control::placeholder {
        color: #999;
        font-weight: 400;

    }

    .modern-btn {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border: none;
        border-radius: 12px;
        padding: 15px 30px;
        font-size: 16px;
        font-weight: 600;
        color: white;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.4);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(30, 60, 114, 0.5);
        background: linear-gradient(135deg, #2a5298 0%, #3d6bb3 100%);
    }

    .modern-btn:active {
        transform: translateY(0);
        box-shadow: 0 5px 15px rgba(30, 60, 114, 0.4);
    }

    .modern-alert {
        border-radius: 12px;
        border: none;
        padding: 15px 40px 15px 10px;
        margin-bottom: 20px;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        position: relative;
        font-size: 14px;
        line-height: 1.4;
    }

    /* Desktop Styles */
    @media (min-width: 769px) {
        .modern-alert {
            padding: 15px 40px 15px 10px;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 25px;
        }
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .modern-alert {
            padding: 12px 35px 12px 10px;
            font-size: 13px;
            line-height: 1.3;
            margin-bottom: 15px;
            border-radius: 12px;
        }
    }

    /* Small Mobile Styles */
    @media (max-width: 480px) {
        .modern-alert {
            padding: 10px 30px 10px 12px;
            font-size: 12px;
            line-height: 1.2;
            margin-bottom: 12px;
            border-radius: 6px;
        }
    }

    .modern-alert.alert-danger {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
    }

    .modern-alert.alert-info {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }

    /* Close Button Styling */
    .modern-alert .close {
        color: white;
        opacity: 0.8;
        font-size: 18px;
        font-weight: bold;
        line-height: 1;
        text-shadow: none;
        transition: all 0.3s ease;
        padding: 0;
        margin: 0;
        background: none;
        border: none;
        position: absolute;
        top: 8px;
        right: 12px;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .modern-alert .close:after {
        content: '×';
        font-size: 20px;
        font-weight: bold;
    }

    .modern-alert .close:hover,
    .modern-alert .close:focus {
        color: white;
        opacity: 1;
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .modern-alert .close:before {
        content: '';
    }

    /* Responsive Close Button */
    @media (min-width: 769px) {
        .modern-alert .close {
            width: 28px;
            height: 28px;
            top: 10px;
            right: 15px;
            font-size: 20px;
        }
    }

    @media (max-width: 768px) {
        .modern-alert .close {
            width: 22px;
            height: 22px;
            top: 6px;
            right: 10px;
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .modern-alert .close {
            width: 20px;
            height: 20px;
            top: 5px;
            right: 8px;
            font-size: 14px;
        }
    }
</style>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <!-- Logo Section -->
                <div class="row">
                    <div class="col-lg-12">
                        <img class="img-logo" src="{{ asset('assets/WIS_LOGO_2025_V2.png') }}" alt="WIS Logo">
                    </div>
                </div>

                <!-- Modern Login Card -->
                <div class="modern-login-card">
                    <!-- Alert Messages -->
                    <div class="layout-login">
                        @if (Session::get('getError'))
                            <div class="alert alert-danger modern-alert alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {!! Session::get('getError') !!}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger modern-alert alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {!! implode('', $errors->all('<li>:message</li>')) !!}
                            </div>
                        @endif

                        @if (Session::has('message'))
                            <div class="alert alert-info modern-alert alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {!! Session::get('message') !!}
                            </div>
                        @endif
                    </div>

                    <!-- Login Form -->
                    <div class="panel-body">
                        {!! Form::open(['route' => 'login', 'role' => 'form', 'autocomplete' => 'off']) !!}
                        <fieldset>
                            @if ($errors->has('username'))
                                <div class="form-group modern-input-group has-error">
                                @else
                                    <div class="form-group modern-input-group">
                            @endif
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" name="username" id="username" class="form-control"
                                    placeholder="Username" required>
                            </div>
                    </div>

                    @if ($errors->has('password'))
                        <div class="form-group modern-input-group has-error">
                        @else
                            <div class="form-group modern-input-group">
                    @endif
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        {!! Form::input('password', 'password', old('password'), [
                            'class' => 'form-control password-field',
                            'placeholder' => 'Password',
                            'maxlength' => 30,
                            'required' => true,
                            'id' => 'password-field',
                        ]) !!}
                        <span class="input-group-addon-eye"><i class="fa fa-eye"></i></span>
                    </div>
                </div>

                {!! Form::submit('Sign In', ['class' => 'modern-btn']) !!}
                </fieldset>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    @include('assets_script_1')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus to username input
            const usernameField = document.getElementById('username');
            if (usernameField) {
                usernameField.focus();
            }

            const passwordField = document.getElementById('password-field');
            const eyeIcon = document.querySelector('.input-group-addon-eye i');

            if (passwordField && eyeIcon) {
                // Add click event to the eye icon
                document.querySelector('.input-group-addon-eye').addEventListener('click', function() {
                    if (passwordField.type === 'password') {
                        // Show password
                        passwordField.type = 'text';
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');

                        // Add visual feedback
                        this.style.background = 'linear-gradient(135deg, #e74c3c, #c0392b)';

                        // Show temporary message
                        showPasswordStatus('Password visible', 'warning');
                    } else {
                        // Hide password
                        passwordField.type = 'password';
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');

                        // Reset visual feedback
                        this.style.background = '';

                        // Show temporary message
                        showPasswordStatus('Password hidden', 'info');
                    }
                });
            }

            // Function to show password status
            function showPasswordStatus(message, type) {
                // Remove existing status if any
                const existingStatus = document.querySelector('.password-status');
                if (existingStatus) {
                    existingStatus.remove();
                }

                // Create status element
                const status = document.createElement('div');
                status.className = `password-status modern-alert alert-${type}`;
                status.textContent = message;
                status.style.cssText = `
                    position: absolute;
                    top: -35px;
                    right: 0;
                    padding: 8px 12px;
                    font-size: 12px;
                    border-radius: 6px;
                    z-index: 1000;
                    animation: fadeInOut 2s ease-in-out;
                `;

                // Add animation
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes fadeInOut {
                        0% { opacity: 0; transform: translateY(10px); }
                        20% { opacity: 1; transform: translateY(0); }
                        80% { opacity: 1; transform: translateY(0); }
                        100% { opacity: 0; transform: translateY(-10px); }
                    }
                `;
                document.head.appendChild(style);

                // Add to password field container
                const passwordContainer = passwordField.closest('.modern-input-group');
                passwordContainer.style.position = 'relative';
                passwordContainer.appendChild(status);

                // Remove after animation
                setTimeout(() => {
                    if (status.parentNode) {
                        status.remove();
                    }
                }, 2000);
            }
        });
    </script>
</body>

</html>
