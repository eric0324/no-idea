<?php

class NoIdeaPostView
{
    public function no_idea_post_admin(): void
    {
        add_submenu_page(
            'no-idea',
            'Post',
            'Post',
            'publish_posts',
            'no-idea-post',
            [&$this, 'no_idea_post_view'],
        );
    }

    /**
     * @return void
     */
    public function no_idea_post_view(): void
    {
        $options = get_option(NO_IDEA_OPTION_PREFIX . 'basic');

        ?>
        <?php
            if ( (! isset( $options['open_ai_key'] ) ) ) {
                echo '<div class="update-nag notice notice-warning inline">Open AI Key has not been filled in. Please go to <b>Settings</b> to filled in.</div>';
            }
        ?>
        <div></div>
        <h2><?php _e('Generate Post', 'post-idea') ?></h2>
        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <?php wp_nonce_field('post_nonce', 'post_nonce'); ?>
            <input type="hidden" name="action" value="generate_post">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="title"><?php _e('Title', 'no-idea') ?></label>
                    </th>
                    <td>
                        <input type="text" id="title" name="title" required>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="prompt"><?php _e('Prompt', 'no-idea') ?></label>
                    </th>
                    <td>
                        <textarea id="no-idea" name="prompt" cols="100" rows="10" required></textarea>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="max-tokens"><?php _e('Max Tokens', 'no-idea') ?></label>
                    </th>
                    <td>
                        <input type="number" id="max-tokens" name="max_tokens" required>
                    </td>
                </tr>
            </table>
            <button type="submit" class="button"><?php _e('Generate', 'no-idea') ?></button>
        </form>
        <?php
    }
}