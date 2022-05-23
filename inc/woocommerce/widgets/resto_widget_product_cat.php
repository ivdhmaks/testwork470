<?php

// Register widget 'KMSTD_Widget_ProductCategories'
function resto_widget_product_categories_init() {
    register_widget( 'Resto_Widget_ProductCategories' );
}
add_action( 'widgets_init', 'resto_widget_product_categories_init' );

/**
 * Resto Product categories widget class.
 *
 * @extends WP_Widget
 */
class Resto_Widget_ProductCategories extends WP_Widget {

        /**
	 * Current Category.
	 *
	 * @var bool
	 */
	public $current_cat_id;
    
        /**
	 * Parent Category.
	 *
	 * @var bool
	 */
	public $parent_cat_id;
        
        /**
	 * Category ancestors.
	 *
	 * @var array
	 */
	public $cat_ancestors;
        
        /**
         *  List of all product categories
         * 
         * @var array 
         */
        public $categories;
        
        /**
         * Setup a new widget instance.
         */
	public function __construct() {
            $widget_ops = array(
                'classname'                   => 'resto_widget_product_cat',
                'description'                 => 'A list of Woocommerce product categories for Resto theme.',
                'customize_selective_refresh' => true,
            );
            parent::__construct( 'resto_product_cat_widget', 'Resto Product Categories', $widget_ops );
	}

	/**
	 * Outputs the content for the current Categories widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Categories widget instance.
	 */
	public function widget( $args, $instance ) {

            global $wp_query;

            $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Categories';

            $this->categories = array();

            $this->current_cat_id = false;
            $this->parent_cat_id = false;
            $this->cat_ancestors = array();
            if ( is_tax( 'product_cat' ) ) {
                $current_term = $wp_query->queried_object;
                $this->current_cat_id   = $current_term->term_id;
                $this->cat_ancestors = get_ancestors( $this->current_cat_id, 'product_cat' );
                if( $current_term->parent > 0 ){
                    $this->parent_cat_id = $current_term->parent;
                }
            } 

            $cat_args = array(
                'taxonomy'      => 'product_cat',
                'hide_empty'    => true, // don't show empty categories
                //'hide_empty'    => false, // show empty categories
                'hierarchical'  => true, // show hierarchy
                // using sort ordering, specified by Drag'n'Drop in Admin menu for Product Category taxonomy
                'orderby'       => 'meta_value_num',
                'meta_key'      => 'order',
                'order'         => 'ASC',
            );
            $categories = get_terms( $cat_args );

            // convert WP_Term object to array
            foreach( $categories as $cat_term ){
                $this->categories[] = (array)$cat_term;
            }

            // build tree of categories
            $cat_tree = $this->build_tree($this->categories, 0);

            //echo $args['before_widget'];
            echo '<div class="menu sidebar__menu resto-product_cat-widget">';

            if ( $title ) {
                echo '<div class="menu__title">' . $title . '</div>';
            }

            echo '<ul class="cat-menu__list category-widget-menu">';
            
            foreach( $cat_tree as $key => $cat ){
                $active = ($this->is_active_category($cat['term_id'])) ? 'active open' : '';
                $has_submenu = ( !empty($cat['childs']) ) ? 'has_submenu' : '';
                ?>
                <li class="cat-menu__item <?php echo $active; ?> <?php echo $has_submenu; ?>">
                    <a href="<?php echo get_term_link( $cat['term_id'], 'product_cat' ); ?>" class="menu__link"><?php echo $cat['name']; ?></a>
                    <?php if( !empty($cat['childs']) ) { ?>
                        <span class="submenu_toggle"></span>
                        <ul class="cat-submenu" >
                        <?php 
                        foreach( $cat['childs'] as $child ) { 
                            $active_sub_menu = ($this->is_active_category($child['term_id'])) ? 'active' : '';
                        ?>
                        <li class="cat-submenu__item <?php echo $active_sub_menu; ?>">
                            <a href="<?php echo get_term_link( $child['term_id'], 'product_cat' ); ?>" class="menu__link"><?php echo $child['name']; ?></a>
                        </li>
                        <?php } ?>
                        </ul>
                    <?php } ?>
                </li>
                <?php
            }
            echo '</ul>';

            //echo $args['after_widget'];
            echo '</div>';
	}

	/**
	 * Handles updating settings for the current Categories widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
            $instance                 = $old_instance;
            $instance['title']        = sanitize_text_field( $new_instance['title'] );

            return $instance;
	}

	/**
	 * Outputs the settings form for the Categories widget.
         * 
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
            // Defaults.
            //$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
            $title = !empty( $instance['title'] ) ? $instance['title'] : '';
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <?php
	}

        /**
         * Determine if category is active
         * Helper method
         * 
         * @param int $cat_id
         * @return boolean
         */
        private function is_active_category($cat_id){
            // if( $cat_id == $this->current_cat_id || in_array($cat_id, $this->cat_ancestors) ){
            if( $cat_id == $this->current_cat_id || $cat_id == $this->parent_cat_id ){
                return true;
            }
            return false;
        }
        
        /**
         * Build category tree
         * Convert plain array to array tree
         * Helper method
         * 
         * @param array $elements
         * @param int $parent_id
         * @return array
         */
        private function build_tree($elements, $parent_id = 0){
            $branch = array();
 
            foreach ($elements as $element) {
                if ($element['parent'] == $parent_id) {
                    // childs
                    $childs = $this->build_tree($elements, $element['term_id']);
                    if ($childs) {
                        $element['childs'] = $childs;
                    }
                    $branch[] = $element;
                }
            }

            return $branch;
        }
        
}
