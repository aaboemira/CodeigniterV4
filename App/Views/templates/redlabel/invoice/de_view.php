<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        *{
            font-size:14px;
            font-family: Arial, sans-serif;
        }
        h3{
            font-size:18px;
        }

        .order-table {
            width: 100%;
        }

        table {
            font-family: Arial, sans-serif; /* Updated font family */
            width: 100%;
            text-allign:center;
            border-collapse: collapse;
        }

        /* Style for the header-table (add borders) */
        .header-table {
            width: 100%; /* Make the header table full width */
            border: 1px solid black; /* Add a border around the header table */
        }


        .header-table th {
            text-align: center;
            font-size:15px;

        }
        .header-table td {
            font-size:12px;
            padding-left:4px;
        }
        .header-table tfoot td{
            font-size:12px;;
        }
        .header-table thead,.header-table tbody  {
            border-bottom:1px solid black;
        }
        .header-table tfoot tr td:first-child {
            background-color:#DCDCDC;
        }
        .header-table tbody tr td:first-child {
            background-color:#DCDCDC;
        }

        .order-details{
            width:45px;
            padding-top:35px !important;
        }
        /* Style for table cells */
        .order-details table td,
        .order-details table th,.order-table th ,.order-table td{
            border: none; /* Remove borders from regular cells */
            text-align: center;

        }
        .order-table td ,.order-table th{
            padding: 5px;
        }
        /* Style for table rows (except header and footer) */
        .order-details table tbody tr {
            border-top: none; /* Remove top border from regular rows */
        }
        .order-table thead tr{
            border-bottom:1px solid black;
        }
        .order-table tbody {
            border-bottom:1px solid black;
        }


        .user-details {
            width:350px;
            font-family: Arial, sans-serif; /* Use a specific font */
        }


        .user-details p {
            padding:0 !important;
            margin:0 !important;
            font-size: 14px;
        }
        #footer, .header {
            display: table;
            width: 100%;
        }

        /* Style for each column within the footer */
        .footer-column,.user-details, .order-details{
            display: table-cell;
            text-align: left;
            vertical-align: top;
            padding: 0 10px;
        }
        #footer{
            font-size:12px;
        }
        #footer {
            position: fixed;
            bottom: 55;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
        }
        .total-cell {
            width:100%;
            border-top: 1px solid black; /* Add border to the bottom of the cell */
        }
        .header-table thead tr,.header-table thead td{
            border-bottom:1px solid black;
        }
        .title{
            text-decoration:underline;
            font-size:11px !important;
            margin-bottom:6px !important;
        }
        .last td {
            padding:0 !important;
            margin:0 !important;
        }
    </style>
</head>
<body>
<footer id="footer">
    <div class="footer-column">
        <p>Node Devices GmbH<br>
            Neuhauserstr. 36c<br>
            70599 Stuttgart<br>
            Geschäftsführer: Stefan Nothdurft</p>
    </div>
    <div class="footer-column">
        <p>Handelsregister: HRB778133<br>
            Amtsgericht: Stuttgart<br>
            Ust-IdNr.: DE341495844<br>
            St.-Nr.: 99030/08057</p>
    </div>
    <div class="footer-column">
        <p>Bank: Finom<br>
            IBAN: DE43110101015633713256<br>
            BIC (SWIFT-Code): SOBKDEB2XXX<br>
            PayPal: kontakt@nodedevices.de</p>
    </div>
</footer>
<main>
    <div style="margin-bottom:70px;">
        <div style="text-align: right;">
            <!-- Logo Image -->
        </div>
        <p class="title" style="margin-top:30px;padding-left:10px; text-align:left;">Node Devices GmbH • Neuhauserstr. 36c • 70599 Stuttgart</p>

        <div class="header">
        hello

            <!-- Order Details -->
            <!-- Your table and other details -->
        </div>
        <h3 style="text-align:left">Invoice</h3>
        <table class="order-table">
            <thead>
                <!-- Table Headers -->
            </thead>
            <tbody>
                <?php foreach ($arr_products as $product): ?>
                    <tr>
                        <!-- Product details -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <!-- Footer Totals -->
            </tfoot>
        </table>

        <!-- Additional Information -->
    </div>
</main>
</body>
</html>
