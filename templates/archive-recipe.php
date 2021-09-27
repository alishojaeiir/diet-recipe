<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();
?>

<div class="container" id="app">
    <div class="row">
        <div class="col-md-12">
            <h2 style="text-align:center"><?php _e('Recipes','diet');?></h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <input
                    class="close"
                    type="text"
                    v-model.trim="search"
                    placeholder="Search recipe..."
                    @keyup="onSelectData"/>
            <div id="searchclear" v-if="results.length!==0" @click="removeSearch"><i class="fa fa-times"></i></div>
            <div ref="searchResult" class="m-0 search-result" v-if="results.length !== 0">
                <div class="item" :class="{active: searchIndex === i}" :class="{active+i}" @mouseover="searchIndex=i" ref="i" v-for="(recipe,i) in results" :key="recipe.id">
                    <img v-bind:src=recipe.thumbnail.small  class="card-img-right m-2 flex-auto d-none d-md-block" style="float:left;height: 50px; weight: 50px;">
                    <div class="card-body d-flex flex-column align-items-start">
                        <a class="text-dark" v-bind:href=recipe.link >{{ recipe.title }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br />
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card flex-md-row mb-3 box-shadow h-md-250" v-for="recipe in recipes">
                <img class="card-img-right m-2 flex-auto d-none d-md-block" v-bind:alt=recipe.title style="width: 150px; height:150px" v-bind:src=recipe.thumbnail.small>

                <div class="card-body d-flex flex-column align-items-start">
                    <h3 class="m-0">
                        <a class="text-dark" v-bind:href=recipe.link >{{ recipe.title }}</a>
                    </h3>
                    <?php do_action('after_recipe_title'); ?>
                    <div>
                        <p class="rating float-left">
                            <span>
                                <i class="fa fa-star" v-if="recipe.rating>=1"></i>
                                <i class="fa fa-star-o" v-if="recipe.rating===0"></i>
                            </span>
                            <span>
                                <i class="fa fa-star" v-if="recipe.rating>=2"></i>
                                <i class="fa fa-star-o" v-if="recipe.rating <= 1"></i>
                            </span>
                            <span>
                                <i class="fa fa-star" v-if="recipe.rating>=3"></i>
                                <i class="fa fa-star-o" v-if="recipe.rating <= 2"></i>
                            </span>
                            <span>
                                <i class="fa fa-star" v-if="recipe.rating>=4"></i>
                                <i class="fa fa-star-o" v-if="recipe.rating <= 3"></i>
                            </span>
                            <span>
                                <i class="fa fa-star" v-if="recipe.rating >= 5"></i>
                                <i class="fa fa-star-o" v-if="recipe.rating <= 4"></i>
                            </span>
                        </p>
                        <div class="float-left ml-3 mr-3">|</div>
                        <p class="float-left"><span><i class=" fa fa-clock-o"></i></span> {{recipe.time.hours}}h : {{recipe.time.minutes}}m </p>
                    </div>
                    <div v-html="recipe.short_content" style="text-align: justify;"></div>
                </div>
            </div>
            <button v-if="show_more_button_is_visible" @click="show_more"><?php _e('Show more','diet') ?></button>
        </div>
    </div>
</div>
<?php
get_footer();
?>
    <script src="<?php echo DIET_URL ?>assets/js/vue.min.js"></script>
    <script>
        const ARROW_DOWN_KEYCODE = 40;
        const ARROW_UP_KEYCODE= 38;
        const ENTER_KEYCODE = 13;
        var app = new Vue({
            el: '#app',
            data: {
                recipes: [],
                url: '<?php echo site_url(); ?>/diet/recipes',
                search: '',
                results: [],
                searchIndex:0,
                page:1,
                show_more_button_is_visible: true
            },
            methods: {
                loadRecipes: async function () {
                    this.recipes = await this.getRecipes();
                },
                getRecipes: async function() {
                    const response = await fetch(this.url+'?page='+this.page);
                    return await response.json();
                },
                onSelectData: async function(e) {
                    const isArrowDownKey = e.keyCode === ARROW_DOWN_KEYCODE;
                    const isArrowUpKey = e.keyCode === ARROW_UP_KEYCODE;
                    const isEnterKey = e.keyCode === ENTER_KEYCODE;

                    if(isArrowDownKey && this.searchIndex < this.results.length - 1) {
                        console.log('up');
                        this.searchIndex++;
                    } else if(isArrowUpKey && this.searchIndex > 0) {
                        console.log('down');
                        this.searchIndex--;
                    } else if(isEnterKey) {
                        this.openUrl(this.searchIndex);
                    } else {
                        if(this.search.length > 2){
                            const response = await fetch(this.url + '?q=' + this.search.toLowerCase());
                            const data = await response.json();
                            console.log(data)
                            this.results = data;
                        }else{
                            this.results = [];
                        }
                    }
                },
                openUrl: function (index){
                    window.location.href = this.results[index-1].link;
                },
                removeSearch: function (){
                    this.results = [];
                    this.searchIndex = 0;
                    this.search = '';
                },
                show_more: async function (){
                    this.page++;
                    const data = await this.getRecipes();
                    if(data.length !== 0){
                        data.forEach((value, index) => {
                            this.recipes.push(value);
                        });
                    }else{
                        this.show_more_button_is_visible = false
                    }
                }
            },
            mounted: function () {
                this.loadRecipes();
            }
        })
    </script>