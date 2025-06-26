<?php
/*
Template Name: Custom Login
*/

get_header();

// Redirect if user is already logged in
if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $selected_course_id = get_user_meta($user_id, 'selected_course_id', true);
    
    if ($selected_course_id && get_post_status($selected_course_id) === 'publish') {
        wp_redirect( get_permalink($selected_course_id) );
    } else {
        wp_redirect( home_url( '/courses/' ) );
    }
    exit;
}

$error = '';

if ( isset($_POST['submit']) ) {
    $creds = array();
    $creds['user_login']    = sanitize_user( $_POST['username'] );
    $creds['user_password'] = $_POST['password'];
    $creds['remember']      = isset( $_POST['remember'] );

    $user = wp_signon( $creds, false );
    if ( is_wp_error( $user ) ) {
        $error = $user->get_error_message();
    } else {
        wp_redirect( home_url( '/courses/' ) );
        exit;
    }
}
?>

<style>
.custom-login-wrapper {
    display: flex;
    height: 100vh;
    width: 100%;
    font-family: "Segoe UI", sans-serif;
}

.login-image-side {
    flex: 1;
    background: url('https://mldenizen.com/wp-content/uploads/2025/05/ChatGPT-Image-13-mag-2025-14_20_03-e1747282190803.png') no-repeat center center;
    background-size: cover;
}

.login-form-side {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
    background-color: #f9f9f9;
}

.login-box {
    width: 100%;
    max-width: 400px;
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.login-box h2 {
    margin-bottom: 24px;
    font-size: 28px;
    color: #333;
}

.login-box input[type="text"],
.login-box input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
}

.login-box input[type="checkbox"] {
    margin-right: 8px;
}

.login-box input[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #0073aa;
    border: none;
    color: white;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
}

.login-box input[type="submit"]:hover {
    background-color: #005a87;
}

.login-box .error {
    color: red;
    margin-bottom: 16px;
}
</style>

<div class="custom-login-wrapper">
    <div class="login-image-side"></div>
    <div class="login-form-side">
        <div class="login-box">
            <h2>Welcome Back</h2>
            <?php if ( ! empty( $error ) ) : ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username or Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <p>
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </p>
                <input type="submit" name="submit" value="Log In">
            </form>
            <div class="extra-links">
                <a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password?</a> |
                <?php
                error_log('signup'.home_url('/signup/'))
                ?>
                <a href="<?php echo home_url('/signup/'); ?>">Register</a>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
