<?php # -*- coding: utf-8 -*-
namespace TwigWordPressView\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Class TestCase
 */
class TestCase extends PHPUnitTestCase
{
    protected static $sourcePath;

    /**
     * Constructs a test case with the given name.
     *
     * @param string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::$sourcePath = dirname(__DIR__, 2);
    }

    /**
     * SetUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();

        $this->defineCommonWPFunctions();
    }

    /**
     * TearDown
     */
    protected function tearDown(): void
    {
        Monkey\tearDown();
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * Define Common WordPress Functions
     */
    protected function defineCommonWPFunctions(): void
    {
        Functions\when('__')->returnArg(1);
        Functions\when('esc_url')->returnArg(1);
        Functions\when('esc_html__')->returnArg(1);
        Functions\when('esc_html_x')->returnArg(1);
        Functions\when('sanitize_key')->alias(function ($key) {
            return preg_replace('/[^a-z0-9_\-]/', '', strtolower($key));
        });
        Functions\when('wp_parse_args')->alias(function ($args, $defaults) {
            if (is_object($args)) {
                $r = get_object_vars($args);
            } else {
                if (is_array($args)) {
                    $r =& $args;
                } else {
                    wp_parse_str($args, $r);
                }
            }

            if (is_array($defaults)) {
                return array_merge($defaults, $r);
            }

            return $r;
        });
        Functions\when('plugin_dir_path')->justReturn(static::$sourcePath);
        Functions\when('untrailingslashit')->alias(function ($val) {
            return rtrim($val, '/');
        });
    }

    /**
     * @param string $string
     * @return string
     */
    protected function cleanMarkup(string $string): string
    {
        $output = str_replace(["\n", "\t", "\r", "\r\n"], '', $string);
        $output = preg_replace('/>\s+/', '>', $output);
        $output = preg_replace('/\s+</', '<', $output);
        $output = preg_replace('/\s{2,}/', ' ', $output);
        $output = trim($output);

        return $output;
    }
}
