<?php

class Email {
    /**
     * @var string
     */
    private string $admin_email = "admin@gmail.com";
    /**
     * @var string
     */
    private string $domain = "domain.com";
    /**
     * @var string
     */
    private string $email_from = "email@domain.com";

    /**
     * Sends an email.
     *
     * @param string $email The recipient's email address.
     * @param string $subject The email subject.
     * @param string $name The recipient's name.
     * @param string $message The email message.
     * @return bool True if the email was sent successfully, false otherwise.
     */
    public function sendMail(string $email, string $subject, string $name, string $message): bool {
        $from = $this->getEmailFrom();

        $header = "From: $from\r\n";
        $header .= "Cc: $from\r\n";
        $header .= "X-Sender: <$from>\n";
        $header .= 'X-Mailer: PHP/' . phpversion();
        $header .= "X-Priority: 1\n"; // Urgent message!
        $header .= "Return-Path: $from\n"; // Return path for errors
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";

        return mail($email, $subject, $message, $header);
    }

    /**
     * @return string
     */
    public function getAdminEmail(): string
    {
        return $this->admin_email;
    }

    /**
     * @param string $admin_email
     */
    public function setAdminEmail(string $admin_email): void
    {
        $this->admin_email = $admin_email;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getEmailFrom(): string
    {
        return $this->email_from;
    }

    /**
     * @param string $email_from
     */
    public function setEmailFrom(string $email_from): void
    {
        $this->email_from = $email_from;
    }
}