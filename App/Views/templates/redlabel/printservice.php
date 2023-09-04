<style>
.fullscreen {
    background-repeat: no-repeat;
    background-position: left;
    background-size: cover;
    height: 800px;
    width: 100vw;
    display: flex;
    justify-content: center;
    align-items: center;
}

@media (max-width: 1259px) {
    .fullscreen {
        background-image: url(<?= base_url('jpg/PSX_20210703_023942_small2.jpg') ?>);
    }
}

@media (min-width:1260px) {
    .fullscreen {
        background-image: url(<?= base_url('jpg/PSX_20210703_023942_small2.jpg') ?>);
    }
}
</style>
<div class="fullscreen">
    <div class="container">

        <div class="w3-display-topleft" style="padding:24px 48px">
            <h3 style="font-size:6vw" class="w3-blue w3-padding-Medium w5-hide-small w3-center ">3D Printservice</h3>
        </div>

        <div class=" w3-display-topleft" style="padding:80px 48px">
            <h3 style="font-size:4vw " class="w3-blue w3-text-black w3-padding-Medium w3-center">Beratung, Konstruktion
                und Druck Ihrer Prototypen und
                Kleinserien</h3>
        </div>

        

        <div class="w3-display-topleft" style="padding:500px 48px">

            <p><a style="font-size:4vw"  href="#about" class="w3-left w3-button w3-text-blue w3-white w3-padding-large">Projektanfrage</a></p>
            <div style="margin-bottom: 5em"></div>
<br></br>
            <p><a style="font-size:4vw" href="#more" class="w3-left w3-button w3-text-blue w3-white w3-padding-large">Leistungen im Detail</a>
            </p>
        </div>
    </div>
</div>


<div id="more">
    <ul class="w3-ul w3-border">
        <li>
            <h2>Leistungen</h2>
        </li>

        <li>Beratung</li>
        <li>Konstruktion</li>
        <li>Druck</li>
        <li>Nachbearbeitung -Schleifen -Polieren -Lackieren / Airbrush</li>
        <li>Eilservice</li>
    </ul>

</div>

<div id="about">
    <div class="jumbotron jumbotron-sm">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <h1 class="h1">
                        <?= lang_safe('get_angebot') ?> </h1>
                    <small><?= lang_safe('get_angebot_text') ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                if (session('resultSend')) {
                    ?>
                <hr>
                <div class="alert alert-info"><?= session('resultSend') ?></div>
                <hr>
                <?php }
                ?>
                <div class="well well-sm">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <?= lang_safe('name') ?></label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="<?= lang_safe('placeholder_name') ?>" required="required" />
                                </div>
                                <div class="form-group">
                                    <label for="subject">
                                        <?= lang_safe('projektname') ?></label>
                                    <input type="text" name="subject"
                                        placeholder="<?= lang_safe('placeholder_projektname') ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="email">
                                        <?= lang_safe('email_address') ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="glyphicon glyphicon-envelope"></span>
                                        </span>
                                        <input type="email" name="email" class="form-control" id="email"
                                            placeholder="<?= lang_safe('placeholder_email') ?>" required="required" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stueckzahl">
                                        <?= lang_safe('stueckzahl') ?></label>
                                    <div class="input-group">
                                        <input type="number" min="1" max="1000000" name="stueckzahl"
                                            class="form-control" id="stueckzahl" placeholder="1" required="required" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="material">
                                        <?= lang_safe('material') ?></label>
                                    <div class="input-group">
                                        <select name="material" id="material">
<option value="-">-</option>
<option value="PLA">PLA</option>
                                            <option value="abs">ABS</option>
                                            <option value="petg">PETG</option>
<option value="asa">ASA</option>
<option value="nylon">NYLON</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="colour">
                                        <?= lang_safe('colour') ?></label>
                                    <div class="input-group">
                                        <select name="colour" id="colour">
                                            <option value="schwarz">schwarz</option>
                                            <option value="weiss">weiss</option>
                                            <option value="rot">rot</option>
                                            <option value="blau">blau</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="oberflaeche">
                                        <?= lang_safe('oberflaeche') ?></label>
                                    <div class="input-group">
                                        <select name="oberflaeche" id="oberflaeche">
                                            <option value="Unbehandelt">unbehandelt</option>
                                            <option value="Lackiert">lackiert</option>
                                            <option value="Schutzlack matt">schutzlack matt</option>
                                            <option value="Schutzlack hochglanz">schutzlack hochglanz</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="upload">
                                        <?= lang_safe('modell_upload') ?></label>
                                    <input type="file" id="attachment" name="attachment" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <?= lang_safe('message') ?></label>
                                    <textarea name="message" id="message" class="form-control" rows="9" cols="25"
                                        required="required" placeholder="<?= lang_safe('message') ?>"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right" id="btnContactUs">
                                    <?= lang_safe('anfrage_absenden') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- pull down  -->
    <div style="margin-top:150px"></div>

</div>