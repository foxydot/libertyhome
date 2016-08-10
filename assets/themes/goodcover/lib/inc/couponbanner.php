<?php
add_action('genesis_after_header','msdlab_show_coupon_banner',12);

function msdlab_show_coupon_banner(){
    if(is_front_page()){
        return false;
    }
    $fp = get_option('page_on_front');
    $template = get_post_meta($fp,'_wp_page_template',TRUE);
    if($template == 'page-sectioned.php'){
        global $msd_custom_pages,$sectioned_page_metabox;
        $i = 0;
        $meta = $sectioned_page_metabox->the_meta($fp);
        if(is_object($sectioned_page_metabox)){
        while($sectioned_page_metabox->have_fields('sections')){
            $layout = $sectioned_page_metabox->get_the_value('layout');
            $name = $sectioned_page_metabox->get_the_value('section-name');
            if($name != 'coupon'){continue;}
            switch($layout){
                case "four-col":
                    $sections[] = MSDSectionedPage::column_output($meta['sections'][$i],$i);
                    break;
                case "three-col":
                    $sections[] = MSDSectionedPage::column_output($meta['sections'][$i],$i);
                    break;
                case "two-col":
                    $sections[] = MSDSectionedPage::column_output($meta['sections'][$i],$i);
                    break;
                default:
                    $sections[] = MSDSectionedPage::default_output($meta['sections'][$i],$i);
                    break;
            }
            $i++;
        }//close while
        print '<div class="sectioned-page-wrapper">';
        print implode("\n",$sections);
        print '</div>';
        }//clsoe if
    }
}
