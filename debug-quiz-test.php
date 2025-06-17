<?php
/**
 * Debug Quiz Test - Simple test to verify elegant-quiz component
 *
 * This file tests the elegant-quiz component in isolation
 */

// Include WordPress
require_once('../../../../../../wp-load.php');

get_header();
?>

<div style="padding: 2rem; background: #f5f5f5; min-height: 100vh;">
    <h1>Debug: Elegant Quiz Component Test</h1>

    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-width: 600px;">
        <h2>Testing Elegant Quiz Component</h2>

        <?php
        echo '<p><strong>Template Part Path:</strong> ' . get_template_directory() . '/template-parts/components/elegant-quiz.php</p>';
        echo '<p><strong>File Exists:</strong> ' . (file_exists(get_template_directory() . '/template-parts/components/elegant-quiz.php') ? 'YES' : 'NO') . '</p>';

        // Test the component inclusion
        echo '<div style="border: 2px solid #007cba; padding: 1rem; margin: 1rem 0;">';
        echo '<h3>Quiz Component Output:</h3>';

        get_template_part('template-parts/components/elegant-quiz', null, array(
            'title' => 'Debug Quiz Test',
            'subtitle' => 'Testing if the quiz component loads properly',
            'css_class' => 'debug-quiz-test',
            'show_progress' => true
        ));

        echo '</div>';
        ?>

        <script>
        // Debug JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== ELEGANT QUIZ DEBUG ===');
            console.log('Quiz containers found:', document.querySelectorAll('.elegant-quiz').length);
            console.log('Quiz config:', window.elegantQuizConfig);
            console.log('ElegantQuizComponent available:', typeof window.ElegantQuizComponent);

            // List all quiz containers
            document.querySelectorAll('.elegant-quiz').forEach((container, index) => {
                console.log(`Quiz container ${index}:`, container);
                console.log(`  - ID: ${container.id}`);
                console.log(`  - Classes: ${container.className}`);
                console.log(`  - Data attributes:`, container.dataset);
            });
        });
        </script>
    </div>
</div>

<?php get_footer(); ?>
