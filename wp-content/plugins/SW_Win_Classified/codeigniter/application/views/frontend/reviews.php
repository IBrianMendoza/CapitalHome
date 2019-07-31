<br style="clear:both;" />

<h4 id="form_review"><?php echo __('Reviews', 'sw_win'); ?></h4>

<div class="reviews-container">

<?php if(sw_is_logged_user() && $already_reviewed): ?>

<p class="alert alert-info">
    <?php echo __('Thanks on review', 'sw_win'); ?>
</p>

<?php elseif(sw_is_logged_user()): ?>
    <?php _form_messages(__('Saved successfully', 'sw_win'), NULL, 'review'); ?>

    <form class="form-horizontal no-padding " method="post" action="#form_review">
        <div class="control-group">
        <label class="" for="inputRating"><?php echo __('Rating', 'sw_win'); ?></label>
        <div class="controls">
            <input type="number" data-max="5" data-min="1" name="stars" id="stars" class="rating form-control INPUTBOX" data-empty-value="5" value="5" data-active-icon="glyphicon-star orange" data-inactive-icon="glyphicon-star-empty orange" />
        </div>
        </div>
        <div class="control-group">
            <label class="" for="inputMessageR"><?php echo __('Message', 'sw_win'); ?></label>
            <div class="controls">
                <textarea id="inputMessageR" class="form-control TEXTAREA" rows="3" name="message" rows="3" placeholder="<?php echo __('Message', 'sw_win'); ?>"></textarea>
            </div>
        </div>
        <input class="hidden" id="widget_id" name="widget_id" type="text" value="review" />
        <br style="clear: both;" />
        <div class="control-group" >
            <div class="controls">
                <button type="submit" class="btn"><?php echo __('Send', 'sw_win'); ?></button>
            </div>
        </div>
        <br style="clear: both;" />
    </form>

<?php else: ?>

    <p class="alert alert-success">
        <?php echo __('Login to review', 'sw_win'); ?>, <a href="<?php echo get_permalink(sw_settings('register_page')); ?>"><?php echo __('Open login page', 'sw_win'); ?></a>
    </p>

<?php endif; ?>


<?php if(sw_count($reviews_all) > 0): ?>
<ul class="media-list clearfix">
    <?php foreach($reviews_all as $review_data): ?>
    <?php //dump($review_data); ?>
    <li class="media clearfix">
        <div class="pull-left">
            <img src="<?php echo get_gravatar($review_data->user_email, 75); ?>" alt="" class="image" />
        </div>
        <div class="media-body">
            <h4 class="media-heading"><div class="review_stars_<?php echo $review_data->stars; ?>"> </div></h4>
            <?php if($review_data->is_visible): ?>
                <?php echo $review_data->message; ?>
            <?php else: ?>
                <?php echo __('Hidden by admin', 'sw_win'); ?>
            <?php endif; ?>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="alert alert-success">
    <?php echo __('No reviews available', 'sw_win'); ?>
</p>
<?php endif; ?>

</div>

<style>

</style>