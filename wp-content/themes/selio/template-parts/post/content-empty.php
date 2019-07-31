<article id="post-<?php the_ID(); ?>" <?php post_class('recent-posts-item'); ?>>
    <div class="excerpt">
        <?php
        the_content();
        ?>
    </div>
    <footer class="post-footer">
    </footer>
</article>

