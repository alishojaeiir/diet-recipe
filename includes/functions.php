<?php
require_once 'recipe_post_type.php';

add_action( 'wp_enqueue_scripts', 'diet_specific_style' );
function diet_specific_style($hook){
    if (is_singular( 'recipe' ) || is_tax( 'recipe_cat' ) || is_post_type_archive( 'recipe' )) {
        wp_enqueue_style('bootstrap-4.0-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',array(),DIET_VERSION);
        wp_enqueue_style('diet-main-style', DIET_URL.'/assets/css/style.css',array(),DIET_VERSION);
        wp_enqueue_style('font-awesome-style', 'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css',array(),DIET_VERSION);
    }
}

function template_loader( $template )
{
    if ( is_embed() ) {
        return $template;
    }
    if (is_singular( 'recipe' )) {
        $template = DIET_TEMP.'/single-recipe.php';
    } elseif ( is_recipe_taxonomy() ) {
        if ( is_tax( 'recipe_cat' ) ) {
            $template = DIET_TEMP.'/recipe-cat.php';
        }
    } elseif (is_post_type_archive( 'recipe' )) {
        $template =  DIET_TEMP.'/archive-recipe.php';
    }
    return $template;
}
