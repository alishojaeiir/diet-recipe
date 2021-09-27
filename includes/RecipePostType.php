<?php
Class RecipePostType
{
    public function __construct()
    {
        add_action( 'init', array($this,'diet_post_type_init'));
        add_action( 'add_meta_boxes', array($this,'add_recipes_metaboxes'));
        add_action( 'save_post', array($this,'diet_save_recipes_meta'),10, 2 );
    }

    function diet_post_type_init() {
        $labels = array(
            'name'                  => _x( 'Recipe', 'Post type general name', 'diet' ),
            'singular_name'         => _x( 'Recipe', 'Post type singular name', 'diet' ),
            'menu_name'             => _x( 'Recipes', 'Admin Menu text', 'diet' ),
            'name_admin_bar'        => _x( 'Recipe', 'Add New on Toolbar', 'diet' ),
            'add_new'               => __( 'Add New', 'diet' ),
            'add_new_item'          => __( 'Add New Recipe', 'diet' ),
            'new_item'              => __( 'New Recipe', 'diet' ),
            'edit_item'             => __( 'Edit Recipe', 'diet' ),
            'view_item'             => __( 'View Recipe', 'diet' ),
            'all_items'             => __( 'All Recipes', 'diet' ),
            'search_items'          => __( 'Search recipes', 'diet' ),
            'parent_item_colon'     => __( 'Parent recipes:', 'diet' ),
            'not_found'             => __( 'No recipes found.', 'diet' ),
            'not_found_in_trash'    => __( 'No recipes found in Trash.', 'diet' ),
            'featured_image'        => _x( 'recipe image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'diet' ),
            'set_featured_image'    => _x( 'Set recipe image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'diet' ),
            'remove_featured_image' => _x( 'Remove recipe image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'diet' ),
            'use_featured_image'    => _x( 'Use as recipe image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'diet' ),
            'archives'              => _x( 'recipe archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'diet' ),
            'insert_into_item'      => _x( 'Insert into recipe', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'diet' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this recipe', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'diet' ),
            'filter_items_list'     => _x( 'Filter recipes list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'diet' ),
            'items_list_navigation' => _x( 'recipes list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'diet' ),
            'items_list'            => _x( 'recipes list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'diet' ),
        );
        $args = array(
            'labels'             => $labels,
            'description'        => 'recipes custom post type.',
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => 20,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ,'custom-fields','comments'),
            'show_in_rest'       => true,
            'menu_icon'   => 'dashicons-editor-spellcheck',
            'taxonomies' => array('recipe_cat'),
        );
        register_post_type( 'recipe', $args );

        register_taxonomy( 'recipe_cat', array('recipes'), array(
                'hierarchical' => true,
                'label' => __('Categories'),
                'singular_label' => __('Category'),
                'show_in_rest' => true,
                'rewrite' => array( 'slug' => 'recipe_cat', 'with_front'=> false )
            )
        );

        register_taxonomy_for_object_type( 'recipe_cat', 'recipe' );
    }

    function add_recipes_metaboxes() {
        add_meta_box('diet_recipe_time', __('Cooking Time', 'diet'), array($this,'diet_recipe_time_callback'), 'recipe', 'side', 'default');
        add_meta_box('diet_recipe_rating', __('Rating', 'diet'), array($this,'diet_recipe_rating_callback'), 'recipe', 'side', 'default');
        add_meta_box('diet_recipe_short_desc', __('Short Description', 'diet'), array($this,'diet_recipe_short_desc_callback'), 'recipe');
        add_meta_box('diet_recipe_ingredients', __('Recipe Ingredients', 'diet'), array($this,'diet_recipe_ingredients_callback'), 'recipe');
    }
    function diet_recipe_time_callback() {
        global $post;
        wp_nonce_field( basename( __FILE__ ), 'recipe_fields' );
        $time_hours = (int) get_post_meta( $post->ID, 'diet_time_hours', true );
        $time_minutes = (int) get_post_meta( $post->ID, 'diet_time_minutes', true );
        echo '<input type="number" name="time_hours" value="' . esc_textarea( $time_hours )  . '" style="width:120px">:';
        echo '<input type="number" name="time_minutes" value="' . esc_textarea( $time_minutes )  . '" style="width:120px">';
    }

    function diet_recipe_rating_callback() {
        global $post;
        wp_nonce_field( basename( __FILE__ ), 'recipe_fields' );
        $rating = (int) get_post_meta( $post->ID, 'diet_rating', true );
        echo '<select name="diet_rating">';
        for($i=0;$i<6;$i++){
            $selected = ($rating==$i)?'selected':'';
            echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        }
        echo '</select>';
    }
    function diet_recipe_short_desc_callback(){
        global $post;
        $diet_recipe_short_desc = get_post_meta( $post->ID, 'recipe_short_desc', true );
        wp_editor($diet_recipe_short_desc,'recipe_short_desc');
    }


    function diet_recipe_ingredients_callback(){
        global $post;
        $recipe_ingredients = get_post_meta( $post->ID, 'diet_recipe_ingredients', true );
        ?>
        <div id="diet_ingredients">
            <?php
            if(is_array($recipe_ingredients)):
                foreach ($recipe_ingredients as $ingredient): ?>
                    <div>
                        <textarea name="recipe_ingredients[material][]" style="width: 45%" placeholder="material"><?php echo $ingredient['material']; ?></textarea>
                        <textarea name="recipe_ingredients[amount][]" style="width: 45%" placeholder="amount"><?php echo $ingredient['amount']; ?></textarea>
                        <a onclick="diet_remove_elmnt(this)"><span class="dashicons dashicons-trash" ></span></a>
                    </div>
                <?php endforeach; endif; ?>
        </div>
        <div style="text-align: center; margin: 5px"><a onclick="diet_add_elmnt()"><span class="dashicons dashicons-plus-alt"></span></a></div>
        <script>
            function diet_remove_elmnt(_this) {
                jQuery(_this).parent().remove();
            }
            function diet_add_elmnt(){
                var block = '<div>'+
                    '<textarea name="recipe_ingredients[material][]" style="width: 45%" placeholder="material"></textarea>'+
                    '<textarea name="recipe_ingredients[amount][]" style="width: 45%" placeholder="amount"></textarea>'+
                    '<a onclick="diet_remove_elmnt(this)"><span class="dashicons dashicons-trash" ></span></a>'+
                    '</div>';
                jQuery('#diet_ingredients').append(block);
            }
        </script>
        <?php
    }

    function diet_save_recipes_meta( $post_id, $post ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
        if (  ! wp_verify_nonce( $_POST['recipe_fields'], basename(__FILE__) ) ) {
            return $post_id;
        }
        if ( 'revision' === $post->post_type ) {
            return $post_id;
        }
        $recipe_meta['diet_time_hours'] = (int) $_POST['time_hours'];
        $recipe_meta['diet_time_minutes'] = (int) $_POST['time_minutes'];
        $recipe_meta['diet_rating'] = (int) $_POST['diet_rating'];
        $recipe_meta['recipe_short_desc'] = sanitize_text_field($_POST['recipe_short_desc']);
        foreach ($_POST['recipe_ingredients']['material'] as $key => $material){
            $recipe_meta['diet_recipe_ingredients'][]=array(
                'material' => sanitize_text_field($material),
                'amount' => sanitize_text_field($_POST['recipe_ingredients']['amount'][$key]),
            );
        }
        foreach ( $recipe_meta as $key => $value ) :
            if ( get_post_meta( $post_id, $key, true ) ) {
                update_post_meta( $post_id, $key, $value );

            } else {
                add_post_meta( $post_id, $key, $value);
            }
            if ( ! $value ) {
                delete_post_meta( $post_id, $key );
            }
        endforeach;
        return $post_id;
    }
}
new RecipePostType();