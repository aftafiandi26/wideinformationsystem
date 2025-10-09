<?php

namespace App\Services;

class EtcLeaveServices
{
    /**
     * Get all leave categories with custom values
     * 
     * @return array
     */
    public static function getAllLeaveCategories()
    {
        return [
            [
                'id' => 3,
                'name' => 'Sick',
                'value' => 'sick',
                'description' => 'Sick leave for medical reasons',
                'max_days' => null, // No limit
                'requires_medical_cert' => true,
                'is_paid' => true
            ],
            [
                'id' => 4,
                'name' => 'Wedding (3 days)',
                'value' => 'wedding',
                'description' => 'Wedding leave for personal marriage',
                'max_days' => 3,
                'requires_medical_cert' => false,
                'is_paid' => true
            ],
            [
                'id' => 5,
                'name' => 'Birth of child (2 days)',
                'value' => 'birth_child',
                'description' => 'Leave for birth of child',
                'max_days' => 2,
                'requires_medical_cert' => false,
                'is_paid' => true
            ],
            [
                'id' => 6,
                'name' => 'Unpaid',
                'value' => 'unpaid',
                'description' => 'Unpaid leave without salary',
                'max_days' => null, // No limit
                'requires_medical_cert' => false,
                'is_paid' => false
            ],
            [
                'id' => 7,
                'name' => 'Son circumcision/baptism (2 days)',
                'value' => 'circumcision_baptism',
                'description' => 'Leave for son circumcision or baptism ceremony',
                'max_days' => 2,
                'requires_medical_cert' => false,
                'is_paid' => true
            ],
            [
                'id' => 8,
                'name' => 'Death of family (3 days)',
                'value' => 'death_family',
                'description' => 'Bereavement leave for family member death',
                'max_days' => 3,
                'requires_medical_cert' => false,
                'is_paid' => true
            ],
            [
                'id' => 9,
                'name' => 'Death of family in law (2 days)',
                'value' => 'death_family_inlaw',
                'description' => 'Bereavement leave for in-law family member death',
                'max_days' => 2,
                'requires_medical_cert' => false,
                'is_paid' => true
            ],
            [
                'id' => 10,
                'name' => 'Maternity (3 Month)',
                'value' => 'maternity',
                'description' => 'Maternity leave for childbirth',
                'max_days' => 90, // 3 months
                'requires_medical_cert' => true,
                'is_paid' => true
            ],
            [
                'id' => 11,
                'name' => 'Others',
                'value' => 'others',
                'description' => 'Other types of leave',
                'max_days' => null, // No limit
                'requires_medical_cert' => false,
                'is_paid' => true
            ]
        ];
    }

    /**
     * Get leave category by ID
     * 
     * @param int $id
     * @return array|null
     */
    public static function getLeaveCategoryById($id)
    {
        $categories = self::getAllLeaveCategories();
        
        foreach ($categories as $category) {
            if ($category['id'] == $id) {
                return $category;
            }
        }
        
        return null;
    }

    /**
     * Get leave category by value
     * 
     * @param string $value
     * @return array|null
     */
    public static function getLeaveCategoryByValue($value)
    {
        $categories = self::getAllLeaveCategories();
        
        foreach ($categories as $category) {
            if ($category['value'] == $value) {
                return $category;
            }
        }
        
        return null;
    }

    /**
     * Get leave categories as options for dropdown
     * 
     * @return array
     */
    public static function getLeaveCategoriesOptions()
    {
        $categories = self::getAllLeaveCategories();
        $options = [];
        
        foreach ($categories as $category) {
            $options[] = [
                'value' => $category['id'],
                'text' => $category['name'],
                'data-value' => $category['value'],
                'data-max-days' => $category['max_days'],
                'data-requires-cert' => $category['requires_medical_cert'] ? 'true' : 'false',
                'data-is-paid' => $category['is_paid'] ? 'true' : 'false'
            ];
        }
        
        return $options;
    }

    /**
     * Get leave categories as JSON
     * 
     * @return string
     */
    public static function getLeaveCategoriesJson()
    {
        return json_encode(self::getAllLeaveCategories());
    }

    /**
     * Get leave categories as options JSON
     * 
     * @return string
     */
    public static function getLeaveCategoriesOptionsJson()
    {
        return json_encode(self::getLeaveCategoriesOptions());
    }

    /**
     * Validate leave category and days
     * 
     * @param int $categoryId
     * @param int $requestedDays
     * @return array
     */
    public static function validateLeaveRequest($categoryId, $requestedDays)
    {
        $category = self::getLeaveCategoryById($categoryId);
        
        if (!$category) {
            return [
                'valid' => false,
                'message' => 'Invalid leave category'
            ];
        }
        
        // Check if category has max days limit
        if ($category['max_days'] !== null && $requestedDays > $category['max_days']) {
            return [
                'valid' => false,
                'message' => "Maximum days for {$category['name']} is {$category['max_days']} days"
            ];
        }
        
        // Check if minimum days required
        if ($requestedDays < 1) {
            return [
                'valid' => false,
                'message' => 'Minimum leave days is 1 day'
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Leave request is valid',
            'category' => $category,
            'requires_medical_cert' => $category['requires_medical_cert'],
            'is_paid' => $category['is_paid']
        ];
    }

    /**
     * Get leave category requirements
     * 
     * @param int $categoryId
     * @return array
     */
    public static function getLeaveRequirements($categoryId)
    {
        $category = self::getLeaveCategoryById($categoryId);
        
        if (!$category) {
            return [
                'requires_medical_cert' => false,
                'is_paid' => false,
                'max_days' => null,
                'description' => 'Unknown category'
            ];
        }
        
        return [
            'requires_medical_cert' => $category['requires_medical_cert'],
            'is_paid' => $category['is_paid'],
            'max_days' => $category['max_days'],
            'description' => $category['description']
        ];
    }

    /**
     * Get paid leave categories only
     * 
     * @return array
     */
    public static function getPaidLeaveCategories()
    {
        $categories = self::getAllLeaveCategories();
        
        return array_filter($categories, function($category) {
            return $category['is_paid'] === true;
        });
    }

    /**
     * Get unpaid leave categories only
     * 
     * @return array
     */
    public static function getUnpaidLeaveCategories()
    {
        $categories = self::getAllLeaveCategories();
        
        return array_filter($categories, function($category) {
            return $category['is_paid'] === false;
        });
    }

    /**
     * Get categories that require medical certificate
     * 
     * @return array
     */
    public static function getMedicalCertRequiredCategories()
    {
        $categories = self::getAllLeaveCategories();
        
        return array_filter($categories, function($category) {
            return $category['requires_medical_cert'] === true;
        });
    }

    /**
     * Get categories with max days limit
     * 
     * @return array
     */
    public static function getLimitedDaysCategories()
    {
        $categories = self::getAllLeaveCategories();
        
        return array_filter($categories, function($category) {
            return $category['max_days'] !== null;
        });
    }

    /**
     * Get categories without max days limit
     * 
     * @return array
     */
    public static function getUnlimitedDaysCategories()
    {
        $categories = self::getAllLeaveCategories();
        
        return array_filter($categories, function($category) {
            return $category['max_days'] === null;
        });
    }
}
