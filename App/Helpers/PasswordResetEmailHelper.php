<?php

namespace App\Helpers;
class PasswordResetEmailHelper
{

    function generateEmailHTML($data,$resetLink)
    {

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
            </style>
        </head>
        <body>
            <div class="container">
                <img class="nav_logo" alt="Brand" src="https://www.nodedevices.de/png/NODEMATIC_SMALL_TR.png">
                <hr style="margin-top: 5px;">
                    <h1>Hallo ' . $data->first_name .' '.$data->last_name .',  </h1>
                    <p>Jemand hat eine Zurücksetzung des Passworts für Ihr Konto angefordert. Wenn Sie das nicht waren, können Sie diese E-Mail sicher ignorieren.</p>
                    <p>Klicken Sie auf den folgenden Link, um Ihr Passwort zurückzusetzen:</p>
                    <a href="' . $resetLink . '">'.$resetLink.'</a>
                    <p>Wenn Sie diesen Link nicht innerhalb von 2 Stunden verwenden, läuft er aus Sicherheitsgründen ab.</p>
                    <p>Vielen Dank, dass Sie unseren Service nutzen.</p>
                    <p>Ihr Nodematic-Team</p>
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
        </body>
    </html>';

        return $template;
    }

    function generateEmailHTML_en($data,$resetLink)
    {

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

            </style>
        </head>
        <body>
            <div class="container">
                <img class="nav_logo" alt="Brand" src="https://www.nodedevices.de/png/NODEMATIC_SMALL_TR.png">
                <hr style="margin-top: 5px;">
                    <h1>Hello ' . $data->first_name .' '.$data->last_name .',</h1>
                    <p>Someone has requested a password reset for your account. If this was not you, you can safely ignore this email.</p>
                    <p>Aktuelles Datum: '.$data->expirationTime.'</p>
                    <p>Click on the following link to reset your password:</p>
                    <a style="font-size:12px;" href="' . $resetLink . '">'.$resetLink.'</a>
                    <p>If you do not use this link within 2 hours, it will expire for security reasons.</p>
                    <p>Thank you for using our service.</p>
                    <p>Your Nodematic  Team</p>
    
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
        </body>
    </html>';

        return $template;
    }
}