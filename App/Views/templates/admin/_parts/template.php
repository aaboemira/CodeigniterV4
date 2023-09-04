<!-- app/Views/my_view.php -->

<?= view('templates/admin/_parts/header', isset($head)?$head:[]) ?>

<?= view('templates/admin/'.$page, isset($data)?$data:[]) ?>

<?= view('templates/admin/_parts/footer', isset($footer)?$footer:[]) ?>
