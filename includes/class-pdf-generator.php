<?php
require_once plugin_dir_path(__FILE__) . '../dompdf/vendor/autoload.php';
use Dompdf\Dompdf;

class PDF_Generator {
    public static function init() {
        add_action('admin_post_generate_pdf', [__CLASS__, 'generate_pdf']);
    }

    // Generate and force-download PDF
    public static function generate_pdf() {
        // Security check
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'generate_pdf_nonce')) {
            wp_die('Invalid request.');
        }

        // Get data
        $post_id = intval($_POST['post_id']);
        $data = Dynamic_Fields::get_certification_data($post_id);
        $html = Certification_Templates::render_pdf_template([
            'type' => sanitize_text_field($_POST['certification_type']),
            'stage' => sanitize_text_field($_POST['stage'])
        ]);

        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("certification-{$post_id}.pdf");
        exit;
    }
}