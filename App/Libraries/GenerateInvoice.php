<?php
namespace App\Libraries;

require_once APPPATH.'Libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class GenerateInvoice
{

    protected $dompdf;

    public function __construct()
    {
        $this->dompdf = new Dompdf();
    }

    public function generatePdf($html, $paper = 'A4', $orientation = 'portrait')
    {
        $this->dompdf->set_option('isPhpEnabled', true); // Enable PHP for dynamic content
        $this->dompdf->set_option('isHtml5ParserEnabled', true); // Enable HTML5 parsing
        //$this->dompdf->set_option('isRemoteEnabled', true); // Allow loading remote content (including images)
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    function generateInvoiceHtml($order, $arr_products) {

        $html = '
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
            <img src="data:image/png;base64,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" height="70" alt="logo" border="0">
            </div>
            <p class="title" style="margin-top:30px;padding-left:10px; style="text-align:left;">Node Devices GmbH • Neuhauserstr. 36c • 70599 Stuttgart</p>

            <div class="header">';
        $userDetailsHtml = '<div class="user-details">';
        $userDetailsHtml .= '<p> ' . $order['billing_first_name'].' '.$order['billing_last_name'] . '';
        if (!empty($order['billing_company'])) {
            $userDetailsHtml .= ' <br>' . $order['billing_company'] . '';
        }
            $userDetailsHtml .= '<br>' . $order['billing_street'] . ' ' . $order['billing_housenr']. '';
            $userDetailsHtml .= '<br>' . $order['billing_post_code'] . ' ' . $order['billing_city']  . '';
            $userDetailsHtml .= '<br>' . $order['billing_country'] . '</p>';
            $userDetailsHtml .= '</div>';

            $html .= $userDetailsHtml;
        $html .= '
                <div class="order-details" >
                    <table class="header-table">
                        <thead">
                            <tr >
                                <th  colspan="2" style="text-align:left;padding-left:7px; font-weight:normal !important;":>Rechnung Nr. SHND ' .$order['order_id'].'  </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>                            
                            <td style="text-align:right">
                            Rechnungsdatum:
                            </td>
                            <td style="text-align:left">
                            '.$order['date'].'
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        
                            <tr>                            
                                <td style="text-align:right">
                                    Bestelldatum:
                                </td>
                                <td style="text-align:left">
                                '.$order['date'].'
                                </td>
                            </tr>
                            <tr >                            
                                <td style="text-align:right">
                                    Email:
                                </td>
                                <td style="text-align:left">
                                    kontakt@nodedevices.de
                                </td>
                            </tr>
                            <tr>                            
                                <td style="text-align:right">
                                    Internet:
                                </td>
                                <td style="text-align:left">
                                    www.nodedevices.de
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <h3 style="text-allign:left">
                Rechnung
            </h3>
            <table class="order-table">
                <thead>
                <tr>
                    <th>Pos.</th>
                    <th>Artikelnr.</th>
                    <th>Bezeichnung</th>
                    <th>Menge</th>
                    <th>MwSt.</th>
                    <th>Stück </th>
                    <th>Gesamt</th>
                </tr>
                </thead>
                <tbody>';
        $totalDiscount=0;
        $totalVat=0;
        $totalNet=0;
        $totalSub=0;
        $rowCount = 0; // Initialize a row count variable
        foreach ($arr_products as $product) {
            $rowCount++;
            $html .= '<tr>
                        <td>' . $rowCount . '</td>
                        <td>' . (!empty($product['product_info']['article_nr']) ? $product['product_info']['article_nr'] : '-') . '</td>
                        <td>' . $product['product_info']['title'] . '</td>
                        <td>' . $product['product_quantity'] . '</td>
                        <td>19%</td>
                        <td>' . number_format($product['product_info']['price'],2) .$order['currency']. '</td>
                        <td>' . number_format($product['product_info']['price'] * $product['product_quantity'], 2) .$order['currency']. '</td>
                    </tr>';
            $priceIncludingVAT = $product['product_info']['price'] * $product['product_quantity'];
            $discountPercentage = $order['discount'];
            $vatRate = 0.19; // 19% VAT rate
            $ProductNetTotal = round(($priceIncludingVAT / (1 + ($vatRate ))),2);

            // Calculate the VAT amount
            $ProductVat = round(($priceIncludingVAT - $ProductNetTotal),2);
            // Calculate the discount amount
            $ProductDiscount = round((($priceIncludingVAT * $discountPercentage) / 100),2);




            //Total For all products
            $totalDiscount+=$ProductDiscount;
            $totalVat+=$ProductVat;
            $totalSub+=$ProductNetTotal;
            $totalNet+=$priceIncludingVAT-$ProductDiscount;


        }
        $shipping_price = empty($order['shipping_price']) ? 0 : $order['shipping_price'];
        if ($shipping_price!= 0) {
            $netPriceForShipping = $shipping_price / (1 + ($vatRate ));
            $shippingVat=$shipping_price-$netPriceForShipping;
            $totalSub+=$netPriceForShipping;
            $totalVat+=$shippingVat;
            $totalNet+=$shipping_price;
            $rowCount++;
            $html .= '<tr>
                    <td>' . $rowCount . '</td>
                    <td>-</td>
                    <td>' . $order['shipping_type']. '</td>
                    <td>' . 1 . '</td>
                    <td>19%</td>
                    <td>' . number_format($order['shipping_price'],2) .$order['currency']. '</td>
                    <td>' . number_format($order['shipping_price'], 2) .$order['currency']. '</td>
                </tr>';
        }
        $html .= '</tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">Gesamt netto</td>
                        <td colspan="2">'.number_format($totalSub, 2).$order['currency'].'</td>
                    </tr>';
        if ($order['discount'] != 0) {
            $html .= '<tr>
                                <td colspan="3"></td>
                                    <td colspan="2">Rabatt</td>
                                    <td colspan="2" style="padding-right:10px;">-' . number_format($totalDiscount, 2).$order['currency']. '</td>
                                  </tr>';
        }


        $html.='        
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">zzgl. MwSt. 19.00%</td>
                        <td colspan="2">'.number_format($totalVat, 2).$order['currency'].'</td>
                    </tr>
                    ';
        $html.=' 
                    <tr class"total" style="padding-bottom:0 !important; margin-bottom:0 !important;">
                        <td colspan="3"></td>
                        <td colspan="2" style="font-weight:bold !important; padding-bottom:0 !important; margin-bottom:0 !important;">Gesamtbetrag</td>
                        <td colspan="2" style="font-weight:bold !important;padding-bottom:0 !important; margin-bottom:0 !important;">'.number_format($totalNet, 2).$order['currency'].'</td>
                    </tr>
                    <tr class"last">
                        <td colspan="3"></td>
                        <td colspan="4"><div class="total-cell" ></div><div style="margin-top:2px;" class="total-cell"></div></td>
                    <tr>

                </tfoot>
            </table>
            <p>
                Soweit nichts anderes angegeben ist, gilt der Zeitpunkt der Rechnungsausstellung als Zeitpunkt der Leistung.
            </p>
            <p style="margin:0 !important;">
                Zahlungsart: ' .$order['payment_type']. ' </p>
            <p style="margin:0 !important;">
                Versandart: ' . $order['shipping_type'].'
            </p>
            <p>
                Die Ware bleibt bis zur vollständigen Bezahlung unser Eigentum.
                Es gelten unsere allgemeinen Geschäftsbedingungen.
            </p>
            <p>
                Falls Sie Fragen zu Ihrer Bestellung haben, können Sie unsere Mailsupport-Hotline gerne jederzeit über <b style="color:red;text-decoration: underline;">kontakt@nodedevices.de</b> kontaktieren.
            </p>
            <p>
                Freundliche Grüße<br>
                Node Devices GmbH
            </p>
            <p style="text-align:center !important;">- Dieses Formular wurde maschinell erstellt und ist ohne Unterschrift gültig -</p>
            </div>
        </body>
        </html>';

        return $this->generatePdf($html);
    }

    function generateInvoiceHtmlEnglish($order, $arr_products) {
        $html = '<!DOCTYPE html>
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
                text-align:center;
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
            Managing Director: Stefan Nothdurft</p>
        </div>
        <div class="footer-column">
            <p>Commercial Register: HRB778133<br>
            Local Court: Stuttgart<br>
            VAT ID: DE341495844<br>
            Tax Number: 99030/08057</p>
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
            <img src="data:image/png;base64,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" height="70" alt="logo" border="0">
        </div>
        <p class="title" style="margin-top:30px;padding-left:10px; text-align:left;">Node Devices GmbH • Neuhauserstr. 36c • 70599 Stuttgart</p>

        <div class="header">';
        $userDetailsHtml = '<div class="user-details">';
        $userDetailsHtml .= '<p> ' . $order['billing_first_name'].' '.$order['billing_last_name'] . '';
        if (!empty($order['billing_company'])) {
            $userDetailsHtml .= ' <br>' . $order['billing_company'] . '';
        }
        $userDetailsHtml .= '<br>' . $order['billing_street'] . ' ' . $order['billing_housenr']. '';
        $userDetailsHtml .= '<br>' . $order['billing_post_code'] . ' ' . $order['billing_city']  . '';
        $userDetailsHtml .= '<br>' . $order['billing_country'] . '</p>';
        $userDetailsHtml .= '</div>';
        $html .= $userDetailsHtml;

        $html .= '
            <div class="order-details" >
                <table class="header-table">
                    <thead">
                        <tr >
                            <th  colspan="2" style="text-align:left;padding-left:7px; font-weight:normal !important;":>Invoice No. SHND ' .$order['order_id'].'  </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>                            
                        <td style="text-align:right">
                        Invoice Date:
                        </td>
                        <td style="text-align:left">
                        '.$order['date'].'
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>

                        <tr>                            
                            <td style="text-align:right">
                                Order Date:
                            </td>
                            <td style="text-align:left">
                            '.$order['date'].'
                            </td>
                        </tr>
                        <tr >                            
                            <td style="text-align:right">
                                Email:
                            </td>
                            <td style="text-align:left">
                                kontakt@nodedevices.de
                            </td>
                        </tr>
                        <tr>                            
                            <td style="text-align:right">
                                Website:
                            </td>
                            <td style="text-align:left">
                                www.nodedevices.de
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <h3 style="text-align:left">
            Invoice
        </h3>
        <table class="order-table">
            <thead>
            <tr>
                <th>Item</th>
                <th>Article No.</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>VAT</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>';
        $totalDiscount=0;
        $totalVat=0;
        $totalNet=0;
        $totalSub=0;
        $rowCount = 0; // Initialize a row count variable
        foreach ($arr_products as $product) {
            $rowCount++;
            $html .= '<tr>
                    <td>' . $rowCount . '</td>
                    <td>' . (!empty($product['product_info']['article_nr']) ? $product['product_info']['article_nr'] : '-') . '</td>
                    <td>' . $product['product_info']['title'] . '</td>
                    <td>' . $product['product_quantity'] . '</td>
                    <td>19%</td>
                    <td>' . number_format($product['product_info']['price'],2) .$order['currency']. '</td>
                    <td>' . number_format($product['product_info']['price'] * $product['product_quantity'], 2) .$order['currency']. '</td>
                </tr>';
            $priceIncludingVAT = $product['product_info']['price'] * $product['product_quantity'];
            $discountPercentage = $order['discount'];
            $vatRate = 0.19; // 19% VAT rate
            $ProductNetTotal = round(($priceIncludingVAT / (1 + ($vatRate ))),2);

            // Calculate the VAT amount
            $ProductVat = round(($priceIncludingVAT - $ProductNetTotal),2);
            // Calculate the discount amount
            $ProductDiscount = round((($priceIncludingVAT * $discountPercentage) / 100),2);




            //Total For all products
            $totalDiscount+=$ProductDiscount;
            $totalVat+=$ProductVat;
            $totalSub+=$ProductNetTotal;
            $totalNet+=$priceIncludingVAT-$ProductDiscount;


        }
        $shipping_price = empty($order['shipping_price']) ? 0 : $order['shipping_price'];
        if ($shipping_price!= 0) {
            $netPriceForShipping = $shipping_price / (1 + ($vatRate ));
            $shippingVat=$shipping_price-$netPriceForShipping;
            $totalSub+=$netPriceForShipping;
            $totalVat+=$shippingVat;
            $totalNet+=$shipping_price;
            $rowCount++;
            $html .= '<tr>
                <td>' . $rowCount . '</td>
                <td>-</td>
                <td>' . $order['shipping_type']. '</td>
                <td>' . 1 . '</td>
                <td>19%</td>
                <td>' . number_format($order['shipping_price'],2) .$order['currency']. '</td>
                <td>' . number_format($order['shipping_price'], 2) .$order['currency']. '</td>
            </tr>';
        }
        $html .= '</tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2">Total Net</td>
                    <td colspan="2">'.number_format($totalSub, 2).$order['currency'].'</td>
                </tr>';
        if ($order['discount'] != 0) {
            $html .= '<tr>
                            <td colspan="3"></td>
                                <td colspan="2">Discount</td>
                                <td colspan="2"style="padding-right:10px;">-' . number_format($totalDiscount, 2).$order['currency']. '</td>
                              </tr>';
        }


        $html.='        
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2">VAT 19.00%</td>
                    <td colspan="2">'.number_format($totalVat, 2).$order['currency'].'</td>
                </tr>
                ';
        $html.=' 
                <tr class"total" style="padding-bottom:0 !important; margin-bottom:0 !important;">
                    <td colspan="3"></td>
                    <td colspan="2" style="font-weight:bold !important; padding-bottom:0 !important; margin-bottom:0 !important;">Total Amount</td>
                    <td colspan="2" style="font-weight:bold !important;padding-bottom:0 !important; margin-bottom:0 !important;">'.number_format($totalNet, 2).$order['currency'].'</td>
                </tr>
                <tr class"last">
                    <td colspan="3"></td>
                    <td colspan="4"><div class="total-cell" ></div><div style="margin-top:2px;" class="total-cell"></div></td>
                <tr>

            </tfoot>
        </table>
        <p>
            Unless otherwise stated, the time of invoicing is considered the time of performance.
        </p>
        <p style="margin:0 !important;">
            Payment Method: ' .$order['payment_type']. ' </p>
        <p style="margin:0 !important;">
            Shipping Method: ' . $order['shipping_type'].'
        </p>
        <p>
            The goods remain our property until full payment is received.
            Our general terms and conditions apply.
        </p>
        <p>
            If you have any questions about your order, feel free to contact our email support hotline anytime at <b style="color:red;text-decoration: underline;">kontakt@nodedevices.de</b>.
        </p>
        <p>
            Kind Regards<br>
            Node Devices GmbH
        </p>
        <p style="text-align:center !important;">- This form was generated by a machine and is valid without a signature -</p>
        </div>
    </body>
    </html>';

        return $this->generatePdf($html);
    }


}
















