<?php
require_once './BaseDao.php';

class DestinationsDao extends BaseDao {
    public function __construct() {
        parent::__construct('destinations');
    }

    // Get all destinations
    public function getAllDestinations() {
        return $this->getAll();
    }

    // Get destination by ID
    public function getDestinationById($id) {
        return $this->getById($id);
    }

    // Create a new destination
    public function createDestination($data) {
        return $this->insert($data);
    }

    // Update destination
    public function updateDestination($id, $data) {
        return $this->update($id, $data);
    }

    // Delete destination
    public function deleteDestination($id) {
        return $this->delete($id);
    }
}
?>
