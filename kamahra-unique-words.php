<?php
/*
Plugin Name: Kamahra Unique Words
Description: Extract unique words from input text using a custom separator.
Version: 1.0
Author: Your Name
*/

// ✅ Step 1: Register JS and CSS assets
function kamahra_unique_words_register_assets() {
    wp_register_script(
        'unique-words-js',
        plugins_url('assets/unique-words.js', __FILE__),
        array(),
        null,
        true // Load in footer
    );

    wp_register_style(
        'unique-words-css',
        plugins_url('assets/style.css', __FILE__)
    );
}
add_action('init', 'kamahra_unique_words_register_assets');


// ✅ Step 2: Define shortcode and return HTML with dynamic styles
function kamahra_unique_words_shortcode() {
    ob_start();

    // Fetch saved colors or use defaults
    $bg_color = get_option('kamahra_bg_color', '#F9F7E8');
    $text_color = get_option('kamahra_text_color', '#1F1F1F');
    $button_color = get_option('kamahra_button_color', '#FF2FB2');
    ?>

    <style>
        #kamahra-unique-words {
            background-color: <?php echo esc_attr($bg_color); ?>;
            color: <?php echo esc_attr($text_color); ?>;
            padding: 1em;
            border-radius: 10px;
        }

        #kamahra-unique-words textarea,
        #kamahra-unique-words input[type="text"] {
            width: 100%;
            margin-bottom: 1em;
            padding: 0.5em;
            border-radius: 5px;
            border: 1px solid #ccc;
            color: <?php echo esc_attr($text_color); ?>;
        }

        #kuw-output {
            background-color: #fff;
        }

        #kuw-copy-button {
            background-color: <?php echo esc_attr($button_color); ?>;
            color: white;
            padding: 0.5em 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .kuw-label {
            font-weight: bold;
        }

        .kuw-help {
            font-style: italic;
            color: #999999;
        }
    </style>

    <div id="kamahra-unique-words" class="kamahra-plugin-wrapper">

        <label for="kuw-input" class="kuw-label">Input Text</label><br>
        <textarea id="kuw-input" class="kuw-textarea" rows="6" placeholder="Enter your text here..."></textarea>

        <label for="kuw-separator" class="kuw-label">Output Word Separator</label><br>
        <input type="text" id="kuw-separator" class="kuw-input" placeholder=", ">

        <label for="kuw-output" class="kuw-label">Unique Words</label><br>
        <textarea id="kuw-output" class="kuw-textarea" rows="6" readonly placeholder="Unique words will appear here."></textarea>

        <p class="kuw-help"><em>Note: Separator must be typed before pasting input.</em></p>
        <button id="kuw-copy-button">Copy to Clipboard</button>

    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('kamahra_unique_words', 'kamahra_unique_words_shortcode');


// ✅ Step 3: Load CSS and JS only if shortcode is used
function kamahra_enqueue_assets() {
    if (!is_singular()) return;

    global $post;
    if (has_shortcode($post->post_content, 'kamahra_unique_words')) {
        wp_enqueue_style('unique-words-css');
        wp_enqueue_script('unique-words-js');
    }
}
add_action('wp_enqueue_scripts', 'kamahra_enqueue_assets');


// ✅ Step 4: Add a settings page to WP admin menu
function kamahra_add_settings_page() {
    add_options_page(
        'Kamahra Unique Words Settings',
        'Kamahra Colors',
        'manage_options',
        'kamahra-unique-words-settings',
        'kamahra_render_settings_page'
    );
}
add_action('admin_menu', 'kamahra_add_settings_page');


// ✅ Step 5: Render the settings page with form
function kamahra_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Kamahra Unique Words – Color Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('kamahra_settings_group');
            do_settings_sections('kamahra-unique-words-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}


// ✅ Step 6: Register the color picker settings
function kamahra_register_settings() {
    register_setting('kamahra_settings_group', 'kamahra_bg_color');
    register_setting('kamahra_settings_group', 'kamahra_text_color');
    register_setting('kamahra_settings_group', 'kamahra_button_color');

    add_settings_section('kamahra_main_section', '', null, 'kamahra-unique-words-settings');

    add_settings_field(
        'kamahra_bg_color',
        'Background Color',
        function() {
            $value = get_option('kamahra_bg_color', '#F9F7E8');
            echo "<input type='color' name='kamahra_bg_color' value='" . esc_attr($value) . "' />";
        },
        'kamahra-unique-words-settings',
        'kamahra_main_section'
    );

    add_settings_field(
        'kamahra_text_color',
        'Text Color',
        function() {
            $value = get_option('kamahra_text_color', '#1F1F1F');
            echo "<input type='color' name='kamahra_text_color' value='" . esc_attr($value) . "' />";
        },
        'kamahra-unique-words-settings',
        'kamahra_main_section'
    );

    add_settings_field(
        'kamahra_button_color',
        'Button Color',
        function() {
            $value = get_option('kamahra_button_color', '#FF2FB2');
            echo "<input type='color' name='kamahra_button_color' value='" . esc_attr($value) . "' />";
        },
        'kamahra-unique-words-settings',
        'kamahra_main_section'
    );
}
add_action('admin_init', 'kamahra_register_settings');
