<?php
/*
Plugin Name: MCP Context Viewer
Plugin URI: https://www.mcp-hunt.com/
Description: Validate and view MCP (Model Context Protocol) JSON metadata in your WordPress admin.
Version: 1.0.0
Author: MCP Hunt
Author URI: https://www.mcp-hunt.com/
License: MIT
*/

add_action('admin_menu', function() {
    add_menu_page(
        'MCP Context Viewer',
        'MCP Viewer',
        'manage_options',
        'mcp-context-viewer',
        'mcp_context_viewer_page',
        'dashicons-media-code'
    );
});

function mcp_context_viewer_page() {
    ?>
    <div class="wrap">
        <h1>MCP Context Viewer</h1>
        <p>Paste your MCP JSON below to validate and view it formatted.</p>
        <form method="post">
            <textarea name="mcp_json" rows="10" cols="80"><?php echo esc_textarea($_POST['mcp_json'] ?? ''); ?></textarea><br>
            <p><input type="submit" class="button button-primary" value="Validate & View"></p>
        </form>
    <?php
    if (!empty($_POST['mcp_json'])) {
        echo '<h2>Result:</h2>';
        $json = json_decode(stripslashes($_POST['mcp_json']), true);
        if (!$json) {
            echo '<div style="color:red;">Invalid JSON!</div>';
        } else {
            $valid = isset($json['model']) && isset($json['inputs']) && isset($json['outputs']);
            if ($valid) {
                echo '<div style="color:green;">✅ Valid MCP Context</div>';
            } else {
                echo '<div style="color:red;">⚠️ Missing required fields: model, inputs, outputs</div>';
            }
            echo '<h3>Normalized:</h3>';
            $normalized = array_merge([
                'model' => '',
                'inputs' => [],
                'outputs' => [],
                'metadata' => new stdClass()
            ], $json);
            echo '<pre>' . esc_html(json_encode($normalized, JSON_PRETTY_PRINT)) . '</pre>';
        }
    }
    ?>
        <p>Learn more about MCP at <a href="https://www.mcp-hunt.com/" target="_blank">mcp-hunt.com</a></p>
    </div>
    <?php
}
