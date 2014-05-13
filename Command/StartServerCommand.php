<?php

namespace Leobenoist\SocketBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Symfony command configuration
*
* @author    Leo Benoist <leo.benoist@gmail.com>
* @copyright 2014 Leo Benoist
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
**/
class StartServerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('leobenoist:socket:start')
            ->setDescription('Start NodeJS and Socket.IO server.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Not Implemented Yet");
    }

}
