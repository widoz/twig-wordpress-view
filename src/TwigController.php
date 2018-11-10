<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the Twig WordPress View Theme package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TwigWordPressView;

use Twig;
use Twig\Error\Error as TwigError;
use WordPressTemplate\Controller;
use WordPressTemplate\ViewData;

/**
 * Class TemplateRender
 */
final class TwigController implements Controller
{
    /**
     * @var \Twig_Environment Instance of the class
     */
    private $twigEnv;

    /**
     * TemplateRender constructor
     *
     * @param Twig\Environment $twigEnv
     */
    public function __construct(Twig\Environment $twigEnv)
    {
        $this->twigEnv = $twigEnv;
    }

    /**
     * @inheritdoc
     *
     * @param TwigData $data
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(ViewData $data): void
    {
        $modelData = $data->model()->data();
        $templatePath = $data->templatePath();

        if (!$modelData) {
            return;
        }

        $this
            ->twigLoad($templatePath)
            ->display($modelData);
    }

    /**
     * Load Template Data
     *
     * @param string $path
     * @return null|\Twig_TemplateWrapper
     * @throws TwigError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function twigLoad(string $path): ?\Twig_TemplateWrapper
    {
        try {
            return $this->twigEnv->load($path);
        } catch (TwigError $exc) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                throw $exc;
            }
        }
    }
}
