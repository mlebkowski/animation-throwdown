<?php


namespace Nassau\CartoonBattle\Services\Imagine;


use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Liip\ImagineBundle\Model\Binary;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class EmbedCardLoader implements LoaderInterface
{

    const SUFFIX = '.frame.png';

    private $rootDir;

    private $binary;

    public function __construct($rootDir, $binary)
    {
        $this->rootDir = $rootDir;
        $this->binary = $binary;
    }


    /**
     * Retrieve the Image represented by the given path.
     *
     * The path may be a file path on a filesystem, or any unique identifier among the storage engine implemented by this Loader.
     *
     * @param mixed $path
     *
     * @return \Liip\ImagineBundle\Binary\BinaryInterface|string An image binary content
     */
    public function find($path)
    {
        $path = urldecode($path);
        if (substr($path, - strlen(self::SUFFIX)) !== self::SUFFIX) {
            throw new NotFoundHttpException;
        }

        $url = 'https://cartoon-battle.cards/screenshot?' . substr($path, 0, -strlen(self::SUFFIX));

        $command = sprintf(
            '%s --javascript-delay 5000 --debug-javascript --width 8000 --height 670 --transparent --format png %s - 2>/dev/null',
            escapeshellcmd($this->binary),
            escapeshellarg($url)
        );

        $process = new Process($command, sprintf('%s/../', $this->rootDir));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $data = $process->getOutput();

        return new Binary($data, 'image/png', 'png');
    }
}
