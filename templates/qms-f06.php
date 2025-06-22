<?php
/**
 * stage-6.php
 * Builds the F-06 Documentation Review Report HTML for PDF generation.
 * Expects $post_id to be the client post ID.
 */

// fetch our ACF fields
$org          = get_field('organization_name',         $post_id) ?: '—';
$date         = get_field('f06_date',                  $post_id) ?: date('d-m-Y');
$standard     = get_field('f06_standard',              $post_id) ?: '—';
$auditors     = get_field('f06_auditors',              $post_id) ?: [];
$sites        = get_field('f06_sites',                 $post_id) ?: [];
$quality_date = get_field('f06_quality_manual_date',   $post_id) ?: '';
$quality_no   = get_field('f06_quality_manual_issueno',$post_id) ?: '';
$clauses      = get_field('f06_clauses',               $post_id) ?: [];

// start buffering
ob_start();
?>

<style>
  body { font-family: Arial, sans-serif; font-size:12px; }
  table { border-collapse: collapse; width:100%; }
  th, td { border:1px solid #333; padding:6px; vertical-align:top; }
  th { background:#eee; }
  .header td { border:none; padding:4px; }
  .header .title { text-align:center; font-size:16px; font-weight:bold; }
</style>

<table class="header">
  <tr>
    <td><?php echo get_stylesheet_directory_uri() ?>/assets/logo.png</td>
    <td class="title">DOCUMENTATION REVIEW REPORT</td>
    <td>F-06 (Version 2.00, 20.03.2016)</td>
  </tr>
</table>

<table class="header" style="margin-top:8px;">
  <tr>
    <td><strong>Organization:</strong> <?php echo esc_html($org) ?></td>
    <td><strong>Date:</strong> <?php echo esc_html($date) ?></td>
  </tr>
  <tr>
    <td><strong>Standard:</strong> <?php echo esc_html($standard) ?></td>
    <td><strong>Auditor(s):</strong> <?php echo esc_html( implode(', ',$auditors) ) ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Sites:</strong> <?php echo esc_html( implode('; ',$sites) ) ?></td>
  </tr>
  <tr>
    <td><strong>Quality Manual Date:</strong> <?php echo esc_html($quality_date) ?></td>
    <td><strong>Issue No:</strong> <?php echo esc_html($quality_no) ?></td>
  </tr>
</table>

<table style="margin-top:12px;">
  <tr>
    <th style="width:8%">Cl No</th>
    <th style="width:52%">Requirement</th>
    <th style="width:10%">Compliance</th>
    <th>Comments</th>
  </tr>

  <?php foreach($clauses as $row): 
    $cl   = esc_html($row['f06_clause_number']);
    $req  = esc_html($row['f06_requirement']);
    $yes  = $row['f06_compliance'] ? 'Yes' : '';
    $cmt  = esc_html($row['f06_comments']);
  ?>
    <tr>
      <td><?php echo $cl ?></td>
      <td><?php echo $req ?></td>
      <td style="text-align:center"><?php echo $yes ?></td>
      <td><?php echo $cmt ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<?php
// grab the HTML and return it
return ob_get_clean();
