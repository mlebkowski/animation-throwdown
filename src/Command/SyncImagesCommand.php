<?php

namespace Nassau\CartoonBattle\Command;

use Nassau\CartoonBattle\Entity\Unit;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SyncImagesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('animation-throwdown:sync-images')
            ->addOption('filter', null, InputOption::VALUE_REQUIRED, '', 'card');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $uploader = $this->getContainer()->get('cartoon_battle.s3_client');
        $cdn = $this->getContainer()->get('cartoon_battle.cloudfront_client');
        $bucketName = $this->getContainer()->getParameter('remote_media.cdn.s3.bucket');
        $filterManager = $this->getContainer()->get('liip_imagine.filter.manager');
        $cacheManager = $this->getContainer()->get('liip_imagine.cache.manager');
        $dataManager = $this->getContainer()->get('liip_imagine.data.manager');
        $filterName = $input->getOption('filter');

        /** @var Unit[] $items */
        $items = $em->getRepository('CartoonBattleBundle:Unit')
            ->createQueryBuilder('unit')
            ->where('unit.image is not null')
            ->getQuery()
            ->getResult();

        $invalidationPaths = [];

        foreach ($items as $unit) {
            $path = $unit->getImage()->getUrl();
            $source = parse_url($cacheManager->resolve($path, $filterName), PHP_URL_PATH);

            if ($source === $unit->getImageStorage()->getSourceUrl()) {
                continue;
            }

            if (false === $cacheManager->isStored($path, $filterName)) {
                $output->writeln(sprintf('Storing <comment>%s</comment>', $unit->getName()));
                $binary = $dataManager->find($filterName, $path);
                $cacheManager->store($filterManager->applyFilter($binary, $filterName), $path, $filterName);
            }

            $unit->getImageStorage()->setSourceUrl($source);
            $em->persist($unit);

            $targetPath = $unit->getImageUrl();

            if (false === in_array($targetPath, $invalidationPaths)) {
                $output->writeln(sprintf('Updating <comment>%s</comment> image: <info>%s</info>', $unit->getName(), $targetPath));

                $uploader->copyObject([
                    'Bucket' => $bucketName,
                    'Key' => trim($targetPath, '/'),
                    'CopySource' => "{$bucketName}{$source}",
                    'MetadataDirective' => 'REPLACE',
                    'ContentType' => 'image/png',
                    'CacheControl' => 'public, max-age=283824000',
                    'Expires' => gmdate('D, d M Y H:i:s T', strtotime('+9 years')),
                ]);
            }

            $invalidationPaths[] = $targetPath;

            $em->flush();
        }

        $sizeofInvalidationPaths = sizeof($invalidationPaths);

        if ($sizeofInvalidationPaths) {
            $output->writeln(sprintf('Invalidating <info>%d</info> CDN paths', $sizeofInvalidationPaths));

            $cdn->createInvalidation([
                'DistributionId' => $this->getContainer()->getParameter('cartoon_battle.cdn.distribution_id'),
                'InvalidationBatch' => [
                    'Paths' => [
                        'Quantity' => $sizeofInvalidationPaths,
                        'Items' => $invalidationPaths,
                    ],
                    'CallerReference' => (new \DateTime)->format('Y-m-d H:i:s'),
                ],
            ]);
        }
    }


}
