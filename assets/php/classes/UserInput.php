<?php

class UserInput {
    /**
     * @param string $data
     * @return string
     */
    public static function sanitize(string $data): string {
        // Remove whitespace from the beginning and end of the string
        $data = trim($data);

        // Strip HTML and PHP tags from the string
        $data = strip_tags($data);

        // Convert special characters to HTML entities to prevent XSS attacks
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        // Remove NULL bytes and other special characters
        $data = str_replace(chr(0), '', $data);
        $data = str_replace("%00", "", $data);
        $data = str_replace("%0", "", $data);



        return str_replace("\0", "", $data);
    }
}