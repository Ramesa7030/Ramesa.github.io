<?php
header('Content-Type: application/json');

// Configuration
$recipient = "ramesa.suppliers@outlook.com"; // Your email address
$subject = "New Contact Form Submission from Ramesa Suppliers Website";

// Validate input
$errors = [];
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$industry = filter_input(INPUT_POST, 'industry', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Basic validation
if (empty($name)) {
    $errors[] = "Name is required";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required";
}

if (empty($message)) {
    $errors[] = "Message is required";
}

// If validation errors, return them
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Build email content
$email_content = "Name: $name\n";
$email_content .= "Email: $email\n";
$email_content .= "Industry: " . ($industry ? $industry : "Not specified") . "\n\n";
$email_content .= "Message:\n$message\n";

// Build email headers
$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
if (mail($recipient, $subject, $email_content, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Server error: Unable to send email']);
}