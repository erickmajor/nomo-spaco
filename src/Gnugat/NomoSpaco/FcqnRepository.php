<?php

namespace Gnugat\NomoSpaco;

use Gnugat\NomoSpaco\Composer\AutoloadRepository;
use Gnugat\NomoSpaco\Composer\FileRepository;
use Exception;
use ReflectionClass;

/**
 * Retrieves a list of fully qualified classnames.
 *
 * @api
 */
class FcqnRepository
{
    /**
     * @var AutoloadRepository
     */
    private $autoloadRepository;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @param AutoloadRepository $autoloadRepository
     * @param FileRepository     $fileRepository
     */
    public function __construct(
        AutoloadRepository $autoloadRepository,
        FileRepository $fileRepository
    )
    {
        $this->autoloadRepository = $autoloadRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param string $projectRoot
     *
     * @return array
     *
     * @api
     */
    public function findAll($projectRoot)
    {
        $paths = $this->autoloadRepository->findAll($projectRoot);
        $files = $this->fileRepository->findPhp($paths);
        $fcqns = array();
        foreach ($files as $file) {
            $fcqns[] = $file->getNamespace().'\\'.$file->getClassname();
        }

        return $fcqns;
    }

    /**
     * @param string $projectRoot
     * @param string $classname
     *
     * @return array
     *
     * @api
     */
    public function findOne($projectRoot, $classname)
    {
        $paths = $this->autoloadRepository->findAll($projectRoot);
        $files = $this->fileRepository->findPhp($paths);
        $fcqns = array();
        foreach ($files as $file) {
            if ($classname !== $file->getClassname()) {
                continue;
            }
            $fcqns[] = $file->getNamespace().'\\'.$classname;
        }

        return $fcqns;
    }
}
