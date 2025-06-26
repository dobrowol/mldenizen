<?php
/*
Template Name: Custom Register
*/

get_header();

// Redirect logged-in users
if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/courses/' ) );
    exit;
}

$errors = [];
$success = '';

if ( isset($_POST['submit']) ) {
    $username = sanitize_user( $_POST['username'] );
    $email    = sanitize_email( $_POST['email'] );
    $password = $_POST['password'];

    if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
        $errors[] = 'All fields are required.';
    }

    if ( ! is_email( $email ) ) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ( username_exists( $username ) ) {
        $errors[] = 'Username already exists.';
    }

    if ( email_exists( $email ) ) {
        $errors[] = 'Email already registered.';
    }

    if ( empty( $errors ) ) {
        $user_id = wp_create_user( $username, $password, $email );
        if ( is_wp_error( $user_id ) ) {
            $errors[] = $user_id->get_error_message();
        } else {
            $success = 'Registration successful! <a href="' . home_url('/login/') . '">Log in here</a>.';
        }
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
.login-box input[type="email"],
.login-box input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
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

.login-box .error,
.login-box .success {
    margin-bottom: 16px;
    font-size: 14px;
    padding: 12px;
    border-radius: 6px;
}

.login-box .error {
    background: #ffe0e0;
    color: #990000;
}

.login-box .success {
    background: #e0ffe0;
    color: #006600;
}

.login-box .extra-links {
    margin-top: 20px;
    text-align: center;
}

.login-box .extra-links a {
    color: #0073aa;
    text-decoration: none;
    font-size: 14px;
}

.login-box .extra-links a:hover {
    text-decoration: underline;
}
</style>

<div class="custom-login-wrapper">
    <div class="login-image-side"></div>
    <div class="login-form-side">
        <div class="login-box">
            <h2>Create Account</h2>

            <?php if ( $errors ) : ?>
                <div class="error"><?php echo implode('<br>', $errors); ?></div>
            <?php elseif ( $success ) : ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" name="submit" value="Register">
            </form>

            <div class="extra-links">
                Already have an account? <a href="<?php echo home_url('/login/'); ?>">Log in</a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
