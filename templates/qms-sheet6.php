<?php
/**
 * stage-06.php
 *
 * Generates the “Stage I Audit Registration” letter for PDF.
 * Expects $post_id to be set to the client’s post ID.
 */

// Pull all the ACF fields you need (create these in ACF)
$org             = get_field('_customer_name',        $post_id) ?: '—';
$date            = get_field('f06_date',                 $post_id) ?: date('d-m-Y');
$ref_no          = get_field('_ref_no',               $post_id) ?: '';
$site_address    = get_field('_address',         $post_id) ?: '';
$audit_date      = get_field('_audit_date',           $post_id) ?: '';
$audit_team      = get_field('_auditor_name',           $post_id) ?: [];
$audit_team_list = is_array($audit_team) 
                   ? implode(', ',$audit_team) 
                   : $audit_team;

// Start output buffering
ob_start();
?>

<style>
  body { font-family: Arial, sans-serif; font-size:12px; line-height:1.4; }
  .logo { text-align:center; margin-bottom:20px; }
  .logo img { max-width:150px; }
  .metadata { width:100%; margin-bottom:20px; }
  .metadata td { vertical-align:top; padding:4px; }
  .content { margin: 0 20px; }
  .content p { margin:10px 0; }
  .signature { margin-top:40px; }
</style>

<div class="logo">
  <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/logo.png" alt="Logo">
</div>

<table class="metadata">
  <tr>
    <td><strong>Date:</strong></td>
    <td><?php echo esc_html($date) ?></td>
  </tr>
  <tr>
    <td><strong>Ref No.:</strong></td>
    <td><?php echo esc_html($ref_no) ?></td>
  </tr>
</table>

<div class="content">
  <p><strong>To,</strong><br>
     <strong><?php echo esc_html($org) ?></strong><br>
     <?php echo nl2br(esc_html($site_address)) ?>
  </p>

  <p><strong>Sub:</strong> Stage – I Audit Registration</p>

  <p>Dear Sir,</p>

  <p>
    This is to inform you that <?php echo esc_html( get_field('f06_standard',$post_id) ?: 'ISO 9001:2015' ) ?>
    Stage I audit shall be conducted at your premises on Dt. <?php echo esc_html($audit_date) ?>.
  </p>

  <p>
    The audit team will consist of: <strong><?php echo esc_html($audit_team_list) ?></strong>.
  </p>

  <p>
    Kindly indicate any reservation or objection regarding members of the team before the audit date.
  </p>
  <p>
    Kindly extend your co-operation to the team.
  </p>
  <p>
    Also please confirm your availability and make necessary arrangements.  
    Also find attached the audit schedule for your reference.
  </p>

  <p>Thanking you,</p>

  <p>Yours faithfully,</p>

  <div class="signature">
    <p>For Global Management Certification Services Pvt. Ltd.</p>
    <p><em>Authorized Signatory</em></p>
  </div>
</div>

<?php
// Return the buffered HTML
return ob_get_clean();
