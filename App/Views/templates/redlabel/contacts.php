<style>
    #map {
        height: 400px;
        width: 100%;
    }
</style>
<div id="contacts">


    <div class=" container">
        <div class="row">
            <div class="alone title">
                <span>
                    <?= lang_safe('contact_us') ?>
                </span>
            </div>
        </div>


        <div class="container">
            <div class="row">
                <p style="font-size:16px;">
                    <?= lang_safe('contact_us_text') ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                if (session('resultSend')) {
                    ?>
                    <hr>
                    <div class="alert alert-info">
                        <?= session('resultSend') ?>
                    </div>
                    <hr>
                <?php }
                ?>
                <div class="well well-sm">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <?= lang_safe('name') ?>
                                    </label>
                                    <input type="text" name="name" class="form-control" id="name" value="<?= @$_SESSION['name'] ?>"
                                        placeholder="<?= lang_safe('enter_name') ?>" required="required" />
                                </div>
                                <div class="form-group">
                                    <label for="email">
                                        <?= lang_safe('email_address') ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="glyphicon glyphicon-envelope"></span>
                                        </span>
                                        <input type="email" name="email" class="form-control" id="email" value="<?= @$_SESSION['email'] ?>"
                                            placeholder="<?= lang_safe('enter_email') ?>" required="required" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="subject">
                                        <?= lang_safe('subject') ?>
                                    </label>
                                    <input type="text" name="subject" class="form-control" id="subject" value="<?= @$_SESSION['subject'] ?>"
                                        placeholder="<?= lang_safe('enter_subject') ?>" required="required" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="message">
                                        <?= lang_safe('message') ?>
                                    </label>
                                    <textarea name="message" id="message" class="form-control" rows="9" cols="25"  value="<?= @$_SESSION['message'] ?>"
                                        required="required" placeholder="<?= lang_safe('enter_message') ?>"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>

                                        <?= lang_safe('dataprotection_contact_accept1') ?>
                                        <a
                                            href="<?= LANG_URL . '/page/' . "Datenschutz" ?>"><?= lang_safe('dataprotection_contact_accept2') ?></a>

                                        <?= lang_safe('dataprotection_contact_accept3') ?>
                                        <sup>
                                            <?= lang_safe('required') ?>
                                        </sup>

                                    </label>

                                    <input style="transform: scale(1.5); margin-left: 10px;" type="checkbox"
                                        name="dataprotection" id="dataprotection" required="required"
                                        value="dataprotection" />

                                </div>

                                <div class="form-group">
                                    <img alt="Verification code" id="captcha" src="">
                                    <button type="button" id="refreshCaptcha" class="btn btn-secondary">
                                        <i class="fa fa-refresh"></i> <!-- This is the Font Awesome refresh icon -->
                                    </button>

                                </div>
                                <div class="form-group">
                                    <label for="capcha">
                                        <?= lang_safe('captcha_enter', 'Please enter captcha') ?>
                                    </label>
                                    <input type="text" name="code" class="form-control"
                                           placeholder="<?= lang_safe('please_enter_capcha') ?>" =""/>
                                </div>
                          
                            </div>
                            
                            
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-new pull-left" id="btnContactUs">
                                    <?= lang_safe('send_message') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <div class="col-md-4">
                <form>
                    <legend><span class="glyphicon glyphicon-globe"></span> <?= lang_safe('our_office') ?></legend>
                    <address>
                        <?= html_entity_decode($contactspage) ?>
                    </address>
                </form>
            </div> -->
        </div>
    </div>
    <?php if (trim($googleApi) != null && trim($googleMaps) != null) { ?>
        <div id="map"></div>
        <?php $coordinates = explode(',', $googleMaps); ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?= $googleApi ?>"></script>
        <script>
            function initialize() {
                var myLatlng = new google.maps.LatLng(<?= $coordinates[0] ?>, <?= $coordinates[1] ?>);
                var mapOptions = {
                    zoom: 10,
                    center: myLatlng
                }
                var map = new google.maps.Map(document.getElementById("map"), mapOptions);
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    title: "Here we are!"
                });
                marker.setMap(map);
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
    <?php } ?>
</div>

<script>

    fetch('<?= base_url('captcha') ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('captcha').setAttribute('src', data.image);
        })
    document.addEventListener('DOMContentLoaded', function () {
        // Function to refresh the captcha image
        function refreshCaptcha() {
            fetch('<?= base_url('captcha') ?>')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captcha').setAttribute('src', data.image);
                })
                .catch(error => console.error('Error refreshing captcha:', error));
        }

        // Add click event for the "Refresh" button
        const refreshButton = document.getElementById('refreshCaptcha');
        refreshButton.addEventListener('click', refreshCaptcha);
    });
</script>