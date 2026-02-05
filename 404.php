<?php
/**
 * 404 Error Page
 *
 * @package BallStreet
 */

get_header(); ?>

<div class="error-page" style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 24px;">
    <div style="font-family: var(--font-display); font-size: clamp(6rem, 15vw, 10rem); font-weight: 400; color: var(--bg-card); line-height: 1; margin-bottom: 16px;">404</div>
    <h1 style="font-family: var(--font-display); font-size: clamp(1.5rem, 4vw, 2.5rem); color: var(--text-primary); margin-bottom: 16px;">Page Not Found</h1>
    <p style="font-size: 16px; color: var(--text-secondary); max-width: 480px; margin-bottom: 32px;">The page you're looking for doesn't exist or has been moved. Try searching or go back to the homepage.</p>
    <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;">
        <a href="<?php echo esc_url(home_url("/")); ?>" class="btn btn-primary">
            Back to Home
        </a>
        <a href="<?php echo esc_url(
            home_url("/contact"),
        ); ?>" class="btn btn-secondary">
            Contact Us
        </a>
    </div>
</div>

<?php get_footer(); ?>
