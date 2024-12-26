<?php
session_start();
include("./include/db-connection.php");

class PasswordReset {
    private $conn;
    public $error_message = '';

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function sendCode($email) {
        if ($this->isCodeRecentlyGenerated($email)) {
            return json_encode(['error' => 'A code was already generated in the last 24 hours. Please try again later.']);
        }

        $validation_code = rand(100000, 999999);
        if ($this->insertCode($email, $validation_code)) {
            $_SESSION['generated_code'] = $validation_code;
            return json_encode(['code' => $validation_code]);
        } else {
            return json_encode(['error' => 'Failed to generate code. Please try again']);
        }
    }

    public function verifyCode($email, $entered_code) {
        $latest_code = $this->getLatestCode($email);

        if ($latest_code && $entered_code == $latest_code) {
            header("Location: reset-password.php");
            exit();
        } else {
            return "Invalid or expired verification code!";
        }
    }
    
    private function isCodeRecentlyGenerated($email) {
        $query = "SELECT created_at FROM forgot_password 
                    WHERE user_id = (SELECT id FROM users WHERE email = ?) 
                    ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $created_at = $row['created_at'];
            $time_diff = time() - strtotime($created_at);
            return $time_diff < 24 * 3600; 
        }
        return false;
    }

    private function insertCode($email, $code) {
        $query = "INSERT INTO forgot_password (user_id, code, created_at) VALUES ((SELECT id FROM users WHERE email = ?), ?, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('si', $email, $code);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    private function getLatestCode($email) {
        $query = "SELECT code FROM forgot_password WHERE user_id = (SELECT id FROM users WHERE email = ?) 
                    AND created_at > NOW() - INTERVAL 24 HOUR 
                    ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? $row['code'] : null;
    }
}

$error_message = '';
$passwordReset = new PasswordReset($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['reset_email'];

    if (isset($_POST['send_code'])) {
        echo $passwordReset->sendCode($email);
        exit();
    }

    if (isset($_POST['verify_code'])) {
        $entered_code = $_POST['code'];
        $error_message = $passwordReset->verifyCode($email, $entered_code);
    }
}
?>

<!doctype html>

<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="horizontal-menu-template-no-customizer"
  data-style="light"> 
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Forgot Password Basic - Pages | Materialize - Material Design HTML Admin Template</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/remixicon/remixicon.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="position-relative">
      <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
        <div class="authentication-inner py-6">
          <!-- Logo -->
          <div class="card p-md-7 p-1">
            <!-- Forgot Password -->
            <div class="app-brand justify-content-center mt-5">
              <a href="index.html" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <span style="color: #666cff">
                    <svg width="268" height="150" viewBox="0 0 38 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path
                        d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z"
                        fill="currentColor" />
                      <path
                        d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z"
                        fill="url(#paint0_linear_2989_100980)"
                        fill-opacity="0.4" />
                      <path
                        d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z"
                        fill="currentColor" />
                      <path
                        d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                        fill="currentColor" />
                      <path
                        d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                        fill="url(#paint1_linear_2989_100980)"
                        fill-opacity="0.4" />
                      <path
                        d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z"
                        fill="currentColor" />
                      <defs>
                        <linearGradient
                          id="paint0_linear_2989_100980"
                          x1="5.36642"
                          y1="0.849138"
                          x2="10.532"
                          y2="24.104"
                          gradientUnits="userSpaceOnUse">
                          <stop offset="0" stop-opacity="1" />
                          <stop offset="1" stop-opacity="0" />
                        </linearGradient>
                        <linearGradient
                          id="paint1_linear_2989_100980"
                          x1="5.19475"
                          y1="0.849139"
                          x2="10.3357"
                          y2="24.1155"
                          gradientUnits="userSpaceOnUse">
                          <stop offset="0" stop-opacity="1" />
                          <stop offset="1" stop-opacity="0" />
                        </linearGradient>
                      </defs>
                    </svg>
                  </span>
                </span>
                <span class="app-brand-text demo text-heading fw-semibold">Materialize</span>
              </a>
            </div>
            <!-- /Logo -->
            <div class="card-body mt-1">
            <h4 class="mb-1">Verify Code 💬</h4>
            <p class="mb-5">Send code to verify your email for reset password</p>

              <form method="post" id="verify-code-form" novalidate>
                <div class="form-group">
                    <label>Code:</label>
                    <input name="code" id="code" type="text" class="form-control" placeholder="Enter verification code">
                    <span class="error text-danger"></span>
                </div>
                <div style="margin-top: 10px;">
                    <button type="button" name="send_code" id="sendCode" class="btn btn-primary d-grid w-100">Send Code</button>
                </div>
                <div style="margin-top: 10px;">
                    <button type="submit" name="verify_code" class="btn btn-primary d-grid w-100">Verify Code</button>
                </div>
                <?php if (!empty($error_message)) echo "<div class='text-danger'>$error_message</div>"; ?>
            </form>
            <div class="text-center" style="margin-top: 10px;">
                <a href="index.php" class="d-flex align-items-center justify-content-center">
                  <i class="ri-arrow-left-s-line scaleX-n1-rtl ri-20px me-1_5"></i>
                  Back to login
                </a>
              </div>
            </div>
          </div>
          <!-- /Forgot Password -->
          <img
            alt="mask"
            src="assets/img/illustrations/auth-basic-forgot-password-mask-light.png"
            class="authentication-image d-none d-lg-block"
            data-app-light-img="illustrations/auth-basic-forgot-password-mask-light.png"
            data-app-dark-img="illustrations/auth-basic-forgot-password-mask-dark.png" />
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/pages-auth.js"></script>
    <script src='assets/js/script.js'></script>
    <script>
        $(document).ready(function() {
            $('#sendCode').click(function() {
                console.log('code send');
                
                $.post('', { send_code: true }, function(response) {
                    var data = JSON.parse(response);
                    if (data.code) {
                        alert(' verification code: ' + data.code);
                    } else if (data.error) {
                        alert(data.error);
                    }
                });
            });
        });
    </script>
  </body>
</html>
