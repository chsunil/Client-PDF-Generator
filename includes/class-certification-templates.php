<?php

/**
 * Handles certification template loading and rendering
 */
class Certification_Templates {

    /**
     * Initialize template system
     */
    public static function init() {
        add_shortcode('generate_certification_pdf', [__CLASS__, 'shortcode_handler']);
    }

    /**
     * Render PDF template with dynamic data
     */
    public static function render($args = []) {
        $defaults = [
            'post_id' => 0,
            'type'    => 'qms',
            'stage'   => '3'
        ];

        $args = wp_parse_args($args, $defaults);

        try {
            // Validate inputs
            if (!$args['post_id']) {
                throw new Exception('Missing post ID');
            }

            // Get template path
            $template_path = self::get_template_path(
                sanitize_file_name($args['type']),
                intval($args['stage'])
            );

            // Get replacement data
            $data = Dynamic_Fields::get_certification_data($args['post_id']);

            // Buffer output
            ob_start();
            include $template_path;
            $html = ob_get_clean();

            // Replace placeholders
            return self::replace_placeholders($html, $data);
        } catch (Exception $e) {
            error_log("[Certification Templates] Error: " . $e->getMessage());
            return '<div class="error">Error generating template: ' . esc_html($e->getMessage()) . '</div>';
        }
    }

    /**
     * Get absolute path to template file
     */
    private static function get_template_path($type, $stage) {
        $valid_stages = [1, 2, 3];

        // Validate stage
        if (!in_array($stage, $valid_stages)) {
            throw new Exception("Invalid stage: $stage");
        }

        // Build path
        $template_file = plugin_dir_path(__FILE__) .
            "../templates/" .
            sanitize_file_name($type) . "/" .
            "stage-{$stage}.php";

        // Verify template exists
        if (!file_exists($template_file)) {
            throw new Exception("Template not found: $type/stage-$stage");
        }

        return $template_file;
    }

    /**
     * Replace template placeholders with real values
     */
    private static function replace_placeholders($html, $data) {
        foreach ($data as $key => $value) {
            $placeholder = "[$key]";
            $html = str_replace(
                $placeholder,
                esc_html($value),
                $html
            );
        }
        return $html;
    }

    /**
     * Shortcode handler for frontend display
     */
    public static function shortcode_handler($atts) {
        $atts = shortcode_atts([
            'type'  => 'qms',
            'stage' => '3',
            'post_id' => get_the_ID()
        ], $atts);

        return self::render([
            'type'    => $atts['type'],
            'stage'   => $atts['stage'],
            'post_id' => $atts['post_id']
        ]);
    }
}
