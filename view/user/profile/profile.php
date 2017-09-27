<div class='profile-wrapper'>

    <div class="avatar-widget">

        <div class="avatar-header">
            <i class="material-icons">person</i> <?= $user->authority ?>
        </div>

        <div class="avatar">
            <img src="<?= $user->img ?>">
        </div>

        <div class="avatar-info">
            <h1><?= $user->name ?> </h1>
            <p><?= $user->email ?> </p>
        </div>

        <div class="avatar-commands">
            <a href="<?= $app->link('user/edit') ?>"><i class="material-icons">edit</i> </a> <br>
            <a href="<?= $app->link('user/logout') ?>"><i class="material-icons">power_settings_new</i></a>
        </div>
    </div>
</div>
