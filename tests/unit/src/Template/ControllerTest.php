<?php # -*- coding: utf-8 -*-

namespace TwigWordPressView\Tests\Unit\Template;

use TwigWp\Factory;
use WordPressTemplate\ViewModel;
use TwigWordPressView\TwigController;
use TwigWordPressView\Tests\TestCase;
use WordPressModel\Model;

class ControllerTest extends TestCase
{
    public function testInstance()
    {
        $twigFactory = new Factory(new \Twig\Loader\ArrayLoader([
            'test.twig' => 'This is a {{test}}',
        ]), []);
        $sut = new TwigController($twigFactory->create());

        self::assertInstanceOf(TwigController::class, $sut);
    }

    public function testRender()
    {
        $twigFactory = new Factory(new \Twig\Loader\ArrayLoader([
            'test.twig' => 'This is a {{test}}',
        ]), []);
        $data = new class implements ViewModel
        {
            public function model(): Model
            {
                return new class implements Model
                {
                    public function data(): array
                    {
                        return [
                            'test' => 'Unit Test for Twig.',
                        ];
                    }
                };
            }

            public function templatePath(): string
            {
                return 'test.twig';
            }
        };

        self::expectOutputString('This is a Unit Test for Twig.');

        $sut = new TwigController($twigFactory->create());
        $sut->render($data);
    }
}
