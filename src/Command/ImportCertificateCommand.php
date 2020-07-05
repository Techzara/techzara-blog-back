<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Command;

use App\Manager\CertificateManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ImportCertificateCommand.
 */
class ImportCertificateCommand extends Command
{
    /** @var string  */
    protected static $defaultName = 'app:import:certificate';

    private CertificateManager $manager;

    /**
     * ImportCertificateCommand constructor.
     *
     * @param CertificateManager $manager
     * @param string|null        $name
     */
    public function __construct(CertificateManager $manager, string $name = null)
    {
        parent::__construct($name);
        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import techzara certificate')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->manager->loadCertificate();

        $io->success('You did it.');

        return 0;
    }
}
