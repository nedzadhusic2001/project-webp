<?php
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/BaseService.php';

class UserService extends BaseService {
    public function __construct() {
        parent::__construct(new UserDao());
    }

    /**
     * Register a new user with basic validation
     */
    public function registerUser($userData) {
        // Required fields validation
        $required = ['email', 'password', 'first_name', 'last_name'];
        foreach ($required as $field) {
            if (empty($userData[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Email validation
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Password strength check
        if (strlen($userData['password']) < 8) {
            throw new Exception("Password must be at least 8 characters");
        }

        // Hash password
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData['created_at'] = date('Y-m-d H:i:s');

        return $this->add($userData);
    }

    /**
     * Authenticate user login
     */
    public function authenticate($email, $password) {
        $user = $this->dao->getUserByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password");
        }

        return $user;
    }

    /**
     * Get users with optional filters
     */
    public function getUsers($filters = [], $limit = 10) {
        return $this->dao->getAll(
            $filters,
            ['last_name' => 'ASC'],
            $limit
        );
    }

    /**
     * Update user profile with validation
     */
    public function updateProfile($userId, $profileData) {
        // Remove restricted fields
        unset($profileData['email'], $profileData['password']);

        return $this->update($userId, $profileData);
    }
}
?>