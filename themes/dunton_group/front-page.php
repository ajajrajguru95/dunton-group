<?php get_header(); ?>
<?php if (have_posts()) : ?>
  <?php
  while (have_posts()) : the_post();

  ?>
    <!-- Default Page-->

    <!-- Page content -->
      <section class="default-section">
            <?= the_content(); ?>
      </section>


<?php
  endwhile;
endif;
?>

<?php get_footer(); ?>