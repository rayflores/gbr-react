<?php
/**
 * Main template.
 */

?>
<?php
get_header(); ?>
    <div>
        <h1>themes/assessment/index.php</h1>
        <p>Edit me to make your theme!</p>
        <div class="info-box">
            Log in to admin at <a href="<?php echo esc_url( home_url( 'wp-admin' ) ); ?>">/wp-admin/</a>
            with username <strong>admin</strong> and password <strong>password</strong>.
        </div>
        <div id="app"></div>
    </div>
<?php
get_footer();