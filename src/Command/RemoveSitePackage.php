<?php


namespace App\Command;

use App\Api\ApiProvider;
use App\Repository\SitePackageRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RemoveSitePackage
 * @package App\Command
 */
class RemoveSitePackage extends Command
{
    // the name of the command (the part after "bin/console")
    /**
     * @var string
     */
    protected static $defaultName = 'app:remove-old-site-package-extension';


    /**
     * @var SitePackageRepository
     */
    private $sitePackageRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * RemoveSitePackage constructor.
     * @param SitePackageRepository $sitePackageRepository
     * @param ContainerInterface $container
     */
    public function __construct(SitePackageRepository $sitePackageRepository, ContainerInterface  $container)
    {
        parent::__construct();
        $this->sitePackageRepository = $sitePackageRepository;
        $this->container = $container;
    }


    /**
     *
     */
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Remove old Site Package extension')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you remove old Site Package extension (3 days)..')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $origin = new \DateTime('now');
        $sitePackages = $this->sitePackageRepository->findAll();

        foreach($sitePackages as $sitePackage){
            $target = $sitePackage->getCreated();
            $interval = $origin->diff($target);
            $days = intval( $interval->format('%R%a'));
            $numberDays =  abs($days);
            if($numberDays >= 3){

                $fileName = basename($sitePackage->getPath());
                // remove file
                $filesystem = new Filesystem();
                $currentDirPath = getcwd();
                $filesystem->remove(['symlink', $currentDirPath.$sitePackage->getPath(),$fileName]);
                $entityManager = $this->container->get('doctrine')->getManager();
                $entityManager->remove($sitePackage);
                $entityManager->flush();
            }
        }

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}