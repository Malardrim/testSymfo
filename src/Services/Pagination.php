<?php


namespace App\Services;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Pagination
{
    /**
     * @var string $entityClass Name of the class to manage
     */
    private $entityClass;

    /**
     * @var int $limit Limit of rows per page
     */
    private $limit = 10;

    /**
     * @var int $currentPage
     */
    private $currentPage = 1;

    /**
     * @var ObjectManager $manager ObjectManager
     */
    private $manager;

    /**
     * @var string
     */
    private $route;

    /**
     * @var Environment $twig Twig env
     */
    private $twig;

    /**
     * @var string $templateName Name of the template to call
     */
    private $templateName;

    /**
     * Pagination constructor.
     * @param ObjectManager $manager
     * @param Environment $twig
     * @param RequestStack $requestStack
     * @param string $templateName
     */
    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $requestStack, string $templateName)
    {
        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $requestStack->getCurrentRequest()->get('_route');
        $this->templateName = $templateName;
    }

    /**
     * Displays the pagination
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function display(){
        $this->twig->display($this->templateName, [
            'page' => $this->currentPage,
            'total_pages' => $this->countPages(),
            'route' => $this->getRoute()
        ]);
    }

    /**
     * Retrieves data from the DB
     */
    public function getData(){
        $repo = $this->manager->getRepository($this->entityClass);
        $offset = $this->currentPage * $this->limit - $this->limit;
        $data = $repo->findBy([], [], $this->limit, $offset);

        return $data;
    }

    /**
     * Counts the max pages available
     *
     * @return float
     */
    public function countPages(){
        return ceil($this->manager->getRepository($this->entityClass)->count([]) / $this->limit);
    }

    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param mixed $entityClass
     * @return Pagination
     */
    public function setEntityClass($entityClass): Pagination
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @param int $limit
     * @return Pagination
     */
    public function setLimit(int $limit): Pagination
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $currentPage
     * @return Pagination
     */
    public function setCurrentPage(int $currentPage): Pagination
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param string $route
     * @return Pagination
     */
    public function setRoute(string $route): Pagination
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $templateName
     * @return Pagination
     */
    public function setTemplateName(string $templateName): Pagination
    {
        $this->templateName = $templateName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return $this->templateName;
    }
}