<!-- app/Views/my_view.php -->

<?= view('templates/redlabel/_parts/header', $head) ?>

<?= view('templates/redlabel/'.$page, $data) ?>

<?= view('templates/redlabel/_parts/footer', $footer) ?>
