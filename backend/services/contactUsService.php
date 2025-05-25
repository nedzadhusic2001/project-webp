<?php
require_once __DIR__ . '/../dao/ContactUsDao.php';
require_once __DIR__ . '/BaseService.php';

class ContactUsService extends BaseService {
    public function __construct() {
        parent::__construct(new ContactUsDao());
    }

    /**
     * Validates and processes contact form submission
     */
    public function processContactSubmission($contactData) {
        // 1. Basic validation
        $requiredFields = ['name', 'email', 'message'];
        foreach ($requiredFields as $field) {
            if (empty(trim($contactData[$field]))) {
                throw new Exception("Please fill in all required fields");
            }
        }

        // 2. Validate email format
        if (!filter_var($contactData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address");
        }

        // 3. Sanitize inputs
        $cleanData = [
            'name' => htmlspecialchars($contactData['name']),
            'email' => filter_var($contactData['email'], FILTER_SANITIZE_EMAIL),
            'phone' => isset($contactData['phone']) ? preg_replace('/[^0-9+]/', '', $contactData['phone']) : null,
            'message' => htmlspecialchars($contactData['message']),
            'submitted_at' => date('Y-m-d H:i:s')
        ];

        // 4. Save to database
        return $this->add($cleanData);
    }

    /**
     * Gets recent contact submissions (last 30 days by default)
     */
    public function getRecentSubmissions($days = 30) {
        $dateLimit = date('Y-m-d', strtotime("-$days days"));
        return $this->dao->getAll([
            'submitted_at >=' => $dateLimit
        ], [
            'submitted_at' => 'DESC'
        ]);
    }

    /**
     * Marks a submission as resolved
     */
    public function markAsResolved($contactId) {
        return $this->update($contactId, [
            'status' => 'resolved',
            'resolved_at' => date('Y-m-d H:i:s')
        ]);
    }
}
?>