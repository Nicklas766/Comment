<!-- Question section  -->
<div class="question" style="width:50%; margin:auto;">
    <!-- Text for question  -->
    <h1><?=  $question->title ?></h1>
    <div>
        <h1>
            <img src="<?=  $question->question->img ?>">
            <a href="<?= $this->url("users/$question->user") ?>"> <?= $question->user ?></a>
         </h1>
        <?= $question->question->markdown ?>
    </div>


    <p>score:<?= $question->question->vote->score ?></p>
    <h1>Rep:<?= $question->question->userObj->reputation ?></h1>

    <!-- like or dislike -->
    <p class="like">Gilla</p>
    <p class="dislike">Ogilla</p>
    <input type="hidden" name="parentType" value="post">
    <input type="hidden" name="parentId" value="<?= $question->question->id ?>">

    <!-- Tags for question  -->
    <?php foreach ($question->tags as $tag) : ?>
        <a href="<?= $this->url("question/tagged/$tag") ?>"><?= $tag ?></a>
    <?php endforeach; ?>


    <!--    Commments     -->
    <?php foreach ($question->question->comments as $comment) : ?>
        <div style="width:10%; background:white;display: inline-block;">
            <img style="height:20px;" src="<?= $comment->img ?>">
            <a href="<?= $this->url("users/$comment->user") ?>"> <?= $comment->user ?></a>
        </div>

        <!-- Actual comment with score  -->
        <div class="comment" style="width:80%; display: inline-block;">
            <?= $comment->markdown?>
            <p>score:<?= $comment->vote->score ?></p>

            <!-- like or dislike -->
            <p class="like">Gilla</p>
            <p class="dislike">Ogilla</p>
            <input type="hidden" name="parentType" value="comment">
            <input type="hidden" name="parentId" value="<?= $comment->id ?>">
        </div>
    <?php endforeach; ?>

    <!--    Make comment form     -->
    <p class="kommentera">Kommentera</p>
    <form method="POST">
        <textarea></textarea>
        <input type="hidden" value="<?=$question->id?>">
        <p>Skicka</p>
    </form>
</div>


<div class="sort-answers">
    <p>Sortering</p>
    <a href="<?= $this->url("question/$question->id/date")   . "?" . $_SERVER['QUERY_STRING'] ?>">Datum</a>
    <a href="<?= $this->url("question/$question->id/points") . "?" . $_SERVER['QUERY_STRING'] ?>">Poäng</a>
    <a href="<?= $this->url("question/$question->id/vote")   . "?" . $_SERVER['QUERY_STRING'] ?>">Röster</a>

    <a href="<?= $this->url($this->currentUrl()) ?>?order=up">Upp</a>
    <a href="<?= $this->url($this->currentUrl()) ?>?order=down">Ner</a>
<div>
