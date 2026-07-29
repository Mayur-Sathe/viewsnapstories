<?php
require_once __DIR__ . '/../inc/bootstrap.php';

function handle_contact() {
    rate_limit_check();
    $input = $GLOBALS['_INPUT'] ?? [];

    $validator = new Validator();
    if (!$validator->validate($input, [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email',
        'subject' => 'required|min:3|max:200',
        'message' => 'required|min:10|max:2000',
    ])) {
        json_response(['success' => false, 'error' => 'Validation failed', 'errors' => $validator->errors()], 400);
    }

    $message = [
        'id' => 'msg_' . bin2hex(random_bytes(8)),
        'name' => htmlspecialchars($input['name'], ENT_QUOTES, 'UTF-8'),
        'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
        'subject' => htmlspecialchars($input['subject'], ENT_QUOTES, 'UTF-8'),
        'message' => htmlspecialchars($input['message'], ENT_QUOTES, 'UTF-8'),
        'created_at' => time(),
        'ip_hash' => md5($_SERVER['REMOTE_ADDR'] ?? ''),
        'read' => false,
    ];

    $contactsFile = __DIR__ . '/../data/contacts.json';
    $contacts = [];
    if (file_exists($contactsFile)) {
        $contacts = json_decode(file_get_contents($contactsFile), true) ?? [];
    }
    $contacts[] = $message;
    file_put_contents($contactsFile, json_encode($contacts, JSON_PRETTY_PRINT), LOCK_EX);

    // ponytail: email notification omitted for MVP
    // If needed later, add mail() or SMTP here

    json_response(['success' => true, 'message' => 'Thank you for reaching out. We\'ll get back to you soon.']);
}
