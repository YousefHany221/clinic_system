<?php
session_start();
require_once 'Validator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        // Register validation rules
        $rules = [
            'name' => "required|max:255|min:3",
            'email' => "required|email",
            'phone' => "required|number|min:11|max:11",
            'password' => "required|min:8|max:50|confirmed",
        ];

        $validator = new Validator($_POST);

        foreach ($rules as $field => $rule) {
            if (isset($_POST[$field])) {
                $validator->validate($_POST[$field], $field, $rule);
            }
        }

        if ($validator->has_errors()) {
            $_SESSION['errors'] = $validator->get_errors();
            $_SESSION['old'] = $_POST;
            header('Location: ../index.php?page=register');
            exit;
        } else {
            // Here you would normally save to database
            $_SESSION['success'] = "Registration successful!";
            header('Location: ../index.php?page=login');
            exit;
        }
    } elseif ($action === 'login') {
        // Login validation rules
        $rules = [
            'email' => "required|email",
            'password' => "required|min:8",
        ];

        $validator = new Validator($_POST);

        foreach ($rules as $field => $rule) {
            if (isset($_POST[$field])) {
                $validator->validate($_POST[$field], $field, $rule);
            }
        }

        if ($validator->has_errors()) {
            $_SESSION['errors'] = $validator->get_errors();
            $_SESSION['old'] = $_POST;
            header('Location: ../index.php?page=login');
            exit;
        } else {
            // Here you would normally check credentials against database
            // For now, we'll simulate a successful login
            $_SESSION['user'] = [
                'email' => $_POST['email'],
                'name' => 'User Name', // This would come from database
                'logged_in' => true
            ];
            $_SESSION['success'] = "Login successful!";
            header('Location: ../index.php?page=home');
            exit;
        }
    }
} else {
    die("405 | Method Not Allowed");
}
