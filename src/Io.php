<?php
namespace Modulus;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Terminal;

class Io extends OutputStyle implements StyleInterface
{
    const MAX_LINE_LENGTH = 120;

    private $input;
    private $questionHelper;
    private $progressBar;
    private $lineLength;
    private $bufferedOutput;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->bufferedOutput = new BufferedOutput($output->getVerbosity(), false, clone $output->getFormatter());
        // Windows cmd wraps lines as soon as the terminal width is reached, whether there are following chars or not.
        $width = (new Terminal())->getWidth() ?: self::MAX_LINE_LENGTH;
        $this->lineLength = min($width - (int) (DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);

        parent::__construct($output);
    }

    /**
     * Formats a message as a block of text.
     *
     * @param string|array $messages The message to write in the block
     * @param string|null  $type     The block type (added in [] on first line)
     * @param string|null  $style    The style to apply to the whole block
     * @param string       $prefix   The prefix for the block
     * @param bool         $padding  Whether to add vertical padding
     * @param bool         $escape   Whether to escape the message
     */
    public function block($messages, $type = null, $style = null, $prefix = ' ', $padding = false, $escape = true)
    {
        $messages = is_array($messages) ? array_values($messages) : array($messages);

        $this->autoPrependBlock();
        $this->writeln($this->createBlock($messages, $type, $style, $prefix, $padding, $escape));
        $this->newLine();
    }

    /**
     * Formats a command title.
     *
     * @param string $message
     */
    public function title($message)
    {
        $this->autoPrependBlock();
        $this->writeln(array(
            sprintf('<fg=cyan;options=bold>  %s</>', OutputFormatter::escapeTrailingBackslash($message)),
            sprintf('<fg=cyan;options=bold>  %s</>', str_repeat('=', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
        ));
        $this->newLine();
    }

    /**
     * Formats a section title.
     *
     * @param string $message
     */
    public function section($message)
    {
        $this->autoPrependBlock();
        $this->writeln(array(
            sprintf('<fg=magenta>  %s</>', OutputFormatter::escapeTrailingBackslash($message)),
            sprintf('<fg=magenta>  %s</>', str_repeat('-', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
        ));
        $this->newLine();
    }

    /**
     * Formats a list.
     *
     * @param array $elements
     */
    public function listing(array $elements)
    {
        $this->autoPrependText();
        $elements = array_map(function ($element) {
            return sprintf(' - %s', $element);
        }, $elements);

        $this->writeln($elements);
        $this->newLine();
    }

    /**
     * Formats informational text.
     *
     * @param string|array $message
     */
    public function text($message)
    {
        $this->autoPrependText();

        $messages = is_array($message) ? array_values($message) : array($message);
        foreach ($messages as $message) {
            $this->writeln(sprintf(' %s', $message));
        }
    }

    /**
     * Formats a command comment.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function comment($message,$padding=false)
    {
        $this->block($message, null, 'comment', ' ', $padding, false);
    }

    /**
     * Formats a success result bar.
     *
     * @param string|array $message
     */
    public function success($message,$padding=true)
    {
        $this->block($message, 'OK', 'fg=black;bg=green', ' ', $padding);
    }

    /**
     * Formats an error result bar.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function error($message,$padding=true)
    {
        $this->block($message, 'ERROR', 'error', ' ', $padding);
    }

    /**
     * Formats an warning result bar.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function warning($message,$padding=true)
    {
        $this->block($message, 'WARNING', 'fg=black;bg=yellow', ' ', $padding);
    }

    /**
     * Formats a note admonition.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function note($message,$padding=false)
    {
        $this->block($message, 'NOTE', 'fg=yellow', ' ! ',$padding);

    }

    /**
     * Formats a caution admonition.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function caution($message,$padding=true)
    {
        $this->block($message, 'CAUTION', 'fg=white;bg=red', ' ! ', $padding);
    }

    /**
     * Formats a info admonition.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function info($message,$padding=false)
    {
        $this->block($message, null, "info", ' ', $padding, false);
    }

    /**
     * Formats a table.
     *
     * @param array $headers
     * @param array $rows
     */
    public function table(array $headers, array $rows)
    {
        $style = clone Table::getStyleDefinition('symfony-style-guide');
        $style->setCellHeaderFormat('<info>%s</info>');

        $table = new Table($this);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->setStyle($style);

        $table->render();
        $this->newLine();
    }

    /**
     * Ask for an missing argument.
     *
     * @param string        $argument
     * @param string        $question
     * @param string|null   $default
     * @param callable|null $validator
     * @param int|null      $maxAttempts
     * @param bool          $comment
     * @param string        $commentFormat
     */
    public function askForMissingArgument($argument, $question, $default = null, $validator = null, $maxAttempts = null, $comment = null, $commentFormat = "Argument [%s] set to: %s")
    {
        if( is_null($this->input->getArgument($argument)) ) {
            $this->input->setArgument($argument, $this->ask($question, $default, $validator, $maxAttempts) );
        } elseif( (bool) ( is_null($comment) ? $this->isDebug() : $comment ) ) {
            try {
                if( is_callable($validator) ) {
                    $this->comment( sprintf($commentFormat, $argument, $validator($this->input->getArgument($argument))) );
                } else {
                    $this->comment( sprintf($commentFormat, $argument, $this->input->getArgument($argument)) );
                }
            } catch( RuntimeException $e ) {
                $this->error("Validation Error: ".$e->getMessage());
            }
        }
    }

    /**
     * Ask for an missing option.
     *
     * @param string        $option
     * @param string        $question
     * @param string|null   $default
     * @param callable|null $validator
     * @param int|null      $maxAttempts
     * @param bool          $comment
     * @param string        $commentFormat
     */
    public function askForMissingOption($option, $question, $default = null, $validator = null, $maxAttempts = null, $comment = null, $commentFormat = "Option [%s] set to: %s")
    {
        if( is_null($this->input->getOption($option)) ) {
            $this->input->setOption($option, $this->ask($question, $default, $validator, $maxAttempts) );
        } elseif( (bool) ( is_null($comment) ? $this->isDebug() : $comment ) ) {
            try {
                if( is_callable($validator) ) {
                    $this->comment( sprintf($commentFormat, $option, $validator($this->input->getOption($option))) );
                } else {
                    $this->comment( sprintf($commentFormat, $option, $this->input->getOption($option)) );
                }
            } catch( RuntimeException $e ) {
                $this->error("Validation Error: ".$e->getMessage());
            }
        }
    }

    /**
     * Asks a question.
     *
     * @param string        $question
     * @param string|null   $default
     * @param callable|null $validator
     * @param int|null      $maxAttempts
     *
     * @return string
     */
    public function ask($question, $default = null, $validator = null, $maxAttempts = null)
    {
        $question = new Question("<question>".$question."</question>", $default);
        $question->setValidator($validator);
        $question->setMaxAttempts($maxAttempts);

        return $this->askQuestion($question);
    }

    /**
     * Asks a question with the user input hidden.
     *
     * @param string        $question
     * @param callable|null $validator
     * @param int|null      $maxAttempts
     *
     * @return string
     */
    public function askHidden($question, $validator = null, $maxAttempts = null)
    {
        $question = new Question($question);

        $question->setHidden(true);
        $question->setValidator($validator);
        $question->setMaxAttempts($maxAttempts);

        return $this->askQuestion($question);
    }

    /**
     * Asks for confirmation.
     *
     * @param string $question
     * @param bool   $default
     *
     * @return bool
     */
    public function confirm($question, $default = true)
    {
        return $this->askQuestion(new ConfirmationQuestion("<question>".$question."</question>", $default));
    }

    /**
     * Asks a choice question.
     *
     * @param string          $question
     * @param array           $choices
     * @param string|int|null $default
     *
     * @return string
     */
    public function choice($question, array $choices, $default = null)
    {
        if (null !== $default) {
            $values = array_flip($choices);
            $default = $values[$default];
        }

        return $this->askQuestion(new ChoiceQuestion("<question>".$question."</question>", $choices, $default));
    }

    /**
     * Add newline(s).
     *
     * @param int $count The number of newlines
     */
    public function newLine($count = 1)
    {
        parent::newLine($count);
        $this->bufferedOutput->write(str_repeat("\n", $count));
    }

    /**
     * Starts the progress output.
     *
     * @param int $max Maximum steps (0 if unknown)
     */
    public function progressStart($max = 0)
    {
        $this->progressBar = $this->createProgressBar($max);
        $this->progressBar->start();
    }

    /**
     * Advances the progress output X steps.
     *
     * @param int $step Number of steps to advance
     */
    public function progressAdvance($step = 1)
    {
        $this->getProgressBar()->advance($step);
    }

    /**
     * Finishes the progress output.
     */
    public function progressFinish()
    {
        $this->getProgressBar()->finish();
        $this->newLine(2);
        $this->progressBar = null;
    }

    /**
     * @param Question $question
     *
     * @return string
     */
    public function askQuestion(Question $question)
    {
        if ($this->input->isInteractive()) {
            $this->autoPrependBlock();
        }

        if (!$this->questionHelper) {
            $this->questionHelper = new SymfonyQuestionHelper();
        }

        $answer = $this->questionHelper->ask($this->input, $this, $question);

        if ($this->input->isInteractive()) {
            $this->newLine();
            $this->bufferedOutput->write("\n");
        }

        return $answer;
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($messages, $type = self::OUTPUT_NORMAL)
    {
        parent::writeln($messages, $type);
        $this->bufferedOutput->writeln($this->reduceBuffer($messages), $type);
    }

    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL)
    {
        parent::write($messages, $newline, $type);
        $this->bufferedOutput->write($this->reduceBuffer($messages), $newline, $type);
    }

    /**
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    private function getProgressBar()
    {
        if (!$this->progressBar) {
            throw new RuntimeException('The ProgressBar is not started.');
        }

        return $this->progressBar;
    }

    private function autoPrependBlock()
    {
        $chars = substr(str_replace(PHP_EOL, "\n", $this->bufferedOutput->fetch()), -2);

        if (!isset($chars[0])) {
            return $this->newLine(); //empty history, so we should start with a new line.
        }
        //Prepend new line for each non LF chars (This means no blank line was output before)
        $this->newLine(2 - substr_count($chars, "\n"));
    }

    private function autoPrependText()
    {
        $fetched = $this->bufferedOutput->fetch();
        //Prepend new line if last char isn't EOL:
        if ("\n" !== substr($fetched, -1)) {
            $this->newLine();
        }
    }

    private function reduceBuffer($messages)
    {
        // We need to know if the two last chars are PHP_EOL
        // Preserve the last 4 chars inserted (PHP_EOL on windows is two chars) in the history buffer
        return array_map(function ($value) {
            return substr($value, -4);
        }, array_merge(array($this->bufferedOutput->fetch()), (array) $messages));
    }

    private function createBlock($messages, $type = null, $style = null, $prefix = ' ', $padding = false, $escape = false)
    {
        $indentLength = 0;
        $prefixLength = Helper::strlenWithoutDecoration($this->getFormatter(), $prefix);
        $lines = array();
        $lineIndentation = "";

        if (null !== $type) {
            $type = sprintf('[%s] ', $type);
            $indentLength = strlen($type);
            $lineIndentation = str_repeat(' ', $indentLength);
        }

        // wrap and add newlines for each element
        foreach ($messages as $key => $message) {
            if ($escape) {
                $message = OutputFormatter::escape($message);
            }

            $lines = array_merge($lines, explode(PHP_EOL, wordwrap($message, $this->lineLength - $prefixLength - $indentLength, PHP_EOL, true)));

            if (count($messages) > 1 && $key < count($messages) - 1) {
                $lines[] = '';
            }
        }

        $firstLineIndex = 0;
        if ($padding && $this->isDecorated()) {
            $firstLineIndex = 1;
            array_unshift($lines, '');
            $lines[] = '';
        }

        foreach ($lines as $i => &$line) {
            if (null !== $type) {
                $line = $firstLineIndex === $i ? $type.$line : $lineIndentation.$line;
            }

            $line = $prefix.$line;
            $line .= str_repeat(' ', $this->lineLength - Helper::strlenWithoutDecoration($this->getFormatter(), $line));

            if ($style) {
                $line = sprintf('<%s>%s</>', $style, $line);
            }
        }

        return $lines;
    }
}