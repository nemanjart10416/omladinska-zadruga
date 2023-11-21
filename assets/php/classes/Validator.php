<?php

/**
 *
 */
class Validator {
    /**
     * @var array
     */
    protected array $data = [];
    /**
     * @var array
     */
    protected array $rules = [];
    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules) {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * @return void
     */
    public static function usageExample() {
        $data = [
            'name_pass' => 'John Doe',
            'name_fail' => '',
            'aa' => 'aaaaaa',
            'email_pass' => 'valid@example.com',
            'email_fail' => 'invalidemail',
            'age_pass' => '30',
            'age_fail' => 'thirty',
            'website_pass' => 'https://example.com',
            'website_fail' => 'invalidwebsite',
            'date_pass' => '2023-08-12',
            'date_fail' => 'notadate',
            'alphaField_pass' => 'abcd',
            'alphaField_fail' => '1234',
            'alphaNumericField_pass' => '123abc',
            'alphaNumericField_fail' => 'abc123@',
            'regexField_pass' => 'ABC123',
            'regexField_fail' => 'invalid',
            'uniqueField_pass' => 'unique@example.com',
            'uniqueField_fail' => 'duplicate@example.com',
            'inField_pass' => 'option1',
            'inField_fail' => 'option4',
            'notInField_pass' => 'option3',
            'notInField_fail' => 'option1',
            'password_pass' => 'password123',
            'password_fail' => 'short',
            'password_confirmation_pass' => 'password123',
            'password_confirmation_fail' => 'mismatch',
            'customDate_pass' => '2023-08-12',
            'customDate_fail' => 'invaliddate',
            'wrong_int1' => '1.1',
            'wrong_int2' => 1.1,
            'wrong_int3' => true,
        ];

        $rules = [
            'wrong_int1' => 'integer',
            'wrong_int2' => 'integer',
            'wrong_int3' => 'integer',
            'name_pass' => 'required|min:3|max:50',
            'name_fail' => 'required|min:3|max:50',
            'email_pass' => 'required|email',
            'email_fail' => 'required|email',
            'aa' => 'max|3',
            'age_pass' => 'numeric',
            'age_fail' => 'numeric',
            'website_pass' => 'url',
            'website_fail' => 'url',
            'date_pass' => 'date',
            'date_fail' => 'date',
            'alphaField_pass' => 'alpha',
            'alphaField_fail' => 'alpha',
            'alphaNumericField_pass' => 'alpha_numeric',
            'alphaNumericField_fail' => 'alpha_numeric',
            'regexField_pass' => 'regex:/^[A-Z0-9]+$/',
            'regexField_fail' => 'regex:/^[A-Z0-9]+$/',
            'uniqueField_pass' => 'unique:users,email',
            'uniqueField_fail' => 'unique:users,email',
            'inField_pass' => 'in:option1,option2,option3',
            'inField_fail' => 'in:option1,option2,option3',
            'notInField_pass' => 'not_in:option1,option2',
            'notInField_fail' => 'not_in:option1,option2',
            'password_pass' => 'required|confirmed|min:8',
            'password_fail' => 'required|confirmed|min:8',
            'password_confirmation_pass' => 'confirmed',
            'password_confirmation_fail' => 'confirmed',
            'customDate_pass' => 'date_format:Y-m-d',
            'customDate_fail' => 'date_format:Y-m-d',
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
    }

    /**
     * @return bool
     */
    public function validate(): bool {
        foreach ($this->rules as $field => $rule) {
            $rules = explode('|', $rule);

            foreach ($rules as $singleRule) {
                $this->applyRule($field, $singleRule);
            }
        }

        return empty($this->errors);
    }

    /**
     * @param string $field
     * @param string $rule
     * @return void
     */
    protected function applyRule(string $field, string $rule): void {
        $parts = explode(':', $rule);
        $ruleName = $parts[0];
        $ruleValue = isset($parts[1]) ? $parts[1] : null;

        if ($ruleName === 'required') { //OK
            if (empty($this->data[$field])) {
                $this->addError($field, 'Field is required.');
            }
        } elseif ($ruleName === 'min') { //OK
            /* Required: Ensures that a field must be provided and not empty. */
            if (isset($this->data[$field]) && strlen($this->data[$field]) < $ruleValue) {
                $this->addError($field, "Field must be at least $ruleValue characters.");
            }
        }elseif ($ruleName === 'max') { //OK
            /* Max Length: Requires a field's value to have a maximum length. */
            if (isset($this->data[$field]) && strlen($this->data[$field]) > $ruleValue) {
                $this->addError($field, "Field must not exceed $ruleValue characters.");
            }
        } elseif ($ruleName === 'email') { //OK
            /* Email: Validates that a field's value is a valid email address. */
            if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                $this->addError($field, 'Invalid email format.');
            }
        } elseif ($ruleName === 'numeric') { //OK
            /* Numeric: Validates that a field's value is a number. */
            if (!is_numeric($this->data[$field])) {
                $this->addError($field, 'Field must be numeric.');
            }
        } elseif ($ruleName === 'integer') { //OK
            /* Integer: Validates that a field's value is an integer. */
            if (!filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
                $this->addError($field, 'Field must be an integer.');
            }
        } elseif ($ruleName === 'url') { // OK
            /* URL: Validates that a field's value is a valid URL. */
            if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
                $this->addError($field, 'Invalid URL format.');
            }
        } elseif ($ruleName === 'date') { //OK
            /* Date: Validates that a field's value is a valid date. */
            if (isset($this->data[$field]) && !strtotime($this->data[$field])) {
                $this->addError($field, 'Invalid date format.');
            }
        } elseif ($ruleName === 'alpha') { //OK
            /* Alpha: Validates that a field's value contains only letters. */
            if (isset($this->data[$field]) && !ctype_alpha($this->data[$field])) {
                $this->addError($field, 'Field must contain only letters.');
            }
        } elseif ($ruleName === 'alpha_numeric') {//OK
            /* Alpha-Numeric: Validates that a field's value contains only letters and numbers. */
            if (isset($this->data[$field]) && !ctype_alnum($this->data[$field])) {
                $this->addError($field, 'Field must contain only letters and numbers.');
            }
        } elseif ($ruleName === 'regex') {//TODO
            /* Regex: Validates a field's value using a regular expression pattern. */
            if (isset($this->data[$field]) && !preg_match($ruleValue, $this->data[$field])) {
                $this->addError($field, 'Field format is invalid.');
            }
        } elseif ($ruleName === 'unique') {//TODO
            /* Unique: Validates that a field's value is unique within a database table. */
            /*if (isset($this->data[$field]) && $this->isUnique($field, $this->data[$field], $ruleValue)) {
                $this->addError($field, 'Field value must be unique.');
            }*/
        } elseif ($ruleName === 'in') {//OK
            /* In: Validates that a field's value is in a predefined list of values. */
            if (isset($this->data[$field]) && !in_array($this->data[$field], explode(',', $ruleValue))) {
                $this->addError($field, 'Invalid selection.');
            }
        } elseif ($ruleName === 'not_in') {//OK
            /* Not In: Validates that a field's value is not in a predefined list of values. */
            if (isset($this->data[$field]) && in_array($this->data[$field], explode(',', $ruleValue))) {
                $this->addError($field, 'Invalid selection.');
            }
        } elseif ($ruleName === 'confirmed') {//TODO
            /* Confirmed: Validates that a field's value matches a confirmation field (e.g., password confirmation). */
            $confirmedField = "{$field}_confirmation";
            if (isset($this->data[$field]) && isset($this->data[$confirmedField]) && $this->data[$field] !== $this->data[$confirmedField]) {
                $this->addError($field, 'Confirmation does not match.');
            }
        } elseif ($ruleName === 'date_format') {//TODO
            /* Date Format: Validates that a field's value matches a specific date format. */
            if (isset($this->data[$field]) && !DateTime::createFromFormat($ruleValue, $this->data[$field])) {
                $this->addError($field, 'Invalid date format.');
            }
        }

        // Add more rule implementations here
    }

    /**
     * @param string $field
     * @param string $message
     * @return void
     */
    protected function addError(string $field, string $message): void {
        $this->errors[$field][] = $message;
    }

    /**
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }
}