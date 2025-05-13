<?php
/*
Plugin Name: Client PDF Generator
Description: Generate PDFs for ACF 'client' posts via AJAX and DOMPDF.
Version: 1.0
Author: Sunil
*/

if (! defined('ABSPATH')) exit;

/**
 * 1) Load DOMPDF
 */
function cpdf_load_dompdf() {
    // Adjust path if you unpacked dompdf somewhere else
    if (! class_exists('Dompdf\Dompdf')) {
        require_once plugin_dir_path(__FILE__) . 'dompdf/autoload.inc.php';
    }
}
add_action('init', 'cpdf_load_dompdf');


/**
 * 2) Enqueue front-end script + localize our AJAX URL + nonce
 */
function cpdf_enqueue_scripts() {
    error_log('[PDF] Checking page conditions. Current page ID: ' . get_queried_object_id());

    // Match your exact client list page slug
    if (is_page('all-clients')) {
        error_log('[PDF] Loading scripts for client list page');

        wp_enqueue_script(
            'cpdf-generate-pdf',
            plugin_dir_url(__FILE__) . 'assets/js/generate-pdf.js',
            array(),
            '1.0',
            true
        );
        wp_localize_script('cpdf-generate-pdf', 'cpdfVars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('generate_pdf_public'),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'cpdf_enqueue_scripts');


/**
 * 3) AJAX handler: generate the PDF
 */
// client-pdf-generator.php
function cpdf_handle_generate_pdf() {
    try {
        // 3a) Security checks
        if (!is_user_logged_in()) {
            throw new Exception('Unauthorized. Please log in.', 401);
        }
        // Verify capabilities
        if (!current_user_can('edit_posts')) {
            throw new Exception('Permission denied', 403);
        }
        // Manual nonce verification
        $nonce = $_POST['nonce'] ?? '';
        if (!wp_verify_nonce(sanitize_text_field($nonce), 'generate_pdf_public')) {
            error_log("[PDF] Nonce failed. Received: $nonce | Expected: " . wp_create_nonce('generate_pdf_public'));
            throw new Exception('Security check failed', 403);
        }

        // 3b) Validate post ID
        $post_id = intval($_POST['post_id'] ?? 0);
        error_log("[PDF Generator] Validating Post ID: $post_id");
        if (!$post_id || get_post_type($post_id) !== 'client') {
            throw new Exception('Invalid post ID', 400);
        }

        // 3c) Gather ACF fields
        error_log("[PDF Generator] Fetching ACF fields for Post ID: $post_id");
        $client_name = get_field('organization_name', $post_id) ?: 'Client Name Not Found';
        // $client_address = get_field('address', $post_id) ?: 'Client Address Not Found';

        // 3d) Build HTML
        $html = "
            <h1>Client Agreement</h1>
            <p><strong>Client:</strong> $client_name </p>
            <p><strong>Address:</strong></p>
        ";

        // 3e) Generate PDF with DOMPDF
        error_log("[PDF Generator] Initializing DOMPDF");
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 3f) Save PDF
        $upload = wp_upload_dir();
        $dir = trailingslashit($upload['basedir']) . 'client_pdfs/';
        if (!file_exists($dir)) {
            error_log("[PDF Generator] Creating directory: $dir");
            wp_mkdir_p($dir);
        }
        $filename = "agreement-{$post_id}-" . date('Ymd') . '.pdf';
        $path = $dir . $filename;

        error_log("[PDF Generator] Saving PDF to: $path");
        $bytes_written = file_put_contents($path, $dompdf->output());
        if ($bytes_written === false) {
            throw new Exception('Failed to save PDF file', 500);
        }

        // 3g) Update ACF field
        $url = trailingslashit($upload['baseurl']) . "client_pdfs/{$filename}";
        update_field('generated_pdf_url', $url, $post_id);
        error_log("[PDF Generator] PDF generated successfully: $url");

        wp_send_json_success(['pdf_url' => $url]);
    } catch (Exception $e) {
        error_log("[PDF Generator ERROR] " . $e->getMessage());
        wp_send_json_error([
            'message' => $e->getMessage(),
            'code'    => $e->getCode()
        ], $e->getCode() ?: 500);
    }
}
add_action('wp_ajax_generate_pdf', 'cpdf_handle_generate_pdf');
add_action('wp_ajax_nopriv_generate_pdf', 'cpdf_handle_generate_pdf');
