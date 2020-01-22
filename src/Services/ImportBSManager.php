<?php


namespace App\Services;


use App\Entity\CategoryEntry;
use App\Entity\SelectionEntry;
use Doctrine\Common\Persistence\ObjectManager;
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
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
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
     * @param $class
     * @throws ReflectionException
     */
    public function mangeGenericEntry($entry, $class = "CategoryEntry")
    {
        $reflection = new ReflectionClass($class);
        if (!empty($entry->nodeName) && ucfirst($entry->nodeName) == $reflection->getShortName() && !empty($entry->attributes)) {
            $entity = new $class();
            foreach ($entry->attributes as $attribute) {
                $setter = "set" . ucfirst($attribute->nodeName);
                if (method_exists($entity, $setter)) {
                    $entity->$setter($attribute->nodeValue);
                }else{
                    $entity->addProperty($attribute->nodeName, $attribute->nodeValue);
                }
            }
            if (!$this->manager->getRepository(get_class($entity))->findOneBy(['id' => $entity->getId()])){
                $this->manager->persist($entity);
            }
        }
    }

    /**
     *
     */
    public function importCategoryEntries()
    {
        $data = $this->deserializeLink();
        $this->recursiveMapping($data, "mangeGenericEntry", CategoryEntry::class);
        $this->recursiveMapping($data, "mangeGenericEntry", SelectionEntry::class);
        $this->manager->flush();

    }

    /**
     * @param $data
     * @param $callback
     * @param null $class
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