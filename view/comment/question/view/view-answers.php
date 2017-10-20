<!-- Answer Section  -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<div style="width:50%; margin:auto;">
    <?php foreach ($answers as $answer) : ?>
        <div class="answer" style="background:#FFF8DC;">
            <h1>
                <img src="<?=  $answer->img ?>">
                 <a href="<?= $this->url("users/$answer->user") ?>"> <?= $answer->user ?></a>
             </h1>
            <?= $answer->markdown ?>



            <!-- If accepted show style, else show acceptme button and hidden value for jquery .each -->
            <?php if($answer->accepted == "yes") : ?>
                <p>Im accepted</p>
            <?php else : ?>
                <p class="acceptme">acceptme</p>
                <input type="hidden" value="<?= $answer->id?>">
            <?php endif; ?>






        <!-- Comments for answers  -->
        <?php foreach ($answer->comments as $comment) : ?>
            <div style="width:10%; background:white;display: inline-block;">
                <img style="height:20px;" src="<?= $comment->img ?>">
                <a href="<?= $this->url("users/$comment->user") ?>"> <?= $comment->user ?></a>
            </div>
            <div style="width:80%; display: inline-block;">
                <?= $comment->markdown?>
            </div>

        <?php endforeach; ?>

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
    var currentUrl = "<?= $this->url("question/$answer->questionId")?>";
    var acceptUrl = "<?= $this->url("question/accept")?>";
    var commentUrl = "<?= $this->url("question/comment")?>";


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
