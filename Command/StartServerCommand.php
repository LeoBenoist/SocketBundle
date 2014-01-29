<?php

namespace Leobenoist\SocketBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 *
 * @author Léo Benoist leo@benoi.st
 */
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
//        $this->process = new Process('node /Users/leobenoist/www/leobenoist.local/Leobenoist/src/Leobenoist/SocketBundle/server/server.js', null, array('/usr/local/bin/'));
//
        $output->writeln("Not Implemented Yet");
    }

}

