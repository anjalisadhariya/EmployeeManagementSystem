<?php
session_start();
include('./db-connection.php');

class UserAuth {
    private $conn;
    private $email;
    private $password;
    private $hashed_password;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $password, $remember = false) {
        $this->email = $email;
        $this->password = $password;
        $this->hashed_password = md5($password);

        $response = ['success' => false, 'error' => 'Invalid email or password'];

        if ($this->authenticateUser()) {
            if ($remember == true) {
                $this->setRememberMeCookies();
            }
            $response['success'] = true;
            unset($response['error']);
        }

        return json_encode($response);
    }

    private function authenticateUser() {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $this->email, $this->hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            return true;
        }

        return false;
    }

    private function setRememberMeCookies() {
        setcookie("user_email", $this->email, time() + (30 * 24 * 60 * 60), "/");
        setcookie("user_password", $this->password, time() + (30 * 24 * 60 * 60), "/");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $auth = new UserAuth($conn);
    echo $auth->login($email, $password, $remember);
}
?>
