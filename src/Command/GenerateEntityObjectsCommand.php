<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateEntityObjectsCommand extends Command
{
    const COMMAND_NAME = 'sandbox:generate-entities';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(self::COMMAND_NAME);
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Generate entities');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $categories = $this->getCategories();
        $io->info('Categories ready');

        $tags = $this->getTags();
        $tagsChunked = array_chunk($tags, 10);
        $io->info('Tags ready');

        $io->info('Starting import...');

        $progressBar = new ProgressBar($output, 100000);

        for ($i = 1; $i <= 100000; $i++) {
            $article = new Article();
            $article->setTitle(sprintf('Artículo %d', $i));

            $randomCategory = $categories[rand(0, 9)];
            $article->setCategory($randomCategory);

            $randomTags = $tagsChunked[rand(0, 9)];
            foreach ($randomTags as $tag) {
                $article->addTag($tag);
            }

            $this->entityManager->persist($article);

            $progressBar->advance();
            if (($i % 100) === 0) {
                $this->entityManager->flush();
            }
        }

        $this->entityManager->flush();
        $progressBar->finish();

        $io->success('Entities generated!');

        return Command::SUCCESS;
    }

    private function getCategories(): array
    {
        $categories = [];

        for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $category->setName(sprintf('Categoría %d', $i));
            $categories[] = $category;
        }

        return $categories;
    }

    private function getTags(): array
    {
        $tags = [];

        for ($i = 1; $i <= 100; $i++) {
            $tag = new Tag();
            $tag->setName(sprintf('Etiqueta %d', $i));
            $tags[] = $tag;
        }

        return $tags;
    }
}
