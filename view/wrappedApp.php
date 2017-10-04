<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>

    <?php foreach ($stylesheets as $stylesheet) : ?>
    <link rel="stylesheet" type="text/css" href="<?= $this->asset($stylesheet) ?>">
    <?php endforeach; ?>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

</head>
<body>

<!-- hidden navbar -->
<div class="dropdown-content">
    <div class="dropdown-header">
        <h1> Meny </h1>
    </div>
    <a href="<?= $app->link('') ?>">Home</a>
    <a href="<?= $app->link('about') ?>">Om mig</a>
    <a href="<?= $app->link('report') ?>">Report</a>
    <a href="<?= $app->link('aboutPage') ?>">Om sidan</a>
    <a href="<?= $app->link('book') ?>">Book</a>
    <a href="<?= $app->link('article/mvc') ?>">MVC-Artikel</a>
    <a href="<?= $app->link('article/di') ?>">DI-Artikel</a>
    <a href="<?= $app->link('remserver') ?>">Remserver</a>
    <a href="<?= $app->link('comment') ?>">Comment</a>
</div>

<div class="wrap-all">
    <?php if ($this->regionHasContent("header")) : ?>
        <?php $this->renderRegion("header") ?>
    <?php endif; ?>

    <?php if ($this->regionHasContent("main")) : ?>
        <?php $this->renderRegion("main") ?>
    <?php endif; ?>

    <?php if ($this->regionHasContent("footer")) : ?>
        <?php $this->renderRegion("footer") ?>
    <?php endif; ?>
</div>



<!-- javascript goes at end -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php foreach ($javascripts as $javascript) : ?>
    <script src="<?= $this->asset($javascript) ?>"></script>
<?php endforeach; ?>

</body>
</html>
