<?php
$category_obj = \helper\category::find_category_by_slug_nocache($category_slug, 'game');
$theme_url = '/' . get_config('root_theme') . "/" . \helper\options::options_by_key_type_nocache('index_theme');
$keywords = '';
$is_hot = '';
$is_new = '';
$detect = new Mobile_Detect;
$not_equal = array();
$type = '';
$display = 'yes';
$field_order = 'views';
if ($sort != null && $sort == 'most_played') {
    $field_order = 'views';
}
$order_type = 'desc';
$not_equal['type'] = array('VIDEO');
$page = ($_GET['page'] != null && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
$limit = ($limit != null) ? $limit : \helper\options::options_by_key_type_nocache('game_category_limit', 'display');
$list_games = \helper\game::get_paging_nocache($page, $limit, $keywords, $type, $display, $is_hot, $is_new, $field_order, $order_type, $category_obj->id, $not_equal);
$count = \helper\game::get_count_nocache($keywords, $type, $display, $is_hot, $is_new, $category_obj->id, $not_equal);
$paging = \helper\game::paging_link($count, $page, $limit);
$categories = \helper\category::find_by_taxonomy_nocache('game');
?>
<?php if ($list_games != null && count($list_games)): ?>
    <?php
    $shuffle = shuffle($list_games);
    ?>
    <div class="grid">
        <div class="col-lg-7 col-md-12 col-12">
            <div class="box-left">
                <div class="box-position">
                    <div class="category-game-slide">
                        <div class="swiper-wrapper">
                            <?php foreach ($list_games as $index => $game): ?>
                                <div class="swiper-slide">
                                    <div class="box-games">
                                        <div class="box-img">
                                            <img src="<?php echo $theme_url . "/resources/images/placeholder.png" ?>"
                                                 data-src="<?php echo \helper\image::convert_webpp(\helper\image::get_thumbnail($game->image, 250, 150, 'm')) ?>"
                                                 alt="<?php echo $game->name ?>" class="lazy">
                                            <div class="box-games-item">
                                                <div class="box-title">
                                                    <div class="box-name">
                                                        <?php echo $game->name ?>
                                                    </div>
                                                    <div class="box-description">
                                                        <?php echo $game->excerpt ?>
                                                    </div>
                                                </div>
                                                <a href="/<?php echo $game->slug ?>">
                                                    <div class="box--game-play">
                                                        <button class="btn--play-game">
                                                             <span class="svg-icon" aria-hidden="true">
                                                                 <svg class="svg-icon__link">
                                                                     <use xlink:href="#icon-game-controller"></use>
                                                                 </svg>
                                                             </span>
                                                        </button>
                                                        <span>Play Game</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-12 col-12">
            <div class="box-right">
                <div class="category-slide">
                    <div class="swiper-wrapper">
                        <?php foreach ($categories as $index => $category): ?>
                            <?php
                            $metadata = json_decode($category->metadata);
                            $image = $metadata->image;
                            ?>
                            <div class="box-category swiper-slide">
                                <div class="box-category--item">
                                    <div class="box-category--img">
                                        <img src="<?php echo \helper\image::get_thumbnail($image, 40, 40, 'm') ?>"
                                             alt="<?php echo $category->name ?>">
                                    </div>
                                    <div class="box-category--info">
                                        <div class="box-category--name"><?php echo $category->name ?></div>
                                        <div class="box-category--description"><?php echo html_entity_decode($category->description) ?></div>
                                    </div>
                                </div>
                                <a href="/<?php echo $category->slug ?>.games">
                                    <div class="box-category--play">View</div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
  