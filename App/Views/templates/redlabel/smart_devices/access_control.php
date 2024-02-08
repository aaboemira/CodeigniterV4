<style>
    .table-responsive .table th,
    .table-responsive .table td {
        border-top: none; /* Remove default top border */
        border-right: none; /* Remove default right border */
        border-left: none; /* Remove default left border */
        border-bottom: 1px solid #ccc; /* Add a bottom border */
        padding: 8px !important;
        text-align: center;
        font-size: 1.6rem; /* Default font size */
        vertical-align: middle;
    }
    .table-responsive .table td button , .table-responsive .table td a{
            font-size: 1.4rem
        }
/* Media query for tablets (e.g., 768px to 1024px) */
    @media (min-width: 768px) and (max-width: 1024px) {
        .table-responsive .table th,
        .table-responsive .table td, {
            font-size: 1.5rem; /* Slightly smaller font size */
        }
        .table-responsive .table td button , .table-responsive .table td a{
            font-size: 1.3rem
        }
    }

    /* Media query for small devices (e.g., 576px to 768px) */
    @media (min-width: 576px) and (max-width: 767px) {
        .table-responsive .table th,
        .table-responsive .table td,.table-responsive .table td button , .table-responsive .table td a {
            font-size: 1.6rem; /* Even smaller font size */
        }
        .table-responsive .table td button , .table-responsive .table td a{
            font-size: 1.4rem
        }
    }

    /* Media query for extra small devices (less than 576px) */
    @media (max-width: 575px) {
        .table-responsive .table th,
        .table-responsive .table td,.table-responsive .table td button , .table-responsive .table td a {
            font-size: 1.3rem; /* Smallest font size */
        }
        .table-responsive .table td button , .table-responsive .table td a{
            font-size: 1.2rem
        }
        .table-responsive .table th:nth-child(1),
        .table-responsive .table td:nth-child(1) {
            display: none;
        }
        .col-md-9{
            padding-left: 5px !important;
            padding-right: 5px !important;

        }
    }
    @media (max-width: 420px) {
        .table-responsive .table th,
        .table-responsive .table td {
            font-size: 1.1rem; /* Smallest font size */
        }
        .table-responsive .table td button , .table-responsive .table td a{
            font-size: 1.1rem
        }
        .table-responsive .table th:nth-child(1),
        .table-responsive .table td:nth-child(1) {
            display: none;
        }
    }
    @media (max-width: 768px) {
    .table-responsive .table td button , .table-responsive .table td a  {
        display: block;
        width: 100%; /* Make the button expand to the full width of its container */
        margin-top: 10px; /* Add margin at the top for spacing */
        }
        .table-responsive{
            border: none !important;
        }
    }
    .table-responsive .table th {
        background-color: #f0f0f0; /* Grey background for th */
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
        <h2><?= lang_safe('access_control_for') ?>: <?= $device['device_name'] ?></h2>

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

            <?php if (session('errors')) {
                ?>
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4><span class="glyphicon glyphicon-alert"></span>
                            <?= lang_safe('finded_errors') ?>
                        </h4>
                        <?php
                        foreach (session('errors') as $error) {
                            echo htmlspecialchars($error) . '<br>';
                        }
                        ?>
                    </div>
                <?php
            }
            ?>
            <h3><?= lang_safe('add_new_guest') ?></h3>

            <form action="<?= base_url('/smartdevices/addGuest') ?>" method="post" id="add-guest-form">
                <div class="form-group">
                    <div class="row" style="margin:0px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_email"><?= lang_safe('email') ?></label>
                                <input type="text" class="form-control" id="user_email" name="user_email" placeholder="<?= lang_safe('email') ?>"  value="<?= old('user_email') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password"><?= lang_safe('password') ?></label>
                                <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" maxlength="10" placeholder="<?= lang_safe('enter_password') ?>"
                                oninput="removeTrailingSpaces(this)" value="<?= old('password') ?>"> 
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default toggle-password"
                                            data-target="password">
                                        <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                    </button>
                                </span>
                            </div>                            
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="can_control"><?= lang_safe('can_control') ?> *</label>
                                <select size="1" id="can_control" name="can_control" class="form-control">
                                    <option value="1"><?= lang_safe('granted') ?></option>
                                    <option value="0"><?= lang_safe('denied') ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <input type="hidden" class="form-control" id="device_id" name="device_id" value="<?= $device['device_id']?>">

                        <div class="col-md-12">
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-new"><?= lang_safe('add_guest') ?></button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <h3><?= lang_safe('list_of_guests') ?></h3>
                <?php if (empty($guests)): ?>
                <div class="alert alert-secondary" style="text-align:center;">
                    <p><?= lang_safe('no_guests_found') ?></p>
                </div>
                <?php else: ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th><?= lang_safe('pos') ?></th>
                        <th><?= lang_safe('guest_email') ?></th>
                        <th><?= lang_safe('can_control') ?></th>
                        <th><?= lang_safe('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; // Initialize the counter ?>

                    <?php foreach ($guests as $guest): ?>
                        <tr>
                            <td><?= $count++ ?></td> <!-- Output the count -->
                            <td><?= $guest['email'] ?></td>
                            <td>
                                <i class="fa <?= $guest['can_control'] ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' ?>"
                                   style="font-size: 2rem"></i>
                                <?= $guest['can_control'] ? lang_safe('yes') : lang_safe('no') ?>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editGuestModal" data-device-id data-guest-id="<?= $guest['id'] ?>" data-guest-email="<?= $guest['email'] ?>" data-guest-password="<?= $guest['guest_password'] ?>" data-can-control="<?= $guest['can_control'] ?>">
                                    <i class="fa fa-pencil"></i> <?= lang_safe('edit') ?>
                                </button>
                                <a href="<?= LANG_URL.'/smartdevices/deleteGuest/' . $guest['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?= lang_safe('confirm_delete_guest') ?>');">
                                    <i class="fa fa-trash"></i> <?= lang_safe('delete') ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>

            </div>
            <!-- Edit Guest Modal -->
            <div class="modal " id="editGuestModal" tabindex="-1" role="dialog" aria-labelledby="editGuestModalLabel" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-header">
                            <h3 class="modal-title" id="editGuestModalLabel"><?= lang_safe('edit_guest') ?></h3>
                        </div>
                        <form action="<?= base_url('/smartdevices/updateGuest') ?>" method="post" id="edit-guest-form">
                            <div class="modal-body">
                                <input type="hidden" id="edit_guest_id" name="guest_id">
                                <div class="form-group">
                                    <label for="edit_user_email"><?= lang_safe('email') ?></label>
                                    <input type="text" class="form-control" id="edit_user_email" name="user_email" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_can_control"><?= lang_safe('can_control') ?></label>
                                    <select size="1" id="edit_can_control" name="can_control" class="form-control">
                                        <option value="1"><?= lang_safe('granted') ?></option>
                                        <option value="0"><?= lang_safe('denied') ?></option>
                                    </select>
                                </div>
                            <div class="form-group">
                                <label for="password"><?= lang_safe('password') ?></label>
                                <div class="input-group">
                                <input type="password" class="form-control" id="edit_password" name="password" maxlength="10" placeholder="<?= lang_safe('enter_password') ?>"
                                oninput="removeTrailingSpaces(this)">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default toggle-password"
                                            data-target="edit_password">
                                        <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                    </button>
                                </span>
                            </div>                            
                            </div>
                            <input type="hidden" class="form-control" id="device_id" name="device_id" value="<?=$device['device_id']?>">

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-new"><?= lang_safe('save_changes') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $('#editGuestModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var guestId = button.data('guest-id'); // Extract info from data-* attributes
        var guestEmail = button.data('guest-email');
        var canControl = button.data('can-control');
        var guestPassword = button.data('guest-password');

        var modal = $(this);
        modal.find('.modal-body #edit_guest_id').val(guestId);
        modal.find('.modal-body #edit_user_email').val(guestEmail);
        modal.find('.modal-body #edit_password').val(guestPassword);
        modal.find('.modal-body #edit_can_control').val(canControl ? '1' : '0');

    });
</script>
