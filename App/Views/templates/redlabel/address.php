<div class="container-fluid user-page">
    <div class="row">
            <ol class="breadcrumb">
                <li><a href="<?= LANG_URL ?>">Home</a></li>
                <li><a href="<?= LANG_URL ?>/myaccount">Account</a></li>
                <li><?php $url = service('uri'); echo str_replace('-',' ',ucfirst(str_replace('-',' ',$url->getSegment($url->getTotalSegments())))) ?></li>
            </ol>
            <?= view('templates/redlabel/_parts/sidebar'); ?>
            <div class="col-md-9">

            <div class="alone title">
                    <span>
                    <h2><?= lang_safe('newsletters', ucfirst(str_replace('-',' ',$url->getSegment($url->getTotalSegments())))) ?></h2>
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
                <?php if(validationError('error')) { ?>
                <div class="alert alert-danger">
                    <?= validationError('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                <div class="well well-sm">
                    
                </div>
            </div> 
        </div>
    </div>
</div>