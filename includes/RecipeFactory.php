<?php

class RecipeFactory
{
    public static function get_recipe( $recipe_id )
    {
        $recipe_id = self::get_recipe_id( $recipe_id );
        if(!$recipe_id){
            return null;
        }
        $post = get_post($recipe_id);

        if(!$post){
            return null;
        }
        $small_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($recipe_id));
        $full_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($recipe_id),'full');
        $time_hours = (int) get_post_meta( $post->ID, 'diet_time_hours', true );
        $time_minutes = (int) get_post_meta( $post->ID, 'diet_time_minutes', true );
        $rating = (int) get_post_meta( $post->ID, 'diet_rating', true );
        $recipe_short_desc = get_post_meta( $post->ID, 'recipe_short_desc', true );

        $recipe = array(
            'id'=> $post->ID,
            'title' => $post->post_title,
            'short_content' => $recipe_short_desc,
            'content' => $post->post_content,
            'thumbnail' => array(
                'small' => $small_thumbnail[0],
                'full' => $full_thumbnail[0],
            ),
            'post_name' => $post->post_name,
            'rating' => $rating,
            'time' => array(
                'hours' => $time_hours,
                'minutes' => $time_minutes
            ),
            'link'  => get_post_permalink($recipe_id),
        );
        return $recipe;
    }
    public static function get_recipe_id( $recipe ) {
        if ( is_numeric( $recipe ) ) {
            return $recipe;
        } elseif ( ! empty( $recipe->ID ) ) {
            return $recipe->ID;
        } else {
            return false;
        }
    }
}