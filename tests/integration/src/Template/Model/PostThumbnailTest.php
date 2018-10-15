<?php # -*- coding: utf-8 -*-

namespace TwigWordPressView\Tests\Integration\Template\Model;

use Brain\Monkey\Functions;
use TwigWp\Factory;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\PostThumbnail;

class PostThumbnailTest extends TestCase
{
    public function testHtmlOutput()
    {
        $postMock = \Mockery::mock('\WP_Post');
        $postMock->ID = 1;

        Functions\when('apply_filters')
            ->returnArg(2);

        Functions\expect('has_post_thumbnail')
            ->once()
            ->with($postMock)
            ->andReturn(true);

        Functions\expect('current_theme_supports')
            ->once()
            ->with('post-thumbnails')
            ->andReturn(true);

        Functions\expect('wp_get_attachment_caption')
            ->once()
            ->with($postMock->ID)
            ->andReturn('Caption');

        Functions\expect('get_permalink')
            ->once()
            ->with($postMock)
            ->andReturn('permalink');

        Functions\expect('get_post_thumbnail_id')
            ->once()
            ->with($postMock)
            ->andReturn(1);

        Functions\expect('wp_attachment_is_image')
            ->once()
            ->with(1)
            ->andReturn(true);

        Functions\expect('wp_get_attachment_image_url')
            ->once()
            ->with(1, 'post-thumbnail')
            ->andReturn('image_url');

        Functions\expect('get_post_meta')
            ->once()
            ->with(1, '_wp_attachment_image_alt', true)
            ->andReturn('alt');

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new PostThumbnail($postMock), 'thumbnail.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<a href="permalink"><figure class="thumbnail"><img src="image_url" class="thumbnail__image" alt="alt" /><figcaption class="thumbnail__caption">Caption</figcaption></figure></a>',
            $output
        );
    }
}
