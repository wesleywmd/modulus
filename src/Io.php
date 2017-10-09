<?php
namespace Modulus;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Question\Question;

class Io extends \Symfony\Component\Console\Style\SymfonyStyle
{

    /**
     * @inheritdoc
     */
    public function comment($message,$padding=false)
    {
        $this->block($message, null, "comment", ' ', $padding, false);
    }

    /**
     * @inheritdoc
     */
    public function info($message,$padding=false)
    {
        $this->block($message, null, "info", ' ', $padding, false);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message,$padding=true)
    {
        $this->block($message, 'ERROR', 'error', ' ', $padding);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message,$padding=true)
    {
        $this->block($message, 'WARNING', 'fg=black;bg=yellow', ' ', $padding);
    }

    /**
     * {@inheritdoc}
     */
    public function caution($message,$padding=true)
    {
        $this->block($message, 'CAUTION', 'fg=white;bg=red', ' ! ', $padding);
    }

    /**
     * {@inheritdoc}
     */
    public function ask($question, $default = null, $validator = null, $maxAttempts = null)
    {
        $question = new Question("<question>".$question."</question>", $default);
        $question->setValidator($validator);
        $question->setMaxAttempts($maxAttempts);

        return $this->askQuestion($question);
    }

    /**
     * {@inheritdoc}
     */
    public function title($message)
    {
        $this->writeln(array(
            sprintf('<fg=cyan;options=bold>%s</>', OutputFormatter::escapeTrailingBackslash($message)),
            sprintf('<fg=cyan;options=bold>%s</>', str_repeat('=', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
        ));
        $this->newLine();
    }

}