<?php


namespace App\Controller;


use App\Entity\UserIcon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/images")
 */
class ImagesController extends AbstractController
{
    /**
     * @Route("/user_icon/{icon}", name="user_icon_path")
     * @param string|null $icon
     * @return BinaryFileResponse
     */
    public function userIconAction(?string $icon){
        if (is_numeric($icon)){
            $icon = $this->getDoctrine()->getRepository(UserIcon::class)->findOneBy(['id' => $icon]);
        }else{
            $icon = $this->getDoctrine()->getRepository(UserIcon::class)->findOneBy(['name' => $icon]);
        }
        return new BinaryFileResponse($icon->getImageFile()->getLinkTarget());
    }
}