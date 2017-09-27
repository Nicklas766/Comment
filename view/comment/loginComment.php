<div class='wrapper' style="background:#F0F0F0; min-height:700px;">
    <div class="featured-widget" style="color:#009CE6; margin-bottom: 24px;">

        <h1> Skapa en kommentar </h1>
        <div class='login-widget' style="position:relative;">
            <div style="background:white; opacity: 0.8; height:100%; border:solid 2px; color:black; width:100%; font-size:16px; position:absolute;">
                <h3> Du behöver vara inloggad för att göra en kommentar </h3>
                <a href="<?= $app->link('user/login') ?>"> Logga in </a> <br>
                <a href="<?= $app->link('user/create') ?>"> Skapa konto </a>
            </div>
            <form action="<?=$app->link('comment/create')?>" method="post">
                 <textarea style="border:1px solid;" type="text" placeholder="comment" name="comment"></textarea>
                 <button disabled type="submit">Skicka</button>
            </form>
        </div>

        <div style="width:100%; margin-bottom:200px;" />
    </div>
</div>
