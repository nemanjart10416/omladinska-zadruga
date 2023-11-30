<?php
include_once("assets/php/funkcije.php");

/*
// Example of creating an instance of EmployerProfile using the constructor
$employerProfile = new EmployerProfile(
    null,
    5, // Replace with the actual user ID
    "ABC Company",
    "123456789",
    "987654321",
    "company@example.com",
    "1234567890",
    "IT Services",
    "123 Main Street"
);

// Call the createProfile method
$profileCreationResult = $employerProfile->createProfile();

// Check the result
if ($profileCreationResult) {
    echo "Employer profile created successfully!";
} else {
    echo "Error creating employer profile.";
}

try {
    echo "<br>".EmployerProfile::getProfileById(1)."<br>";
} catch (Exception $e) {
}

try {
    echo "<br>".EmployerProfile::getProfileByUserId(5)."<br>";
} catch (Exception $e) {
}
*/
/*
// Test data
$employerProfileData = [
    'user_id' => 5,
    'company_title' => "ABC Company",
    'company_pib' => "1234567/89",
    'company_mb' => "987654321",
    'company_email' => "company@example.com",
    'company_phone' => "1234567890",
    'company_work_field' => "IT Services",
    'company_address' => "123 Main Street"
];

// Check if PIB is taken
if (EmployerProfile::isPibTaken($employerProfileData['company_pib'])) {
    echo "PIB is already taken.";
} else {
    echo "PIB is available.";
}

// Check if MB is taken
if (EmployerProfile::isMbTaken($employerProfileData['company_mb'])) {
    echo "MB is already taken.";
} else {
    echo "MB is available.";
}

// Check if email is taken
if (EmployerProfile::isEmailTaken($employerProfileData['company_email'])) {
    echo "Email is already taken.";
} else {
    echo "Email is available.";
}

// Check if phone is taken
if (EmployerProfile::isPhoneTaken($employerProfileData['company_phone'])) {
    echo "Phone is already taken.";
} else {
    echo "Phone is available.";
}*/
/*
$e = EmployerProfile::getProfileByUserId(5);

$e->deleteProfile();
*/
/*
$employerProfile = new EmployerProfile(
    null,
    5, // Replace with the actual user ID
    "ABC Company",
    "123456789",
    "987654321",
    "company@example.com",
    "1234567890",
    "IT Services",
    "123 Main Street"
);

// Call the createProfile method
$profileCreationResult = $employerProfile->createProfile();

$employerProfile = new EmployerProfile(
    null,
    5, // Replace with the actual user ID
    "ABC Company2",
    "1234567892",
    "9876543212",
    "comp2any@example.com",
    "12345267890",
    "IT Services2",
    "123 Main Street2"
);

// Call the createProfile method
$profileCreationResult = $employerProfile->createProfile();
*/

$data = [
    'pib_pass' => '1234567891',
    'pib_fail' => '123456789',
    'mb_pass' => '987654321',
    'mb_fail' => '98765432',
    // ... other test data ...
];

$rules = [
    'pib_pass' => 'pib_format',
    'pib_fail' => 'pib_format',
    'mb_pass' => 'mb_format',
    'mb_fail' => 'mb_format',
    // ... other rules ...
];

$validator = new Validator($data, $rules);

if ($validator->validate()) {
    echo "Validation successful.";
} else {
    $errors = $validator->getErrors();

    foreach ($errors as $field => $fieldErrors) {
        foreach ($fieldErrors as $error) {
            echo Message::danger("Error in $field: $error");
        }
    }
}
?>