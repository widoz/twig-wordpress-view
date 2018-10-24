<?php # -*- coding: utf-8 -*-
// phpcs:disable

namespace TwigWordPressView\Tests\Integration\Template\Model;

use Brain\Monkey\Functions;
use TwigWp\Factory;
use TwigWordPressView\TwigData;
use Widoz\Bem\BemPrefixed;
use TwigWordPressView\TwigController;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\FigureAttachmentImage;

class FigureAttachmentTemplateTest extends TestCase
{
    public function testHtmlOutput()
    {
        Functions\when('absint')
            ->justReturn(1);

        Functions\when('esc_html')
            ->returnArg(1);

        Functions\when('TwigWordPressView\\Functions\\ksesPost')
            ->returnArg(1);

        Functions\expect('wp_attachment_is_image')
            ->once()
            ->andReturn(true);

        Functions\expect('wp_get_attachment_caption')
            ->once()
            ->with(1)
            ->andReturn('caption test');

        Functions\expect('get_post_meta')
            ->once()
            ->with(1, '_wp_attachment_image_alt', true)
            ->andReturn('alt');

        Functions\expect('wp_get_attachment_image_src')
            ->once()
            ->andReturn([
                'Image Url',
                50,
                50,
                true
            ]);

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(
            new FigureAttachmentImage(1, '', new BemPrefixed('block')),
            'figureImage.twig'
        );

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<figure class="block"><img src="image_url" class="block__image" alt="alt" width="50" height="50" /><figcaption class="block__caption">caption test</figcaption></figure>',
            $output
        );
    }
}
