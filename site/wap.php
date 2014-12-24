<?php
### We Need RSS
define('WP_USE_THEMES', false);
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
wp('feed=rss');

### Set Header To WML
header('Content-Type: text/vnd.wap.wml;charset=utf-8');


### Echo XML
echo '<?xml version="1.0" encoding="utf-8"?'.'>';
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD WML 2.0//EN" "http://www.wapforum.org/dtd/wml20.dtd">
<wml>
<card id="WordPress" title="<?php bloginfo_rss('name'); ?>">
<?php if(empty($_GET['p'])): ?>
    <?php $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1; ?>
    <?php $the_query = new WP_Query(array('post_type' => array('post'), 'paged' => $paged)); ?>
    <?php if($the_query->have_posts()): ?>
        <?php while ($the_query->have_posts()): $the_query->the_post(); ?>
            <p>
                <?php the_time(get_option('date_format').' ('.get_option('time_format').')'); ?><br />
                - <a href="<?php bloginfo('siteurl'); ?>/wap.php?p=<?php the_ID(); ?>"><?php the_title_rss(); ?></a>
            </p>
        <?php endwhile; ?>
        <?php
            $older = $paged + 1;
            if ($older > $the_query->max_num_pages){
                $older = $the_query->max_num_pages;
            }
            $newer = $paged - 1;
            if($newer < 1){
                $newer = 1;
            }
        ?>
        <p><a href="/wap.php?page=<?php echo $older ?>">&lt;&lt; Entradas antiguas</a> | <a href="/wap.php?page=<?php echo $newer ?>">Entradas nuevas &gt;&gt;</a></p>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
<?php else : ?>
    <?php $the_query = new WP_Query('p='.$_GET['p']); ?>
	<?php if ($the_query->have_posts()) : ?>
		<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
			<p>&gt; <?php the_title_rss(); ?></p>
			<p>&gt; <?php the_time(get_option('date_format').' ('.get_option('time_format').')'); ?></p>
			<p>&gt; En <?php echo strip_tags(get_the_category_list(', ')); ?></p>
			<p>&gt; Por <?php the_author(); ?></p>
			<p><?php the_content_rss(); ?></p>
		<?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p>No se encontraron posts con el criterio seleccionado.</p>
        <?php wp_reset_postdata(); ?>
	<?php endif; ?>
	<br />
	<p><a href="wap.php">&lt;&lt; <?php bloginfo_rss('name'); ?></a></p>
<?php endif; ?>
</card>
</wml>
