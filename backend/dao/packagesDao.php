<?php
require_once __DIR__ . '/BaseDao.php';


class PackagesDao extends BaseDao {
    public function __construct() {
        parent::__construct('packages');
    }

    // Get all packages
    public function getAllPackages() {
        return $this->getAll();
    }

    // Get package by ID
    public function getPackageById($id) {
        return $this->getById($id);
    }

    // Create a new package
    public function createPackage($data) {
        return $this->insert($data);
    }

    // Update package
    public function updatePackage($id, $data) {
        return $this->update($id, $data);
    }

    // Delete package
    public function deletePackage($id) {
        return $this->delete($id);
    }

    // Optional: Get all packages by destination ID
    public function getPackagesByDestination($destination_id) {
        $stmt = $this->connection->prepare("SELECT * FROM packages WHERE destination_id = :destination_id");
        $stmt->bindParam(':destination_id', $destination_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
