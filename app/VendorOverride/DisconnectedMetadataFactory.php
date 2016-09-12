<?php

namespace Doctrine\Bundle\DoctrineBundle\Mapping;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * This class provides methods to access Doctrine entity class metadata for a
 * given bundle, namespace or entity class, for generation purposes
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DisconnectedMetadataFactory
{
    private $registry;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry A ManagerRegistry instance
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Gets the metadata of all classes of a bundle.
     *
     * @param BundleInterface $bundle A BundleInterface instance
     *
     * @return ClassMetadataCollection A ClassMetadataCollection instance
     *
     * @throws \RuntimeException When bundle does not contain mapped entities
     */
    public function getBundleMetadata(BundleInterface $bundle)
    {
        $namespace = $bundle->getNamespace();
        $metadata = $this->getMetadataForNamespace($namespace);
        if (!$metadata->getMetadata()) {
            throw new \RuntimeException(sprintf('Bundle "%s" does not contain any mapped entities.',
                $bundle->getName()));
        }

        $path = $this->getBasePathForClass($bundle->getName(), $bundle->getNamespace(), $bundle->getPath());

        $metadata->setPath($path);
        $metadata->setNamespace($bundle->getNamespace());

        return $metadata;
    }

    /**
     * @param string $namespace
     *
     * @return ClassMetadataCollection
     */
    private function getMetadataForNamespace($namespace)
    {
        $metadata = [];
        foreach ($this->getAllMetadata() as $m) {
            if (strpos($m->name, $namespace) === 0) {
                $metadata[] = $m;
            }
        }

        return new ClassMetadataCollection($metadata);
    }

    /**
     * @return array
     */
    private function getAllMetadata()
    {
        $metadata = [];
        foreach ($this->registry->getManagers() as $em) {
            $cmf = new DisconnectedClassMetadataFactory();
            $cmf->setEntityManager($em);
            foreach ($cmf->getAllMetadata() as $m) {
                $metadata[] = $m;
            }
        }

        return $metadata;
    }

    /**
     * Gets the metadata of a class.
     *
     * @param string $class A class name
     * @param string $path  The path where the class is stored (if known)
     *
     * @return ClassMetadataCollection A ClassMetadataCollection instance
     *
     * @throws MappingException When class is not valid entity or mapped superclass
     */
    public function getClassMetadata($class, $path = null)
    {
        $metadata = $this->getMetadataForClass($class);
        if (!$metadata->getMetadata()) {
            throw MappingException::classIsNotAValidEntityOrMappedSuperClass($class);
        }

        $this->findNamespaceAndPathForMetadata($metadata, $path);

        return $metadata;
    }

    /**
     * @param string $entity
     *
     * @return ClassMetadataCollection
     */
    private function getMetadataForClass($entity)
    {
        foreach ($this->registry->getManagers() as $em) {
            $cmf = new DisconnectedClassMetadataFactory();
            $cmf->setEntityManager($em);

            if (!$cmf->isTransient($entity)) {
                return new ClassMetadataCollection([$cmf->getMetadataFor($entity)]);
            }
        }

        return new ClassMetadataCollection([]);
    }

    /**
     * Find and configure path and namespace for the metadata collection.
     *
     * @param ClassMetadataCollection $metadata
     * @param string|null             $path
     *
     * @throws \RuntimeException When unable to determine the path
     */
    public function findNamespaceAndPathForMetadata(ClassMetadataCollection $metadata, $path = null)
    {
        $all = $metadata->getMetadata();
        if (class_exists($all[0]->name)) {
            $r = new \ReflectionClass($all[0]->name);
            $path = $this->getBasePathForClass($r->getName(), $r->getNamespaceName(), dirname($r->getFilename()));
            $ns = $r->getNamespaceName();

        } elseif ($path) {
            // Get namespace by removing the last component of the FQCN
            $nsParts = explode('\\', $all[0]->name);
            array_pop($nsParts);
            $ns = implode('\\', $nsParts);

        } else {
            throw new \RuntimeException(sprintf('Unable to determine where to save the "%s" class (use the --path option).',
                $all[0]->name));
        }

        $metadata->setPath($path);
        $metadata->setNamespace($ns);
    }

    /**
     * Gets the metadata of all classes of a namespace.
     *
     * @param string $namespace A namespace name
     * @param string $path      The path where the class is stored (if known)
     *
     * @return ClassMetadataCollection A ClassMetadataCollection instance
     *
     * @throws \RuntimeException When namespace not contain mapped entities
     */
    public function getNamespaceMetadata($namespace, $path = null)
    {
        $metadata = $this->getMetadataForNamespace($namespace);
        if (!$metadata->getMetadata()) {
            throw new \RuntimeException(sprintf('Namespace "%s" does not contain any mapped entities.', $namespace));
        }

        $this->findNamespaceAndPathForMetadata($metadata, $path);

        return $metadata;
    }

    /**
     * Get a base path for a class
     *
     * @param string $name      class name
     * @param string $namespace class namespace
     * @param string $path      class path
     *
     * @return string
     * @throws \RuntimeException When base path not found
     */
    private function getBasePathForClass($name, $namespace, $path)
    {
        $composerClassLoader = $this->getComposerClassLoader();
        if ($composerClassLoader !== null) {
            $psr4Paths = $this->findPathsByPsr4Prefix($namespace, $composerClassLoader);
            if ($psr4Paths !== []) {
                // We just use the first path for now
                return $psr4Paths[0];
            }
        }

        $namespace = str_replace('\\', '/', $namespace);
        $search = str_replace('\\', '/', $path);
        $destination = str_replace('/' . $namespace, '', $search, $c);

        if ($c != 1) {
            throw new \RuntimeException(sprintf('Can\'t find base path for "%s" (path: "%s", destination: "%s").',
                $name, $path, $destination));
        }

        return $destination;
    }

    /**
     * Gets the composer class loader from the list of registered autoloaders
     *
     * @return \Composer\Autoload\ClassLoader
     */
    private function getComposerClassLoader()
    {
        $activeAutloaders = spl_autoload_functions();
        foreach ($activeAutloaders as $autoloaderFunction) {
            if (!is_array($autoloaderFunction)) {
                continue;
            }

            $classLoader = $autoloaderFunction[0];
            if ($classLoader instanceof \Symfony\Component\Debug\DebugClassLoader) {
                $classLoader = $classLoader->getClassLoader()[0];
            }

            if (!is_object($classLoader)) {
                continue;
            }

            if ($classLoader instanceof \Composer\Autoload\ClassLoader) {
                return $classLoader;
            }
        }

        return null;
    }

    /**
     * Matches the namespace against all registered psr4 prefixes and
     * returns their mapped paths if found
     *
     * @param string                         $namespace           The full namespace to search for
     * @param \Composer\Autoload\ClassLoader $composerClassLoader A composer class loader instance to get the list of
     *                                                            psr4 preixes from
     *
     * @return array The found paths for the namespace or an empty array if none matched
     */
    private function findPathsByPsr4Prefix($namespace, $composerClassLoader)
    {
        foreach ($composerClassLoader->getPrefixesPsr4() as $prefix => $paths) {
            if (strpos($namespace, $prefix) === 0) {
                return $paths;
            }
        }

        return [];
    }
}
