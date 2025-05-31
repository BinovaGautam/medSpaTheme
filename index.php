<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link sr-only-focusable" href="#main"><?php esc_html_e('Skip to main content', 'preetidreams'); ?></a>

<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="container">

        <?php if (have_posts()) : ?>

            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>

            <div class="posts-grid">
                <?php while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large', ['alt' => get_the_title()]); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="entry-header">
                                <?php
                                if (is_singular()) :
                                    the_title('<h1 class="entry-title">', '</h1>');
                                else :
                                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                                endif;
                                ?>

                                <?php if ('post' === get_post_type()) : ?>
                                    <div class="entry-meta">
                                        <time class="entry-date" datetime="<?php echo get_the_date('c'); ?>">
                                            <?php echo get_the_date(); ?>
                                        </time>
                                        <span class="author">
                                            <?php printf(__('by %s', 'preetidreams'), get_the_author()); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <div class="entry-content">
                                <?php
                                if (is_singular()) {
                                    the_content();

                                    wp_link_pages([
                                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'preetidreams'),
                                        'after'  => '</div>',
                                    ]);
                                } else {
                                    the_excerpt();
                                    echo '<a href="' . esc_url(get_permalink()) . '" class="read-more">' . esc_html__('Read More', 'preetidreams') . '</a>';
                                }
                                ?>
                            </div>

                            <?php if (is_singular() && (get_edit_post_link() || comments_open() || get_comments_number())) : ?>
                                <footer class="entry-footer">
                                    <?php
                                    // Categories and tags for posts
                                    if ('post' === get_post_type()) {
                                        $categories_list = get_the_category_list(', ');
                                        $tags_list = get_the_tag_list('', ', ');

                                        if ($categories_list) {
                                            printf('<div class="cat-links">' . __('Categories: %1$s', 'preetidreams') . '</div>', $categories_list);
                                        }

                                        if ($tags_list) {
                                            printf('<div class="tags-links">' . __('Tags: %1$s', 'preetidreams') . '</div>', $tags_list);
                                        }
                                    }

                                    // Edit link
                                    edit_post_link(
                                        sprintf(
                                            wp_kses(__('Edit <span class="sr-only">%s</span>', 'preetidreams'), ['span' => ['class' => []]]),
                                            get_the_title()
                                        ),
                                        '<div class="edit-link">',
                                        '</div>'
                                    );
                                    ?>
                                </footer>
                            <?php endif; ?>
                        </div>

                    </article>

                    <?php
                    // Comments on single posts/pages
                    if (is_singular() && (comments_open() || get_comments_number())) {
                        comments_template();
                    }
                    ?>

                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination([
                'prev_text' => __('&laquo; Previous', 'preetidreams'),
                'next_text' => __('Next &raquo;', 'preetidreams'),
                'class'     => 'pagination-wrapper',
            ]);
            ?>

        <?php else : ?>

            <div class="no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Nothing here', 'preetidreams'); ?></h1>
                </header>

                <div class="page-content">
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>
                        <p>
                            <?php
                            printf(
                                wp_kses(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'preetidreams'), ['a' => ['href' => []]]),
                                esc_url(admin_url('post-new.php'))
                            );
                            ?>
                        </p>
                    <?php elseif (is_search()) : ?>
                        <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'preetidreams'); ?></p>
                        <?php get_search_form(); ?>
                    <?php else : ?>
                        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'preetidreams'); ?></p>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>

</body>
</html>
