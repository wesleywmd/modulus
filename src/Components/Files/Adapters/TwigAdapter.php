<?php
namespace Modulus\Components\Files\Adapters;

use Modulus\Components\Config\ConfigArrayInterface;
use Twig_Environment;

/**
 * Class TwigAdapter
 * @package Modulus\Components\Files\Adapters
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
class TwigAdapter implements FileAdapterInterface
{
    /** @var \Twig_Environment  */
    protected $twig;

    /** @var string */
    protected $templateLocation;

    /**
     * @param                   $templateLocation
     * @param \Twig_Environment $twig
     */
    public function __construct(
        $templateLocation,
        Twig_Environment $twig = null
    ) {
        $this->twig = $twig;
        $this->templateLocation = $templateLocation;
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function setTwig(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $templateLocation
     */
    public function setTemplateLocation($templateLocation)
    {
        $this->templateLocation = $templateLocation;
    }

    /**
     * @param \Modulus\Components\Config\ConfigArrayInterface $configArray
     *
     * @return string
     * @throws \Exception
     */
    public function render(ConfigArrayInterface $configArray)
    {
        if( is_null($this->twig) || is_null($this->templateLocation) ) {
            throw new \Exception("Adapter not setup correctly.");
        }
        $array = $configArray->get();
        $array["__configArray"] = $configArray;
        return $this->twig->render($this->templateLocation, $array );
    }
}