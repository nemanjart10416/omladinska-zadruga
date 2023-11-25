<?php

/**
 *
 */
class PasswordResetTokenManager{
    /**
     * Generates a token which will be used for password reset.
     *
     * @return string
     * @throws Exception
     */
    public static function generateToken(): string {
        return bin2hex(random_bytes(32)); // Generate a random token
    }

    /**
     * Stores token for password reset in database.
     *
     * @param int $userId
     * @param string $token
     * @param DateTimeImmutable $expirationDate
     *
     * @return bool
     */
    public static function storeToken(string $userEmail, string $token, DateTimeImmutable $expirationDate): bool {
        try {
            // Store the token in the database with the associated user ID and expiration date
            $sql = "INSERT INTO password_reset (id, user_email, token, expiration_date) VALUES (NULL, ?, ?, ?)";
            $params = [$userEmail, $token, $expirationDate->format('Y-m-d H:i:s')];

            return Connection::setP($sql, $params);
        } catch (Exception $e) {
            // Handle exception (log the error, return false, etc.)
            return false;
        }
    }

    /**
     * Sends password reset toekn via email.
     *
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public static function sendPasswordResetEmail(string $email, string $token): bool {
        $resetLink = "https://example.com/forgot-password?token=$token";
        $subject = 'Password Reset';
        $message = "Click the following link to reset your password: $resetLink";

        // Send email
        return (new Email)->sendMail($email, $subject, $email, $message);
    }

    /**
     * Validates the password reset token. If token is valid, returns email address for that token.
     *
     * @param string $token The password reset token.
     * @return bool|string True if the token is valid, false otherwise.
     */
    public static function validateToken(string $token): bool|string {
        try {
            // Prepare the SQL query with placeholders for user email and token
            $sql = "SELECT * FROM password_reset WHERE token = ? AND expiration_date > NOW()";
            $params = [$token];

            $result = Connection::getP($sql, $params);

            if($result->num_rows > 0) {
                return $result->fetch_assoc()["user_email"];
            }else{
                return false;
            }
        } catch (Exception $e) {
            // Handle exception (log the error, return false, etc.)
            return false;
        }
    }

    /**
     * Deletes the password reset token from the database.
     *
     * @param string $token The reset token to be deleted.
     * @return bool True on success, false on failure.
     */
    public static function deleteToken(string $token): bool {
        try {
            // Prepare the SQL query with a placeholder for the token
            $sql = "DELETE FROM password_reset WHERE token = ?";
            $params = [$token];

            return Connection::setP($sql, $params);
        } catch (Exception $e) {
            // Handle exception (log the error, return false, etc.)
            return false;
        }
    }
}