<?php

declare(strict_types=1);

namespace Phico\View\Latte;

use Latte\Engine;
use Latte\Loaders\StringLoader;
use Phico\View\{ViewException, ViewInterface};

class Latte implements ViewInterface
{
    private Engine $latte;
    private array $options = [
        'use_cache' => false,
        'view_path' => '',
        'cache_path' => 'storage/views',
    ];


    public function __construct(array $overrides = [])
    {
        // apply options overriding with known overrides
        foreach ($this->options as $k => $v) {
            $this->options[$k] = (isset($overrides[$k])) ? $overrides[$k] : $v;
        }

        $this->latte = new Engine;
        $this->latte->setAutoRefresh(!$this->options['use_cache']);
        $this->latte->setTempDirectory($this->options['cache_path']);
    }
    public function render(string $template, array $data = [], bool $is_string = false): string
    {
        try {

            $path = path(sprintf('%s/%s', $this->options['view_path'], $template));
            return $this->latte->renderToString($path, $data);

        } catch (\Throwable $th) {

            throw new ViewException(sprintf('%s in file %s line %d', $th->getMessage(), $th->getFile(), $th->getLine()), 5050, $th);

        }
    }
    public function string(string $code, array $data = []): ?string
    {
        $this->latte->setLoader(new StringLoader([
            'inline.code' => $code,
        ]));

        return $this->latte->renderToString('inline.code', $data);
    }
    public function template(string $template, array $data = []): string
    {
        return $this->render($template, $data);
    }
}
