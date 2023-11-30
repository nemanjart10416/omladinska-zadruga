<?php
/**
 * Class EmployerProfile
 */
class EmployerProfile
{
    /**
     * @var int|null
     */
    private ?int $id;

    /**
     * @var int
     */
    private int $userId;

    /**
     * @var string
     */
    private string $companyTitle;

    /**
     * @var string
     */
    private string $companyPib;

    /**
     * @var string
     */
    private string $companyMb;

    /**
     * @var string
     */
    private string $companyEmail;

    /**
     * @var string
     */
    private string $companyPhone;

    /**
     * @var string
     */
    private string $companyWorkField;

    /**
     * @var string
     */
    private string $companyAddress;

    /**
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $updatedAt;

    /**
     * EmployerProfile constructor.
     *
     * @param int|null $id
     * @param int $userId
     * @param string $companyTitle
     * @param string $companyPib
     * @param string $companyMb
     * @param string $companyEmail
     * @param string $companyPhone
     * @param string $companyWorkField
     * @param string $companyAddress
     * @param DateTimeImmutable|null $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        ?int $id, int $userId, string $companyTitle, string $companyPib, string $companyMb, string $companyEmail, string $companyPhone, string $companyWorkField,
        string $companyAddress, ?DateTimeImmutable $createdAt = null, ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->companyTitle = $companyTitle;
        $this->companyPib = $companyPib;
        $this->companyMb = $companyMb;
        $this->companyEmail = $companyEmail;
        $this->companyPhone = $companyPhone;
        $this->companyWorkField = $companyWorkField;
        $this->companyAddress = $companyAddress;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Create a new employer profile in the database.
     *
     * @return EmployerProfile|null
     * @throws Exception
     */
    public function createProfile(): ?EmployerProfile {
        $sql = "
            INSERT INTO employer_profile 
                (employer_user_id, company_title, company_pib, company_mb, company_email, company_phone, company_work_field, company_address, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ";

        $params = [
            $this->userId, $this->companyTitle, $this->companyPib, $this->companyMb, $this->companyEmail, $this->companyPhone, $this->companyWorkField, $this->companyAddress
        ];

        // Execute the prepared statement using setP function
        $success = Connection::setP($sql, $params);

        if ($success) {
            // Get the inserted profile's data
            $getProfileSql = "SELECT * FROM employer_profile WHERE employer_user_id = ?";
            $getProfileParams = [$this->userId];

            $profileResult = Connection::getP($getProfileSql, $getProfileParams);

            if ($profile = $profileResult->fetch_assoc()) {
                return new EmployerProfile(
                    $profile['employer_profile_id'], $profile['employer_user_id'], $profile['company_title'], $profile['company_pib'],
                    $profile['company_mb'], $profile['company_email'], $profile['company_phone'], $profile['company_work_field'],
                    $profile['company_address'], new DateTimeImmutable($profile['created_at']), new DateTimeImmutable($profile['updated_at'])
                );
            }
        }

        return null;
    }

    /**
     * Get an employer profile by its ID.
     *
     * @param int $profileId
     *
     * @return EmployerProfile|null
     * @throws Exception
     */
    public static function getProfileById(int $profileId): ?EmployerProfile {
        $sql = "SELECT * FROM employer_profile WHERE employer_profile_id = ?";
        $params = [$profileId];

        $profileResult = Connection::getP($sql, $params);

        if ($profile = $profileResult->fetch_assoc()) {
            return new EmployerProfile(
                $profile['employer_profile_id'], $profile['employer_user_id'], $profile['company_title'], $profile['company_pib'], $profile['company_mb'],
                $profile['company_email'], $profile['company_phone'], $profile['company_work_field'], $profile['company_address'],
                new DateTimeImmutable($profile['created_at']), new DateTimeImmutable($profile['updated_at'])
            );
        }

        return null;
    }

    /**
     * Get an employer profile by user ID.
     *
     * @param int $userId
     *
     * @return EmployerProfile|null
     * @throws Exception
     */
    public static function getProfileByUserId(int $userId): ?EmployerProfile {
        $sql = "SELECT * FROM employer_profile WHERE employer_user_id = ?";
        $params = [$userId];

        $profileResult = Connection::getP($sql, $params);

        if ($profile = $profileResult->fetch_assoc()) {
            return new EmployerProfile(
                $profile['employer_profile_id'], $profile['employer_user_id'], $profile['company_title'], $profile['company_pib'], $profile['company_mb'],
                $profile['company_email'], $profile['company_phone'], $profile['company_work_field'], $profile['company_address'],
                new DateTimeImmutable($profile['created_at']), new DateTimeImmutable($profile['updated_at'])
            );
        }

        return null;
    }

    /**
     * Check if a given PIB is already taken by another employer profile.
     *
     * @param string $pib
     * @return bool
     */
    public static function isPibTaken(string $pib): bool
    {
        $sql = "SELECT COUNT(*) as count FROM employer_profile WHERE company_pib = ?";
        $params = [$pib];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Check if a given MB is already taken by another employer profile.
     *
     * @param string $mb
     * @return bool
     */
    public static function isMbTaken(string $mb): bool
    {
        $sql = "SELECT COUNT(*) as count FROM employer_profile WHERE company_mb = ?";
        $params = [$mb];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Check if a given email is already taken by another employer profile.
     *
     * @param string $email
     * @return bool
     */
    public static function isEmailTaken(string $email): bool
    {
        $sql = "SELECT COUNT(*) as count FROM employer_profile WHERE company_email = ?";
        $params = [$email];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Check if a given phone number is already taken by another employer profile.
     *
     * @param string $phone
     * @return bool
     */
    public static function isPhoneTaken(string $phone): bool
    {
        $sql = "SELECT COUNT(*) as count FROM employer_profile WHERE company_phone = ?";
        $params = [$phone];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Delete the employer profile from the database.
     *
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteProfile(): bool
    {
        // Check if the employer profile has an ID (indicating it exists in the database)
        if ($this->getId() === null) {
            return false; // Unable to delete a profile without an ID
        }

        // SQL query to delete the employer profile by ID
        $sql = "DELETE FROM employer_profile WHERE employer_profile_id = ?";
        $params = [$this->getId()];

        // Execute the delete query
        return Connection::setP($sql, $params);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getCompanyTitle(): string
    {
        return $this->companyTitle;
    }

    /**
     * @param string $companyTitle
     */
    public function setCompanyTitle(string $companyTitle): void
    {
        $this->companyTitle = $companyTitle;
    }

    /**
     * @return string
     */
    public function getCompanyPib(): string
    {
        return $this->companyPib;
    }

    /**
     * @param string $companyPib
     */
    public function setCompanyPib(string $companyPib): void
    {
        $this->companyPib = $companyPib;
    }

    /**
     * @return string
     */
    public function getCompanyMb(): string
    {
        return $this->companyMb;
    }

    /**
     * @param string $companyMb
     */
    public function setCompanyMb(string $companyMb): void
    {
        $this->companyMb = $companyMb;
    }

    /**
     * @return string
     */
    public function getCompanyEmail(): string
    {
        return $this->companyEmail;
    }

    /**
     * @param string $companyEmail
     */
    public function setCompanyEmail(string $companyEmail): void
    {
        $this->companyEmail = $companyEmail;
    }

    /**
     * @return string
     */
    public function getCompanyPhone(): string
    {
        return $this->companyPhone;
    }

    /**
     * @param string $companyPhone
     */
    public function setCompanyPhone(string $companyPhone): void
    {
        $this->companyPhone = $companyPhone;
    }

    /**
     * @return string
     */
    public function getCompanyWorkField(): string
    {
        return $this->companyWorkField;
    }

    /**
     * @param string $companyWorkField
     */
    public function setCompanyWorkField(string $companyWorkField): void
    {
        $this->companyWorkField = $companyWorkField;
    }

    /**
     * @return string
     */
    public function getCompanyAddress(): string
    {
        return $this->companyAddress;
    }

    /**
     * @param string $companyAddress
     */
    public function setCompanyAddress(string $companyAddress): void
    {
        $this->companyAddress = $companyAddress;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable|null $updatedAt
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Convert the object to a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "Employer Profile: \n" .
            "ID: $this->id\n" .
            "User ID: $this->userId\n" .
            "Company Title: $this->companyTitle\n" .
            "Company PIB: $this->companyPib\n" .
            "Company MB: $this->companyMb\n" .
            "Company Email: $this->companyEmail\n" .
            "Company Phone: $this->companyPhone\n" .
            "Company Work Field: $this->companyWorkField\n" .
            "Company Address: $this->companyAddress\n" .
            "Created At: {$this->createdAt->format('Y-m-d H:i:s')}\n" .
            "Updated At: {$this->updatedAt->format('Y-m-d H:i:s')}\n";
    }
}

