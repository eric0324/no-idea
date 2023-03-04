<?php

class NoIdeaSettingView
{
    public function no_idea_setting_admin(): void
    {
        add_submenu_page(
            'no-idea',
            'Setting',
            'Settings',
            'manage_options',
            'no-idea-admin',
            [&$this, 'no_idea_setting_view'],
        );
    }

    /**
     * @return void
     */
    public function no_idea_setting_view(): void
    {
        $options = get_option(NO_IDEA_OPTION_PREFIX . 'basic');

        ?>
        <h2><?php _e('Settings', 'no-idea') ?></h2>
        <form method="post" action="options.php">
            <?php settings_fields(NO_IDEA_OPTION_PREFIX . 'basic'); ?>
            <?php do_settings_sections(NO_IDEA_OPTION_PREFIX . 'basic'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="open-api-token"><?php _e('Open AI Key', 'no-idea') ?></label>
                    </th>
                    <td>
                        <input
                            id="open-api-token"
                            type="password"
                            name="<?php echo esc_attr(NO_IDEA_OPTION_PREFIX) ?>basic[open_ai_key]"
                            value="<?php if (isset($options['open_ai_key']))
                                echo esc_attr($options['open_ai_key']) ?>"
                            required
                        >
                    </td>
                </tr>
            </table>
            <?php
                submit_button();
            ?>
        </form>
        <?php
    }
}