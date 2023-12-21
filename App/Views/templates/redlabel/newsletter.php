<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>">
                    <?= lang_safe('home') ?>
                </a>
            </li>
            <li><a href="<?= LANG_URL ?>/myaccount">
                    <?= lang_safe('my_account') ?>
                </a>
            </li>
            <li>
                <?php $url = service('uri');
                echo str_replace('-', ' ', ucfirst(str_replace('-', ' ', $url->getSegment($url->getTotalSegments())))) ?>
            </li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>
        <div class="col-md-9">
            <div class="alone title">
                <span>
                    <h2>
                        <?= lang_safe('newsletters', ucfirst(str_replace('-', ' ', $url->getSegment($url->getTotalSegments())))) ?>
                    </h2>
                </span>
            </div>

            <?php if (session('success')) { ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if (validationError('error')) { ?>
                <div class="alert alert-danger">
                    <?= validationError('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <div class="well well-sm">
                <?php if ($data['userInfo']['newsletter']): ?>
                    <h3>
                        <?= lang_safe('subscribed_heading') ?>
                    </h3>
                    <p>
                        <?= lang_safe('subscribed_info') ?>
                    </p>
                    <form action="<?= LANG_URL ?>/newsletter/unsubscribe" method="post">
                        <button type="submit" class="btn btn-new">
                            <?= lang_safe('unsubscribe') ?>
                        </button>
                    </form>
                <?php else: ?>
                    <form action="<?= LANG_URL ?>/newsletter/subscribe" method="post">
                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="subscription_agreement" required
                                    style="margin-top:25px; transform: scale(1.3);">
                                <h3>
                                    <?= lang_safe('not_subscribed_heading') ?>
                                </h3>
                            </label>
                        </div>
                        <p>
                            <?= lang_safe('not_subscribed_info') ?>
                        </p>
                        <button type="submit" class="btn btn-new">
                            <?= lang_safe('subscribe') ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>