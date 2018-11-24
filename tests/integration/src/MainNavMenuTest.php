<?php # -*- coding: utf-8 -*-
// phpcs:disable
namespace TwigWordPressView\Tests\Integration\Template\Model;

use \Brain\Monkey\Functions;
use TwigWp\Factory;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\MainNavMenu;

class MainNavMenuTest extends TestCase
{
    public function testHtmlOutputContainsJumpToContentLink()
    {
        Functions\expect('has_nav_menu')
            ->once()
            ->andReturn(true);

        Functions\when('wp_nav_menu')
            ->justReturn('Menu Nav HTML');

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new MainNavMenu('theme_location', 'menu_id', 2), 'menu.main.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        $contained = strpos(
            $output,
            '<a href="#content" id="jump_to_content" class="nav-main__to-content">Jump To Content</a>'
        );

        self::assertTrue(false !== $contained);
    }
}
