<div>

    <!-- The Question -->
    <div style="color:black; width:100%; margin:auto; padding:15px; ">
        <h1><?=  $question->title ?></h1>
            <h1><img src="<?=  $question->img ?>"> <?=  $question->user ?>: </h1>

            <?=  $question->text ?>
    </div>

    <!-- The comments for the Question -->
        <div>
            <?php foreach ($question->comments as $comment) : ?>
                <?= $comment->user ?>: <?= $comment->text ?>
            <?php endforeach; ?>
        </div>
        <a href="">Kommentera frågan</a>


     <!-- All below here is answers for question -->
    <div style="color:black; width:100%; margin:auto; padding:15px; ">
        <?php foreach ($question->answers as $answer) : ?>
            <h1><img src="<?=  $answer->img ?>"> <?=  $answer->user ?>: </h1>
            <?= $answer->text ?>

            <div>
                <?php foreach ($answer->comments as $comment) : ?>
                    <?= $comment->user ?>: <?= $comment->text ?>
                <?php endforeach; ?>
            <div>
        <a href="">Kommentera svaret</a>
        <?php endforeach; ?>
    </div>

</div>
