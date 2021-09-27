<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

?>
<div class="container" id="app">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center mb-10"><?php echo single_cat_title(); ?></h1>
        </div>
    </div>
<?php
while (have_posts()){
    the_post();
    $post_id = get_the_ID();
    $recipe = RecipeFactory::get_recipe($post_id);
    ?>
    <div class="card flex-md-row mb-3 box-shadow h-md-250">
        <img class="card-img-right m-2 flex-auto d-none d-md-block" style="width: 150px; height:150px" src="<?php echo $recipe['thumbnail']['small'] ?>">
        <div class="card-body d-flex flex-column align-items-start">
            <h3 class="m-0">
                <a class="text-dark" href="<?php echo $recipe['link']; ?>" ><?php echo $recipe['title'] ?></a>
            </h3>
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
    <?php
}
?>
</div>
<?php
get_footer();

