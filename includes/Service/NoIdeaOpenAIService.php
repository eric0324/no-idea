<?php

class NoIdeaOpenAIService
{
    /**
     * @var string
     */
    private $api_url;
    private $options;

    public function __construct()
    {
        $this->api_url = 'https://api.openai.com/v1/completions';

        $this->options = get_option( NO_IDEA_OPTION_PREFIX . 'basic' );
    }

    /**
     * @throws JsonException
     */
    public function generate_post_content(): void
    {
        if ( empty($_POST) || !wp_verify_nonce($_POST[ 'post_nonce' ], 'post_nonce') ) {
            wp_die( 'Generate post error.' );
        }

        $prompt = sanitize_textarea_field( $_POST['prompt'] );
        $max_tokens = sanitize_text_field( $_POST['max_tokens'] );
        $title = sanitize_text_field( $_POST[ 'title' ] );

        $generated_text = $this->send_api( $prompt, $max_tokens );

        $new_post = array(
            'post_title' => $title,
            'post_content' => $generated_text,
            'post_author' => 1
        );
        $new_post = wp_insert_post( $new_post );
        wp_redirect( get_edit_post_link( $new_post ) );
        exit;
    }

    /**
     * @throws JsonException
     */
    public function generate_product_content(): void
    {
        if ( empty($_POST) || !wp_verify_nonce($_POST['product_nonce'], 'product_nonce') ) {
            wp_die('Generate product error.');
        }
        $prompt = sanitize_textarea_field( $_POST['prompt'] );
        $max_tokens = sanitize_text_field( $_POST['max_tokens'] );
        $title = sanitize_text_field( $_POST[ 'title' ] );

        $generated_text = $this->send_api( $prompt, $max_tokens );

        $product = new WC_Product_Simple();
        $product->set_name( $title ); // product title
        $product->set_slug( $title );
        $product->set_regular_price( 0 ); // in current shop currency
        $product->set_short_description( $generated_text );
        $product->set_description( $generated_text );
        $product->save();

        wp_safe_redirect( '/wp-admin/edit.php?post_type=product' );

        exit;
    }

    /**
     * @param $prompt
     * @param $max_tokens
     * @return string
     * @throws JsonException
     */
    private function send_api( $prompt, $max_tokens ): string
    {
        $data = array(
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => ( int )$max_tokens,
            'temperature' => 1,
            'n' => 1
        );

        $headers = array(
            'Authorization' => 'Bearer ' . $this->options[ 'open_ai_key' ],
            'Content-Type' => 'application/json',
        );

        $response = wp_remote_post($this->api_url, array(
            'headers' => $headers,
            'body' => json_encode( $data, JSON_THROW_ON_ERROR ),
            'timeout' => 30,
        ));

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            wp_die( $error_message );
        }

        $body = wp_remote_retrieve_body( $response );
        $json = json_decode( $body, true, 512, JSON_THROW_ON_ERROR );

        return $json['choices'][0]['text'];
    }
}