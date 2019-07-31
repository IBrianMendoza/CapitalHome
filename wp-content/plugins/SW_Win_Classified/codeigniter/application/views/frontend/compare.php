
<table class="table table-striped table-hover">

<tr>
    <td></td>

    <?php foreach($listings as $listing): ?>
    <td>
        <a href="<?php echo listing_url($listing); ?>"><img src="<?php echo _show_img($listing->image_filename, '520x330', false); ?>" alt="" class="" /></a>
    </td>
    <?php endforeach; ?>

</tr>

<?php foreach($fields as $field):

if(!in_array($field->type, array('INTEGER', 'INPUTBOX', 'CHECKBOX', 'DROPDOWN')))continue; 
?>
<tr>
    <td><?php echo $field->field_name; ?></td>

    <?php foreach($listings as $listing): ?>
    
    <?php 
        $json_obj = json_decode($listing->json_object);

        if(isset($json_obj->{'field_'.$field->idfield}))
        {
            $value = $json_obj->{'field_'.$field->idfield};
            
            if($field->type == 'CHECKBOX')
            {
                if($value == 1)
                {
                    $value = '<i class="fa fa-check" aria-hidden="true"></i>';
                }
                else
                {
                    $value = '<i class="fa fa-close" aria-hidden="true"></i>';
                }
            }
            
            $value = character_limiter($value, 20);
            
            echo '<td>'.$field->prefix.' '.$value.' '.$field->suffix.'</td>';
        }
        else
        {
            echo '<td>-</td>';
        }
    ?>

    <?php endforeach; ?>

</tr>
<?php endforeach; ?>
</table>