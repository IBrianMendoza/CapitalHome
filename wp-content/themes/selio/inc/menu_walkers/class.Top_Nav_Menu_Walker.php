<?php

/**
 * Create a nav menu with very top navigation markup.
 */

class Top_Nav_Menu_Walker extends Walker_Nav_Menu
{
	/**
	 * Start the element output.
	 *
	 * @param  string $output Passed by reference. Used to append additional content.
	 * @param  object $item   Menu item data object.
	 * @param  int $depth     Depth of menu item. May be used for padding.
	 * @param  array $args    Additional strings.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
	{

        $active='';
        if($item->current == true)
        {
            $active = 'active';
        }
        
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        if(strpos($class_names,'current-menu-ancestor') !==FALSE){
            $active = 'active';
        }

	    if($depth == 0)
		  $output     .= '<li class="nav-item dropdown '.$active.'">';
		else
		  $output     .= '<li class="'.$active.'">';

		$attributes  = '';
        
		! empty ( $item->attr_title )
			// Avoid redundant titles
			and $item->attr_title !== $item->title
			and $attributes .= ' title="' . esc_attr( $item->attr_title ) .'"';

		! empty ( $item->url )
			and $attributes .= ' href="' . esc_attr( $item->url ) .'"';

        
        if($depth == 0)
        {
            if(isset($args->walker->has_children) && $args->walker->has_children)
            {
                $attributes.=' class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" ';
            }
            else
            {
                $attributes.=' class="nav-link" href="#" role="button" ';
            }
        }
        else
            $attributes.=' class="dropdown-item" ';

		$attributes  = trim( $attributes );
		$title       = apply_filters( 'the_title', $item->title, $item->ID );
        
        if(isset($args->walker->has_children) && $args->walker->has_children)
            $title.='<span class="caret"></span>';
        
        $item_output = "<a $attributes>$title</a>";
        
        if(is_object($args))
		$item_output = "$args->before<a $attributes>$args->link_before$title</a>"
						. "$args->link_after$args->after";

		// Since $output is called by reference we don't need to return anything.
		$output .= apply_filters(
			'walker_nav_menu_start_el'
			,   $item_output
			,   $item
			,   $depth
			,   $args
		);
	}

	/**
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = Array() )
	{
		$output .= '<ul class="dropdown-menu animated">';
	}

	/**
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = Array() )
	{
		$output .= '</ul>';
	}

	/**
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return void
	 */
	function end_el( &$output, $item, $depth = 0, $args = Array() )
	{
	   //if($depth == 0)
		$output .= '</li>';
	}
}