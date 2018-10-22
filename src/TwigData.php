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

use WordPressModel\Model;
use WordPressTemplate\ViewData;

/**
 * Class TwigData
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class TwigData implements ViewData
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * TwigData constructor.
     *
     * @param Model $model
     * @param string $templatePath
     */
    public function __construct(Model $model, string $templatePath)
    {
        $this->model = $model;
        $this->templatePath = $templatePath;
    }

    /**
     * @return Model
     */
    public function model(): Model
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function templatePath(): string
    {
        return $this->templatePath;
    }
}
