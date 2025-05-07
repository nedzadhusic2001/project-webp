<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PackagesDao.php';

class PackageService extends BaseService {
    public function __construct() {
        parent::__construct(new PackagesDao());
    }

    /**
     * Get active packages (not expired)
     */
    public function getActivePackages() {
        return $this->dao->getAll([
            'end_date >=' => date('Y-m-d'),
            'status' => 'active'
        ], ['start_date' => 'ASC']);
    }

    /**
     * Get packages within price range
     */
    public function getPackagesByPriceRange($minPrice, $maxPrice) {
        return $this->dao->getAll([
            'price >=' => $minPrice,
            'price <=' => $maxPrice,
            'status' => 'active'
        ], ['price' => 'ASC']);
    }

    /**
     * Get popular packages (most booked)
     */
    public function getPopularPackages($limit = 5) {
        return $this->dao->getAll(
            ['status' => 'active'],
            ['bookings_count' => 'DESC'],
            $limit
        );
    }

    /**
     * Simple package availability check
     */
    public function checkAvailability($packageId, $date) {
        $package = $this->getById($packageId);
        return ($package['status'] === 'active') && 
            ($date >= $package['start_date']) && 
            ($date <= $package['end_date']);
    }
}
?>