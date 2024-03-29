
<div id="support" class="support-div">
        <div class="container">
        <div class="row">
            <div class="alone title ">
                <span>
                <?= lang_safe('support_title') ?>
                </span>
            </div>
            
            <div class="col-md-8 col-sm-12">
                <div class="description ">
                    <p class="support-description upp-text" ><?= lang_safe('support_description_1') ?></p>
                    <p class="support-description upp-text" ><?= lang_safe('support_description_2') ?></p>
                </div>      
                <ul class="support-contact-list">
                    <a href="<?= base_url('/contacts')?>">
                        <li class="support-contact-item">
                            <img class="support-contact-icon" src="<?= base_url('png/contact.png') ?>" alt="<?= lang_safe('contact_alt_text') ?>" />
                            <div class="support-contact-text">
                                <p class="support-contact-text-line"><?= lang_safe('support_contact_text') ?></p>
                            </div>
                        </li>
                    </a>
                    <a href="https://wa.me/+4971125286437">
                        <li class="support-contact-item">
                            <img class="support-contact-icon" src="<?= base_url('png/wa_widget.png') ?>" alt="<?= lang_safe('whatsapp_alt_text') ?>" />
                            <div class="support-contact-text">
                                <p class="support-contact-text-line"><?= lang_safe('support_whatsapp_text') ?></p>
                            </div>
                        </li>
                    </a>
                    <a href="tel:+4971125286437">
                        <li class="support-contact-item">
                            <img class="support-contact-icon" src="<?= base_url('png/phone_widget.png') ?>" alt="<?= lang_safe('phone_alt_text') ?>" />
                            <div class="support-contact-text">
                                <p class="support-contact-text-line"><?= lang_safe('support_phone_text') ?></p>
                                <p class="support-contact-text-line">+49 711 252 864 37</p>

                            </div>
                        </li>
                    <a>
                    <a href="mailto:kontakt@nodedevices.de">
                        <li  class="support-contact-item">
                            <img class="support-contact-icon" src="<?= base_url('png/mail_widget.png') ?>" alt="<?= lang_safe('email_alt_text') ?>" />
                            <div class="support-contact-text">
                                <p class="support-contact-text-line"><?= lang_safe('support_email_text') ?></p>
                                <p class="support-contact-text-line">kontakt@nodedevices.de</p>

                            </div>
                        </li>
                    </a>
                </ul>
            </div>
            <div class="col-md-4 col-sm-12 support-image-container">
                <picture>
                    <source media="(min-width: 1200px)" srcset="<?= base_url('jpg/support_img.jpg') ?>">
                    <source media="(min-width: 768px)" srcset="<?= base_url('jpg/support_img_360.jpg') ?>">
                    <img src="<?= base_url('jpg/support_img.jpg') ?>" alt="Support Image" width="100%">
                </picture>
            </div>

            </div>
        </div>
</div>