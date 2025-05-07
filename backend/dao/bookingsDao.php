<?php
require_once __DIR__ . '/BaseDao.php';


class BookingsDao extends BaseDao {
    public function __construct() {
        parent::__construct('bookings');
    }

    // Get all bookings
    public function getAllBookings() {
        return $this->getAll();
    }

    // Get booking by ID (already in BaseDao, but can be wrapped)
    public function getBookingById($id) {
        return $this->getById($id);
    }

    // Create a new booking
    public function createBooking($data) {
        return $this->insert($data);
    }

    // Update booking
    public function updateBooking($id, $data) {
        return $this->update($id, $data);
    }

    // Delete booking
    public function deleteBooking($id) {
        return $this->delete($id);
    }

    // Optional: Get all bookings for a specific user
    public function getBookingsByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM bookings WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
