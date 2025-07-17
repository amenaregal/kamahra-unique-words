
<?php
/*
Plugin Name: Kamahra Unique Words
Description: A minimal plugin with folder structure and assets.
Version: 1.0
Author: Your Name
*/

// Register JS and CSS files
function kamahra_unique_words_register_assets() {
    wp_register_script('unique-words-js', plugins_url('assets/unique-words.js', __FILE__), array(), null, true);
    wp_register_style('unique-words-css', plugins_url('assets/style.css', __FILE__));
}
add_action('init', 'kamahra_unique_words_register_assets');
