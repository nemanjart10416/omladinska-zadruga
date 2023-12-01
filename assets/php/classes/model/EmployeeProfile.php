<?php

/**
 * Class EmployeeProfile
 */
class EmployeeProfile
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
    private string $employmentStatus;

    /**
     * @var string
     */
    private string $employeeIdCardNumber;

    /**
     * @var string
     */
    private string $employeeMb;

    /**
     * @var string|null
     */
    private ?string $employeeResume;

    /**
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $updatedAt;

    /**
     * EmployeeProfile constructor.
     *
     * @param int|null $id
     * @param int $userId
     * @param string $employmentStatus
     * @param string $employeeIdCardNumber
     * @param string $employeeMb
     * @param string|null $employeeResume
     * @param DateTimeImmutable|null $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        ?int $id, int $userId, string $employmentStatus, string $employeeIdCardNumber, string $employeeMb, ?string $employeeResume = null,
        ?DateTimeImmutable $createdAt = null, ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->employmentStatus = $employmentStatus;
        $this->employeeIdCardNumber = $employeeIdCardNumber;
        $this->employeeMb = $employeeMb;
        $this->employeeResume = $employeeResume;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Create a new employer profile in the database.
     *
     * @return bool
     */
    public function createProfile(): bool {
        $sql = "
            INSERT INTO employee_profile 
                (employee_profile_id, employee_user_id, employment_status, employee_id_card_number, employee_mb, employee_resume, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ";

        $params = [$this->id, $this->userId, $this->employmentStatus, $this->employeeIdCardNumber, $this->employeeMb, $this->employeeResume];

        // Execute the prepared statement using setP function
        return Connection::setP($sql, $params);
    }

    /**
     * Get an employee profile by its ID.
     *
     * @param int $profileId
     *
     * @return EmployeeProfile|null
     * @throws Exception
     */
    public static function getProfileById(int $profileId): ?EmployeeProfile {
        $sql = "SELECT * FROM employee_profile WHERE employee_profile_id = ?";
        $params = [$profileId];

        $profileResult = Connection::getP($sql, $params);

        if ($profile = $profileResult->fetch_assoc()) {
            return new EmployeeProfile(
                $profile['employee_profile_id'],
                $profile['employee_user_id'],
                $profile['employment_status'],
                $profile['employee_id_card_number'],
                $profile['employee_mb'],
                $profile['employee_resume'],
                new DateTimeImmutable($profile['created_at']),
                new DateTimeImmutable($profile['updated_at'])
            );
        }

        return null;
    }

    /**
     * Get an employee profile by user ID.
     *
     * @param int $userId
     *
     * @return EmployeeProfile|null
     * @throws Exception
     */
    public static function getProfileByUserId(int $userId): ?EmployeeProfile {
        $sql = "SELECT * FROM employee_profile WHERE employee_user_id = ?";
        $params = [$userId];

        $profileResult = Connection::getP($sql, $params);

        if ($profile = $profileResult->fetch_assoc()) {
            return new EmployeeProfile(
                $profile['employee_profile_id'],
                $profile['employee_user_id'],
                $profile['employment_status'],
                $profile['employee_id_card_number'],
                $profile['employee_mb'],
                $profile['employee_resume'],
                new DateTimeImmutable($profile['created_at']),
                new DateTimeImmutable($profile['updated_at'])
            );
        }

        return null;
    }

    /**
     * Check if a given ID card number is already taken by another employee profile.
     *
     * @param string $idCardNumber
     * @return bool
     */
    public static function isIdCardNumberTaken(string $idCardNumber): bool
    {
        $sql = "SELECT COUNT(*) as count FROM employee_profile WHERE employee_id_card_number = ?";
        $params = [$idCardNumber];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Check if a given MB is already taken by another employee profile.
     *
     * @param string $mb
     * @return bool
     */
    public static function isMbTaken(string $mb): bool
    {
        $sql = "SELECT COUNT(*) as count FROM employee_profile WHERE employee_mb = ?";
        $params = [$mb];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Delete the employee profile from the database.
     *
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteProfile(): bool
    {
        // Check if the employee profile has an ID (indicating it exists in the database)
        if ($this->getId() === null) {
            return false; // Unable to delete a profile without an ID
        }

        // SQL query to delete the employee profile by ID
        $sql = "DELETE FROM employee_profile WHERE employee_profile_id = ?";
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
    public function getEmploymentStatus(): string
    {
        return $this->employmentStatus;
    }

    /**
     * @param string $employmentStatus
     */
    public function setEmploymentStatus(string $employmentStatus): void
    {
        $this->employmentStatus = $employmentStatus;
    }

    /**
     * @return string
     */
    public function getEmployeeIdCardNumber(): string
    {
        return $this->employeeIdCardNumber;
    }

    /**
     * @param string $employeeIdCardNumber
     */
    public function setEmployeeIdCardNumber(string $employeeIdCardNumber): void
    {
        $this->employeeIdCardNumber = $employeeIdCardNumber;
    }

    /**
     * @return string
     */
    public function getEmployeeMb(): string
    {
        return $this->employeeMb;
    }

    /**
     * @param string $employeeMb
     */
    public function setEmployeeMb(string $employeeMb): void
    {
        $this->employeeMb = $employeeMb;
    }

    /**
     * @return string|null
     */
    public function getEmployeeResume(): ?string
    {
        return $this->employeeResume;
    }

    /**
     * @param string|null $employeeResume
     */
    public function setEmployeeResume(?string $employeeResume): void
    {
        $this->employeeResume = $employeeResume;
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
        return "Employee Profile: \n" .
            "ID: $this->id\n" .
            "User ID: $this->userId\n" .
            "Employment Status: $this->employmentStatus\n" .
            "ID Card Number: $this->employeeIdCardNumber\n" .
            "MB: $this->employeeMb\n" .
            "Resume: $this->employeeResume\n" .
            "Created At: {$this->createdAt->format('Y-m-d H:i:s')}\n" .
            "Updated At: {$this->updatedAt->format('Y-m-d H:i:s')}\n";
    }


}
