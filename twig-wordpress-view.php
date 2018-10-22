<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Twig WordPress View
 * Plugin URI: http://github.com/widoz/twig-wordpress-view
 * Author: Guido Scialfa
 * Author URI: https://guidoscialfa.com
 * Description: WordPress twig views for your theme
 * Version: 0.1.0-dev
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wordpress-model
 */

/*
 * This file is part of the twig-wordpress-view package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TwigWordPressView;

// phpcs:disable

(function () {
    /**
     * Custom Admin Notice Message
     *
     * @param string $message
     * @param string $noticeType
     * @param array $allowedMarkup
     */
    function adminNotice(string $message, string $noticeType, array $allowedMarkup = []): void
    {
        add_action('admin_notices', function () use ($message, $noticeType, $allowedMarkup) {
            ?>
            <div class="notice notice-<?= esc_attr($noticeType) ?>">
                <p><?= wp_kses($message, $allowedMarkup) ?></p>
            </div>
            <?php
        });
    }

    function bootstrap(): void
    {
        $autoloader = plugin_dir_path(__FILE__) . '/vendor/autoload.php';

        if (!file_exists($autoloader)) {
            adminNotice(
                sprintf(
                // translators: %s Is the name of the plugin.
                    __('%s: No autoloader found, plugin cannot load properly.',
                        'twig-wordpress-view'),
                    '<strong>' . esc_html__('Twig WordPress View',
                        'twig-wordpress-view') . '</strong>'
                ),
                'error',
                ['strong' => true]
            );

            return;
        }

        require_once $autoloader;
    }

    add_action('plugins_loaded', __NAMESPACE__ . '\\bootstrap');
})();
