<!-- story-slides -->


<style>
    .story-slides {
        text-align: center;
        z-index: 100;
    }


    .slide-item img {
        object-fit: contain;
        height: 100vh;
    }
</style>

<div class=" pos-fx all-0 story-slides blurBg desk-mw" id="story-slides" <?php if (!$tg_first_time) {
                                                                                echo 'style="display: none;"';
                                                                            } ?>>
    <div class="slider">
        <?php
        $story_slides = dbget("SELECT * FROM tg_story_slides");
        foreach ($story_slides as $ss) {
            echo "
                <div class='slide-item'>
                    <img src='{$ss['img']}' alt=''>
                </div>
            ";
        }
        ?>
    </div>
    <button onclick="$(this).parent().fadeOut()" class="btn" style="position:absolute;bottom:2%;width:50%;left:0;right:0;margin:auto">Skip story</button>
</div>