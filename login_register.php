<?php
session_start();
require_once 'config.php';

function redirectToIndex() {
    header("Location: index.php");
    exit();
}

if (isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $role     = trim($_POST['role']);

    $validRoles = ['admin', 'user'];

    if (!$email || empty($name) || empty($password) || !in_array($role, $validRoles)) {
        $_SESSION['register_error'] = 'Please fill in all fields correctly.';
        $_SESSION['active_form']    = 'register';
        redirectToIndex();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $checkEmail = $stmt->get_result();
    $stmt->close();

    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered.';
        $_SESSION['active_form']    = 'register';
    } else {
        $stmt = $conn->prepare("INSERT INTO user (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            $_SESSION['register_success'] = 'Registration successful. Please log in.';
        } else {
            error_log($stmt->error);
            $_SESSION['register_error'] = 'Registration failed. Please try again.';
        }

        $stmt->close();
    }

    redirectToIndex();
}

if (isset($_POST['login'])) {
    $email    = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email || empty($password)) {
        $_SESSION['login_error'] = 'Please enter a valid email and password.';
        $_SESSION['active_form'] = 'login';
        redirectToIndex();
    }

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['name']  = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role']  = $user['role'];

            $redirectPage = ($user['role'] === 'admin') ? 'admin_page.php' : 'user_page.php';
            header("Location: $redirectPage");
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password.';
    $_SESSION['active_form'] = 'login';
    redirectToIndex();
}
?>