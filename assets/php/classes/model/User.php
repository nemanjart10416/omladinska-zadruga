<?php

use JetBrains\PhpStorm\NoReturn;

/**
 *
 */
class User{
    /**
     * @var int|null
     */
    private ?int $id;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $birthday;

    /**
     * @var string
     */
    private string $address;

    /**
     * @var string
     */
    private string $phone;

    /**
     * @var string
     */
    private string $role;

    /**
     * @var string|null
     */
    private ?string $confirmationStatus;

    /**
     * @var string|null
     */
    private ?string $availableStatus;

    /**
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $updatedAt;

    /**
     * @param int|null $id
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @param DateTimeImmutable $birthday
     * @param string $address
     * @param string $phone
     * @param string $role
     * @param string|null $confirmationStatus
     * @param string|null $availableStatus
     * @param DateTimeImmutable|null $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        ?int $id,
        string $username,
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        DateTimeImmutable $birthday,
        string $address,
        string $phone,
        string $role,
        ?string $confirmationStatus,
        ?string $availableStatus,
        ?DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthday = $birthday;
        $this->address = $address;
        $this->phone = $phone;
        $this->role = $role;
        $this->confirmationStatus = $confirmationStatus;
        $this->availableStatus = $availableStatus;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Authenticates a user based on the provided credentials.
     *
     * @param string $username The username of the user to authenticate.
     * @param string $password The password of the user to authenticate.
     *
     * @return bool Returns true if authentication is successful; otherwise, returns false.
     * @throws Exception
     */
    public static function authenticateUser(string $username, string $password): bool {
        // Get user data from the database based on the provided username
        $sql = "SELECT * FROM users WHERE username = ?";
        $params = [$username];
        $result = Connection::getP($sql, $params);

        // Check if the user with the given username exists
        if ($result && $result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            // Verify the password hash
            if (password_verify($password, $userData['password'])) {
                // Authentication successful, create session and return true
                self::handleLogin(new User(
                    $userData['id'],
                    $userData['username'],
                    $userData['email'],
                    $userData['password'],
                    $userData['first_name'],
                    $userData['last_name'],
                    new DateTimeImmutable($userData['birthday']),
                    $userData['address'],
                    $userData['phone'],
                    $userData['role'],
                    $userData['confirmation_status'],
                    $userData['available_status'],
                    new DateTimeImmutable($userData['created_at']),
                    new DateTimeImmutable($userData['updated_at'])
                ));
            }
        }

        // Authentication failed, return false
        return false;
    }

    /**
     * Activates the user's account.
     *
     * @return bool True if the account is successfully activated, false otherwise.
     */
    public function activateAccount(): bool {
        // Implement logic to activate the user's account (e.g., update a 'verified' flag in the users table)
        // Example SQL query: UPDATE users SET verified = 1 WHERE id = ?

        // Execute the query and handle the result
        $sql = "UPDATE users SET confirmation_status = 'confirmed' WHERE id = ?";
        $params = [$this->id];
        $success = Connection::setP($sql, $params);

        if ($success) {
            return true; // Account activated successfully
        } else {
            return false; // Account activation failed
        }
    }

    /**
     * Creates a new user in the database and returns the user object.
     *
     * @return User The newly created user object.
     *
     * @throws Exception If there is an error creating the user.
     */
    public function createUser(): User {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // SQL query with placeholders for prepared statement
        $sql = "
            INSERT INTO users (username, email, password, first_name, last_name, birthday, address, phone, role, confirmation_status, available_status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'not_confirmed', 'active', NOW(), NOW())
        ";

        // Bind parameters for the prepared statement
        $params = [$this->username,$this->email, $hashedPassword, $this->firstName, $this->lastName, $this->birthday->format('Y-m-d'), $this->address, $this->phone, $this->role];

        // Execute the prepared statement using setP function
        $success = Connection::setP($sql, $params);

        // Check if the user was successfully created and get the user data from the database
        if ($success) {
            $getUserSql = "SELECT * FROM users WHERE username = ?";
            $getUserParams = [$this->username];

            $userResult = Connection::getP($getUserSql, $getUserParams);

            if ($user = $userResult->fetch_assoc()) {
                // Create a User object and return it
                $u = new User(
                    $user['id'], $user['username'], $user['email'], $user['password'], $user['first_name'], $user['last_name'], new DateTimeImmutable($user['birthday']), $user['address'],
                    $user['phone'], $user['role'], $user['confirmation_status'], $user['available_status'], new DateTimeImmutable($user['created_at']), new DateTimeImmutable($user['updated_at'])
                );

                // Create an email confirmation token
                $token = TokenManager::generateToken(); //TODO

                // Get the current date and time
                $currentDateTime = new DateTimeImmutable();

                // Add one day to the current date and time
                $oneDayLater = $currentDateTime->modify('+1 day');

                // Now $oneDayLater contains the date and time one day from now
                ///echo $oneDayLater->format('Y-m-d H:i:s');

                TokenManager::storeToken($u->getId(),$token,$oneDayLater);
                TokenManager::sendConfirmationEmail($u->getEmail(),$token); //TODO

                return $u;
            }
        }

        // Placeholder return value, replace this with actual logic
        throw new Exception("Failed to create user.");
    }

    /**
     * Creates a session for the authenticated user and performs redirect based on user role.
     *
     * @param User $user The authenticated user object.
     *
     * @return void
     */
    #[NoReturn] public static function handleLogin(User $user): void {
        if($user->getConfirmationStatus()==="not_confirmed"){
            die("You need to confirm email address.");
        }

        if($user->getAvailableStatus()==="deleted"){
            die("this account is deleted.");
        }

        // Store user information in the session
        $_SESSION['user'] = serialize($user);

        // Perform redirect based on user role
        if ($user->getRole()==="super_administrator") {
            header("Location: super_admin/"); // Redirect to admin dashboard
        }else if ($user->getRole()==="administrator") {
            header("Location: admin/"); // Redirect to admin dashboard
        }else if ($user->getRole()==="employer") {
            header("Location: employer/"); // Redirect to admin dashboard
        }else if ($user->getRole()==="user") {
            header("Location: user/"); // Redirect to admin dashboard
        }  else {
            Functions::logout();
            header("Location: home.php"); // Redirect to regular user homepage
        }

        // End script execution after the redirect
        exit();
    }

    /**
     * Get a user by email.
     *
     * @param string $email The email address of the user.
     * @return User|null The User object if found, or null if not found.
     * @throws Exception
     */
    public static function getUserByEmail(string $email): ?User
    {
        try {
            $sql = "SELECT * FROM users WHERE email = ?";
            $params = [$email];

            $result = Connection::getP($sql, $params);

            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                // Create and return a User object
                return new User(
                    $userData['id'],
                    $userData['username'],
                    $userData['email'],
                    $userData['password'],
                    $userData['first_name'],
                    $userData['last_name'],
                    new DateTimeImmutable($userData['birthday']),
                    $userData['address'],
                    $userData['phone'],
                    $userData['role'],
                    $userData['confirmation_status'],
                    $userData['available_status'],
                    new DateTimeImmutable($userData['created_at']),
                    new DateTimeImmutable($userData['updated_at'])
                );
            } else {
                return null; // User not found
            }
        } catch (Exception $e) {
            // Handle exception (log the error, return null, etc.)
            throw new Exception("Error fetching user by email");
        }
    }

    /**
     * Get a user by email which is not deleted account.
     *
     * @param string $email The email address of the user.
     * @return User|null The User object if found, or null if not found.
     * @throws Exception
     */
    public static function getActiveUserByEmail(string $email): ?User
    {
        try {
            $sql = "SELECT * FROM users WHERE email = ? AND available_status != 'deleted'";
            $params = [$email];

            $result = Connection::getP($sql, $params);

            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                // Create and return a User object
                return new User(
                    $userData['id'],
                    $userData['username'],
                    $userData['email'],
                    $userData['password'],
                    $userData['first_name'],
                    $userData['last_name'],
                    new DateTimeImmutable($userData['birthday']),
                    $userData['address'],
                    $userData['phone'],
                    $userData['role'],
                    $userData['confirmation_status'],
                    $userData['available_status'],
                    new DateTimeImmutable($userData['created_at']),
                    new DateTimeImmutable($userData['updated_at'])
                );
            } else {
                return null; // User not found
            }
        } catch (Exception $e) {
            // Handle exception (log the error, return null, etc.)
            throw new Exception("Error fetching user by email");
        }
    }

    /**
     * @param $password
     * @return bool
     */
    public function resetPassword($password): bool {

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $params = [$hashedPassword, $this->id];

            return Connection::setP($sql, $params);
        } catch (Exception $e) {
            // Handle exception (log the error, return false, etc.)
            return false;
        }
    }

    /**
     * Checks if a username is already taken in the database.
     *
     * @param string $username The username to check.
     * @return bool True if the username is taken, false otherwise.
     */
    public static function isUsernameTaken(string $username): bool {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
        $params = [$username];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Checks if an email address is already taken in the database.
     *
     * @param string $email The email address to check.
     * @return bool True if the email address is taken, false otherwise.
     */
    public static function isEmailTaken(string $email): bool {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $params = [$email];
        $result = Connection::getP($sql, $params);

        if ($result && $row = $result->fetch_assoc()) {
            return (intval($row['count']) > 0);
        }

        return false;
    }

    /**
     * Deletes the user from the database based on their ID.
     *
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteUser(): bool {
        $conn = Connection::connection();

        // Prepare the SQL query with a placeholder for user ID
        $sql = "DELETE FROM users WHERE id = ?";
        $params = [$this->id];

        // Use setP method to execute the delete query
        $success = Connection::setP($sql, $params);

        // Close the database connection
        $conn->close();

        // Return true if deletion is successful, false otherwise
        return $success;
    }

    /**
     * Retrieves all users from the database.
     *
     * @return array
     * @throws Exception
     */
    public static function getAllUsers(): array {
        $userData = Connection::get("SELECT * FROM users");

        $users = [];
        foreach ($userData as $userDataItem) {
            // Instantiate User objects and add them to the $users array
            $user = new User(
                $userDataItem['id'], $userDataItem['username'],
                $userDataItem['email'],
                $userDataItem['password'],
                $userDataItem['first_name'],
                $userDataItem['last_name'],
                new DateTimeImmutable($userDataItem['birthday']),
                $userDataItem['address'],
                $userDataItem['phone'],
                $userDataItem['role'],
                $userDataItem['confirmation_status'],
                $userDataItem['available_status'],
                new DateTimeImmutable($userDataItem['created_at']),
                new DateTimeImmutable($userDataItem['updated_at'])

            );
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Retrieves a user by their ID from the database.
     *
     * @param int $userId
     *
     * @return User|null Returns the User object if the user is found; otherwise, returns null.
     * @throws Exception
     */
    public static function getUserById(int $userId): ?User {
        $sql = "SELECT * FROM users WHERE id = ?";
        $params = [$userId];
        $result = Connection::getP($sql, $params);

        if ($result && $result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            // Extract user data fields (adjust field names accordingly)
            return new User(
                $userData['id'],
                $userData['username'],
                $userData['email'],
                $userData['password'],
                $userData['first_name'],
                $userData['last_name'],
                new DateTimeImmutable($userData['birthday']),
                $userData['address'],
                $userData['phone'],
                $userData['role'],
                $userData['confirmation_status'],
                $userData['available_status'],
                new DateTimeImmutable($userData['created_at']),
                new DateTimeImmutable($userData['updated_at'])

            );
        }

        // Return null if the user is not found in the database
        return null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getBirthday(): DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * @param DateTimeImmutable $birthday
     */
    public function setBirthday(DateTimeImmutable $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getConfirmationStatus(): string
    {
        return $this->confirmationStatus;
    }

    /**
     * @param string $confirmationStatus
     */
    public function setConfirmationStatus(string $confirmationStatus): void
    {
        $this->confirmationStatus = $confirmationStatus;
    }

    /**
     * @return string
     */
    public function getAvailableStatus(): string
    {
        return $this->availableStatus;
    }

    /**
     * @param string $availableStatus
     */
    public function setAvailableStatus(string $availableStatus): void
    {
        $this->availableStatus = $availableStatus;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable $updatedAt
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Convert the user object to a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            "User ID: %d\nUsername: %s\nEmail: %s\nPassword: %s\nFirst Name: %s\nLast Name: %s\nBirthday: %s\nAddress: %s\nPhone: %s\nRole: %s\nConfirmation Status: %s\nAvailable Status: %s\nCreated At: %s\nUpdated At: %s\n",
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->firstName,
            $this->lastName,
            $this->birthday->format('Y-m-d'), // Adjust the date format as needed
            $this->address,
            $this->phone,
            $this->role,
            $this->confirmationStatus,
            $this->availableStatus,
            $this->createdAt->format('Y-m-d H:i:s'), // Adjust the date format as needed
            $this->updatedAt->format('Y-m-d H:i:s') // Adjust the date format as needed
        );
    }
}