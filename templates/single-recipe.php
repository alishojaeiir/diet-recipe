<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
$post_id = get_the_ID();
$recipe = RecipeFactory::get_recipe($post_id);

?>
<div class="container" id="app">
    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-4">
            <img src="<?php echo $recipe['thumbnail']['medium'] ?>">
        </div>
        <div class="col-md-8">
            <h1 class="display-4 text-left ml-0 mr-0 mb-2 mt-4"><?php echo $recipe['title'];?></h1>
            <?php do_action('after_recipe_title'); ?>
            <div class="d-flex">
                <p class="rating float-left">
                    <?php

                    for($i=1;$i<6;$i++){
                        if($i<=$recipe['rating'])
                            echo '<span><i class="fa fa-star"></i></span>';
                        else
                            echo '<span><i class="fa fa-star-o"></i></span>';
                    }
                    ?>
                </p>
                <div class="float-left ml-3 mr-3">|</div>
                <p class="float-left"><span><i class=" fa fa-clock-o"></i></span> <?php echo $recipe['time']['hours'].__('h','diet') ?> : <?php echo $recipe['time']['minutes'].__('m','diet') ?> </p>
            </div>
            <div style="text-align: justify;"><?php echo $recipe['short_content']; ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <h3>ingredients</h3>
            <ul class="list-group m-0">
                <?php if(is_array($recipe['ingredients'])):
                    foreach ($recipe['ingredients'] as $ingredient) {
                        echo '<li class="list-group-item m-0 mt-2">'.$ingredient['material'].' : '.$ingredient['amount'].'</li>';
                    }
                endif; ?>
            </ul>
        </div>
        <div class="col-md-8">
            <?php echo $recipe['content']; ?>
        </div>

    </div>
</div>
<?php
get_footer();
?>