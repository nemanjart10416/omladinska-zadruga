<?php

class TokenManager {
    /**
     * Generates a unique confirmation token.
     *
     * @return string The generated token.
     * @throws Exception
     */
    public static function generateToken(): string {
        return bin2hex(random_bytes(32)); // Generate a random token
    }

    /**
     * Stores the confirmation token in the database.
     *
     * @param int $userId The user ID associated with the token.
     * @param string $token The confirmation token.
     * @param DateTimeImmutable $expirationDate The expiration date of the token.
     * @return bool True on success, false on failure.
     */
    public static function storeToken(int $userId, string $token, DateTimeImmutable $expirationDate): bool {
        try {
            // Prepare the SQL query with placeholders for user ID, token, and expiration date
            $sql = "INSERT INTO confirmation_tokens (user_id, token, expiration_date) VALUES (?, ?, ?)";
            $params = [$userId,$token,$expirationDate->format('Y-m-d H:i:s')];

            return Connection::setP($sql, $params);
        } catch (Exception $e) {
            // Handle exception (log the error, return false, etc.)
            return false;
        }
    }

    /**
     * Validates the confirmation token.
     *
     * @param string $token The confirmation token.
     * @return bool True if the token is valid, false otherwise.
     */
    public static function validateToken(string $token): bool {
        try {
            // Prepare the SQL query with placeholders for user ID and token
            $sql = "SELECT * FROM confirmation_tokens WHERE token = ? AND expiration_date > NOW()";
            $params = [$token];

            $result = Connection::getP($sql, $params);

            return $result->num_rows > 0;
        } catch (Exception $e) {
            // Handle exception (log the error, return false, etc.)
            return false;
        }
    }

    /**
     * Retrieves the user ID associated with the given token from the database.
     *
     * @param string $token The confirmation token.
     * @return int|false The user ID if the token is valid, or false if the token is not found.
     */
    public static function getUserIdByToken(string $token): int|false {
        // Prepare the SQL query with a placeholder for the token
        $sql = "SELECT user_id FROM confirmation_tokens WHERE token = ?";
        $params = [$token];

        $result = Connection::getP($sql, $params);
        $userId = $result->fetch_assoc()["user_id"];

        // If a valid row is found, return the user ID, otherwise return false
        if ($userId) {
            return $userId;
        } else {
            return false;
        }
    }

    /**
     * Deletes the confirmation token from the database.
     *
     * @param int $userId The user ID associated with the token.
     * @return bool True on success, false on failure.
     */
    public static function deleteToken(int $userId): bool {
        try {
            // Prepare the SQL query with a placeholder for user ID
            $sql = "DELETE FROM confirmation_tokens WHERE user_id = ?";
            $params = [$userId];

            return Connection::setP($sql, $params);
        } catch (Exception $e) {
            // Handle exception (log the error, return false, etc.)
            return false;
        }
    }

    /**
     * Sends a confirmation email to the user with the confirmation token link.
     *
     * @param string $email The user's email address.
     * @param string $token The confirmation token.
     * @return bool True on success, false on failure.
     */
    public static function sendConfirmationEmail(string $email, string $token): bool {
        // Example code to send an email (using PHP's mail function)
        /*
        $to = $email;
        $subject = 'Account Confirmation';
        $message = "Click the following link to confirm your account: http://example.com/confirm?token=$token";
        $headers = 'From: webmaster@example.com';

        // Send email
        $success = mail($to, $subject, $message, $headers);

        // Return true on success, false on failure
        return $success;*/

        $file = fopen("email_confirm.txt", "w") or die("Unable to open file!");
        $txt = "Click the following link to confirm your account: https://127.0.0.1/omladinska-zadruga/email_confirm?token=$token";
        fwrite($file, $txt);
        fclose($file);

        return true;
    }
}