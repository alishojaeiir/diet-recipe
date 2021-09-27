<?php
class DietEndpoint
{
    public function __construct()
    {
        add_action('parse_request', array(&$this, 'sniff_requests'));
        add_filter('query_vars', array(&$this, 'add_query_vars'));
        add_action('init', array(&$this, 'add_endpoint'));
    }
    public function sniff_requests(){
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        global $wp;
        if (isset($wp->query_vars['diet_get_recipe'])) {
            $catid = (isset($_GET['category_id']) && $_GET['category_id'] !=0) ? (int) $_GET['category_id'] : null;
            $order = 'desc';
            $post_count = (isset($_GET['count']) && $_GET['count'] !=0) ? (int) $_GET['count'] : 2;
            $order = (isset($_GET['order'])) ? sanitize_text_field($_GET['order']) : $order;
            $paged = (isset($_GET['page']) && $_GET['page'] !=0) ? (int) $_GET['page'] : 1;
            $search_query = (isset($_GET['q'])) ? sanitize_text_field($_GET['q']) : null;
            $args = array(
                'post_type' => 'recipe',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $post_count,
                'paged' => $paged,
                'order' => $order,
                'orderby' => 'meta_value_num',
                'meta_query' => array(),
                'tax_query' => array(),
            );
            if ($search_query != null) {
                $args['s'] = $search_query;
            }
            if ($catid != null && $catid != 0) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'recipe_cat',
                    'field' => 'id',
                    'terms' => $catid
                );
            }
            $recipes = new WP_Query($args);
            $json = array();
            while ($recipes->have_posts()) :
                try {
                    $recipes->the_post();
                    $recipe_id = get_the_ID();
                    $json[] = RecipeFactory::get_recipe($recipe_id);
                } catch (Exception $e) {
                    //continue;
                }
            endwhile;
            wp_send_json($json);
        }
    }
    public function add_query_vars($vars)
    {
        $vars[] = 'diet_get_recipe';
        return $vars;
    }
    public function add_endpoint(){
        add_rewrite_rule('^diet/recipes$', 'index.php?diet_get_recipe=$matches[1]', 'top');
    }
}
new DietEndpoint();
