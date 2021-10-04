<?php
/*
    Plugin Name: Tr Movies
    Plugin URI: https://toroplay.com
    Description: Turn your WordPress into a CMS for films and series, TMDb API included.
    Author: ToroThemes
    Version: 2.2.6
    Author URI: http://torothemes.com/
    License: Private
*/

    define('TRMOVIES', 'TRMOVIES');
    define( 'TR_MOVIES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    define( 'TR_MOVIES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    if(is_admin()){
        $trmovieslicense=get_option('trmovieslicense');
        if($trmovieslicense!=''){
            require_once(TR_MOVIES_PLUGIN_DIR.'update.php');
            $trmovies_update = PucFactory::buildUpdateChecker(
                'http://toroflix.ml/?trapi=3&trproduct=Tr-movies&trserial='.$trmovieslicense,
                __FILE__
            );
        }

        // NEW
        
        $ar = get_option( 'tr_movies_options' );

        if($ar['prefix_posts']==''){

            $options_array=array( 'api' => $ar['api'], 'field_trailer' => 'field_trailer', 'field_title' => 'field_title', 'field_id' => 'field_id', 'field_imdbid' => 'field_imdbid', 'field_date' => 'field_date', 'field_duration' => 'field_runtime', 'field_backdrop' => 'field_backdrop', 'slug_directors' => 'director', 'slug_cast' => 'cast', 'field_links' => 'trmovieslink', 'upload_images' => 2, 'field_poster_hotlink' => 'poster_hotlink', 'field_backdrop_hotlink' => 'backdrop_hotlink', 'field_date_last' => 'field_date_last', 'field_inproduction' => 'field_inproduction', 'field_status' => 'status', 'field_nepisodes' => 'number_of_episodes', 'field_nseasons' => 'number_of_seasons', 'slug_directors_tv' => 'director_tv', 'slug_cast_tv' => 'cast_tv', 'field_slug_episode' => __('episode', TRMOVIES), 'field_slug_season' => __('season', TRMOVIES), 'field_slug_letters' => __('letters', TRMOVIES), 'prefix_posts' => 2, 'prefix_movies_permalink' => 'movie', 'prefix_series_permalink' => 'serie' );
            
            update_option('tr_movies_options', $options_array);
        }
        
    }

    add_action( 'init', 'trmovies_load_textdomain' );

    function trmovies_load_textdomain() {
      load_plugin_textdomain( TRMOVIES, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' ); 
    }

    add_action( 'admin_menu', 'tr_movies_remove_page' );

    function tr_movies_remove_page() {
        remove_menu_page('edit.php');	
    }

    function tr_header_admin() {
        if(isset($_GET['trhide'])){
        echo '<style>'."\n\r";
        echo '
        
        body.frmcd #postbox-container-1,body.frmcd .tr_error_thumb,body.frmcd #setting-error-tgmpa,body.frmcd #screen-meta-links,body.frmcd #wpfooter,body.frmcd #titlediv,body.frmcd .wp-heading-inline,body.frmcd .page-title-action{display: none!important;}
        body.frmcd #poststuff #post-body.columns-2,body.frmcd #wpseo_meta,body.frmcd .wrap{margin:0}
        body.frmcd #wpcontent{margin-right:0}
        body.frmcd #poststuff,body.frmcd #wpbody,body.frmcd #wpbody-content{padding: 0;}
        body.frmcd #wpcontent{padding:0 1rem 1rem}
        body.frmcd .BtnSnd{margin-top:1rem}
        iframe[src="https://toroplay.com/beta/wp-admin/post.php?post=10&action=edit&trhide=1"]{height:800px}
        @media screen and (max-width:600px){
        body.frmcd #wpcontent{padding-top:2.5rem}
        }
        

        
        ';
        echo'</style>';
    }
    }
    add_action( 'admin_head', 'tr_header_admin' );

    add_filter('admin_body_class', 'admin_body_class');

    function admin_body_class( $classes ) {
    if(isset($_GET['trhide'])){
        $classes .= ' frmcd ';
    }
        return $classes;
    }

    function tr_movies_install() {
                
        $options_array=array( 'api' => '', 'field_trailer' => 'field_trailer', 'field_title' => 'field_title', 'field_id' => 'field_id', 'field_imdbid' => 'field_imdbid', 'field_date' => 'field_date', 'field_duration' => 'field_runtime', 'field_backdrop' => 'field_backdrop', 'slug_directors' => 'director', 'slug_cast' => 'cast', 'field_links' => 'trmovieslink', 'upload_images' => 2, 'field_poster_hotlink' => 'poster_hotlink', 'field_backdrop_hotlink' => 'backdrop_hotlink', 'field_date_last' => 'field_date_last', 'field_inproduction' => 'field_inproduction', 'field_status' => 'status', 'field_nepisodes' => 'number_of_episodes', 'field_nseasons' => 'number_of_seasons', 'slug_directors_tv' => 'director_tv', 'slug_cast_tv' => 'cast_tv', 'field_slug_episode' => __('episode', TRMOVIES), 'field_slug_season' => __('season', TRMOVIES), 'field_slug_letters' => __('letters', TRMOVIES), 'prefix_posts' => 2, 'prefix_movies_permalink' => 'movie', 'prefix_series_permalink' => 'serie' );
        
        if(get_option( 'tr_movies_options' )==FALSE){update_option('tr_movies_options', $options_array);}
        
    }

    register_activation_hook(__FILE__, 'tr_movies_install');

    add_action('admin_menu', 'tr_movies_options');

    function tr_movies_options(){
        
        add_menu_page( 'TR Movies', 'TR Movies', 'manage_options', 'tr-movies', 'tr_movies_function', '', 2 );
        if(defined('TR_MOVIES_MOVIES')){
            add_submenu_page( 'tr-movies', __('Movies', TRMOVIES), __('Movies', TRMOVIES), 'publish_posts', 'tr-movies-movie', 'tr_movies_movie' );
        }
        if(defined('TR_MOVIES_SERIES')){
            add_submenu_page( 'tr-movies', __('TV Shows', TRMOVIES), __('TV Shows', TRMOVIES), 'publish_posts', 'tr-movies-tv', 'tr_movies_tvshow' );
        }
    }

    function tr_movies_menu($type=1) {
        
        if($type==1){
            
            $currentclassa = ''; $currentclassb = ''; $currentclassc = ''; $currentclassd = ''; $currentclasse = ''; $currentclassf = '';
            
            $current2classa = ''; $current2classb = ''; $current2classc = ''; $current2classd = '';
            
            if(isset($_GET['page']) and $_GET['page']=='tr-movies-movie' and empty($_GET['action'])){ $currentclassa=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='add'){ $currentclassb=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='directors'){ $currentclassc=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='cast'){ $currentclassd=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='links' and $_GET['action2']!='countries'){ $currentclasse=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='categories'){ $currentclassf=' class="Current"'; }
            
            if(isset($_GET['action2']) and $_GET['action2']=='servers'){ $current2classa=' class="Current"'; }
            if(isset($_GET['action2']) and $_GET['action2']=='languages'){ $current2classb=' class="Current"'; }
            if(isset($_GET['action2']) and $_GET['action2']=='qualitys'){ $current2classc=' class="Current"'; }
            if(isset($_GET['action']) and isset($_GET['action2']) and $_GET['action2']=='countries'){ $current2classd=' class="Current"'; }

            echo'
                <ul class="MnTpAdn">
                    <li'.$currentclassa.'><a href="admin.php?page=tr-movies-movie">'.__('All', TRMOVIES).'</a></li>
                    <li'.$currentclassb.'><a href="admin.php?page=tr-movies-movie&action=add">'.__('Add Movie', TRMOVIES).'</a></li>
                    <li'.$currentclassc.'><a href="admin.php?page=tr-movies-movie&action=directors">'.__('Directors', TRMOVIES).'</a></li>
                    <li'.$currentclassd.'><a href="admin.php?page=tr-movies-movie&action=cast">'.__('Cast', TRMOVIES).'</a></li>
                    <li'.$current2classd.'><a href="admin.php?page=tr-movies-movie&action=links&action2=countries">'.__('Countries', TRMOVIES).'</a></li>
                    <li'.$currentclasse.'>
                        <a href="#">'.__('Links', TRMOVIES).' <i class="dashicons dashicons-arrow-down-alt2"></i></a>
                        <ul>
                            <li'.$current2classa.'><a href="admin.php?page=tr-movies-movie&action=links&action2=servers">'.__('Servers', TRMOVIES).'</a></li>
                            <li'.$current2classb.'><a href="admin.php?page=tr-movies-movie&action=links&action2=languages">'.__('Languages', TRMOVIES).'</a></li>
                            <li'.$current2classc.'><a href="admin.php?page=tr-movies-movie&action=links&action2=qualitys">'.__('Qualitys', TRMOVIES).'</a></li>
                        </ul>
                    </li>
                    <li'.$currentclassf.'><a href="admin.php?page=tr-movies-movie&action=categories">'.__('Categories', TRMOVIES).'</a></li>
                </ul>
            ';
            
        }else{
            
            $currentclassa = ''; $currentclassb = ''; $currentclassc = ''; $currentclassd = ''; $currentclassf = '';
            
            $current2classa = ''; $current2classb = ''; $current2classc = ''; $currentclasse = '';
            
            if(isset($_GET['page']) and $_GET['page']=='tr-movies-tv' and empty($_GET['action'])){ $currentclassa=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='add'){ $currentclassb=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='directors'){ $currentclassc=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='cast'){ $currentclassd=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='categories'){ $currentclassf=' class="Current"'; }
            
            if(isset($_GET['action2']) and $_GET['action2']=='servers'){ $current2classa=' class="Current"'; }
            if(isset($_GET['action2']) and $_GET['action2']=='languages'){ $current2classb=' class="Current"'; }
            if(isset($_GET['action2']) and $_GET['action2']=='qualitys'){ $current2classc=' class="Current"'; }
            if(isset($_GET['action']) and $_GET['action']=='links'){ $currentclasse=' class="Current"'; }
            
            echo'
            
                <ul class="MnTpAdn">
                    <li'.$currentclassa.'><a href="admin.php?page=tr-movies-tv">'.__('All', TRMOVIES).'</a></li>
                    <li'.$currentclassb.'><a href="admin.php?page=tr-movies-tv&action=add">'.__('Add Tv Show', TRMOVIES).'</a></li>
                    <li'.$currentclassc.'><a href="admin.php?page=tr-movies-tv&action=directors">'.__('Directors', TRMOVIES).'</a></li>
                    <li'.$currentclassd.'><a href="admin.php?page=tr-movies-tv&action=cast">'.__('Cast', TRMOVIES).'</a></li>
                    <li'.$currentclasse.'>
                        <a href="#">'.__('Links', TRMOVIES).' <i class="dashicons dashicons-arrow-down-alt2"></i></a>
                        <ul>
                            <li'.$current2classa.'><a href="admin.php?page=tr-movies-tv&action=links&action2=servers">'.__('Servers', TRMOVIES).'</a></li>
                            <li'.$current2classb.'><a href="admin.php?page=tr-movies-tv&action=links&action2=languages">'.__('Languages', TRMOVIES).'</a></li>
                            <li'.$current2classc.'><a href="admin.php?page=tr-movies-tv&action=links&action2=qualitys">'.__('Qualitys', TRMOVIES).'</a></li>
                        </ul>
                    </li>
                    <li'.$currentclassf.'><a href="admin.php?page=tr-movies-tv&action=categories">'.__('Categories', TRMOVIES).'</a></li>
                </ul>
            
            ';
            
        }
        
    }

    function tr_movies_movie() {
        
        echo '<div class="wrap trmovies_content">';
        
        if(isset($_GET['action']) and $_GET['action']=='add' and $_GET['page']=='tr-movies-movie') {

            include( TR_MOVIES_PLUGIN_DIR . 'inc/movies/add_movie.php');
           
        }elseif(isset($_GET['action']) and $_GET['action']=='edit' and $_GET['page']=='tr-movies-movie'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/movies/edit_movie.php');
            
        }elseif(isset($_GET['action']) and $_GET['action']=='directors' and $_GET['page']=='tr-movies-movie'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/directors/directors.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='cast' and $_GET['page']=='tr-movies-movie'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/cast/cast.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='links' and $_GET['action2']=='servers' and $_GET['page']=='tr-movies-movie'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/servers/servers.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='links' and $_GET['action2']=='languages' and $_GET['page']=='tr-movies-movie'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/languages/languages.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='links' and $_GET['action2']=='qualitys' and $_GET['page']=='tr-movies-movie'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/qualitys/qualitys.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='links' and $_GET['action2']=='countries' and $_GET['page']=='tr-movies-movie'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/countries/countries.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='categories' and $_GET['page']=='tr-movies-movie'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/categories/show_categories.php');
            
        }else{

            include( TR_MOVIES_PLUGIN_DIR . 'inc/movies/show_movie.php');
            
        }
        
        echo '</div>';
        
        
    }

    function tr_movies_tvshow() {

        echo '<div class="wrap trmovies_content">';
        
        if(isset($_GET['action']) and $_GET['action']=='add' and $_GET['page']=='tr-movies-tv') {
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tvshows/add_tvshow.php');
            
        }elseif(isset($_GET['action']) and $_GET['action']=='edit' and $_GET['page']=='tr-movies-tv'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tvshows/edit_tvshow.php');
            
         }elseif(isset($_GET['action']) and $_GET['action']=='directors' and $_GET['page']=='tr-movies-tv'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/directors_tv/directors.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='categories' and $_GET['page']=='tr-movies-tv'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/categories_tv/show_categories.php');
            
        }elseif(isset($_GET['action']) and $_GET['action']=='cast' and $_GET['page']=='tr-movies-tv'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/cast_tv/cast.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='links' and $_GET['action2']=='servers' and $_GET['page']=='tr-movies-tv'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/servers_tv/servers.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='links' and $_GET['action2']=='languages' and $_GET['page']=='tr-movies-tv'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/languages_tv/languages.php');            
            
        }elseif(isset($_GET['action']) and $_GET['action']=='links' and $_GET['action2']=='qualitys' and $_GET['page']=='tr-movies-tv'){
            
            include( TR_MOVIES_PLUGIN_DIR . 'inc/tax/qualitys_tv/qualitys.php');            
            
        }else{

            include( TR_MOVIES_PLUGIN_DIR . 'inc/tvshows/show_tvshow.php');
            
        }
        
        echo '</div>';
        
    }

   function tr_movies_function(){
       
        $error = ''; $ok = '';
       
        if(isset($_POST['submit'])){
            
            if(empty($_POST['api_key']) or empty($_POST['field_trailer']) or empty($_POST['field_title']) or empty($_POST['field_id']) or empty($_POST['field_imdbid']) or empty($_POST['field_date']) or empty($_POST['field_duration']) or empty($_POST['field_backdrop']) or empty($_POST['field_directors']) or empty($_POST['field_cast']) or empty($_POST['field_link']) or empty($_POST['upload_images']) or empty($_POST['field_directors_tv']) or empty($_POST['field_slug_episode']) or empty($_POST['field_slug_season'])){
                
                $error='<div class="error"><p>'.__('You can not empty fields', TRMOVIES).'</p></div>';
                
            }else{
                
                $ar_prefix_movies_permalink = ''; $ar_prefix_series_permalink = '';
                
                if(isset($_POST['prefix_movies_permalink'])){ $ar_prefix_movies_permalink=esc_sql($_POST['prefix_movies_permalink']); }
                if(isset($_POST['prefix_series_permalink'])){ $ar_prefix_series_permalink=esc_sql($_POST['prefix_series_permalink']); }
                
                $explode = explode('/%tr_post_type%/', get_option('permalink_structure'));
                if(count($explode)==2){
                    $prefix_posts = 1;
                }else{
                    $prefix_posts = 2;
                }
            
                $array = array(
                    'api' => esc_sql($_POST['api_key']),
                    'field_trailer' => esc_sql($_POST['field_trailer']),
                    'field_title' => esc_sql($_POST['field_title']),
                    'field_id' => esc_sql($_POST['field_id']),
                    'field_imdbid' => esc_sql($_POST['field_imdbid']),
                    'field_date' => esc_sql($_POST['field_date']),
                    'field_duration' => esc_sql($_POST['field_duration']),
                    'field_backdrop' => esc_sql($_POST['field_backdrop']),
                    'slug_directors' => esc_sql($_POST['field_directors']),
                    'slug_cast' => esc_sql($_POST['field_cast']),
                    'field_links' => esc_sql($_POST['field_link']),
                    'upload_images' => intval($_POST['upload_images']),
                    'field_poster_hotlink' => 'poster_hotlink',
                    'field_backdrop_hotlink' => 'backdrop_hotlink',
                    'field_date_last' => 'field_date_last',
                    'field_inproduction' => 'field_inproduction',
                    'field_status' => 'status',
                    'field_nepisodes' => 'number_of_episodes',
                    'field_nseasons' => 'number_of_seasons',
                    'slug_directors_tv' => esc_sql($_POST['field_directors_tv']),
                    'slug_cast_tv' => esc_sql($_POST['field_cast_tv']),
                    'field_slug_season' => esc_sql($_POST['field_slug_season']),
                    'field_slug_episode' => esc_sql($_POST['field_slug_episode']),
                    'field_slug_letters' => esc_sql($_POST['field_slug_letters']),
                    'prefix_posts' => $prefix_posts,
                    'prefix_movies_permalink' => $ar_prefix_movies_permalink,
                    'prefix_series_permalink' => $ar_prefix_series_permalink,
                );
                
                update_option('tr_movies_options', $array);
                
            }
            
        }
              
        $tr_movies_options = get_option('tr_movies_options');


              
        $field_trailer = $tr_movies_options['field_trailer'];
       
        $api_key = $tr_movies_options['api'];
       
        $field_title = $tr_movies_options['field_title'];
       
        $field_id = $tr_movies_options['field_id'];
       
        $field_imdbid = $tr_movies_options['field_imdbid'];
       
        $field_date = $tr_movies_options['field_date'];
       
        $field_duration = $tr_movies_options['field_duration'];
       
        $field_backdrop = $tr_movies_options['field_backdrop'];
       
        $field_directors = $tr_movies_options['slug_directors'];
       
        $field_cast = $tr_movies_options['slug_cast'];
       
        $field_link = $tr_movies_options['field_links'];
       
        $upload_images = $tr_movies_options['upload_images'];
       
        $field_directors_tv = $tr_movies_options['slug_directors_tv'];
       
        $field_cast_tv = $tr_movies_options['slug_cast_tv'];
       
        $field_slug_episode = $tr_movies_options['field_slug_episode'];
        
        $field_slug_season = $tr_movies_options['field_slug_season'];

        $field_slug_letters = $tr_movies_options['field_slug_letters'];
               
        if(!empty($tr_movies_options['prefix_movies_permalink'])){
            $prefix_movies_permalink = $tr_movies_options['prefix_movies_permalink'];
        }else{
            $prefix_movies_permalink = '';
        }
        if(!empty($tr_movies_options['prefix_series_permalink'])){
            $prefix_series_permalink = $tr_movies_options['prefix_series_permalink'];
        }else{
            $prefix_series_permalink = '';
        }
        
        echo '
        <div class="trmovies_content">
            <section class="wrap tr-movies">
                <div class="Top">
                    <h1>TR Movies <span>2.1</span></h1>
                </div>
                
                '.$error.$ok.'
                
                <ul class="tr-config-tab Blkcn TbsBxCn">
                    <li class="Current"><button data-tab="1" type="button">'.__('General', TRMOVIES).'</button></li>
                    <li><button data-tab="2" type="button">'.__('Advanced', TRMOVIES).'</button></li>
                </ul>
                
                <form action="admin.php?page=tr-movies" method="post" id="frmtrmovies" class="Blkcn">
                    <table class="form-table">
                        <tbody>

                            <tr class="tr-config-1 trinpsml">
                                <th scope="row"><label>'.__('API KEY', TRMOVIES).'</label></th>
                                <td><input name="api_key" type="text" value="'.$api_key.'" class="regular-text"> <a class="trmoviesapikey" target="_blank" href="https://www.themoviedb.org/account/">'.__('Get API Key', TRMOVIES).'</a></td>
                            </tr>
                            
                            <tr class="tr-config-1">
                                <th scope="row"><label>'.__('Upload images', TRMOVIES).'</label></th>
                                <td>                                
                                    <ul class="StsOptns AltOptns">

                                        <li><input '.checked( $upload_images, 1, false).' type="radio" name="upload_images" value="1"> <span>'.__('Enable', TRMOVIES).'</span></li>

                                        <li><input '.checked( $upload_images, 2, false).' type="radio" name="upload_images" value="2"> <span>'.__('Disable', TRMOVIES).'</span></li>

                                    </ul>
                                </td>
                            </tr>
                            
                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Movies', TRMOVIES).'</label></th>
                                <td><input name="prefix_movies_permalink" type="text" value="'.$prefix_movies_permalink.'" class="regular-text"></td>
                            </tr>
                            
                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Series', TRMOVIES).'</label></th>
                                <td><input name="prefix_series_permalink" type="text" value="'.$prefix_series_permalink.'" class="regular-text"></td>
                            </tr>
                            
                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Field Trailer', TRMOVIES).'</label></th>
                                <td><input name="field_trailer" type="text" value="'.$field_trailer.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Field Title Original', TRMOVIES).'</label></th>
                                <td><input name="field_title" type="text" value="'.$field_title.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Field ID', TRMOVIES).'</label></th>
                                <td><input name="field_id" type="text" value="'.$field_id.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Field IMDBID', TRMOVIES).'</label></th>
                                <td><input name="field_imdbid" type="text" value="'.$field_imdbid.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Field Date', TRMOVIES).'</label></th>
                                <td><input name="field_date" type="text" value="'.$field_date.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Field Duration', TRMOVIES).'</label></th>
                                <td><input name="field_duration" type="text" value="'.$field_duration.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Field Backdrop', TRMOVIES).'</label></th>
                                <td><input name="field_backdrop" type="text" value="'.$field_backdrop.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Directors', TRMOVIES).'</label></th>
                                <td><input name="field_directors" type="text" value="'.$field_directors.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Directors TV', TRMOVIES).'</label></th>
                                <td><input name="field_directors_tv" type="text" value="'.$field_directors_tv.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Cast', TRMOVIES).'</label></th>
                                <td><input name="field_cast" type="text" value="'.$field_cast.'" class="regular-text"></td>
                            </tr>
                            
                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Cast TV', TRMOVIES).'</label></th>
                                <td><input name="field_cast_tv" type="text" value="'.$field_cast_tv.'" class="regular-text"></td>
                            </tr>
                            
                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Episode', TRMOVIES).'</label></th>
                                <td><input name="field_slug_episode" type="text" value="'.$field_slug_episode.'" class="regular-text"></td>
                            </tr>
                            
                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Season', TRMOVIES).'</label></th>
                                <td><input name="field_slug_season" type="text" value="'.$field_slug_season.'" class="regular-text"></td>
                            </tr>
                            
                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Slug Letters', TRMOVIES).'</label></th>
                                <td><input name="field_slug_letters" type="text" value="'.$field_slug_letters.'" class="regular-text"></td>
                            </tr>

                            <tr class="tr-config-2" style="display:none">
                                <th scope="row"><label>'.__('Field Links', TRMOVIES).'</label></th>
                                <td><input name="field_link" type="text" value="'.$field_link.'" class="regular-text"></td>
                            </tr>

                            <tr>
                                <th>&nbsp;</th>
                                <td>
                                    <input type="hidden" name="nonce" value="'.wp_create_nonce( 'tr-moviesoptions-nonce' ).'">
                                    <input type="hidden" name="action" value="tr_movies_action_options">
                                    <input type="submit" name="submit" id="submit" class="BtnSnd button-tr-importer-redtube" value="'.__('Save Changes', TRMOVIES).'">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </section>
        </div>
        ';
    }

    $tr_movies_options = get_option('tr_movies_options');   

    if(!defined( 'TR_MOVIES_FIELD_TRAILER')){
        define( 'TR_MOVIES_FIELD_TRAILER', $tr_movies_options['field_trailer'] );
    }
    if(!defined('TR_MOVIES_FIELD_ORIGINALTITLE')){
        define( 'TR_MOVIES_FIELD_ORIGINALTITLE', $tr_movies_options['field_title'] );
    }
    if(!defined('TR_MOVIES_FIELD_ID')){
        define( 'TR_MOVIES_FIELD_ID', $tr_movies_options['field_id'] );
    }
    if(!defined('TR_MOVIES_FIELD_IMDBID')){
        define( 'TR_MOVIES_FIELD_IMDBID', $tr_movies_options['field_imdbid'] );
    }
    if(!defined('TR_MOVIES_FIELD_DATE')){
        define( 'TR_MOVIES_FIELD_DATE', $tr_movies_options['field_date'] );
    }
    if(!defined('TR_MOVIES_FIELD_DATE_LAST')){
        define( 'TR_MOVIES_FIELD_DATE_LAST', $tr_movies_options['field_date_last'] );
    }
    if(!defined('TR_MOVIES_FIELD_RUNTIME')){
        define( 'TR_MOVIES_FIELD_RUNTIME', $tr_movies_options['field_duration'] );
    }
    if(!defined('TR_MOVIES_FIELD_BACKDROP')){
        define( 'TR_MOVIES_FIELD_BACKDROP', $tr_movies_options['field_backdrop'] );
    }
    if(!defined('TR_MOVIES_FIELD_DT_BACKDROP')){
        define( 'TR_MOVIES_FIELD_DT_BACKDROP', 'dt_backdrop' ); //Lava
    }
    if(!defined('TR_MOVIES_SLUG_DIRECTORS')){
        define( 'TR_MOVIES_SLUG_DIRECTORS', $tr_movies_options['slug_directors'] );
    }
    if(!defined('TR_MOVIES_SLUG_DIRECTORS_TV')){
        define( 'TR_MOVIES_SLUG_DIRECTORS_TV', $tr_movies_options['slug_directors_tv'] );
    }
    if(!defined('TR_MOVIES_SLUG_CAST')){
        define( 'TR_MOVIES_SLUG_CAST', $tr_movies_options['slug_cast'] );
    }
    if(!defined('TR_MOVIES_SLUG_CAST_TV')){
        define( 'TR_MOVIES_SLUG_CAST_TV', $tr_movies_options['slug_cast_tv'] );
    }
    if(!defined('TR_MOVIES_SLUG_SEASON')){
        define( 'TR_MOVIES_SLUG_SEASON', __('season', TRMOVIES) );
    }
    if(!defined('TR_MOVIES_SLUG_EPISODE')){
        define( 'TR_MOVIES_SLUG_EPISODE', __('episode', TRMOVIES) );
    }
    if(!defined('TR_MOVIES_FIELD_LINK')){
        define( 'TR_MOVIES_FIELD_LINK', $tr_movies_options['field_links'] );
    }
    if(!defined('TR_MOVIES_UPLOAD_IMAGES')){
        define( 'TR_MOVIES_UPLOAD_IMAGES', $tr_movies_options['upload_images'] );
    }
    if(!defined('TR_MOVIES_POSTER_HOTLINK')){
        define( 'TR_MOVIES_POSTER_HOTLINK', $tr_movies_options['field_poster_hotlink'] );
    }
    if(!defined('TR_MOVIES_BACKDROP_HOTLINK')){
        define( 'TR_MOVIES_BACKDROP_HOTLINK', $tr_movies_options['field_backdrop_hotlink'] );
    }
    if(!defined('TR_MOVIES_FIELD_INPRODUCTION')){
        define( 'TR_MOVIES_FIELD_INPRODUCTION', $tr_movies_options['field_inproduction'] );
    }
    if(!defined('TR_MOVIES_FIELD_STATUS')){
        define( 'TR_MOVIES_FIELD_STATUS', $tr_movies_options['field_status'] );
    }
    if(!defined('TR_MOVIES_FIELD_NEPISODES')){
        define( 'TR_MOVIES_FIELD_NEPISODES', $tr_movies_options['field_nepisodes'] );
    }
    if(!defined('TR_MOVIES_FIELD_NSEASONS')){
        define( 'TR_MOVIES_FIELD_NSEASONS', $tr_movies_options['field_nseasons'] );
    }
    if(!defined('TR_MOVIES_PREFIX_POSTS')){
        define( 'TR_MOVIES_PREFIX_POSTS', $tr_movies_options['prefix_posts'] );
    }
    define( 'TR_MOVIES_API_KEY', $tr_movies_options['api'] );

    function tr_movies_init_print() {
        if ( is_admin() ) {
            $_GET['post'] = empty($_GET['post']) ? '' : $_GET['post'];
            $myvars = array( 
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'loading' => __('Loading...', TRMOVIES),
                'nonce' => wp_create_nonce( 'tr-movies-nonce' ),
                'nonce_links' => wp_create_nonce( 'tr-movieslinks-nonce' ),
                'language' => get_bloginfo("language"),
                'api_key' => TR_MOVIES_API_KEY,
                'upload_txt' => __('Upload backdrop', TRMOVIES),
                'nodelete' => __('I can not delete this item.', TRMOVIES),
                'post_id' => intval($_GET['post'])
            );
            wp_enqueue_script( 'tr-movies-admin', TR_MOVIES_PLUGIN_URL."assets/js/admin.js",array('jquery'), '2.2.3', true);
            wp_localize_script( 'tr-movies-admin', 'TrMovies', $myvars );
            wp_enqueue_style("tr-movies-admin", TR_MOVIES_PLUGIN_URL."assets/css/admin.css", false, "2.2.3", "all");
            if(isset($_GET['page']) and $_GET['page']=='tr-movies-movie' or isset($_GET['page']) and $_GET['page']=='tr-movies-tv'){
                wp_enqueue_script('trselect2full', TR_MOVIES_PLUGIN_URL.'assets/js/select2.full.min.js',array('jquery'),'2.2.3',false);
                wp_enqueue_script('trinpfil', TR_MOVIES_PLUGIN_URL.'assets/js/inpfil.js',array('jquery'),'2.2.3',false);
            }
        }
    }

    add_action( 'admin_print_styles', 'tr_movies_init_print', 11 );


	function tr_movieslinks_ajax(){

		$nonce = $_POST['nonce'];
 
	    if ( ! wp_verify_nonce( $nonce, 'tr-movieslinks-nonce' ) )
    	    die ( 'Die!');
	
	
		if($_POST['action'] == 'tr_movieslinks_action') {
            
            if(isset($_POST['type'])){
                
                $term_list_server = wp_get_post_terms(intval($_POST['post_id']), 'server', array("fields" => "all"));
                if(!is_wp_error($term_list_server) and !empty($term_list_server)){
                    foreach($term_list_server as $server_single) {
                    
                        wp_remove_object_terms( intval($_POST['post_id']), $server_single->slug, 'server' );
                    }
                }
                
                $term_list_language = wp_get_post_terms(intval($_POST['post_id']), 'language', array("fields" => "all"));
                if(!is_wp_error($term_list_language) and !empty($term_list_language)){
                    foreach($term_list_language as $language_single) {
                    
                        wp_remove_object_terms( intval($_POST['post_id']), $language_single->slug, 'language' );
                    }
                }
                
                $term_list_quality = wp_get_post_terms(intval($_POST['post_id']), 'quality', array("fields" => "all"));
                if(!is_wp_error($term_list_quality) and !empty($term_list_quality)){
                    foreach($term_list_quality as $quality_single) {
                    
                        wp_remove_object_terms( intval($_POST['post_id']), $quality_single->slug, 'quality' );
                    }
                }
                
                _e('<div class="trmovieswarning trmoviesnotsupport">Remember to update or publish your post or all your links will be lost.</div>', TRMOVIES);
                
            }else{
                        
            $array_check = array('Yaske.org');
            
            $domain = ucwords(trmovies_get_domain($_POST['url']));
            
            if (in_array($domain, $array_check)) {

                $site = trmovies_curl($_POST['url']);
                                             
                if($domain==$array_check[0]){ // yaske.org
                                        
                    $type_ar[] = 1;
                    
                    preg_match('/<div class=\"pelicula\" style=\"\">(\n)<div style=\"text-align:center;\">(\n)(.*?)<\/iframe>/i', $site, $iframe);

                    preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $iframe[3].'</iframe>', $urliframe);

                    $link_ar[] = ($urliframe[1]);

                    $server = ucwords(trmovies_get_domain($urliframe[1], false));

                    preg_match('/<li><b>Idioma:<\/b>(.*?)(\n)<img/i', $site, $lang);  

                    $lang = ucwords(str_replace('-', '', str_replace(' ', '', trim($lang[1]))));

                    preg_match('/<li><b>Calidad:<\/b><a title=\"(.*?)\">(.*?)<\/a><\/li>/i', $site, $quality);  

                    $quality = ucwords(str_replace('-', '', str_replace(' ', '', trim($quality[2]))));
                    
                    $term_server = term_exists($server, 'server');

                    if ($term_server !== 0 && $term_server !== null) {

                        $server_ar[] = $term_server['term_id'];
                        
                    }else{
                        
                        
                        $insert_server = wp_insert_term($server, 'server', array());
                        
                        $server_ar[] = $insert_server['term_id'];
                        
                    }
                    
                    $term_lang = term_exists($lang, 'language');

                    if ($term_lang !== 0 && $term_lang !== null) {

                        $lang_ar[] = $term_lang['term_id'];
                        
                    }else{
                        
                        
                        $insert_lang = wp_insert_term($lang, 'language', array());
                        
                        $lang_ar[] = $insert_lang['term_id'];
                        
                    }
                    
                    $term_quality = term_exists($quality, 'quality');

                    if ($term_quality !== 0 && $term_quality !== null) {

                        $quality_ar[] = $term_quality['term_id'];
                        
                    }else{
                        
                        
                        $insert_quality = wp_insert_term($quality, 'quality', array());
                        
                        $quality_ar[] = $insert_quality['term_id'];
                        
                    }
                
                }
                    
                trmovies_tr_links(count($link_ar), $type_ar, $server_ar, $lang_ar, $quality_ar, $link_ar);
                
                
            }else{
                echo 1;
            }
                
            }
            
		}
		exit;
	}
	
	add_action( 'wp_ajax_tr_movieslinks_action', 'tr_movieslinks_ajax' );
	add_action( 'wp_ajax_nopriv_tr_movieslinks_action', 'tr_movieslinks_ajax');

    function trmovies_tr_links($count, $type_ext, $server_ext, $lang_ext, $quality_ext, $link_ext){
        
        for ($i = 0; $i <= $count-1; $i++) {

            echo'
            <tr class="tr-movies-row">
                <td>
                    <select name="trmovies_type[]">
                        <option value="">'.__('Select', TRMOVIES).'</option>
                        <option '.selected( $type_ext[$i], 1, false ).' value="1" selected>'.__('Online', TRMOVIES).'</option>
                        <option '.selected( $type_ext[$i], 2, false ).' value="2">'.__('Download', TRMOVIES).'</option>
                    </select>
                </td>
                <td>
                    <select name="trmovies_server[]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';

                            $servers = get_categories( array(
                                'orderby' => 'name',
                                'hide_empty' => 0,
                                'taxonomy' => 'server'
                            ) );

                            foreach ( $servers as $server ) {
                                echo '<option '.selected( $server_ext[$i], $server->term_id, false ).' value="'.$server->term_id.'">'.$server->name.'</option>';
                            }
                    echo'
                    </select>                    
                </td>
                <td>
                    <select name="trmovies_lang[]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';

                            $languages = get_categories( array(
                                'orderby' => 'name',
                                'hide_empty' => 0,
                                'taxonomy' => 'language'
                            ) );

                            foreach ( $languages as $lang ) {
                                echo '<option '.selected( $lang_ext[$i], $lang->term_id, false ).' value="'.$lang->term_id.'">'.$lang->name.'</option>';
                            }
                echo'
                    </select>                      
                </td>
                <td>
                    <select name="trmovies_quality[]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';

                            $qualitys = get_categories( array(
                                'orderby' => 'name',
                                'hide_empty' => 0,
                                'taxonomy' => 'quality'
                            ) );

                            foreach ( $qualitys as $quality ) {
                                echo '<option '.selected( $quality_ext[$i], $quality->term_id, false ).' value="'.$quality->term_id.'">'.$quality->name.'</option>';
                            }
                echo'
                    </select>
                </td>
                <td>
                    <input name="trmovies_link[]" value="'.$link_ext[$i].'" type="text">
                </td>
                <td>
                    <input type="text" value="'.date('d').'/'.date('m').'/'.date('Y').'" name="trmovies_date[]">
                </td>
                <td><button id="trmovies_removelink" type="button" class="BtnTrpy"><i class="dashicons dashicons-dismiss"></i>'.__('Delete', TRMOVIES).'</button></td>
            </tr>';
        }
        
        $trmovieslinks = unserialize(get_post_meta(intval($_POST['post_id']), 'trmovieslink', true));
        
        if(count($trmovieslinks)>0){
                
            for ($i = 0; $i <= count($trmovieslinks)-1; $i++) {
                
                echo'
                    <tr class="tr-movies-row">
                        <td>
                            <select name="trmovies_type[]">
                                <option value="">'.__('Select', TRMOVIES).'</option>
                                <option value="1" '.selected( $trmovieslinks[$i]['type'], 1 ).'>'.__('Online', TRMOVIES).'</option>
                                <option value="2" '.selected( $trmovieslinks[$i]['type'], 2 ).'>'.__('Download', TRMOVIES).'</option>
                            </select>
                        </td>
                        <td>
                            <select name="trmovies_server[]">
                                <option value="">'.__('Select', TRMOVIES).'</option>';
                
                                    $servers = get_categories( array(
                                        'orderby' => 'name',
                                        'hide_empty' => 0,
                                        'taxonomy' => 'server'
                                    ) );

                                    foreach ( $servers as $server ) {
                                        echo '<option '.selected( $trmovieslinks[$i]['server'], $server->term_id, false ).' value="'.$server->term_id.'">'.$server->name.'</option>';
                                    }
                    echo'
                            </select>                    
                        </td>
                        <td>
                            <select name="trmovies_lang[]">
                                <option value="">'.__('Select', TRMOVIES).'</option>';
                                
                                    $languages = get_categories( array(
                                        'orderby' => 'name',
                                        'hide_empty' => 0,
                                        'taxonomy' => 'language'
                                    ) );

                                    foreach ( $languages as $lang ) {
                                        echo '<option '.selected( $trmovieslinks[$i]['lang'], $lang->term_id, false ).' value="'.$lang->term_id.'">'.$lang->name.'</option>';
                                    }
                echo'
                            </select>                      
                        </td>
                        <td>
                            <select name="trmovies_quality[]">
                                <option value="">'.__('Select', TRMOVIES).'</option>';

                                    $qualitys = get_categories( array(
                                        'orderby' => 'name',
                                        'hide_empty' => 0,
                                        'taxonomy' => 'quality'
                                    ) );

                                    foreach ( $qualitys as $quality ) {
                                        echo '<option '.selected( $trmovieslinks[$i]['quality'], $quality->term_id, false ).' value="'.$quality->term_id.'">'.$quality->name.'</option>';
                                    }
                        echo'
                            </select>
                        </td>
                        <td>
                            <input name="trmovies_link[]" value="'.$trmovieslinks[$i]['link'].'" type="text">
                        </td>
                        <td>
                            <input type="text" value="'.$trmovieslinks[$i]['date'].'" name="trmovies_date[]">
                        </td>
                        <td><button id="trmovies_removelink" type="button" class="BtnTrpy"><i class="dashicons dashicons-dismiss"></i>'.__('Delete', TRMOVIES).'</button></td>
                    </tr>';
                
            }
        }
        
    }
	function trmovies_img($id, $size, $title=NULL,$exclude=NULL){
        
        if(get_the_post_thumbnail($id, $size)){
            return get_the_post_thumbnail( $id, $size );                
        }elseif(get_post_meta($id, TR_MOVIES_POSTER_HOTLINK, true)){
            if($size=='img-mov-xsm'){$size='w92';}
            if($size=='img-mov-md'){$size='w185';}
            if($size=='thumbnail'){$size='w342';}
            if($size=='img-mov-sm'){$size='w92';}

            return '<img src="//image.tmdb.org/t/p/'.$size.get_post_meta($id, TR_MOVIES_POSTER_HOTLINK, true).'" alt="'.sprintf( __('Image %s', TRMOVIES), get_the_title($id)).'">';
        }elseif(get_post_meta($id, 'poster_url', true)!=''){
            if($size=='img-mov-xsm'){$size='w92';}
            if($size=='img-mov-md'){$size='w185';}
            if($size=='thumbnail'){$size='w342';}
            if($size=='img-mov-sm'){$size='w92';}

            return '<img src="' . get_post_meta($id, 'poster_url', true).'" alt="'.sprintf( __('Image %s', TRMOVIES), get_the_title($id)).'">';
        }else{
            return '<img src="'.get_template_directory_uri().'/img/cnt/noimg-'.$size.'.png" alt="'.sprintf( __('Image %s', TRMOVIES), $title).'">';                
        }
        
	}

    function trmovies_get_domain($url, $ext=TRUE) {
      $pieces = parse_url($url);
      $domain = isset($pieces['host']) ? $pieces['host'] : '';
      if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        if($ext==TRUE){
            return $regs['domain'];
        }else{
            $explode = explode('.', $regs['domain']);
            return $explode[0];
        }
      }
      return false;
    }

    function trmovies_curl ($Url) {
        global $tr_movies_options;

        /*
        if ($tr_movies_options['function_data']==1){ 
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $Url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; U; Android 2.1-update1; ru-ru; GT-I9000 Build/ECLAIR) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17');

            $output = curl_exec($ch);

            curl_close($ch);
        }else{
            
            $output = file_get_contents($Url);
            
        }*/
        
        $response = wp_remote_get( $Url );
        if ( is_array( $response ) ) {
          $header = $response['headers'];
          $output = $response['body'];
        }

        return $output;
    }

    function tr_add_custom_taxonomies() {
        
        global $tr_movies_options;
            
        register_taxonomy('server', '', array(
                'hierarchical' => true,
                'public' => true,
                'rewrite' => true,
                'query_var' => true,
                'labels' => array(
                'name' => __('Servers', TRMOVIES),
                'singular_name' => __('Server', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Servers', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => __('server', TRMOVIES),
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('language', '', array(
                'hierarchical' => true,
                'public' => true,
                'rewrite' => true,
                'query_var' => true,
                'labels' => array(
                'name' => __('Languages', TRMOVIES),
                'singular_name' => __('Language', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Languages', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => __('lang', TRMOVIES),
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('quality', '', array(
                'hierarchical' => true,
                'public' => true,
                'rewrite' => true,
                'query_var' => true,
                'labels' => array(
                'name' => __('Quality', TRMOVIES),
                'singular_name' => __('Quality', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Quality', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => __('quality', TRMOVIES),
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('country', 'post', array(
                'hierarchical' => true,
                'public' => true,
                'rewrite' => true,
                'query_var' => true,
                'labels' => array(
                'name' => __('Countries', TRMOVIES),
                'singular_name' => __('Country', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Countries', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => __('country', TRMOVIES),
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('directors', array('post'), array(
                'hierarchical' => false,
                'labels' => array(
                'name' => __('Directors', TRMOVIES),
                'singular_name' => __('Director', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Directors', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => TR_MOVIES_SLUG_DIRECTORS,
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('cast', array('post'), array(
                'hierarchical' => false,
                'labels' => array(
                'name' => __('Cast', TRMOVIES),
                'singular_name' => __('Cast', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Cast', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => TR_MOVIES_SLUG_CAST,
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('letters', '', array(
                'hierarchical' => true,
                'public' => true,
                'rewrite' => true,
                'query_var' => true,
                'labels' => array(
                'name' => __('Letters', TRMOVIES),
                'singular_name' => __('Letter', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Letters', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => $tr_movies_options['field_slug_letters'],
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('seasons', array('post'), 
            array(
                'hierarchical' => false,
                'labels' => array(
                'name' => __('Seasons', TRMOVIES),
                'singular_name' => __('Season', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Seasons', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => $tr_movies_options['field_slug_season'],
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('episodes', array('post'), array(
                'hierarchical' => false,
                'labels' => array(
                'name' => __('Episodes', TRMOVIES),
                'singular_name' => __('Episode', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Episodes', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => $tr_movies_options['field_slug_episode'],
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('directors_tv', array('post'), array(
                'hierarchical' => false,
                'labels' => array(
                'name' => __('Directors TV', TRMOVIES),
                'singular_name' => __('Director TV', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Directors TV', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => TR_MOVIES_SLUG_DIRECTORS_TV,
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        register_taxonomy('cast_tv', array('post'), array(
                'hierarchical' => false,
                'labels' => array(
                'name' => __('Cast TV', TRMOVIES),
                'singular_name' => __('Cast TV', TRMOVIES),
                'search_items' =>  __('Search', TRMOVIES),
                'all_items' => __('All', TRMOVIES),
                'parent_item' => __('Parent', TRMOVIES),
                'parent_item_colon' => __('Parent', TRMOVIES),
                'edit_item' => __('Edit', TRMOVIES),
                'update_item' => __('Update', TRMOVIES),
                'add_new_item' => __('Add', TRMOVIES),
                'new_item_name' => __('New', TRMOVIES),
                'menu_name' => __('Cast TV', TRMOVIES),
            ),
            'rewrite' => array(
                'slug' => TR_MOVIES_SLUG_CAST_TV,
                'with_front' => true,
                'hierarchical' => true
            ),
        ));
        
        if(is_admin()){
            
            // add abc
            
            $taxonomy_exist = term_exists('A', 'letters');

            if($taxonomy_exist==''){
                wp_insert_term(
                  '#',
                  'letters',
                  array(
                    'slug' => __('0-9', TRMOVIES),
                  )
                );
                for($i=65; $i<=90; $i++) {

                    $letter = strtoupper(chr($i));

                    $term_letter = term_exists($letter, 'letters');
                    if ($term_letter == null) {
                        wp_insert_term($letter, 'letters');
                    }

                }
            }
            
        }
        
    }

    add_action( 'init', 'tr_add_custom_taxonomies', 0 );

    function trmovies_add_rewrite_rules() {
        global $tr_movies_options;
        
        add_rewrite_rule($tr_movies_options['field_slug_letters'].'/([^/]+)/page/([0-9]+)?$',
            'index.php?letters=$matches[1]&trpage=$matches[2]',
            'top'
        );
    }

    add_filter('init', 'trmovies_add_rewrite_rules', 9999);

    function trmovies_insert_query_vars( $vars ){
        array_push($vars, 'trpage');
        return $vars;
    }

    add_filter( 'query_vars','trmovies_insert_query_vars' );

    add_filter( 'posts_where', function( $where, $q )
    {
        if( $name__like = $q->get( '_name__like' ) )
        {
            global $wpdb;
            
            if($name__like=='0-9'){
                $where .= $wpdb->prepare(
                    " AND {$wpdb->posts}.post_title REGEXP '%s' ",
                        $wpdb->esc_like('^[0-9|+|-]')
                );  
            }else{
                $where .= $wpdb->prepare(
                    " AND {$wpdb->posts}.post_title LIKE %s ",
                    str_replace( 
                        array( '**', '*' ), 
                        array( '*',  '%' ),  
                        mb_strtolower( $wpdb->esc_like( $name__like ).'%' ) 
                    )
                );
            }
            
        }       
        return $where;
    }, 10, 2 );

    add_action('pre_get_posts', function($qry) {

            if (is_admin()) return;

            if (is_tax('server') or is_tax('language') or is_tax('quality')){
                $qry->set_404();
            }

        }

    );

if (!function_exists('ntr_config_option')) {

    function ntr_config_option($option, $default=NULL){
        
        $option=get_theme_mod($option);
        
        if($option==''){ $return = $default; }else{ $return = $option; }
        
        return $return;
    }
    
}

if (!function_exists('ntr_config')) {
    
    function ntr_config($var, $display=NULL){
        global $trconf, $trconf_admin, $post;
                
        //if(!isset($trconf['sidebar'])){$trconf['sidebar']=;}
        
        $sidebar=ntr_config_option('tp_sidebar', 2);
                
        if(!isset($trconf['speed'])){ $trconf['speed'] = false; }
        if(!isset($trailer_field)){ $trailer_field = ''; }
        if(!isset($runtime_field)){ $runtime_field = ''; }
        if(!isset($title_field)){ $title_field = ''; }
        if(!isset($date_field)){ $date_field = ''; }
        if(!isset($date_field_year)){ $date_field_year = ''; }
        if(!isset($backdrop_field)){ $backdrop_field = ''; }
        if(!isset($link_field)){ $link_field = ''; }

        // tr movies
        
        if(isset($post)){
        
            $runtime_field = get_post_meta($post->ID, TR_MOVIES_FIELD_RUNTIME, true);
            $runtime_field = $runtime_field == '' ? '' : $runtime_field;

            $trailer_field = get_post_meta($post->ID, TR_MOVIES_FIELD_TRAILER, true);
            $trailer_field = $trailer_field == '' ? '' : $trailer_field;

            $title_field = get_post_meta($post->ID, TR_MOVIES_FIELD_ORIGINALTITLE, true);
            $title_field = $title_field == '' ? '' : $title_field;

            $date_field = get_post_meta($post->ID, TR_MOVIES_FIELD_DATE, true);
            $date_field_year = $date_field;
            if($date_field!=''){
                $date_field = explode('-', $date_field);
                $date_field_year = $date_field['0'] == '' ? '' : $date_field['0'];
                $date_field = $date_field['2'].'-'.$date_field['1'].'-'.$date_field['0'];
                $date_field = $date_field == '' ? '' : $date_field;
            }

            $backdrop_field = get_post_meta($post->ID, TR_MOVIES_FIELD_BACKDROP, true);
            $url_backdrop = wp_get_attachment_image_src($backdrop_field, 'full');
            $backdrop_field = $url_backdrop == '' ? '' : '<img class="TPostBg" src="'.$url_backdrop[0].'" alt="'.__('Background', TRMOVIES).'">';
            
            if($url_backdrop==''){
                $backdrop_field = '<img class="TPostBg" src="//image.tmdb.org/t/p/w780'.get_post_meta($post->ID, 'backdrop_hotlink', true).'" alt="'.__('Background', TRMOVIES).'">';
            }elseif($url_backdrop==''){
                $backdrop_field = '<img class="TPostBg" src="'.get_template_directory_uri().'/img/cnt/img-sld.png" alt="'.__('Background', TRMOVIES).'">';
            }

            $link_field = get_post_meta($post->ID, TR_MOVIES_FIELD_LINK, true);
            $link_field = $link_field == '' ? '' : $link_field;
            
        }
        
        $option=array(

            'speed' => $trconf['speed'],
            'sidebar' => $sidebar,
            
            // tr movies
            'field_trailer' => $trailer_field,
            'field_runtime' => $runtime_field,
            'field_title' => $title_field,
            'field_date' => $date_field,
            'field_date_year' => $date_field_year,
            'field_backdrop' => $backdrop_field,
            'field_link' => $link_field
            
        );
        
        if($display==NULL){ return $option[$var]; }else{ echo $option[$var]; }
        
    }
    
}

    function tr_movies_redirect_validate() {
        ob_start();
        
        $active = '';
        
        $active.= '<p class="ycnus">cURL '; $active.=function_exists('curl_version') ? '<i class="material-icons icon_v">check</i>' : '<i class="material-icons icon_f">clear</i></p>';
        
        $active.='<p class="ycnus">File_get_contents '; $active.= file_get_contents(__FILE__) ? '<i class="material-icons icon_v">check</i>' : '<i class="material-icons icon_f">clear</i></p>';

        /*
        <p class="chsyros">'.__('Choose the active function', TRMOVIES).'</p>
        <ul class="chsoptnsrd">
            <li><input checked type="radio" name="tr_theme_chsopt" value="1"><span> '.__('CURL', TRMOVIES).'</span></li>
            <li><input type="radio" name="tr_theme_chsopt" value="2"><span> '.__('File_get_contents', TRMOVIES).'</span></li>
        </ul>
        */
        
        die('
        
            <html>
                <title>'.__( 'License', TRMOVIES ).'</title>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <script type="text/javascript" src="'.esc_url( home_url( '/' ) ).'wp-includes/js/jquery/jquery.js"></script>
                    <script type="text/javascript" src="'.esc_url( home_url( '/' ) ).'wp-includes/js/jquery/jquery-migrate.min.js"></script>
                    <script type="text/javascript">
                    var cnArgs = {ajaxurl:"'.str_replace("/", "\/", esc_url( home_url( "/" ) )).'wp-admin\/admin-ajax.php", txt: "'.__('Check', TRMOVIES).'", fail: "'.__('Error, the license is not valid', TRMOVIES).'", nonce: "'.wp_create_nonce( 'tr-movies-activacion-nonce' ).'", loading: "'.__('Loading...', TRMOVIES).'"};
                    </script>
                    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
                    <script type="text/javascript" src="'.TR_MOVIES_PLUGIN_URL.'assets/js/tr_activation.js?ver=2.2.3"></script>
                    <style>
                    
                        .material-icons {font-family: "Material Icons";font-weight: normal;font-style: normal;display: inline-block;vertical-align:top;line-height: 1;text-transform: none;letter-spacing: normal;word-wrap: normal;white-space: nowrap;direction: ltr;-webkit-font-smoothing: antialiased;text-rendering: optimizeLegibility;-moz-osx-font-smoothing: grayscale;font-feature-settings: "liga";}
                        html{box-sizing:border-box;font-size: 100%;font-family: sans-serif;}
                        *{margin: 0;padding: 0;}
                        *,:before,:after{box-sizing:inherit}
                        :focus,:active{border: 0;outline: 0;}
                        body{font-size: 1rem;line-height: 1.2;color:#666;background: #f46b45;background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPHJhZGlhbEdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgY3g9IjUwJSIgY3k9IjUwJSIgcj0iNzUlIj4KICAgIDxzdG9wIG9mZnNldD0iMCUiIHN0b3AtY29sb3I9IiNmNDZiNDUiIHN0b3Atb3BhY2l0eT0iMSIvPgogICAgPHN0b3Agb2Zmc2V0PSIxMDAlIiBzdG9wLWNvbG9yPSIjZWVhODQ5IiBzdG9wLW9wYWNpdHk9IjEiLz4KICA8L3JhZGlhbEdyYWRpZW50PgogIDxyZWN0IHg9Ii01MCIgeT0iLTUwIiB3aWR0aD0iMTAxIiBoZWlnaHQ9IjEwMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);background: -moz-radial-gradient(center, ellipse cover,  #f46b45 0%, #eea849 100%);background: -webkit-radial-gradient(center, ellipse cover,  #f46b45 0%,#eea849 100%);background: radial-gradient(ellipse at center,  #f46b45 0%,#eea849 100%);}
                        html,body,.TT-Activation{height: 100%;}
                        .TT-Activation{display: table;width: 100%;text-align: center;}
                        .Content{display: table-cell;vertical-align: middle;padding: 15px;}
                        .Box{background-color: #fff;padding: 15px;max-width: 370px;border-radius:5px;margin-left: auto;margin-right: auto;}
                        .Logo{margin-bottom: 10px;}
                        p{margin-bottom: 10px;}
                        p:last-child{margin-bottom: 0;}
                        input[type="text"]{width: 100%;height: 40px;line-height: normal;border: 3px solid #eceff1;background-color: #fff;color: #999;border-radius: 5px;text-align: center;}
                        input[type="text"]:focus{border-color: #cfd8dc;}
                        button{width: 100%;border-radius: 5px;font-weight: 700;text-transform: uppercase;background-color: #FF8500;border: 0;color: #fff;height: 40px;line-height: 40px;cursor: pointer;}
                        button:hover{opacity: .8;}
                        .chsoptnsrd{overflow: hidden;list-style-type: none;padding: 0;margin: 0 0 1rem;}
                        .chsoptnsrd>li{float: left;width: 50%;text-align: center;position: relative;}
                        .chsoptnsrd>li input{opacity: 0;width: 100%;height: 100%;position: absolute;left: 0;top: 0;cursor:pointer}
                        .chsoptnsrd>li:first-child span{border-radius: 1rem 0 0 1rem}
                        .chsoptnsrd>li:last-child span{border-radius: 0 1rem 1rem 0}
                        .chsoptnsrd>li span{background-color: rgba(139, 195, 74, 0.28);color: #8bc34a;display: block;line-height: 2rem;font-size: .75rem;text-transform: uppercase;font-weight: 700;padding:0 1rem;}
                        .chsoptnsrd>li input:checked+span{background-color: #8bc34a;color: #fff;}
                        .ycnus{line-height:1rem;font-size:.875rem;text-align:right}
                        .ycnus i{line-height:inherit;font-size:1.25rem;width:1.5rem;}
                        .ycnus+.ycnus,.chsoptnsrd{border-bottom:1px solid #eee;padding-bottom:1rem;margin-bottom:1.5rem;}
                        .icon_v,.chsyros{color:#8bc34a}
                        .icon_f{color:#e91e63}
                        .entrylc,.chsyros{text-transform:uppercase;font-weight:700;font-size:.75rem}

                    
                    </style>
                </head>
                <body>
                    <div class="TT-Activation">
                        <div class="Content">
                            <div class="Logo"><img src="'.TR_MOVIES_PLUGIN_URL.'assets/img/torothemes-ss.png" alt="ToroThemes"></div>
                            <div class="Box">
                                <p>'.__('Enter your license', TRMOVIES ).'</p>
                                <p><input laceholder="'.__( 'Enter your license', TRMOVIES ).'" type="text" name="tr_movies_activation_text"></p>
                                <p><button id="tr_movies_activation_bt" name="tr_movies_activation_bt" type="submit">'.__('Check', TRMOVIES).'</button></p>
                                <div id="tr_movies_activation"></div>
                            </div>
                        </div>
                    </div>
                </body>
            </html>
        
        ');
        exit;
    }

    add_action( 'plugins_loaded', 'trmovies_init' );

    function trmovies_init()
    {
        if (is_super_admin() and !isset($_POST['nonce']) and get_option('trmovieslicense')=='1234567890' and empty($_POST['action'])) { add_action('init', 'tr_movies_redirect_validate'); }

    }

    function tr_movies_activation_ajax() {

        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce( $nonce, 'tr-movies-activacion-nonce' ) )
            die ( 'Die');


        if($_POST['action'] == 'tr_movies_activation_action') {
                        
            $check = trmovies_curl("http://toroflix.ml/?trapi=2&trproduct=Tr-movies&trserial=".$_POST['txt'].'&trlang='.get_bloginfo("language").'&trdomain='.esc_url( home_url( '/' ) ) );
            
            if($check==1){
                delete_option("trmovieslicense");
                add_option( "trmovieslicense", esc_sql($_POST["txt"]), "", "yes" );
                echo 1;
            }else{
                delete_option("trmovieslicense");
                echo 0;
            }
            
        }
        exit;
    }
	
	add_action( 'wp_ajax_tr_movies_activation_action', 'tr_movies_activation_ajax' );
	add_action( 'wp_ajax_nopriv_tr_movies_activation_action', 'tr_movies_activation_ajax');

    add_action('admin_notices', 'tr_movies_admin_notices');

    function tr_movies_admin_notices() {

        global $_wp_additional_image_sizes, $tr_movies_options;
                
        $width = get_option( 'thumbnail_size_w' );
        $height = get_option( 'thumbnail_size_h' );
        
        if(!empty($_wp_additional_image_sizes['post-thumbnail'])){
            $width_theme = $_wp_additional_image_sizes['post-thumbnail']['width'];
            $height_theme = $_wp_additional_image_sizes['post-thumbnail']['height'];
        }else{
            $width_theme = 0; $height_theme = 0;
        }
        
        if($width!=$width_theme and $tr_movies_options['upload_images']==1 or $height!=$height_theme and $tr_movies_options['upload_images']==1){
            
            echo '<div class="error tr_error_thumb"><p>'.sprintf( __('Please, %sbefore proceeding%s, set the thumbnail size correctly, %sclick here%s. (%sx%s)', TRMOVIES), '<strong>', '</strong>', '<a href="options-media.php">', '</a>', $width_theme, $height_theme).'</p></div>';
        }
        
    }

    add_action( 'wp_ajax_trmovieslinks', 'trmovieslinks_callback' );
    add_action('wp_ajax_nopriv_trmovieslinks', 'trmovieslinks_callback');

    function trmovieslinks_callback() {
        
		$nonce = $_POST['nonce'];
 
	    if ( ! wp_verify_nonce( $nonce, 'tr-movieslinks-nonce' ) )
        
        die ( 'Die!');

        if($_POST['action'] == 'trmovieslinks') {
            
        if($_POST['type']==1){

            $array_links = array('');
            
            if(isset($_POST['trmovies'])){
                parse_str($_POST['trmovies'], $arraylinks);
                foreach ($arraylinks as $key => $value){
                    $explode = explode('|', $key);
                    
                    $key = $explode[1];
                    $array_links[$key]['type'] = serialize($value['type']);

                    $array_links[$key]['server'] = serialize($value['server']);

                    $array_links[$key]['lang'] = serialize($value['lang']);

                    $array_links[$key]['quality'] = serialize($value['quality']);

                    $array_links[$key]['link'] = serialize( array_map("htmlspecialchars", $value['link']) );

                    $array_links[$key]['date'] = serialize($value['date']);
                    if(isset($array_links[$key])){

                        update_term_meta($key, TR_MOVIES_FIELD_LINK, serialize( $array_links[$key] ) );

                    }

                }
            }
            //echo $value['link'];
            echo __('Save Links', TRMOVIES);
            
        }else{          

            $episodes_list = wp_get_post_terms(intval($_POST['id']), 'episodes', array('orderby' => 'meta_value_num', 'order' => 'ASC', 'fields' => 'all', 'meta_query' => [[
                'key' => 'episode_number',
                'type' => 'NUMERIC',
            ]],) );

            $iseason=intval($_POST['season']);

            $servers = get_categories( array(
                'orderby' => 'name',
                'hide_empty' => 0,
                'taxonomy' => 'server'
            ) );

            $languages = get_categories( array(
                'orderby' => 'name',
                'hide_empty' => 0,
                'taxonomy' => 'language'
            ) );

            $qualitys = get_categories( array(
                'orderby' => 'name',
                'hide_empty' => 0,
                'taxonomy' => 'quality'
            ) );

            $ar_episode_create[] = '';

            foreach ($episodes_list as &$array_create_episode) {
                preg_match('/'.sanitize_title(get_the_title(intval($_POST['id']))).'-(\d+)[x{1}]/', $array_create_episode->slug, $matches);
                $ar_episode_create[] = $matches[1];
                $ar_episode_create[] = get_term_meta($array_create_episode->term_id, 'season_number', true);
            }
            
            $lst_seasons_episodes = array_unique($ar_episode_create);

            if(!in_array($iseason, $lst_seasons_episodes)){ 
                echo '<p>'.__('You must add the episodes of this season before.', TRMOVIES).'</p>';
            }


            $iepisodes_list = 1;
            foreach ($episodes_list as &$value_episode) {
                if(get_term_meta($value_episode->term_id, 'season_number', true)==intval($_POST['season'])){
                $links[$value_episode->term_id]=unserialize(get_term_meta($value_episode->term_id, TR_MOVIES_FIELD_LINK, true));              
                if($iepisodes_list==1){ $episode_on=' on'; }else{ $episode_on=''; }
                    echo'
                    <div class="AACrdn">
                    <div class="Title AALink'.$episode_on.'">Episode <span>'.$iepisodes_list.'</span> <i class="dashicons dashicons-arrow-down-alt2"></i></div>
                    <div class="AAcont">
                    <button type="button" class="Btnmore tr_link_add" data-class="tr-series-row-'.$iseason.'-'.$iepisodes_list.'"><i class="dashicons dashicons-plus-alt"></i> '.__('Add link', TRMOVIES).'</button>
                    <div class="ToroPlay-tblcn">
                    <table class="ToroPlay-tbl">
                    <thead>
                    <tr>
                    <th>'.__('TYPE', TRMOVIES).'</th>
                    <th>'.__('SERVER', TRMOVIES).'</th>
                    <th>'.__('LANGUAGE', TRMOVIES).'</th>
                    <th>'.__('QUALITY', TRMOVIES).'</th>
                    <th>'.__('LINK', TRMOVIES).'</th>
                    <th>'.__('DATE', TRMOVIES).'</th>
                    <th colspan="2"></th>
                    </tr>
                    </thead>
                    <tbody>';

            if(count(unserialize($links[$value_episode->term_id]['type']))>0){

            for($ilnk = 0; $ilnk < count(unserialize($links[$value_episode->term_id]['type'])); ++$ilnk) {

                echo'
                <tr class="tr-series-row-'.$iseason.'-'.$iepisodes_list.'">
                <td>
                    <select class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[type][]">
                        <option value="">'.__('Select', TRMOVIES).'</option>
                        <option '.selected( unserialize($links[$value_episode->term_id]['type'])[$ilnk], 1, false ).' value="1">'.__('Online', TRMOVIES).'</option>
                        <option '.selected( unserialize($links[$value_episode->term_id]['type'])[$ilnk], 2, false ).' value="2">'.__('Download', TRMOVIES).'</option>
                    </select>
                </td>
                <td>
                    <select class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[server][]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';
                        foreach ( $servers as $server ) {
                            echo '<option '.selected( unserialize($links[$value_episode->term_id]['server'])[$ilnk], $server->term_id, false ).' value="'.$server->term_id.'">'.$server->name.'</option>';
                        }
                echo'
                    </select>
                </td>
                <td>
                    <select class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[lang][]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';
                    foreach ( $languages as $lang ) {
                        echo '<option '.selected( unserialize($links[$value_episode->term_id]['lang'])[$ilnk], $lang->term_id, false ).' value="'.$lang->term_id.'">'.$lang->name.'</option>';
                    }
                echo'
                    </select>
                </td>
                <td>
                    <select class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[quality][]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';

                    foreach ( $qualitys as $quality ) {
                        echo '<option '.selected( unserialize($links[$value_episode->term_id]['quality'])[$ilnk], $quality->term_id, false ).' value="'.$quality->term_id.'">'.$quality->name.'</option>';
                    }
                echo'
                    </select>
                </td>
                <td><input class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[link][]" type="text" value="'.unserialize($links[$value_episode->term_id]['link'])[$ilnk].'"></td>
                <td><input class="trmoviesget" type="text" name="trmovies|'.$value_episode->term_id.'[date][]" value="'.unserialize($links[$value_episode->term_id]['date'])[$ilnk].'"></td>
                <td><button data-class="tr-series-row-'.$iseason.'-'.$iepisodes_list.'" type="button" class="BtnTrpy tr_link_remove"><i class="dashicons dashicons-dismiss"></i>'.__('Delete', TRMOVIES).'</button></td>
                <td>
                    <a href="#" class="BtnTrpy move-up tr-move-up"><i class="dashicons dashicons-arrow-up"></i>'.__('Up', TRMOVIES).'</a>
                    <a href="#" class="BtnTrpy move-down tr-move-down"><i class="dashicons dashicons-arrow-down"></i>'.__('Down', TRMOVIES).'</a>
                </td>
                </tr>';

            }

            }else{

                echo'
                <tr class="tr-series-row-'.$iseason.'-'.$iepisodes_list.'">
                <td>
                    <select class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[type][]">
                        <option value="">'.__('Select', TRMOVIES).'</option>
                        <option value="1">'.__('Online', TRMOVIES).'</option>
                        <option value="2">'.__('Download', TRMOVIES).'</option>
                    </select>
                </td>
                <td>
                    <select class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[server][]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';

                        foreach ( $servers as $server ) {
                            echo '<option '.selected( $trmovieslinks[$i]['server'], $server->term_id, false ).' value="'.$server->term_id.'">'.$server->name.'</option>';
                        }
                echo'
                    </select>
                </td>
                <td>
                    <select class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[lang][]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';

                    foreach ( $languages as $lang ) {
                        echo '<option '.selected( $trmovieslinks[$i]['lang'], $lang->term_id, false ).' value="'.$lang->term_id.'">'.$lang->name.'</option>';
                    }
                echo'
                    </select>
                </td>
                <td>
                    <select class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[quality][]">
                        <option value="">'.__('Select', TRMOVIES).'</option>';

                    foreach ( $qualitys as $quality ) {
                        echo '<option '.selected( $trmovieslinks[$i]['quality'], $quality->term_id, false ).' value="'.$quality->term_id.'">'.$quality->name.'</option>';
                    }
                echo'
                    </select>
                </td>
                <td><input class="trmoviesget" name="trmovies|'.$value_episode->term_id.'[link][]" type="text"></td>
                <td><input class="trmoviesget" type="text" name="trmovies|'.$value_episode->term_id.'[date][]"></td>
                <td><button data-class="tr-series-row-'.$iseason.'-'.$iepisodes_list.'" type="button" class="BtnTrpy tr_link_remove"><i class="dashicons dashicons-dismiss"></i>'.__('Delete', TRMOVIES).'</button></td>
                <td>
                    <a href="#" class="BtnTrpy move-up tr-move-up"><i class="dashicons dashicons-arrow-up"></i>'.__('Up', TRMOVIES).'</a>
                    <a href="#" class="BtnTrpy move-down tr-move-down"><i class="dashicons dashicons-arrow-down"></i>'.__('Down', TRMOVIES).'</a>
                </td>
                </tr>';

            }
                echo'
                </tbody>
                </table>
                </div>
                </div>
                </div>';

            $iepisodes_list++;
            }
            }
            
        }
            
        }
        exit;
    }

    if(TR_MOVIES_PREFIX_POSTS==1){
        // Permalinks
        add_filter('post_link', 'tr_movies_permalink', 1, 3);
        add_filter('post_type_link', 'tr_movies_permalink', 1, 3);

        function tr_movies_permalink($permalink, $post_id, $leavename) {
            global $tr_movies_options;

            $type = get_post_meta($post_id->ID, 'tr_post_type', true);
            $type_slug = $type == '' ? $tr_movies_options['prefix_movies_permalink'] : $tr_movies_options['prefix_series_permalink'];
            return str_replace('%tr_post_type%', $type_slug, $permalink);
        }

        function tr_movies_add_rewrite_rules() {
            global $tr_movies_options;

            add_rewrite_rule($tr_movies_options['prefix_series_permalink'].'/([^/]+)/?$',
            'index.php?name=$matches[1]&tr_post_type=2',
            'top');

            add_rewrite_rule($tr_movies_options['prefix_movies_permalink'].'/([^/]+)/?$',
            'index.php?name=$matches[1]&tr_post_type=0',
            'top');
        }
        add_filter('init', 'tr_movies_add_rewrite_rules', 9999);

        function tr_movies_redirect_canonical() {

            if(is_single()){
                $postid = get_queried_object_id();
                $type = get_post_meta($postid, 'tr_post_type', true);
                if($type==''){ $type=0; }
                if($type!=get_query_var('tr_post_type') and get_query_var('tr_post_type')!=''){

                    exit( wp_redirect( get_permalink($postid) ) );

                }

            }

        }

        add_action( 'template_redirect', 'tr_movies_redirect_canonical' );

        function tr_movies_query_vars( $vars ){
            array_push($vars, 'tr_post_type');
            return $vars;
        }
        add_filter( 'query_vars','tr_movies_query_vars' );

        // permalinks
        
    }

    function tr_movies_custom_links_bar($wp_admin_bar) {
        if(is_single()){
            global $post;
            $type = get_post_meta($post->ID, 'tr_post_type', true);

            if($type==2){ $href=get_option('home').'/wp-admin/admin.php?page=tr-movies-tv&action=edit&id='.$post->ID; }else{ $href=get_option('home').'/wp-admin/admin.php?page=tr-movies-movie&action=edit&id='.$post->ID; }

            $args = array(
                'id' => 'trmoviesedit',
                'title' => __('Edit', TRMOVIES), 
                'href' => $href, 
                'meta' => array(
                    'class' => 'trmoviesedit', 
                    'title' => __('Edit', TRMOVIES)
                )
            );
            $wp_admin_bar->add_node($args);
        }
    }
    add_action('admin_bar_menu', 'tr_movies_custom_links_bar', 999);

    function tr_movies_remove_link_bar() {
        global $wp_admin_bar;

        $wp_admin_bar->remove_menu('edit');
    }
    add_action( 'wp_before_admin_bar_render', 'tr_movies_remove_link_bar' );

    add_filter( 'pre_get_document_title', 'tr_filter_search_title', 9999 );
    add_filter( 'wp_title', 'tr_filter_search_title', 9999, 3 );

    function tr_filter_search_title( $title ) {
        if(get_query_var('trfilter')==''){
            return $title;
        }else{
            return __('Advance Search', TRMOVIES);
        }
    }

    function trmovies_base64en($string){
        return base64_encode($string);
    }

    function lav_minutes_to_hours_mins( $timeinminutes ){
    // added by Lava
        if( is_numeric($timeinminutes) ){
            $hours = ltrim(gmdate("i", $timeinminutes), 0);
            $minutes = ltrim(gmdate("s", $timeinminutes), 0);

            $hours = empty($hours) ? 0 : $hours;
            $minutes = empty($minutes) ? 0 : $minutes;

            $timeinminutes = $hours.'h '.$minutes.'m';
        }

        return ($timeinminutes);
    }

?>