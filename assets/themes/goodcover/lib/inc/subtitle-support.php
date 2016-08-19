<?php
/* Subtitle Support */
if(!class_exists('WPAlchemy_MetaBox')){
    include_once WP_CONTENT_DIR.'/wpalchemy/MetaBox.php';
}
add_action('init','add_custom_metaboxes');
add_action('admin_footer','subtitle_footer_hook');
add_action( 'admin_print_scripts', 'my_metabox_styles' );
add_action( 'msdlab_banner_content', 'msdlab_do_post_subtitle' );
add_action( 'msdlab_banner_content', 'msdlab_do_post_intro' );

            //remove_action('genesis_entry_header','genesis_do_post_title');
add_action('genesis_after_header','msdlab_banner_content');


function add_custom_metaboxes(){
    global $subtitle_metabox,$footer_metabox;
    $subtitle_metabox = new WPAlchemy_MetaBox(array
    (
        'id' => '_subtitle',
        'title' => 'Subtitle & Intro Text',
        'types' => array('page'),
        'context' => 'normal', // same as above, defaults to "normal"
        'priority' => 'high', // same as above, defaults to "high"
        'template' => get_stylesheet_directory() . '/lib/template/subtitle-meta.php',
        'autosave' => TRUE,
        'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
        'prefix' => '_msdlab_' // defaults to NULL
    ));
    /*
    $footer_metabox = new WPAlchemy_MetaBox(array
    (
        'id' => '_footer',
        'title' => 'Footer Text',
        'types' => array('page'),
        'context' => 'normal', // same as above, defaults to "normal"
        'priority' => 'high', // same as above, defaults to "high"
        'template' => get_stylesheet_directory() . '/lib/template/footer-meta.php',
        'autosave' => TRUE,
        'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
        'prefix' => '_msdlab_' // defaults to NULL
    ));
     * 
     */
}

function subtitle_footer_hook()
{
    ?><script type="text/javascript">
        jQuery('#titlediv').after(jQuery('#_subtitle_metabox'));
        jQuery('#postdivrich').after(jQuery('#_footer_metabox'));
    </script><?php
}

// include css to help style our custom meta boxes
 
function my_metabox_styles()
{
    if ( is_admin() )
    {
        wp_enqueue_style('wpalchemy-metabox', get_stylesheet_directory_uri() . '/lib/template/meta.css');
    }
}

function msdlab_do_post_subtitle() {
    global $subtitle_metabox,$post;
    $subtitle_metabox->the_meta();
    $subtitle = $subtitle_metabox->get_the_value('subtitle');
    if ( strlen( $subtitle ) == 0 ){
        $parent_id = get_topmost_parent($post->ID);
        $subtitle_metabox->the_meta($parent_id);
        $subtitle = $subtitle_metabox->get_the_value('subtitle');
    }

    if ( strlen( $subtitle ) == 0 )
        return;

    $subtitle = sprintf( '<h2 class="entry-subtitle">%s</h2>', apply_filters( 'genesis_post_title_text', $subtitle ) );
    echo apply_filters( 'genesis_post_title_output', $subtitle ) . "\n";

}

function msdlab_do_post_intro() {
    global $subtitle_metabox,$post;
    $subtitle_metabox->the_meta();
    $intro = $subtitle_metabox->get_the_value('intro');
    if ( strlen( $intro ) == 0 ){
        $parent_id = get_topmost_parent($post->ID);
        $subtitle_metabox->the_meta($parent_id);
        $intro = $subtitle_metabox->get_the_value('intro');
    }

    if ( strlen( $intro ) == 0 )
        return;

    $intro = sprintf( '<div class="intro-text">%s</div>', apply_filters( 'the_content', $intro ) );
    echo  $intro . "\n";

}

function msdlab_do_post_footer() {
    global $footer_metabox;
    $footer_metabox->the_meta();
    $footer = $footer_metabox->get_the_value('footer');

    if ( strlen( $footer ) == 0 )
        return;

    $footer = sprintf( '<div class="footer-text">%s</div>', apply_filters( 'the_content', $footer ) );
    echo  $footer . "\n";

}

function msdlab_banner_content(){
    global $post;
    if(is_front_page()){
        return false;
    } elseif(is_page()){
        global $post;
        $myid = $post->ID;
        $lvl = 2;
        if(get_section_title()!=$post->post_title){
            //add_action('genesis_entry_header','genesis_do_post_title',5);
        }
        $background = strlen(msdlab_get_thumbnail_url($myid,'full'))>0?' style="background-image:url('.msdlab_get_thumbnail_url($myid,'full').')"':'';
        //no featured image? check the parent!
        if ( strlen( $background ) == 0 ){
            $parent_id = get_topmost_parent($post->ID);
            $background = strlen(msdlab_get_thumbnail_url($parent_id,'full'))>0?' style="background-image:url('.msdlab_get_thumbnail_url($parent_id,'full').')"':'';
        }
        
        print '<div class="banner clearfix"'.$background.'>';
        print '<div class="texturize">';
        print '<div class="gradient">';
        print '<div class="wrap">';
        print '<h'.$lvl.' class="section-title">';
        print get_section_title();
        print '</h'.$lvl.'>';
        do_action('msdlab_banner_content');
        print '</div>';
        print '</div>';
        print '</div>';
        print '</div>';
    }
}
