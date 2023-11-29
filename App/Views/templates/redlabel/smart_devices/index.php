<style>
    .table-responsive .table th,
    .table-responsive .table td {
        border-top: none; /* Remove default top border */
        border-right: none; /* Remove default right border */
        border-left: none; /* Remove default left border */
        border-bottom: 1px solid #ccc; /* Add a bottom border */
        padding: 8px;
        text-align: center;
    }

    .table-responsive .table th {
        background-color: #f0f0f0; /* Grey background for th */
    }
    .icon-border {
        position: relative;
        top:-5px;
        border: 1px solid #ccc; /* Adjust color and thickness of border as needed */
        border-radius: 4px; /* Optional: rounds the corners of the border */
        padding: 5px; /* Adjust padding to increase the clickable area around the icon */
        display: inline-block; /* Ensures the padding and border are applied properly */
        margin-right: 5px; /* Optional: adds some space to the right of the icon */
        /* Additional optional styles */
        text-align: center;
        transition: background-color 0.3s ease; /* Smooth transition for hover effect */
    }

    .icon-border:hover {
        background-color: #f0f0f0; /* Slight background color on hover for visual feedback */
        text-decoration: none; /* Removes the underline text decoration from the anchor tag on hover */
    }
</style>
<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
            <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
            <li><?= lang_safe('my_smart_home_devices') ?></li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9">
            <h1 style="color:black;font-size: 2.5em;"><?= lang_safe('my_smart_home_devices') ?></h1>
            <hr>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                    <tr>
                        <th><?= lang_safe('pos') ?></th>
                        <th><?= lang_safe('serial_number') ?></th>
                        <th><?= lang_safe('state') ?></th>
                        <th><?= lang_safe('is_connected') ?></th>
                        <th><?= lang_safe('control') ?></th>
                        <th><?= lang_safe('manage') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($devices)) {
                        foreach ($devices as $device) {
                            ?>
                            <tr>
                                <td><?= $device['DeviceID'] ?></td>
                                <td><?= $device['SerialNumber'] ?></td>
                                <td><?= $device['State'] ?></td>
                                <td><?= $device['IsConnected'] ? lang_safe('yes') : lang_safe('no') ?></td>
                                <td>
                                    <a class="icon-border" href="<?= base_url('/devices/show/' . $device['DeviceID']) ?>"><i class="fa fa-cogs"><?= lang_safe('control') ?></i>
                                    </a>
                                </td>
                                <td>
                                    <a class="icon-border" href="<?= base_url('/devices/show/' . $device['DeviceID']) ?>"><i class="fa fa-cogs"><?= lang_safe('manage') ?></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6"><?= lang_safe('no_devices_found') ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <nav aria-label="Page navigation">
                    <ul class="pagination" style="position:relative !important;z-index:2;">
                        <?= $paginationLinks ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

</div>
