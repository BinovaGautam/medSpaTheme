<?php
/**
 * Treatments Data Store
 *
 * @package MedSpaTheme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class TreatmentsDataStore {
    /**
     * Get all available treatment categories
     *
     * @return array Treatment categories
     */
    public static function get_categories(): array {
        return [
            'injectable' => __('Injectable Artistry', 'medspa'),
            'facial' => __('Facial Renaissance', 'medspa'),
            'precision' => __('Precision Treatments', 'medspa'),
            'regenerative' => __('Regenerative Treatments', 'medspa'),
            'wellness' => __('Wellness Infusions', 'medspa'),
            'artistry' => __('Artistry Enhancement', 'medspa'),
            'laser' => __('Laser Precision', 'medspa'),
            'carbon' => __('Carbon Rejuvenation', 'medspa'),
            'body' => __('Body Sculpting', 'medspa'),
        ];
    }

    /**
     * Get treatment data schema
     *
     * @return array Treatment data schema
     */
    public static function get_schema(): array {
        return [
            'id' => [
                'type' => 'string',
                'required' => true,
            ],
            'title' => [
                'type' => 'string',
                'required' => true,
            ],
            'icon' => [
                'type' => 'string',
                'required' => false,
            ],
            'category' => [
                'type' => 'string',
                'required' => true,
                'enum' => array_keys(self::get_categories()),
            ],
            'description' => [
                'type' => 'string',
                'required' => true,
            ],
            'benefits' => [
                'type' => 'array',
                'required' => false,
            ],
            'duration' => [
                'type' => 'string',
                'required' => true,
            ],
            'price' => [
                'type' => 'object',
                'required' => true,
                'properties' => [
                    'amount' => [
                        'type' => 'number',
                        'required' => true,
                    ],
                    'currency' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'USD',
                    ],
                    'from' => [
                        'type' => 'boolean',
                        'required' => false,
                        'default' => false,
                    ],
                ],
            ],
            'cta' => [
                'type' => 'object',
                'required' => true,
                'properties' => [
                    'primary' => [
                        'type' => 'object',
                        'required' => true,
                        'properties' => [
                            'text' => [
                                'type' => 'string',
                                'required' => true,
                            ],
                            'url' => [
                                'type' => 'string',
                                'required' => true,
                            ],
                        ],
                    ],
                    'secondary' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'text' => [
                                'type' => 'string',
                                'required' => true,
                            ],
                            'url' => [
                                'type' => 'string',
                                'required' => true,
                            ],
                        ],
                    ],
                ],
            ],
            'image' => [
                'type' => 'object',
                'required' => false,
                'properties' => [
                    'src' => [
                        'type' => 'string',
                        'required' => true,
                    ],
                    'alt' => [
                        'type' => 'string',
                        'required' => true,
                    ],
                ],
            ],
            'schema' => [
                'type' => 'object',
                'required' => false,
                'properties' => [
                    '@type' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'MedicalProcedure',
                    ],
                    'name' => [
                        'type' => 'string',
                        'required' => true,
                    ],
                    'description' => [
                        'type' => 'string',
                        'required' => true,
                    ],
                    'bodyLocation' => [
                        'type' => 'string',
                        'required' => false,
                    ],
                    'procedureType' => [
                        'type' => 'string',
                        'required' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get treatment data using adapter pattern
     *
     * @return array Treatment data
     */
    public static function get_treatments(): array {
        static $adapter = null;

        if ($adapter === null) {
            $adapter = new TreatmentsAdapter();
        }

        return $adapter->get_treatments();
    }

    /**
     * Get single treatment by ID
     *
     * @param string $id Treatment ID
     * @return array|null Treatment data or null if not found
     */
    public static function get_treatment($id): ?array {
        static $adapter = null;

        if ($adapter === null) {
            $adapter = new TreatmentsAdapter();
        }

        return $adapter->get_treatment($id);
    }

    /**
     * Get treatments by category
     *
     * @param string $category Category slug
     * @return array Array of treatment data
     */
    public static function get_treatments_by_category($category): array {
        static $adapter = null;

        if ($adapter === null) {
            $adapter = new TreatmentsAdapter();
        }

        return $adapter->get_treatments_by_category($category);
    }

    /**
     * Get current data source type
     *
     * @return string Data source class name
     */
    public static function get_data_source_type(): string {
        static $adapter = null;

        if ($adapter === null) {
            $adapter = new TreatmentsAdapter();
        }

        return $adapter->get_data_source_type();
    }

    /**
     * Validate treatment data against schema
     *
     * @param array $treatment Treatment data to validate
     * @return bool|WP_Error True if valid, WP_Error if invalid
     */
    public static function validate_treatment(array $treatment) {
        $schema = self::get_schema();
        $errors = [];

        foreach ($schema as $field => $rules) {
            if ($rules['required'] && !isset($treatment[$field])) {
                $errors[] = sprintf(__('Missing required field: %s', 'medspa'), $field);
                continue;
            }

            if (isset($rules['enum']) && isset($treatment[$field]) && !in_array($treatment[$field], $rules['enum'])) {
                $errors[] = sprintf(__('Invalid value for %s. Must be one of: %s', 'medspa'), $field, implode(', ', $rules['enum']));
            }
        }

        return empty($errors) ? true : new WP_Error('invalid_treatment', __('Invalid treatment data', 'medspa'), $errors);
    }
}
