<?php
namespace Modulus\Components\Files\Adapters;

use Modulus\Components\Config\ConfigArrayInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlAdapter
 * @package Modulus\Components\Files\Adapters
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
class YamlAdapter implements FileAdapterInterface
{
    private $inline;
    private $indent;
    private $flags;

    public function __construct($inline=2,$indent=4,$flags=0)
    {
        $this->inline = $inline;
        $this->indent = $indent;
        $this->flags = $flags;
    }

    /**
     * @param int $inline
     */
    public function setInline($inline)
    {
        $this->inline = $inline;
    }

    /**
     * @param int $indent
     */
    public function setIndent($indent)
    {
        $this->indent = $indent;
    }

    /**
     * @param int $flags
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;
    }

    public function render(ConfigArrayInterface $configArray)
    {
        return Yaml::dump(
            $configArray->get(),
            $this->inline,
            $this->indent,
            $this->flags
        );
    }
}