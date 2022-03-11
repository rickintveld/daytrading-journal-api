<?php

namespace App\Domain\Command;

use App\Application\Command\CreateUserCommand;
use App\Common\Contracts\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'dtj:user-create';
    protected static $defaultDescription = 'Create a new trader account';

    public function __construct(private CommandBus $commandBus)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'The e-mail adres of the trader')
            ->addArgument('firstname', InputArgument::REQUIRED, 'The firstname of the trader')
            ->addArgument('lastname', InputArgument::REQUIRED, 'The lastname of the trader')
            ->addArgument('capital', InputArgument::REQUIRED, 'The start capital of the trader')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the traders account');
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $questions = [];

        if (!$input->getArgument('email')) {
            $questions['email'] = $this->provideEmailQuestion();
        }

        if (!$input->getArgument('firstname')) {
            $questions['firstname'] = $this->provideFirstnameQuestion();
        }

        if (!$input->getArgument('lastname')) {
            $questions['lastname'] = $this->provideLastnameQuestion();
        }

        if (!$input->getArgument('capital')) {
            $questions['capital'] = $this->provideCapitalQuestion();
        }

        if (!$input->getArgument('password')) {
            $questions['password'] = $this->providePasswordQuestion();
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
        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');
        $capital = (int)$input->getArgument('capital');
        $password = $input->getArgument('password');

        $io->info(sprintf('Creating a new trader with e-mail address: %s', $email));
        $this->commandBus->dispatch(new CreateUserCommand($email, $firstname, $lastname, $capital, $password));

        $io->success(sprintf('Finished creating an account for trader %s %s', $firstname, $lastname));

        return Command::SUCCESS;
    }

    private function provideEmailQuestion(): Question
    {
        $question = new Question('Please provide an e-mail address: ');
        $question->setValidator(function ($email) {
            if (empty($email)) {
                throw new \InvalidArgumentException('E-mail address can not be empty');
            }

            return $email;
        });

        return $question;
    }

    private function provideFirstnameQuestion(): Question
    {
        $question = new Question('Please provide the firstname: ');
        $question->setValidator(function ($firstname) {
            if (empty($firstname)) {
                throw new \InvalidArgumentException('Firstname can not be empty');
            }

            return $firstname;
        });

        return $question;
    }

    private function provideLastnameQuestion(): Question
    {
        $question = new Question('Please provide the lastname: ');
        $question->setValidator(function ($lastname) {
            if (empty($lastname)) {
                throw new \InvalidArgumentException('Lastname can not be empty');
            }

            return $lastname;
        });

        return $question;
    }

    private function provideCapitalQuestion(): Question
    {
        $question = new Question('Please provide the capital: ');
        $question->setValidator(function ($capital) {
            if (empty($capital)) {
                throw new \InvalidArgumentException('Capital can not be empty');
            }

            return $capital;
        });

        return $question;
    }

    private function providePasswordQuestion(): Question
    {
        $question = new Question('Please provide a password: ');
        $question->setHidden(true);
        $question->setValidator(function ($password) {
            if (empty($password)) {
                throw new \InvalidArgumentException('The password can not be empty');
            }

            return $password;
        });

        return $question;
    }
}
