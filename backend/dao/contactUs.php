<?php
require_once './BaseDao.php';

class ContactUsDao extends BaseDao {
    public function __construct() {
        parent::__construct('contact_us');
    }

    // Get all contact requests
    public function getAllContactRequests() {
        return $this->getAll();
    }

    // Get contact request by ID
    public function getContactRequestById($id) {
        return $this->getById($id);
    }

    // Create a new contact request
    public function createContactRequest($data) {
        return $this->insert($data);
    }

    // Update contact request
    public function updateContactRequest($id, $data) {
        return $this->update($id, $data);
    }

    // Delete contact request
    public function deleteContactRequest($id) {
        return $this->delete($id);
    }
}
?>
