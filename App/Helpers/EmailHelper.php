<?php
namespace App\Helpers;
class EmailHelper {

function generateEmailHTML($orderData) {
    $netTotal=0.00;
    $orderData['shipping_price'] = empty($orderData['shipping_price']) ? 0 : $orderData['shipping_price'];
    $shippingPrice = is_numeric($orderData['shipping_price']) ? floatval($orderData['shipping_price']) : 0;
    $shippingPrice=number_format($shippingPrice,2);
    
    $discount = is_numeric($orderData['discount']) ? floatval($orderData['discount']) : 0;
    $discount=number_format($discount,2);
    // Define your template HTML here
    $template = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f5f5f5;
                    color:black;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    font-size:14px;
                }
                /* Add your CSS styles here for table formatting */
                table {
                    width: 100%;
                    border-collapse: collapse;
                    table-layout:fixed; 
                }
                th, td {
                    border-top: none; /* Remove default top border */
                    border-right: none; /* Remove default right border */
                    border-left: none; /* Remove default left border */
                    border-bottom: 1px solid #ccc; /* Add a bottom border */
                    padding: 8px;
                    text-align: center;
                }


                th {
                    background-color: #f0f0f0; /* Grey background for th */
                }

                h1 {
                    font-size: 22px;
                    font-weight: bold; /* Bold */
                    margin-bottom: 10px; /* Add space below */
                }
                h2 {
                    font-size: 18px; /* Adjust font size */
                    color: #930313; /* Red color */
                    margin-bottom:2px !important;

                }

                h3 {
                    font-size: 16px; /* Adjust font size */
                    margin-bottom:2px !important;
                }

                p {
                    font-size: 14px;
                    line-height: 1.5;
                    margin-bottom: 5px;
                }
                .signature {
                    font-style: italic;
                    font-size: 14px;
                    color: #777;
                }

                .centered {
                    text-align: center;
                    margin-top: 20px; /* Add space above */
                    margin-bottom: 20px; /* Add space below */
                }
                table tfoot tr td{
                    text-align:right;
                    padding:4px !important;
                    padding-right:0 !important;
                    padding-left:0 !important;
                }
                @media (max-width: 768px) {
                    .container table th,.container table td {
                        font-size:10px !important;
                    }
                }
                @media (max-width: 450px) {
                    .container table th,.container table td {
                        padding:3px 0 !important;
                    }
                }
                @media (max-width: 380px) {
                    .container table th,.container table td {
                        padding:2px 0 !important;
                    }
                    .container table th,.container table td {
                        font-size:8px !important;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">
            <img class="nav_logo" alt="Brand" src="https://www.nodedevices.de/png/NODEMATIC_SMALL_TR.png">
            <hr style="margin-top: 5px;">
            <h1>VIELEN DANK FÜR IHRE BESTELLUNG!</h1>

            <h2>Eingangsbestätigung Ihrer Bestellung</h2>
            <h3>
                Hallo '.$orderData['billing_full_name'].',
            </h3>
            <p>
                hiermit bestätigen wir Ihre Bestellung, Ihre Bestellnummer: SHND'.$orderData['order_id'].'.
                <br>
            </p>
            <p>Wir prüfen Ihre Bestellung und melden uns in Kürze per Email bei Ihnen.</p>
            <p>Informationen zu Ihrer Bestellung:</p>
            <p>Bestelleingang: '.$orderData['order_date'].'</p>

            <table style="font-size: 12px !important;">
                <col width=10 />
                <col width=20 />
                <col width=28 />
                <col width=12 />
                <col width=15 />
                <col width=15 />
                <thead>
                <tr>
                    <th>Pos.</th>
                    <th>Produkt</th> <!-- German version -->
                    <th>Name</th>
                    <th>Menge</th> <!-- German version -->
                    <th>Preis</th>
                    <th>Summe</th> <!-- German version -->
                </tr>
                </thead>
                <tbody>';

    // Loop through order data and add product rows
    $i=0;
            foreach ($orderData['products'] as $product) {
                $productSubTotal= str_replace(',', '', $product["product_info"]['price']*$product['product_quantity']);
                $netTotal += round(floatval($productSubTotal),2);
                $i++;
                $template .= '<tr>
                                <td>
                                '.$i.'
                                </td>
                                <td>
                                    <img width="30" src="'.$orderData['image_url'] . $product["product_info"]['image'] . '" alt="Product Image Placeholder">
                                </td>
                                <td>' . $product["product_info"]['title'] . '</td>
                                <td>' . $product['product_quantity'] . '</td>
                                <td>' . $product["product_info"]['price'] .$orderData['currency']. '</td>
                                <td>' . number_format($productSubTotal,2).$orderData['currency']. '</td>
                            </tr>';
            }            
            $template .= '</tbody>  
                        <tfoot>
                        <tr>
                        <td colspan="2" style="border:0;"></td>
                        <td colspan="3" style="text-align:right;border:0;">Zwischensumme :</td>
                        <td colspan="1" style="text-align:right;border:0;">'.number_format($netTotal,2).$orderData['currency'].'</td>
                        </tr>';
                        if(!($orderData['discount']==0)){
                            $template.='
                            <tr>
                            <td colspan="2" style="border:0;"></td>
                            <td colspan="3" style="text-align:right;border:0;">Gutscheincode :</td>
                            <td colspan="1" style="text-align:right;border:0;">-'.$discount.$orderData['currency'].'</td>
                            </tr>';
                            $netTotal-=$discount;
                        }
                            if(!($shippingPrice==0)){
                                $template.='
                                <tr>
                                    <td colspan="2" style="border:0;"></td>
                                    <td colspan="3" style="text-align:right;border:0;">'.$orderData['shipping_type'].':</td>
                                    <td colspan="1" style="text-align:right;border:0;">'.$shippingPrice.$orderData['currency'].'</td>                          
                                </tr>';
                                $netTotal+=$shippingPrice;
                            }
                            $template .='
                            <tr style="font-weight:bold;">
                            <td colspan="2" style="border:0;"></td>
                            <td colspan="3" style="text-align:right;border:0;">Gesamtbetrag :</td>
                            <td colspan="1" style="text-align:right;border:0;">'.number_format($netTotal,2).$orderData['currency'].'</td>
                            </tr>
                        </tfoot>
                        </table>
                
                   
                    <h3>Lieferadresse:</h3>
                    <p> '.$orderData['shipping_full_name'].'<br>
                    '.(!empty($orderData['address']['company']) ? $orderData['address']['company'].'<br>' : '').'
                    '.$orderData['address']['shipping_address']['shipping_street'].' ' .$orderData['address']['shipping_address']['shipping_housenr'].'<br>
                    '.$orderData['address']['shipping_address']['shipping_post_code'].' '.$orderData['address']['shipping_address']['shipping_city'].'<br>
                    '.$orderData['address']['shipping_address']['shipping_country'].'</p>

                
                    <h3>Rechnungsadresse:</h3>
                    <p> '.$orderData['billing_full_name'].'<br>
                    '.(!empty($orderData['address']['company']) ? $orderData['address']['company'].'<br>' : '').'
                    '.$orderData['address']['billing_address']['billing_street'].' ' .$orderData['address']['billing_address']['billing_housenr'].'<br>
                    '.$orderData['address']['billing_address']['billing_post_code'].' '.$orderData['address']['billing_address']['billing_city'].'<br>
                    '.$orderData['address']['billing_address']['billing_country'].'</p>

                    <p><span style="font-weight:bold;">Gewählte Zahlungsart: </span>'.$orderData['payment_type'].'</p>
                                
                    <p>Falls Sie Fragen zu Ihrer Bestellung haben, können Sie unsere Mailsupport-Hotline gerne jederzeit über <a href="mailto:kontakt@nodedevices.de" style="color: #930313;">kontakt@nodedevices.de</a> </p>
                
                    <p>Freundliche Grüße</p>
                    <p>Node Devices GmbH</p>
                
                    <hr style="margin-top: 5px;">
                    <div class=" signature centered" style="margin-top: 5px;"> 
                        <p>
                            NODEMATIC ist eine Marke der Node Devices GmbH<br>
                            Neuhauserstr. 36c<br>
                            70599 Stuttgart<br>
                            Email: <a href="mailto:kontakt@nodedevices.de" style="color: blue;">kontakt@nodedevices.de</a>
                            <br>Internet: <a href="https://www.nodedevices.de" style="color: blue;">www.nodedevices.de</a>
                        </p>
                    </div>
                    <div class=" signature centered" style="margin-top: 5px; margin-bottom: 5px;"> <!-- Centered content -->
                        <p>USt-IdNr. DE341495844<br>
                            Registergericht: Amtsgericht Stuttgart HRB 778133
                            <br>Geschäftsführer: Stefan Nothdurft
                        </p>
                    </div>
                    <p class="signature">
                        Diese e-Mail und alle Anhänge sind vertraulich. Die enthaltenen Informationen sind für den Gebrauch der angeführten Personen gedacht. Wenn Sie nicht einer der benannten oder gewünschten Empfänger sind, informieren Sie den Absender bitte sofort und geben Sie den Inhalt nicht an dritte Personen weiter, benutzen Sie den Inhalt nicht für andere Zwecke und speichern oder kopieren Sie den Inhalt nicht auf anderen Medien.
                        <br><br>
                        This e-mail and any attachments are confidential and privileged. The information is intended to be for the use of the individual(s) named above. If you are not the named or intended recipient, please notify the sender immediately and do not disclose the contents to another person, use it for any purpose, or store or copy the information in any medium.
                    </p>
                    </div>
                    </div>
                </body>
                </html>';

    return $template;
}

function generateEmailHTML_en($orderData) {
    $netTotal = 0.00;
    $orderData['shipping_price'] = empty($orderData['shipping_price']) ? 0 : $orderData['shipping_price'];
    $shippingPrice = is_numeric($orderData['shipping_price']) ? floatval($orderData['shipping_price']) : 0;
    $shippingPrice = number_format($shippingPrice, 2);

    $discount = is_numeric($orderData['discount']) ? floatval($orderData['discount']) : 0;
    $discount = number_format($discount, 2);

    // Define your template HTML here
    $template = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f5f5f5;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    font-size: 14px;
                }
                /* Add your CSS styles here for table formatting */
                table {
                    width: 100%;
                    border-collapse: collapse;
                    table-layout:fixed; 

                }
                th, td {
                    border-top: none; /* Remove default top border */
                    border-right: none; /* Remove default right border */
                    border-left: none; /* Remove default left border */
                    border-bottom: 1px solid #ccc; /* Add a bottom border */
                    padding: 8px;
                    text-align: center;
                }
                th {
                    background-color: #f0f0f0; /* Grey background for th */
                }

                h1 {
                    font-size: 22px;
                    font-weight: bold; /* Bold */
                    margin-bottom: 10px; /* Add space below */
                }
                h2 {
                    font-size: 18px; /* Adjust font size */
                    color: #930313; /* Red color */
                    margin-bottom: 2px !important;
                }

                h3 {
                    font-size: 16px; /* Adjust font size */
                    margin-bottom: 2px !important;
                }

                p {
                    font-size: 14px;
                    line-height: 1.5;
                    margin-bottom: 5px;
                }
                .signature {
                    font-style: italic;
                    font-size: 14px;
                    color: #777;
                }

                .centered {
                    text-align: center;
                    margin-top: 20px; /* Add space above */
                    margin-bottom: 20px; /* Add space below */
                }
                table tfoot tr td{
                    text-align:right;
                    padding:4px !important;
                    padding-right:0 !important;
                    padding-left:0 !important;
                }
                @media (max-width: 768px) {
                    .container table th,.container table td {
                        font-size:10px !important;
                    }
                }
                @media (max-width: 450px) {
                    .container table th,.container table td {
                        padding:3px 0 !important;
                    }

                }
                @media (max-width: 380px) {
                    .container table th,.container table td {
                        padding:2px 0 !important;
                    }
                    .container table th,.container table td {
                        font-size:4px !important;
                    }
                }

            </style>
        </head>
        <body>
            <div class="container">
            <img class="nav_logo" alt="Brand" src="https://www.nodedevices.de/png/NODEMATIC_SMALL_TR.png">
            <hr style="margin-top: 5px;">
            <h1>THANK YOU FOR YOUR ORDER!</h1>

            <h2>Order Confirmation</h2>
            <h3>
                Hello '.$orderData['billing_full_name'].',
            </h3>
            <p>
                We hereby confirm your order, your order number: SHND'.$orderData['order_id'].'.
                <br>
            </p>
            <p>We will review your order and get back to you via email shortly.</p>
            <p>Order Date: '.$orderData['order_date'].'</p>

            <table style="font-size: 12px ">
            <col width=10 />
            <col width=20 />
            <col width=28 />
            <col width=12 />
            <col width=15 />
            <col width=15 />
                <thead>
                <tr>
                    <th>POS</th>
                    <th>Product</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>';

    // Loop through order data and add product rows
    $i = 0;
    foreach ($orderData['products'] as $product) {
        $productSubTotal = str_replace(',', '', $product["product_info"]['price'] * $product['product_quantity']);
        $netTotal += round(floatval($productSubTotal), 3);
        $i++;
        $template .= '<tr>
                                <td>
                                '.$i.'
                                </td>
                                <td>
                                    <img width="30" src="'.$orderData['image_url'] . $product["product_info"]['image'] . '" alt="Product Image Placeholder">
                                </td>
                                <td>' . $product["product_info"]['title'] . '</td>
                                <td>' . $product['product_quantity'] . '</td>
                                <td>' . $product["product_info"]['price'] . $orderData['currency'] . '</td>
                                <td>' . number_format($productSubTotal, 2) . $orderData['currency'] . '</td>
                            </tr>';
    }
    $template .= '</tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" style="border:0;"></td>
                            <td colspan="3" style="text-align:right;border:0;">Subtotal :</td>
                            <td colspan="1" style="text-align:right;border:0;">'.number_format($netTotal,2).$orderData['currency'].'</td>
                        </tr>';
                        if (!($orderData['discount'] == 0)) {
                            $template .= '
                                <tr>
                                <td colspan="2" style="border:0;"></td>
                                <td colspan="3" style="text-align:right;border:0;">Discount :</td>
                                <td colspan="1" style="text-align:right;border:0;">-'.$discount.$orderData['currency'].'</td>
                                </tr>';
                            $netTotal -= $discount;
                        }
                        if (!($shippingPrice == 0)) {
                            $template .= '
                                <tr>
                                    <td colspan="2" style="border:0;"></td>
                                    <td colspan="3" style="text-align:right;border:0;">'.$orderData['shipping_type'].':</td>
                                    <td colspan="1" style="text-align:right;border:0;">'.$shippingPrice.$orderData['currency'].'</td>     
                                </tr>';
                            $netTotal += $shippingPrice;
                        }
                        $template .= '
                            <tr style="font-weight:bold;">
                            <td colspan="2" style="border:0;"></td>
                                <td colspan="3" style="text-align:right;border:0;">Total Amount :</td>
                                <td colspan="1" style="text-align:right;border:0;">'.number_format($netTotal, 2).$orderData['currency'].'</td>
                            </tr>
                        </tfoot>
                        </table>

                    <h3>Shipping Address:</h3>
                    <p> '.$orderData['shipping_full_name'].'<br>
                    '.(!empty($orderData['address']['company']) ? $orderData['address']['company'].'<br>' : '').'
                    '.$orderData['address']['shipping_address']['shipping_street'].' ' .$orderData['address']['shipping_address']['shipping_housenr'].'<br>
                    '.$orderData['address']['shipping_address']['shipping_post_code'].' '.$orderData['address']['shipping_address']['shipping_city'].'<br>
                    '.$orderData['address']['shipping_address']['shipping_country'].'</p>
                    
                    <h3>Billing Address:</h3>
                    <p> '.$orderData['billing_full_name'].'<br>
                    '.(!empty($orderData['address']['company']) ? $orderData['address']['company'].'<br>' : '').'
                    '.$orderData['address']['billing_address']['billing_street'].' ' .$orderData['address']['billing_address']['billing_housenr'].'<br>
                    '.$orderData['address']['billing_address']['billing_post_code'].' '.$orderData['address']['billing_address']['billing_city'].'<br>
                    '.$orderData['address']['billing_address']['billing_country'].'</p>
                


                    <p><span style="font-weight:bold;">Selected Payment Method: </span>'.$orderData['payment_type'].'</p>


                    <p>If you have any questions about your order, feel free to contact our email support hotline anytime at <a href="mailto:kontakt@nodedevices.de" style="color: #930313;">kontakt@nodedevices.de</a> </p>

                    <p>Best regards,</p>
                    <p>Node Devices GmbH</p>

                    <hr style="margin-top: 5px;">
                    <div class=" signature centered" style="margin-top: 5px;">
                        <p>
                            NODEMATIC is a brand of Node Devices GmbH<br>
                            Neuhauserstr. 36c<br>
                            70599 Stuttgart<br>
                            Email: <a href="mailto:kontakt@nodedevices.de" style="color: blue;">kontakt@nodedevices.de</a>
                            <br>Website: <a href="https://www.nodedevices.de" style="color: blue;">www.nodedevices.de</a>
                        </p>
                    </div>
                    <div class=" signature centered" style="margin-top: 5px; margin-bottom: 5px;"> <!-- Centered content -->
                        <p>VAT ID: DE341495844<br>
                            Commercial Register: Amtsgericht Stuttgart HRB 778133
                            <br>Managing Director: Stefan Nothdurft
                        </p>
                    </div>
                    <p class="signature">
                        Diese e-Mail und alle Anhänge sind vertraulich. Die enthaltenen Informationen sind für den Gebrauch der angeführten Personen gedacht. Wenn Sie nicht einer der benannten oder gewünschten Empfänger sind, informieren Sie den Absender bitte sofort und geben Sie den Inhalt nicht an dritte Personen weiter, benutzen Sie den Inhalt nicht für andere Zwecke und speichern oder kopieren Sie den Inhalt nicht auf anderen Medien.
                        <br><br>
                        This e-mail and any attachments are confidential and privileged. The information is intended to be for the use of the individual(s) named above. If you are not the named or intended recipient, please notify the sender immediately and do not disclose the contents to another person, use it for any purpose, or store or copy the information in any medium.
                    </p>
                    </div>
                    </div>
                </body>
                </html>';

    return $template;
}
}