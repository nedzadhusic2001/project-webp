<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BookingsDao.php';

class BookingService extends BaseService {
    private $userDao;
    private $packageDao;

    public function __construct() {
        parent::__construct(new BookingsDao());
        $this->userDao = new UserDao(); 
        $this->packageDao = new PackageDao();
    }

    /**
     * Creates a booking with validation
     */
    public function createBooking($bookingData) {
        // 1. Validate required fields
        $required = ['user_id', 'package_id', 'booking_date', 'travelers'];
        foreach ($required as $field) {
            if (empty($bookingData[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // 2. Verify user exists
        if (!$this->userDao->getUserById($bookingData['user_id'])) {
            throw new Exception("User does not exist");
        }

        // 3. Verify package exists and is available
        $package = $this->packageDao->getById($bookingData['package_id']);
        if (!$package || $package['status'] != 'available') {
            throw new Exception("Package not available");
        }

        // 4. Validate traveler count
        if ($bookingData['travelers'] > $package['max_capacity']) {
            throw new Exception("Exceeds maximum capacity for this package");
        }

        // 5. Calculate final price with discounts
        $bookingData['total_price'] = $this->calculatePrice(
            $package['base_price'],
            $bookingData['travelers'],
            $bookingData['user_id']
        );

        // 6. Save to database
        return parent::add($bookingData);
    }

    /**
     * Calculates booking price with potential discounts
     */
    private function calculatePrice($basePrice, $travelers, $userId) {
        $total = $basePrice * $travelers;
        
        // Apply 10% discount for groups > 4
        if ($travelers > 4) {
            $total *= 0.9;
        }

        // Apply 5% loyalty discount for returning customers
        $userBookings = $this->dao->getCount(['user_id' => $userId]);
        if ($userBookings > 2) {
            $total *= 0.95;
        }

        return round($total, 2);
    }

    /**
     * Gets all bookings for a specific user
     */
    public function getUserBookings($userId, $limit = 10) {
        return $this->dao->getAll(
            ['user_id' => $userId],
            ['booking_date' => 'DESC'],
            $limit
        );
    }

    /**
     * Cancels a booking with refund calculation
     */
    public function cancelBooking($bookingId) {
        $booking = $this->getById($bookingId);
        
        if (!$booking) {
            throw new Exception("Booking not found");
        }

        // Calculate refund based on cancellation policy
        $daysUntilTrip = $this->getDaysDifference(
            date('Y-m-d'), 
            $booking['travel_date']
        );

        if ($daysUntilTrip > 30) {
            $refund = $booking['total_price'] * 0.9; // 90% refund
        } elseif ($daysUntilTrip > 7) {
            $refund = $booking['total_price'] * 0.5; // 50% refund
        } else {
            $refund = 0;
        }

        // Update booking status
        return $this->update($bookingId, [
            'status' => 'cancelled',
            'refund_amount' => $refund
        ]);
    }

    private function getDaysDifference($date1, $date2) {
        $diff = strtotime($date2) - strtotime($date1);
        return round($diff / (60 * 60 * 24));
    }
}
?>