<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/DestinationsDao.php';

class DestinationService extends BaseService {
    public function __construct() {
        parent::__construct(new DestinationsDao());
    }

    /**
     * Get featured destinations (by rating or popularity)
     */
    public function getFeaturedDestinations($limit = 5) {
        return $this->dao->getAll(
            ['is_featured' => true],
            ['rating' => 'DESC'],
            $limit
        );
    }

    /**
     * Search destinations by name/country with optional filters
     */
    public function searchDestinations($query, $filters = []) {
        $conditions = [];
        
        if (!empty($query)) {
            $conditions['name LIKE'] = "%$query%";
            $conditions['OR country LIKE'] = "%$query%";
        }

        if (isset($filters['climate'])) {
            $conditions['climate'] = $filters['climate'];
        }

        return $this->dao->getAll(
            $conditions,
            ['name' => 'ASC']
        );
    }

    /**
     * Get destinations suitable for specific season
     */
    public function getSeasonalDestinations($season) {
        $seasonMap = [
            'summer' => ['beach', 'tropical'],
            'winter' => ['ski', 'northern_lights']
        ];

        $types = $seasonMap[strtolower($season)] ?? [];
        
        return $this->dao->getAll(
            ['type' => $types],
            ['popularity' => 'DESC']
        );
    }

    /**
     * Increment view count for a destination
     */
    public function incrementViews($destinationId) {
        return $this->dao->incrementField($destinationId, 'view_count');
    }
}
?>