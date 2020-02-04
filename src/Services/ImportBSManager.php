<?php


namespace App\Services;


use App\Entity\Catalogue;
use App\Entity\CategoryEntry;
use App\Entity\Entry;
use App\Entity\SelectionEntry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportBSManager
{
    const MAIN_DATA_URL = "https://raw.githubusercontent.com/BSData/wh40k/master/Warhammer%2040%2C000%208th%20Edition.gst";
    const GENERIC_ENTRY_MANAGER = 'mangeGenericEntry';

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var string
     */
    private $catalogueId = null;

    /**
     * ImportBSManager constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param $data
     * @param array $result
     * @return mixed
     */
    public function recursiveCrawling($data, $result = [])
    {
        $count = 1;
        $result[0] = $data;
        if (isset($data->childNodes)) {
            foreach ($data->childNodes as $item) {
                $result[$count] = [];
                $result[$count] = $this->recursiveCrawling($item, $result[$count]);
                $count++;
            }
        }
        return $result;
    }

    /**
     * @param string $url
     * @return array
     */
    public function deserializeLink($url = "https://raw.githubusercontent.com/BSData/wh40k/master/Aeldari%20-%20Craftworlds.cat")
    {
        $res = [];
        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $url);
            $crawler = new Crawler($response->getContent());
            $res = [];
            foreach ($crawler as $domElement) {
                $res[] = $this->recursiveCrawling($domElement);
            }
            $res = $res[0];
        } catch (ClientExceptionInterface $e) {
            dump($e->getTraceAsString());
        } catch (RedirectionExceptionInterface $e) {
            dump($e->getTraceAsString());
        } catch (ServerExceptionInterface $e) {
            dump($e->getTraceAsString());
        } catch (TransportExceptionInterface $e) {
            dump($e->getTraceAsString());
        }
        return $res;
    }

    /**
     * @param $entry
     * @param null $parent
     * @return string
     */
    public function mangeGenericEntry($entry, $parent = null)
    {
        $entity = null;
        if (!empty($entry->nodeName) && !empty($entry->attributes)) {
            $class = "App\\Entity\\" . ucfirst($entry->nodeName);
            if (class_exists($class) && is_subclass_of($class, Entry::class)) {
                $entity = new $class();
            } else {
                $entity = new Entry();
                $entity->setDataType($entry->nodeName);
            }
            if ($entity instanceof Entry) {
                foreach ($entry->attributes as $attribute) {
                    $setter = "set" . ucfirst($attribute->nodeName);
                    if (method_exists($entity, $setter)) {
                        $entity->$setter($attribute->nodeValue);
                    } else {
                        $entity->addProperty($attribute->nodeName, $attribute->nodeValue);
                    }
                }
                if ($entry->nodeName == "constraint"){
                    $entity->addProperty("id", $entity->getId());
                    $entity->setId(uniqid("constraint"));
                }
                if ($parent) {
                    $entity->setParent($parent);
                }
                $entity->setCatalogueId($this->catalogueId);
                if ($entity->getId() == null) {
                    $entity->setId(uniqid('unknown'));
                }
            }
        }
        return $entity;
    }

    /**
     * @param $entry
     * @param string $class
     * @throws ReflectionException
     */
    public function mangeCatalogueEntry($entry, $class = "CategoryEntry")
    {
        $reflection = new ReflectionClass($class);
        if (!empty($entry->nodeName) && ucfirst($entry->nodeName) == $reflection->getShortName() && !empty($entry->attributes)) {
            $entity = new $class();
            foreach ($entry->attributes as $attribute) {
                $setter = "set" . ucfirst($attribute->nodeName);
                if (method_exists($entity, $setter)) {
                    $entity->$setter($attribute->nodeValue);
                } else {
                    $entity->addProperty($attribute->nodeName, $attribute->nodeValue);
                }
            }
            if (!$this->manager->getRepository(get_class($entity))->findOneBy(['id' => $entity->getId()])) {
                $this->catalogueId = $entity->getId();
                $entity->setCatalogueId($this->catalogueId);
                $this->manager->persist($entity);
            }
        }
    }

    public function importMainData(){
        $this->catalogueId = "mainData";
        $data = $this->deserializeLink(self::MAIN_DATA_URL);
        $this->importEntryRecursive($data);
        $this->manager->flush();
    }

    /**
     *
     */
    public function importCatalogue()
    {
        $data = $this->deserializeLink();
        $this->catalogueId = $data[0]->attributes->getNamedItem('id')->value;
        $this->cleanCatalogue();
        $this->importEntryRecursive($data);
        $this->manager->flush();

    }

    protected function cleanCatalogue()
    {
        $this->manager->getRepository(Entry::class)->deleteByCatalogue($this->catalogueId);
    }

    public function importEntryRecursive($data, $parent = null)
    {
        foreach ($data as $datum) {
            $elem = $this->mangeGenericEntry($datum, $parent);
            if (!empty($datum->childNodes)) {
                $this->importEntryRecursive($datum->childNodes, $elem);
            }
            if ($elem instanceof Entry && empty($this->manager->getUnitOfWork()->getIdentityMap()[Entry::class][$elem->getId()])) {
                $this->manager->persist($elem);
            }
        }
    }

    /**
     * @param $data
     * @param $callback
     * @param null $class
     * @return string
     */
    public function recursiveMapping($data, $callback, $class = null)
    {
        foreach ($data as $datum) {
            $this->$callback($datum, $class);
            if (!empty($datum->childNodes)) {
                $this->recursiveMapping($datum->childNodes, $callback, $class);
            }
        }
    }
}