<?php

class NoIdeaHomeView
{

    public function no_idea_home_admin(): void
    {
        add_menu_page(
            'Home',
            'NoIdea',
            'publish_posts',
            'no-idea',
            [&$this, 'no_idea_home_view'],
            'dashicons-nametag',
            '120'
        );
    }

    /**
     * @return void
     */
    public function no_idea_home_view(): void
    {
        ?>
        <h2><?php _e('NoIdea', 'no-idea') ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">

                </th>
            </tr>
        </table>
        <?php
    }
}