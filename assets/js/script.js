   
    $(document).ready(function() {
        $('#formAuthentication').submit(function(e) {
            e.preventDefault();
            $('#error').text('');
            $('.error').text('');

            const email = $('#email').val();
            const password = $('#password').val();
            const remember = $('#remember').is(':checked');
            let hasError = false;

            if (!email) {
                $('#email').next().text('Email is required');
                hasError = true;
            } else if (!validateEmail(email)) {
                $('#email').next().text('Invalid email format');
                hasError = true;
            }

            if (!password) {
                $('#password').next().text('Password is required');
                hasError = true;
            }

            if (hasError) return;

            $.ajax({
                url: './include/login-process.php',
                type: 'POST',
                data: { email, password, remember },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'dashboard.php';
                    } else {
                        $('#error').text(response.error);
                    }
                },
                error: function() {
                    $('#error').text('An error occurred. Please try again later.');
                }
            });
        });

        function validateEmail(email) {
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return pattern.test(email);
        }
    });

    $('#forgot-password-form').submit(function(e) {
        
        e.preventDefault(); 
        $('.error').text(''); 

        var email = $('#email').val().trim();
        var hasError = false;

        if (!email) {
            $('#email').next().text('Email is required');
            $("#email").focus();
            hasError = true;
        } else {                
            if (!validateEmail(email)) {
                $("#email").next().text("Please enter a valid email");
                $("#email").focus();
                hasError = true;
            }
        }

        if (!hasError) {
            this.submit(); 
        }
    });

    $('#verify-code-Form').submit(function(e) {
        console.log('submit');
        
        e.preventDefault();
        $('.error').text(''); 

        var code = $('#code').val().trim();
        var hasError = false;

        if (!code) {
            $('#code').next().text('Verification code is required');
            hasError = true;
        }

        if (!hasError) {
            this.submit(); 
        }
    });

    $('#reset-password-form').submit(function(e) {
        e.preventDefault(); 
        $('.error').text(''); 

        var password = $('#password').val().trim();
        var confirmPassword = $('#confirm_password').val().trim();
        var hasError = false;

        if (!password) {
            $('#password').next().text('Password is required');
            hasError = true;
        } else if (password !== confirmPassword) {
            $('#confirm_password').next().text('Passwords do not match');
            hasError = true;
        }

        if (!hasError) {
            this.submit(); 
        }
    });

   
    function validateEmail(email) {
        var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
    }
