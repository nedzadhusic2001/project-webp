<?php
require_once './BaseDao.php';

class UsersDao extends BaseDao {
    public function __construct() {
        parent::__construct('users');
    }

     // Get all users
     public function getAllUsers() {
        return $this->getAll();
    }

    // Get user by email
    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create a new user
    public function createUser($data) {
        return $this->insert($data);
    }

    // Update user
    public function updateUser($id, $data): mixed {
        return $this->update($id, $data);
    }

    // Delete user
    public function deleteUser($id) {
        return $this->delete($id);
    }
}
?>