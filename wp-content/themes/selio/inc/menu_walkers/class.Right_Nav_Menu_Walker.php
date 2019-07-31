<?php

/**
 * Create a nav menu with right navigation markup.
 */

class Right_Nav_Menu_Walker extends Walker_Nav_Menu
{
    
    private $latest_item = array(); 
    
	/**
	 * Start the element output.
	 *
	 * @param  string $output Passed by reference. Used to append additional content.
	 * @param  object $item   Menu item data object.
	 * @param  int $depth     Depth of menu item. May be used for padding.
	 * @param  array $args    Additional strings.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 )
	{
	    if($depth == 0)
		  $output     .= '';

		$attributes  = '';
        
        $this->latest_item[$depth] = $item->ID;
        
        
        //var_selio_dump($args);
        //echo "\n\n";
        
		! empty ( $item->attr_title )
			// Avoid redundant titles
			and $item->attr_title !== $item->title
			and $attributes .= ' title="' . esc_attr( $item->attr_title ) .'"';

		! empty ( $item->url ) && !(isset($args->walker->has_children) && $args->walker->has_children)
			and $attributes .= ' href="' . esc_attr( $item->url ) .'"';
        
        
        $active='';
        if($item->current == true)
        {
            $active = 'active';
        }
    
        if($depth == 0)
        {
            if(isset($args->walker->has_children) && $args->walker->has_children)
            {
                $attributes.=' class="list-group-item list-group-item-success collapsed '.$active.'" data-toggle="collapse" href="#children-'.$item->ID.'" role="button" aria-haspopup="true" aria-expanded="false" ';
            }
            else
            {
                $attributes.=' class="list-group-item list-group-item-success '.$active.'" role="button" aria-haspopup="true" aria-expanded="false" ';
            }
        }
        else
        {
            $attributes.=' class="list-group-item '.$active.'" ';
        }

		$attributes  = trim( $attributes );
		$title       = apply_filters( 'the_title', $item->title, $item->ID );
        
        if(isset($args->walker->has_children) && $args->walker->has_children)
            $title.='<i class="icon-dropdown"></i>';
        
        if(is_object($args))
        {
    		$item_output = "$args->before<a $attributes>$args->link_before$title</a>"
    						. "$args->link_after$args->after";
        }
        else
        {
    		$item_output = "<a $attributes>$title</a>";
        }

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
	   if($depth == 0)
		$output .= '<div class="collapse" aria-expanded="false" id="children-'.$this->latest_item[$depth].'">';
	}

	/**
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = Array() )
	{
	   if($depth == 0)
		$output .= '</div>';
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
	//	$output .= '</li>';
	}
}