<style>
    .table-responsive .table th,
    .table-responsive .table td {
        border-top: none;
        /* Remove default top border */
        border-right: none;
        /* Remove default right border */
        border-left: none;
        /* Remove default left border */
        border-bottom: 1px solid #ccc;
        /* Add a bottom border */
        padding: 5px 10px !important;
        text-align: center;
        font-size: 1.7rem;
        vertical-align: middle;
    }
    .close-custom{
        opacity:0.7 !important;
    }
    .close-custom:hover{
        opacity:1 !important;
    }
    .table-responsive .table th {
        background-color: #f0f0f0;
        /* Grey background for th */
    }
    .table-responsive .table tbody tr:nth-child(odd) {
            background-color: #ffffff !important; /* White background for odd rows */
        }

        .table-responsive .table tbody tr:nth-child(even) {
            background-color: #f2f2f2 !important; /* Grey background for even rows */
        }
    .device-control-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    /* .dropdown{
        position:absolute;
        right:11.5%;
    } */
    .btn-device-action {
        background-color: #f2f2f2;
        /* Light grey background */
        color: #000;
        /* Black text */
        border: 2px solid #007bff;
        /* Bootstrap primary blue border */
        border-radius: 20px;
        /* Rounded borders */
        padding: 10px 40px;
        font-size: 1.5em;
        width: 150px;
        /* Fixed width */
        display: flex;
        justify-content: center;
        align-items: center;
        transition: background-color 0.3s ease;
        /* Transition for hover effect */
    }

    .stop-button {
        border-radius: 5px;
        /* Rounded borders */
        padding: 5px 20px;
        /* Adjust as necessary */
        width: 75px;

    }
    .position-btn img{
            width: 25px;
            height: 25px;
        }
    /* Hover effects */
    .btn-device-action:hover {
        background-color: #d9d9d9;
        /* Darker grey background on hover */
    }

    .btn-device-action:active {
        outline: none;
        /* Removes the outline */
        border: none;
        /* Removes the border if any */
        /* Any other styles you want to apply when the button is clicked */
    }

    /* Add these styles for the connected cell */
    .table tbody td.connected-cell .status {
        position: relative;
        border-radius: 30px;
        top:4px;
        padding: 4px 20px 4px 35px;
    }

    .table tbody td.connected-cell span:after {
        position: absolute;
        top: 7px;
        left: 10px;
        width: 10px;
        height: 10px;
        content: '';
        border-radius: 50%;
    }

    /* Style for 'Connected' status */
    .table tbody td.connected-cell .connected {
        background: #cff6dd;
        color: #1fa750;
    }

    .table tbody td.connected-cell .connected:after {
        background: #23bd5a;
    }

    /* Style for 'Disconnected' status */
    .table tbody td.connected-cell .disconnected {
        background: #f65d55;
        color: whitesmoke;
    }

    .table tbody td.connected-cell .disconnected:after {
        background: whitesmoke;
    }

    .position-btn {
        position: relative;
    }

    .manage-modal-status {
        margin-bottom: 10px;
    }

    .table tbody td.connected-cell .show-more {
        display: block;
        /* Ensure the link is on a new line */
        margin-top: 5px;
        /* Add space above the link */
        color: #007bff;
        /* Bootstrap primary color for consistency */
        text-decoration: underline;
        cursor: pointer;
    }
     .manage-dropdown{
        transform: translateY(-9px);
        top:unset !important;
    } 
    
    /* General Styles for the Error Modal */


    .error-modal-header {
        background-color: #f65d55;
        /* Red background for the header to indicate error */
        color: white;
        /* White text for contrast */
        padding: 10px 15px;
        /* Padding for spacing */
        border-top-left-radius: 10px;
        /* Rounded corners on the top */
        border-top-right-radius: 10px;
    }

    .error-modal-title {
        margin: 0;
        /* Remove default margins */
        font-size: 1.2em;
        /* Slightly larger font for emphasis */
        font-weight: bold;
        /* Bold font for importance */
    }

    .error-modal-body {
        padding: 15px;
        /* Padding for content spacing */
        line-height: 1.5;
        /* Increased line-height for readability */
        color: #333;
        /* Darker text for readability */
    }

    .error-modal-body p {
        margin-bottom: 15px;
        /* Space between paragraphs */
    }

    .error-instructions {
        background-color: #f8f8f8;
        /* Light grey background for instructions area */
        border: 1px solid #ccc;
        /* Subtle border */
        padding: 10px;
        border-radius: 5px;
        /* Rounded corners for the instructions box */
        font-size: 0.9em;
        /* Slightly smaller font size for instructions */
        color: #555;
        /* Grey color for instruction text */
    }

    /* Additional Styling for Buttons or Links in Modal */
    .error-modal-body .btn {
        background-color: #007bff;
        /* Bootstrap primary blue */
        color: white;
        margin-right: 5px;
        /* Space between buttons */
    }

    .error-modal-body .btn:hover {
        background-color: #0056b3;
        /* Darker on hover */
    }

    .show-more {
        color: #007bff !important;
        /* Bootstrap primary blue color for text */
        text-decoration: none !important;
        /* Removes underline */
        transition: all 0.2s ease-in-out;
        /* Smooth transition for hover effects */
        font-size: 0.9em;
        /* Slightly smaller font size */
    }

    .show-more:hover {
        color: #032242 !important;
        /* Slightly darker blue on hover/focus */
        text-decoration: none;
        /* Ensures underline is removed on hover/focus */
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: center;
        padding: 5px;
    }

    th {
        background-color: #f2f2f2;
    }

 
    .add-refresh-buttons {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dropdown-menu{
        left:unset !important;
        right: 10%;
        top:90%;
    }
    .dropdown-menu li a{
        font-size: 1.7rem;
    }
    .dropdown-menu li {
        padding: 3px 3px;
    }
        .table-responsive .table th:nth-child(1),
        .table-responsive .table td:nth-child(1) {
            width: 7%;
        }
        .table-responsive .table th:nth-child(2),
        .table-responsive .table td:nth-child(2) {
            width: 8%;
        }
        .table-responsive .table th:nth-child(3),
        .table-responsive .table td:nth-child(3) {
            width: 25%;
        }
        .table-responsive .table th:nth-child(4),
        .table-responsive .table td:nth-child(4) {
            width:35% ;
        }
        .table-responsive .table th:nth-child(5),
        .table-responsive .table td:nth-child(5) {
            width:25% ;
        }

    .action-buttons-container button {
        width: 80%; /* Fixed width for uniformity */
        margin: 5px 0; /* Margins for spacing between buttons */
        justify-content: center;
        align-items: center;
    }

    /* Space between icon and text */
    .btn-device-action i, .position-btn i {
        margin-right: 10px; /* Space between icon and text */
    }

         /* Styles for mobile devices */
    @media only screen and (max-width: 767px) {
        table {
            width: 100% !important;
            display: block !important;
            overflow-x: auto;
            /* Allows table to be scrollable horizontally if needed */
        }
        /* .dropdown{
        position:absolute;
        right:15.5%;
        } */
        .table-responsive .table th,
        .table-responsive .table td {
            font-size: 16px !important;
        }

        .table-responsive .table th span,
        .table-responsive .table td span{
            font-size: 14px !important;
        }
        .position-btn {
            padding: 4px 2px !important;
        }
        th {
            position: sticky;
            top: 0;
            z-index: 2;
        }
    .dropdown-menu li a{
        font-size: 1.6rem;
    }

    }
    @media only screen and (max-width:600px){
        /* .dropdown{
        position:absolute;
        right:17.5%;
        } */
        .table tbody td.connected-cell span:after {
            top:4px;
        }
        .table tbody td.connected-cell span span {
            display: none;
        }
        .table-responsive .table th:nth-child(1),
        .table-responsive .table td:nth-child(1) {
            display: none;
        }


        th span,
        td span {
            font-size: 13px !important;
            /* Smaller font size for spans */
        }
        .position-btn {
            padding: 2px 2px !important;
        }
        .action-buttons-container button {
            width: 100%; /* Adjust width for smaller screens */
            font-size: 0.9em; /* Smaller font size for buttons */
        }
        .position-btn img{
            width: 17px;
            height: 17px;
        }
        .btn-device-action i, .position-btn i {
            font-size: 1em; /* Adjust icon size for smaller screens */
        }
        .devices-div{
            padding-left:8px !important;
            padding-right: 8px !important;
        }
    }

    @media only screen and (max-width: 400px) {
        .table-responsive .table th,
    .table-responsive .table td {
            font-size: 13px !important;
            padding:10px 2px !important;
        }
        .position-btn img{
            width: 18px;
            height: 18px;
        }
        /* .dropdown{
        position:absolute;
        right:19.5%;
        } */
    }   
</style>

</style>
<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>">
                    <?= lang_safe('home') ?>
                </a></li>
            <li><a href="<?= LANG_URL ?>/myaccount">
                    <?= lang_safe('my_account') ?>
                </a></li>
            <li>
                <?= lang_safe('my_smart_home_devices') ?>
            </li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9 devices-div">
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
            <div class="alone title" style="margin-bottom:20px;">
                <span>
                    <h2>
                        <?= lang_safe('my_smart_home_devices') ?>
                    </h2>
                </span>
            </div>
            <div class="add-refresh-buttons">
                <div class="add-device-button">
                        <a href="<?= base_url('/smartdevices/add') ?>" class="btn btn-new">
                            <?= lang_safe('add_device') ?>
                        </a>
                    </div>
                    <!-- Add the "Refresh All Devices" button here -->
                    <?php if (!empty($devices)): ?>
                    <div class="refresh-devices-button">
                            <button class="btn btn-new" onclick="refreshAllDevices()">
                            <?= lang_safe('refresh_devices','Refresh Devices') ?>
                            </button>
                    </div>
                <?php endif?>
            </div>

            <!-- Bootstrap Modal for Spinner -->
            <div class="modal " id="global-spinner-modal" tabindex="-1" role="dialog"
                aria-labelledby="spinnerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
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
                    <p>
                        <?= lang_safe('no_devices_found') ?>
                    </p>
                <?php else: ?>


                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>
                                    <?= lang_safe('pos') ?>
                                </th>
                                <th>
                                    <?= lang_safe('name') ?>
                                </th>
                                <th>
                                    <?= lang_safe('state') ?>
                                </th>
                                <th>
                                    <?= lang_safe('is_connected') ?>
                                </th>
                                <th>
                                    <?= lang_safe('action', 'Actions') ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $pos = 1;
                            if (!empty($devices)) {
                                foreach ($devices as $device) {
                                    ?>
                                    <tr>
                                        <div id="loading-indicator-<?= $device['device_id'] ?>" style="display: none;">
                                            <!-- Your loading spinner here -->
                                            <input type="hidden" id="device-id-input" value="<?=$device['device_id']?>">

                                        </div>
                                        <td>
                                            <?= $pos++; ?>
                                        </td>
                                        <td>
                                            <?= $device['device_name'] ?>
                                        </td>

                                        <td id="state-<?= $device['device_id'] ?>">
                                            <?= lang_safe('gate_position_' . $device['state']) ?>
                                        </td>
                                        <td class="connected-cell connected-<?= $device['device_id'] ?>">
                                            <span class="status <?= $device['connected'] == 0 ? 'connected' : 'disconnected' ?>">
                                               <span> <?= lang_safe('connection' . $device['connected']) ?></span>
                                            </span>
                                            <?php if ($device['connected'] != 0): ?>
                                                <a href="#" class="show-more" data-toggle="modal"
                                                    data-target="#errorModal-<?= $device['device_id'] ?>">Show More</a>

                                                <!-- Error Modal -->
                                                <div class="modal  error-modal" id="errorModal-<?= $device['device_id'] ?>"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="errorModalLabel-<?= $device['device_id'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header error-modal-header" style="margin-top:2px;">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                <h5 class="modal-title"
                                                                    id="errorModalLabel-<?= $device['device_id'] ?>">
                                                                    <?= lang_safe('connection' . $device['connected']) ?>
                                                                </h5>
                                                            </div>
                                                            <div class="modal-body error-modal-body">
                                                                <p class="error-instructions">
                                                                    <?= lang_safe('connection_error_text' . $device['connected']) ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons-container" >
                                                <!-- Invisible Placeholder Button (for maintaining layout) -->

                                                <!-- Manage Dropdown Icon Button -->
                                                <div class="dropdown" style="position:static !important;" >
                                                    <button class="btn btn-default position-btn dropdown-toggle" type="button" data-toggle="dropdown" data-boundary="viewport">
                                                    <img src="<?= base_url('png/manage.svg') ?>" alt="Manage" >
                                                     <?=lang_safe('manage','Manage')?> 
                                                    </button>
                                                    <ul class="dropdown-menu manage-dropdown">
                                                        <li>
                                                            <a
                                                                href="<?= base_url('/smartdevices/editDevice/' . $device['device_id']) ?>">
                                                                <i class="fa fa-pencil"></i> Edit Device
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= base_url('/smartdevices/deleteDevice/' . $device['device_id']) ?>"
                                                                onclick="return confirm('Are you sure you want to delete this device?');">
                                                                <i class="fa fa-trash"></i> Delete Device
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a
                                                                href="<?= base_url('/smartdevices/accessControl/' . $device['device_id']) ?>">
                                                                <i class="fa fa-users"></i> Access Control
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- Control Icon Button -->
                                                <button class="btn btn-default position-btn" type="button" data-toggle="modal" data-target="#manageDeviceModal-<?= $device['device_id'] ?>">
                                                <img src="<?= base_url('png/control.svg') ?>" alt="Manage" >
                                                 <?= lang_safe('control','Control')?>
                                            </button>
                                            </div>
                                        </td>

                                    </tr>
                                    <div class="modal" id="manageDeviceModal-<?= $device['device_id'] ?>" tabindex="-1"
                                        role="dialog" aria-labelledby="manageDeviceModalLabel-<?= $device['device_id'] ?>"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <button type="button"  class="close" data-dismiss="modal" aria-label="Close" >
                                                    <span aria-hidden="true" >&times;</span>
                                                </button>
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="deviceManagementModalLabel">Controlpanel:
                                                        <?= $device['device_name'] ?>
                                                    </h4>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="manage-modal-status"
                                                        id="status-message-<?= $device['device_id'] ?>">
                                                        <div class="current-status">
                                                            <strong>Current Status:</strong> <span class="status">
                                                                <?= lang_safe('gate_position_' . $device['state']) ?>
                                                            </span>
                                                        </div>
                                                        <div class="loading-text" style="display: none;">
                                                            <strong>Loading...</strong>
                                                        </div>
                                                        <div class="result" style="display: none;">
                                                            <strong>Result:</strong> <span class="result-message"></span>
                                                        </div>
                                                    </div>
                                                    <div class="device-control-buttons">
                                                        <button class="btn btn-device-action" data-action="open"
                                                            data-device-id="<?= $device['device_id'] ?>"
                                                            onclick="controlDevice('open', <?= $device['device_id'] ?>)">
                                                            <div class="icon-wrapper">
                                                                ▲
                                                            </div>
                                                        </button>

                                                        <button class="btn btn-device-action stop-button" data-action="stop"
                                                            data-device-id="<?= $device['device_id'] ?>"
                                                            onclick="controlDevice('stop', <?= $device['device_id'] ?>)">
                                                            <div class="icon-wrapper">
                                                                ∎
                                                            </div>
                                                        </button>

                                                        <button class="btn btn-device-action" data-action="close"
                                                            data-device-id="<?= $device['device_id'] ?>"
                                                            onclick="controlDevice('close', <?= $device['device_id'] ?>)">
                                                            <div class="icon-wrapper">
                                                                ▼
                                                            </div>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">
                                        <?= lang_safe('no_devices_found') ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <!-- <nav aria-label="Page navigation">
                    <ul class="pagination" style="position:relative !important;z-index:2;">
                        <?= $paginationLinks ?>
                    </ul>
                </nav> -->
            </div>
        </div>
    </div>
</div>

</div>
<script>
    var langConnection = [];
    var langErrorConnection = [];
    <?php for ($i = 0; $i <= 5; $i++): ?>
        langConnection[<?= $i ?>] = "<?= lang_safe('connection-' . $i) ?>";
        langErrorConnection[<?= $i ?>] = "<?= lang_safe('connection_error_text-' . $i) ?>";
    <?php endfor; ?>

</script>


<script>
    function openModalAndRefresh(deviceId, modalId) {
        currentModalId = modalId;
        $('#' + modalId).modal('show');
        currentDeviceId = deviceId;

        window.modalStatusRefreshInterval = setInterval(function () {
            refreshModalStatus(deviceId, modalId);
        }, 5000);
    }

    function closeModalAndStopRefresh() {
        var currentModal = $('#' + currentModalId);
        currentModal.find('.result').hide();
        currentModal.modal('hide');

        clearInterval(window.modalStatusRefreshInterval);
        refreshDeviceStatus(currentDeviceId);
    }

    $('.modal').on('shown.bs.modal', function (e) {
        var modalId = $(this).attr('id');
        if (modalId.startsWith('manageDeviceModal-')) {
            var deviceId = modalId.split('-')[1];
            openModalAndRefresh(deviceId, modalId);
        }
    });

    $('.modal').on('hidden.bs.modal', function () {
        var modalId = $(this).attr('id');
        if (modalId.startsWith('manageDeviceModal-')) {
            closeModalAndStopRefresh();
        }
    });

    function showSpinnerInConnectedCell(deviceId) {
        var connectedCell = $('.connected-' + deviceId);
        connectedCell.html('<div class="spinner"><i class="fa fa-spinner fa-spin fa-3x"></i></div>'); // Add your spinner HTML here
    }

    function removeSpinnerFromConnectedCell(deviceId) {
        var connectedCell = $('.connected-' + deviceId);
        connectedCell.find('.spinner').remove();
    }
</script>

<script>
    $(document).ready(function () {
        refreshAllDevices();
    });


    var currentDeviceId;
    function controlDevice(action, deviceId) {
        $('.btn-device-action').prop('disabled', true);
        $('#status-message-' + deviceId + ' .loading-text').show();
        $('#status-message-' + deviceId + ' .result').hide();

        var data = {
            action: action,
            deviceId: deviceId,
        };

        $.ajax({
            url: '<?= base_url('/smartdevices/controlDevice') ?>',
            type: 'POST',
            data: data,
            success: function (response) {
                $('#status-message-' + deviceId + ' .result-message').text(response.message);
                $('#status-message-' + deviceId + ' .loading-text').hide();
                $('#status-message-' + deviceId + ' .result').show();
            },
            error: function (error) {
                $('#status-message-' + deviceId + ' .result-message').text('Error: ' + error.statusText);
            },
            complete: function () {
                $('.btn-device-action').prop('disabled', false);
            }
        });
    }




    function refreshAllDevices() {
    var refreshRequests = [];

    $('#device-id-input').each(function () {
        var deviceId = $(this).val();
        showSpinnerInConnectedCell(deviceId);
        var request = createRefreshRequest(deviceId);
        refreshRequests.push(request);
    });

    $.when.apply($, refreshRequests).then(function () {
    });
}

    function refreshModalStatus(deviceId, modalId) {
        createRefreshRequest(deviceId).then(function (response) {
            var currentStatusElement = $('#' + modalId).find('.current-status .status');

            currentStatusElement.text(response.state);
        });
    }

    function refreshDeviceStatus(deviceId) {
        $('#global-spinner-modal').modal('show'); // Show spinner when starting refresh
        createRefreshRequest(deviceId).always(function () {
            $('#global-spinner-modal').modal('hide'); // Hide spinner once request is complete
        });
    }

    function createRefreshRequest(deviceId) {
    return $.ajax({
        url: '<?= base_url('/smartdevices/refreshDeviceStatus') ?>',
        type: 'POST',
        data: { deviceId: deviceId },
        success: function (response) {
            updateUIWithDeviceStatus(deviceId, response);
        },
        error: function () {
            console.log("Error refreshing device with ID: " + deviceId);
        },
        complete: function () {
            removeSpinnerFromConnectedCell(deviceId);
        }
    });
}

    function updateUIWithDeviceStatus(deviceId, response) {
        updateStatusSpan(deviceId, response.connected, response.connection_message);
        addShowMoreLinkIfNeeded(deviceId, response.connected);
        updateStateCell(deviceId, response.state);
    }

    function updateStatusSpan(deviceId, connected, message) {
    var connectedCell = $('.connected-' + deviceId);
    var statusSpan = connectedCell.find('.status');

    // Check if the status span exists
    if (statusSpan.length === 0) {
        // If not, create it with a nested span for the text
        connectedCell.prepend('<span class="status"><span class="status-text"></span></span>');
        statusSpan = connectedCell.find('.status');
    }

    // Find the nested span where the text should go
    var statusTextSpan = statusSpan.find('.status-text');

    // Determine the class based on the connection status
    var statusClass = connected === 0 ? 'connected' : 'disconnected';

    // Set the class and text
    statusSpan.attr('class', 'status ' + statusClass);
    statusTextSpan.text(message);
}




    function updateStateCell(deviceId, state) {
        var stateCell = $('#state-' + deviceId);
        stateCell.text(state);
    }


    function addShowMoreLinkIfNeeded(deviceId, connected) {
        var connectedCell = $('.connected-' + deviceId);

        if (connected !== 0) {
            if (connectedCell.find('.show-more').length === 0) {
                connectedCell.append('<a href="#" class="show-more" data-toggle="modal" data-target="#errorModal-' + deviceId + '">Show More</a>');
            }
            if ($('#errorModal-' + deviceId).length === 0) {
                createErrorModal(deviceId, connected);
            }
        } else {
            connectedCell.find('.show-more').remove();
            $('#errorModal-' + deviceId).modal('hide').remove();
        }
    }

    function createErrorModal(deviceId, connected) {
    connected = Math.abs(connected);
    var modalHtml = '<div class="modal error-modal" id="errorModal-' + deviceId + '" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel-' + deviceId + '" aria-hidden="true">' +
        '<div class="modal-dialog" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-header error-modal-header">' +
        '<button  type="button" class="close close-custom" data-dismiss="modal" aria-label="Close">' +
        '<span style="color:white !important;" aria-hidden="true">&times;</span>' +
        '</button>' +
        '<h5 class="modal-title" id="errorModalLabel-' + deviceId + '">' + langConnection[connected] + '</h5>' +
        '</div>' +
        '<div class="modal-body error-modal-body">' +
        '<p class="error-instructions">' + langErrorConnection[connected] + '</p>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';

    $('body').append(modalHtml);
}

</script>

