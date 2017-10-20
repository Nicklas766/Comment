<!-- Answer Section  -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<div style="width:50%; margin:auto;">
    <?php foreach ($answers as $answer) : ?>
        <div class="answer" style="background:#FFF8DC;">

            <!-- Actual answer with score  -->
            <h1>
                <img src="<?=  $answer->img ?>">
                 <a href="<?= $this->url("users/$answer->user") ?>"> <?= $answer->user ?></a>
             </h1>
            <?= $answer->markdown ?>

            <!-- Score stats -->
            <h1>score:<?= $answer->vote->score ?></h1>
            <?php foreach ($answer->vote->likes as $like) : ?>
                <?= $like->user?>

                <?php if($like->upVote) : ?>
                    <p>Liked it!</p>
                <?php else : ?>
                    <p>Disliked it!</p>
                <?php endif; ?>

                <?php endforeach; ?>

            <!-- like or dislike -->
            <p class="like">Gilla</p>
            <p class="dislike">Ogilla</p>
            <input type="hidden" name="parentType" value="post">
            <input type="hidden" name="parentId" value="<?= $answer->id ?>">


            <!-- Accepted answer or not  -->
            <?php if($answer->accepted == "yes") : ?>
                <p>Im accepted</p>
            <?php else : ?>
                <p class="acceptme">acceptme</p>
                <input type="hidden" value="<?= $answer->id?>">
            <?php endif; ?>



            <!--    Commments     -->
            <?php foreach ($answer->comments as $comment) : ?>
                <div style="width:10%; background:white;display: inline-block;">
                    <img style="height:20px;" src="<?= $comment->img ?>">
                    <a href="<?= $this->url("users/$comment->user") ?>"> <?= $comment->user ?></a>
                </div>

                <!-- Actual comment with score  -->
                <div style="width:80%; display: inline-block;">
                    <?= $comment->markdown?>
                    <p>score:<?= $comment->vote->score ?></p>
                </div>
            <?php endforeach; ?>



        <!--    Make comment form     -->
        <p class="kommentera">Kommentera</p>
        <form method="POST">
            <textarea></textarea>
            <input type="hidden" value="<?=$answer->id?>">
            <p>Skicka</p>
        </form>
    </div>
<?php endforeach; ?>

</div>


<!-- POST ACCEPTED-->


<script type='text/javascript'>

    // Get needed urls
    var currentUrl  = "<?= $this->url("question/$answer->questionId")?>";
    var acceptUrl   = "<?= $this->url("question/accept")?>";
    var commentUrl  = "<?= $this->url("question/comment")?>";
    var voteUrl     = "<?= $this->url("question/vote")?>";


    $(document).ready(function() {

    // ACCEPT ANSWER AJAX
    $( ".answer" ).each(function() {
        $this = $(this);
        $(this).children(".acceptme").on("click", function(){
        id = $(this).next().val();
        $.ajax({
                type: "POST",
                url: acceptUrl + "/" + id,
                success: function(data){
                    $.ajax({
                            type: "GET",
                            url: currentUrl,
                            success: function(content) {
                            $("body").html(content);
                        }
                    });
                }
            });
        });
    });


    // VOTE OR DISLIKE AJAX
    $( ".answer" ).each(function() {

        // <p class="like">Gilla</p>
        // <p class="dislike">Ogilla</p>
        // <input type="hidden" name="parentType" value="post">
        // <input type="hidden" name="parentId" value="<?= $answer->id ?>">

        $(this).children(".like").on("click", function() {
            var parentType = $(this).next().next().val();
            var parentId = $(this).next().next().next().val();
            console.log("iwasclicked");
            $.ajax({
                    type: "POST",
                    url: voteUrl,
                    data: {
                        parentType: parentType,
                        parentId: parentId,
                        upVote: 1
                    },
                    success: function(data){
                        console.log(data);
                        $.ajax({
                                type: "GET",
                                url: currentUrl,
                                success: function(content) {
                                $("body").html(content);
                            }
                        });
                    }
                });
            });

        $(this).children(".dislike").on("click", function() {
            var parentType = $(this).next().val();
            var parentId = $(this).next().next().val();
            console.log("iwasclicked");
            $.ajax({
                    type: "POST",
                    url: voteUrl,
                    data: {
                        parentType: parentType,
                        parentId: parentId,
                        downVote: 1
                    },
                    success: function(data){
                        console.log(data);
                        $.ajax({
                                type: "GET",
                                url: currentUrl,
                                success: function(content) {
                                $("body").html(content);
                            }
                        });
                    }
                });
            });




    });


    // POST COMMENT AJAX
    $(".answer").each(function() {
        var id = $(this).children("form").children(":nth-child(2)").val();

        // Hide forms, and add click to show forms
        $(this).children("form").hide();
        $(this).children(".kommentera").click(function() {
            $(this).next().toggle("slow", function() {
              // Animation complete.
            });
        });

        $(this).children("form").children(":nth-child(3)").on("click", function(){

            var text = $(this).prev().prev().val();
            var url = commentUrl + "/" + id; // the script where you handle the form input.
            console.log(id);

            console.log(text);

            if (text == "") {
                return true;
            }
            $.ajax({
                   type: "POST",
                   url: url,
                   data: {text: text}, // serializes the form's elements.
                   success: function(data)
                   {
                       $.ajax({
                               type: "GET",
                               url: currentUrl,
                               success: function(content) {
                               $("body").html(content);
                           }
                       });
                   }
                 });
        });
    });

});
</script>
