<?php
class Dynamic_Fields {
    // Replace placeholders with data (e.g., [CompanyName] → "ABC Corp")
    public static function replace_placeholders($html, $data) {
        foreach ($data as $placeholder => $value) {
            $html = str_replace("[$placeholder]", $value, $html);
        }
        return $html;
    }

    // Fetch data from WordPress (custom post meta, user input, etc.)
    public static function get_certification_data($post_id) {
        return [
            'CompanyName' => get_post_meta($post_id, 'organization_name', true),
            'client_type' => get_post_meta($post_id, 'client_type', true),
            // Add more fields as needed
        ];
    }
}
