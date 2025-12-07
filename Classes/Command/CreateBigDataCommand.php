<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Command;

use Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TWOH\TwohMongodbDriver\Adapter\MongodbConnectionPoolAdapter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CreateBigDataCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected function configure(): void
    {
        $this->setHelp('A command to create big test data.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $testDataUserNumber = 1000000;
        $testDataRecordPerUser = 5;

        $output->writeln('Start Import Command!');
        $output->writeln('Create Connection');
        $mongodbConnectionPoolAdapter = GeneralUtility::makeInstance(MongodbConnectionPoolAdapter::class);

        try {
            $output->writeln('Create fake User page clicks!');
            $userInteraction = [];
            for ($j = 0;$j <= $testDataRecordPerUser;$j++) {
                $userInteraction[] = 'Page' . $j;
            }

            $userCollection = $mongodbConnectionPoolAdapter->getConnectionPool()->createCollection('user', []);

            $output->writeln('Create user Collection!');
            $output->writeln($userCollection);

            for ($i = 0;$i <= $testDataUserNumber;$i++) {
                $uuid = 'user' . $i;
                $user = [
                    'username' => 'admin' . $i,
                    'email' => 'admin@example' . $i . '.com',
                    'name' => 'Admin User' . $i,
                    'uuid' => $uuid,
                    'pageInteractions' => $userInteraction,
                    'age' => 33,
                    'isActive' => true,
                ];
                $mongodbConnectionPoolAdapter->getConnectionPool()->insertOneDocument('user', $user);

                $output->writeln('User Profile and Interactions for uuid ' . $uuid . ' successfully saved!');
            }
        } catch (Exception $e) {
            // @extensionScannerIgnoreLine
            $this->logger->error(
                'Exception: ' . $e->getMessage(),
            );
        }

        return Command::SUCCESS;
    }
}
