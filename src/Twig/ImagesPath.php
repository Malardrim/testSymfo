<?php


namespace App\Twig;


use App\Entity\UserIcon;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ImagesPath extends AbstractExtension
{

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * ImagesPath constructor.
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('iconUser', [$this, 'iconUserPath']),
        ];
    }

    public function iconUserPath(?UserIcon $icon)
    {
        $path = "undefined";
        if ($icon instanceof UserIcon){
           $path = $this->router->generate('user_icon_path', ['icon' => $icon->getId()]);
        }
        return $path;
    }
}