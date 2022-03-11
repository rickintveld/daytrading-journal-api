<?php

namespace App\Domain\Command;

use App\Application\Command\AddProfitCommand;
use App\Common\Contracts\CommandBus;
use App\Common\Exception\UserNotFoundException;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Security\Provider\LoginProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProfitAddCommand extends Command
{
    protected static $defaultName = 'dtj:profit-add';
    protected static $defaultDescription = 'Add new profit';

    public function __construct(
        private CommandBus $commandBus,
        private EntityManagerInterface $entityManager,
        private LoginProvider $loginProvider
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'The e-mail address')
             ->addArgument('password', InputArgument::REQUIRED, 'The password')
             ->addArgument('profit', InputArgument::REQUIRED, 'The profit of today');
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $questions = [];

        if (!$input->getArgument('email')) {
            $questions['email'] = $this->provideEmailQuestion();
        }

        if (!$input->getArgument('password')) {
            $questions['password'] = $this->providePasswordQuestion();
        }

        if (!$input->getArgument('profit')) {
            $questions['profit'] = $this->provideProfitQuestion();
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $profit = $input->getArgument('profit');

        try {
            /** @var User $user */
            $user = $this->entityManager->getRepository(User::class)->findByEmailAndPassword($email, $password);
        } catch (UserNotFoundException $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        } catch (NoResultException|NonUniqueResultException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $this->loginProvider->login($user);

        if ($this->loginProvider->getToken()->isAuthenticated()) {
            $io->error('User is not authorized');

            return Command::FAILURE;
        }

        $this->commandBus->dispatch(new AddProfitCommand($user->getUserIdentifier(), $profit));
        $this->loginProvider->logout();

        $io->success(sprintf('Successful added %s to your account', $profit));

        return Command::SUCCESS;
    }

    private function provideEmailQuestion(): Question
    {
        $question = new Question('Please provide your e-mail address: ');
        $question->setValidator(function ($email) {
            if (empty($email)) {
                throw new \InvalidArgumentException('The e-mail address can not be empty');
            }

            return $email;
        });

        return $question;
    }

    private function providePasswordQuestion(): Question
    {
        $question = new Question('Please provide your password: ');
        $question->setHidden(true);
        $question->setValidator(function ($password) {
            if (empty($password)) {
                throw new \InvalidArgumentException('The password can not be empty');
            }

            return $password;
        });

        return $question;
    }

    private function provideProfitQuestion(): Question
    {
        $question = new Question('Please provide the profit you made: ');
        $question->setValidator(function ($profit) {
            if (empty($profit)) {
                throw new \InvalidArgumentException('The profit can not be empty');
            }

            return $profit;
        });

        return $question;
    }
}
