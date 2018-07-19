<?php


namespace Nassau\CartoonBattle\Services\Imagine;


use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Liip\ImagineBundle\Model\Binary;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmbedCardLoader implements LoaderInterface
{

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
        $data = json_decode(urldecode($path), true) ?: json_decode(base64_decode(urldecode($path)), true);

        if (false === isset($data['embed'])) {
            throw new NotFoundHttpException;
        }

        $url = 'https://cartoon-battle.cards/screenshot?' . $data['embed'];

        $command = sprintf(
            '%s --javascript-delay 5000 --debug-javascript --width 340 --height 670 --transparent %s - 2>/dev/null',
            escapeshellcmd($this->binary),
            escapeshellarg($url)
        );

        $data = shell_exec($command);

        return new Binary($data, 'image/png', 'png');
    }
}
