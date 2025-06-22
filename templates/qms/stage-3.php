<?php
// templates/qms/stage-3.php
$data = Dynamic_Fields::get_certification_data($post_id);
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        /* Match original PDF styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px 30px;
            font-size: 12pt;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <!-- Page 1 -->
    <div class="header">
        <h1>CERTIFICATION AGREEMENT inside</h1>
        <p><strong>GLOBAL MANAGEMENT CERTIFICATION SERVICES PRIVATE LIMITED.</strong><br>
            Flat No. 402, Plot No. 410, Matusri Nagar, Miyapur, Hyderabad – 500 049, India.<br>
            Tel: 040-4855 9001, E-mail: info@mcsglobal.in; Website: www.mcsglobal.in</p>
    </div>

    <div class="footer">
        Global MCS 1/9 F-03 QMS (Version 5.00, 30.10.2023)
    </div>

    <!-- Dynamic Fields Table (Page 1) -->
    <table>
        <tr>
            <th>Proposal Ref No:</th>
            <td>[client_type]</td>
            <th>Dated:</th>
            <td>[Date]</td>
            <th>Revision No:</th>
            <td>00</td>
        </tr>
        <tr>
            <th colspan="6">Company Name:</th>
            <td colspan="5">[CompanyName]</td>
        </tr>
        <tr>
            <th colspan="6">Address:</th>
            <td colspan="5">Address</td>
        </tr>
        <tr>
            <th colspan="6">Site(S):</th>
            <td colspan="5">[Sites]</td>
        </tr>
        <tr>
            <th colspan="6">Contact Person:</th>
            <td colspan="5">ContactPerson</td>
        </tr>
        <tr>
            <th colspan="6">Contact Numbers:</th>
            <td colspan="5">ContactNumbers</td>
        </tr>
        <tr>
            <th colspan="6">Certification Scheme:</th>
            <td colspan="5">CertificationScheme</td>
        </tr>
        <tr>
            <th colspan="6">Standard:</th>
            <td colspan="5">Standard</td>
        </tr>
    </table>
</body>

</html>