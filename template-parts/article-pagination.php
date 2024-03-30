<nav class="pagination">
<?php
    $paginate_args = array(
        'end_size'    => 1,
        'mid_size'    => 2,
        'next_text'   => '<span class="fas fa-angle-right"></span>',
        'prev_text'   => '<span class="fas fa-angle-left"></span>',
    );
    echo paginate_links($paginate_args);
    ?>
</nav>
