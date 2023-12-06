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
    .status-indicator {
        width: 20px;  /* Adjust size as needed */
        height: 20px; /* Adjust size as needed */
        border-radius: 50%; /* Makes it a circle */
        display: inline-block;
    }

    .green {
        background-color: green;
    }

    .red {
        background-color: red;
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
            <?php if (session('error')) { ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if (session('success')) { ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <h1 style="color:black;font-size: 2.5em;"><?= lang_safe('my_smart_home_devices') ?></h1>
            <div class="add-device-button">
                <a href="<?= base_url('/smartdevices/add') ?>" class="btn btn-primary">Add New Device</a>
            </div>
            <!-- Bootstrap Modal for Spinner -->
            <div class="modal " id="global-spinner-modal" tabindex="-1" role="dialog" aria-labelledby="spinnerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <!-- Spinner (you can replace this with any spinner of your choice) -->
                            <div class="spinner">
                                <i class="fa fa-spinner fa-spin fa-3x"></i>
                            </div>
                            <p>Loading...</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="table-responsive">
                <?php if (empty($devices)): ?>
                    <p><?= lang_safe('no_devices_found') ?></p>
                <?php else: ?>


                <table class="table custom-table">
                    <thead>
                    <tr>
                        <th><?= lang_safe('pos') ?></th>
                        <th><?= lang_safe('name') ?></th>
                        <th><?= lang_safe('serial_number') ?></th>
                        <th><?= lang_safe('state') ?></th>
                        <th><?= lang_safe('is_connected') ?></th>
                        <th><?= lang_safe('control') ?></th>
                        <th><?= lang_safe('manage') ?></th>
                        <th><?= lang_safe('refresh') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($devices)) {
                        foreach ($devices as $device) {
                            ?>
                            <tr>
                                <div id="loading-indicator-<?= $device['device_id'] ?>" style="display: none;">
                                    <!-- Your loading spinner here -->
                                </div>
                                <td><?= $device['device_id'] ?></td>
                                <td><?= $device['device_name'] ?></td>
                                <td><?= $device['serial_number'] ?></td>
                                <td><?= $device['state'] ?></td>
                                <td class="connected-cell">
                                    <div class="status-indicator <?= $device['connected'] ? 'green' : 'red' ?>"></div>
                                </td>
                                <td>
                                    <a class="icon-border" href="<?= base_url('/devices/show/' . $device['device_id']) ?>"><i class="fa fa-cogs"><?= lang_safe('control') ?></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                            Manage
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="<?= base_url('/smartdevices/editDevice/' . $device['device_id']) ?>">
                                                    <i class="fa fa-pencil"></i> Edit Device
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url('/smartdevices/deleteDevice/' . $device['device_id']) ?>" onclick="return confirm('Are you sure you want to delete this device?');">
                                                    <i class="fa fa-trash"></i> Delete Device
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url('/smartdevices/accessControl/' . $device['device_id']) ?>">
                                                    <i class="fa fa-users"></i> Access Control
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn-refresh" data-device-id="<?= $device['device_id'] ?>" onclick="refreshStatus(<?= $device['device_id'] ?>)">Refresh</button>
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
                <?php endif; ?>
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
<script>
    function refreshStatus(deviceId) {
        $('#global-spinner-modal').modal('show');

        // Show a loading indicator here, e.g., a spinner next to the refresh button
        $.ajax({
            url: '<?= base_url('/smartdevices/refreshDeviceStatus') ?>',
            type: 'POST',
            data: { deviceId: deviceId },
            success: function(response) {
                // Update the UI with the new status
                var row = $('button[data-device-id="' + deviceId + '"]').closest('tr');
                row.find('.connected-cell').html(response.connected ? '<div class="status-indicator green"></div>' : '<div class="status-indicator red"></div>');
                row.find('.state-cell').text(response.state);
                $('#global-spinner-modal').modal('hide');

                // Hide the loading indicator
            },
            error: function() {
                alert("Error refreshing status");
                // Hide the loading indicator
            }
        });
    }
</script>

