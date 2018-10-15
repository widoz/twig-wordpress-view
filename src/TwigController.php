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
     */
    public function render(Data $data): void
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
     * Load the template
     *
     * @param string $path
     * @return \Twig_TemplateWrapper The instance for chaining.
     */
    private function twigLoad(string $path): ?\Twig_TemplateWrapper
    {
        try {
            return $this->twigEnv->load($path);
        } catch (TwigError $exc) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                throw new $exc;
            }
        }
    }
}
