<?php # -*- coding: utf-8 -*-
// phpcs:disable

namespace TwigWordPressView\Tests\Integration;

use Brain\Monkey\Functions;
use TwigWordPressView\Tests\TestCase;
use TwigWordPressView\TwigController;
use TwigWordPressView\TwigData;
use TwigWp\Factory;
use WordPressModel\ArchiveHeader;

class ArchiveHeaderTest extends TestCase
{
    public function testOutputHtmlForArchive()
    {
        Functions\when('get_the_archive_title')
            ->justReturn('Archive Title');

        Functions\when('get_the_archive_description')
            ->justReturn('Archive Description');

        Functions\when('is_home')
            ->justReturn(false);

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new ArchiveHeader(), 'archive.header.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<header class="archive__header"><h1 class="archive__title">Archive Title</h1><p class="archive__description">Archive Description</p></header>',
            $output
        );
    }

    public function testOutputHtmlForHome()
    {
        Functions\when('get_the_archive_title')
            ->justReturn('');

        Functions\when('get_option')
            ->justReturn(1);

        Functions\when('get_the_title')
            ->justReturn('Home Title');

        Functions\when('is_home')
            ->justReturn(true);

        $twigFactory = new Factory(
            new \Twig_Loader_Filesystem([
                TWIG_WP_VIEW_TEST_BASE_DIR . '/views/',
            ]),
            []
        );

        $sut = new TwigController($twigFactory->create());
        $data = new TwigData(new ArchiveHeader(), 'archive.header.twig');

        ob_start();
        $sut->render($data);
        $output = parent::cleanMarkup(ob_get_clean());

        self::assertSame(
            '<header class="archive__header"><h1 class="archive__title">Home Title</h1></header>',
            $output
        );
    }
}
