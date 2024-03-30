<?php
    /* Template Name: Page404 */
    get_header();
    $template_uri = get_template_directory_uri();
?>

<section class="post-content" itemprop="text">
  <div class="content-none">
    <!-- ここに冒頭文を書く -->
    <span class="error-number">404 page</span><br>
    お探しのページが見つかりません。<br><br>
    アクセスしようとしたページは削除、変更されたか、現在利用できない可能性があります。
    <div class="content-none-btn">
      <div class="more-info-btn-cover less-bottom">
        <a href="<?php echo home_url('/'); ?>" class="more-info-btn">
          TOPページへ
        </a>
      </div>
    </div>
  </div>
</section>

<?php
    get_footer();
?>
