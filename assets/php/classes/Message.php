<?php

class Message {
    /**
     * Generate a success alert message.
     *
     * @param string $txt The message text.
     * @return string The formatted success alert HTML.
     */
    public static function success(string $txt): string {
        return self::generateAlert('success', $txt);
    }

    /**
     * Generate a danger alert message.
     *
     * @param string $txt The message text.
     * @return string The formatted danger alert HTML.
     */
    public static function danger(string $txt): string {
        return self::generateAlert('danger', $txt);
    }

    /**
     * Generate a warning alert message.
     *
     * @param string $txt The message text.
     * @return string The formatted warning alert HTML.
     */
    public static function warning(string $txt): string {
        return self::generateAlert('warning', $txt);
    }

    /**
     * Generate an alert message with specified type and text.
     *
     * @param string $type The alert type (success, danger, warning).
     * @param string $txt The message text.
     * @return string The formatted alert HTML.
     */
    private static function generateAlert(string $type, string $txt): string {
        return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
              ' . $txt . '
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
}