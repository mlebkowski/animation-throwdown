<?php


namespace Nassau\CartoonBattle\Services\Imagine;


use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Liip\ImagineBundle\Model\Binary;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmbedCardLoader implements LoaderInterface
{

    const SUFFIX = '.frame.png';

    private $binary;

    public function __construct($binary)
    {
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
            '%s --javascript-delay 5000 --debug-javascript --width 340 --height 670 --transparent %s - 2>/dev/null',
            escapeshellcmd($this->binary),
            escapeshellarg($url)
        );

//        var_dump($command);
//exit;
        $data = shell_exec($command);

        return new Binary($data, 'image/png', 'png');
    }
}
