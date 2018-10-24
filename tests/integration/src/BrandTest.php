<?php # -*- coding: utf-8 -*-
// phpcs:disable
namespace TwigWordPressView\Tests\Integration\Template\Model;

use Brain\Monkey\Functions;
use TwigWp\Factory;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\Brand;

class BrandTest extends TestCase
{
    public function testOutputHtml()
    {
        $this->allowedPostTags();

        Functions\when('esc_html')
            ->returnArg(1);

        Functions\when('wp_is_numeric_array')
            ->justReturn(false);

        Functions\when('wp_get_attachment_caption')
            ->justReturn('');

        Functions\when('wp_attachment_is_image')
            ->justReturn(true);

        Functions\when('get_post_meta')
            ->justReturn('post meta');

        Functions\expect('get_bloginfo')
            ->twice()
            ->with('name')
            ->andReturn('Site Name');

        Functions\expect('get_bloginfo')
            ->once()
            ->with('description')
            ->andReturn('Site Description');

        Functions\expect('get_theme_mod')
            ->once()
            ->with('custom_logo', 0)
            ->andReturn(1);

        Functions\expect('get_header_textcolor')
            ->once()
            ->andReturn('#fff');

        Functions\expect('sanitize_hex_color_no_hash')
            ->once()
            ->with('#fff')
            ->andReturn('fff');

        Functions\expect('home_url')
            ->once()
            ->with('/')
            ->andReturn('/');

        Functions\expect('wp_get_attachment_image_src')
            ->once()
            ->andReturn([
                'Image Url',
                50,
                50,
                true,
            ]);

        Functions\expect('wp_kses_post')
            ->once()
            ->andReturnFirstArg();

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new Brand('post-thumbnails'), 'brand.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<div class="brand"><a class="brand__link" href="/" style="color:#fff"><img src="Image Url" class="brand__image" alt="post meta" width="50" height="50" /><span class="screen-reader-text">Site Name</span></a><p class="brand__description">Site Description</p></div>',
            $output
        );
    }

    public function testOutputMarkupNoCustomLogoShowOnlySiteNameWithAnchor()
    {
        $this->allowedPostTags();

        Functions\when('esc_html')
            ->returnArg(1);

        Functions\when('wp_is_numeric_array')
            ->justReturn(false);

        Functions\when('wp_get_attachment_caption')
            ->justReturn('');

        Functions\when('wp_attachment_is_image')
            ->justReturn(true);

        Functions\when('get_post_meta')
            ->justReturn('post meta');

        Functions\expect('get_bloginfo')
            ->twice()
            ->with('name')
            ->andReturn('Site Name');

        Functions\expect('get_bloginfo')
            ->once()
            ->with('description')
            ->andReturn('Site Description');

        Functions\expect('get_theme_mod')
            ->once()
            ->with('custom_logo', 0)
            ->andReturn(0);

        Functions\expect('get_header_textcolor')
            ->once()
            ->andReturn('#fff');

        Functions\expect('sanitize_hex_color_no_hash')
            ->once()
            ->with('#fff')
            ->andReturn('fff');

        Functions\expect('home_url')
            ->once()
            ->with('/')
            ->andReturn('/');

        Functions\expect('wp_kses_post')
            ->once()
            ->andReturnFirstArg();

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new Brand('post-thumbnails'), 'brand.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<div class="brand"><a class="brand__link" href="/" style="color:#fff">Site Name</a><p class="brand__description">Site Description</p></div>',
            $output
        );
    }

    public function testOutputMarkupNoDescriptionIsShown()
    {
        $this->allowedPostTags();

        Functions\when('esc_html')
            ->returnArg(1);

        Functions\when('wp_is_numeric_array')
            ->justReturn(false);

        Functions\when('wp_get_attachment_caption')
            ->justReturn('');

        Functions\when('wp_attachment_is_image')
            ->justReturn(true);

        Functions\when('get_post_meta')
            ->justReturn('post meta');

        Functions\expect('get_bloginfo')
            ->twice()
            ->with('name')
            ->andReturn('Site Name');

        Functions\expect('get_bloginfo')
            ->once()
            ->with('description')
            ->andReturn('');

        Functions\expect('get_theme_mod')
            ->once()
            ->with('custom_logo', 0)
            ->andReturn(1);

        Functions\expect('get_header_textcolor')
            ->once()
            ->andReturn('#fff');

        Functions\expect('sanitize_hex_color_no_hash')
            ->once()
            ->with('#fff')
            ->andReturn('fff');

        Functions\expect('home_url')
            ->once()
            ->with('/')
            ->andReturn('/');

        Functions\expect('wp_get_attachment_image_src')
            ->once()
            ->andReturn([
                'Image Url',
                50,
                50,
                true,
            ]);

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new Brand('post-thumbnails'), 'brand.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<div class="brand"><a class="brand__link" href="/" style="color:#fff"><img src="Image Url" class="brand__image" alt="post meta" width="50" height="50" /><span class="screen-reader-text">Site Name</span></a></div>',
            $output
        );
    }

    private function allowedPostTags()
    {
        global $allowedposttags;

        $allowedposttags = [
            'a' => [
                'href' => true,
                'rel' => true,
                'rev' => true,
                'name' => true,
                'target' => true,
            ],
            'img' => [
                'alt' => true,
                'align' => true,
                'border' => true,
                'height' => true,
                'hspace' => true,
                'longdesc' => true,
                'vspace' => true,
                'src' => true,
                'usemap' => true,
                'width' => true,
            ],
        ];
    }
}
