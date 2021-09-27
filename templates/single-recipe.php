<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
$post_id = get_the_ID();
$time_hours = (int) get_post_meta($post_id, 'diet_time_hours', true );
$time_minutes = (int) get_post_meta( $post_id, 'diet_time_minutes', true );
$rating = (int) get_post_meta( $post_id, 'diet_rating', true );
$recipe_short_desc = get_post_meta( $post_id, 'recipe_short_desc', true );
$recipe_ingredients = get_post_meta( $post_id, 'diet_recipe_ingredients', true );

?>
<div class="container" id="app">
    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-4">
            <img src="<?php echo get_the_post_thumbnail_url($post_id,'medium'); ?>">
        </div>
        <div class="col-md-8">
            <h1 class="display-4 text-left ml-0 mr-0 mb-2 mt-4"><?php echo get_the_title();?></h1>
            <?php do_action('after_recipe_title'); ?>
            <div class="d-flex">
                <p class="rating float-left">
                    <?php

                    for($i=1;$i<6;$i++){
                        if($i<=$rating)
                            echo '<span><i class="fa fa-star"></i></span>';
                        else
                            echo '<span><i class="fa fa-star-o"></i></span>';
                    }
                    ?>
                </p>
                <div class="float-left ml-3 mr-3">|</div>
                <p class="float-left"><span><i class=" fa fa-clock-o"></i></span> <?php echo $time_hours.__('h','diet') ?> : <?php echo $time_minutes.__('m','diet') ?> </p>
            </div>
            <div style="text-align: justify;"><?php echo $recipe_short_desc; ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <h3>ingredients</h3>
            <ul class="list-group m-0">
                <?php if(is_array($recipe_ingredients)):
                    foreach ($recipe_ingredients as $ingredient) {
                        echo '<li class="list-group-item m-0 mt-2">'.$ingredient['material'].' : '.$ingredient['amount'].'</li>';
                    }
                endif; ?>
            </ul>
        </div>
        <div class="col-md-8">
            <?php echo get_the_content(); ?>
        </div>

    </div>
</div>
<?php
get_footer();
?>