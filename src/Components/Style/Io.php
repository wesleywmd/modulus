<?php
namespace Modulus\Components\Style;

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
use Symfony\Component\Console\Terminal;

/**
 * Class Io
 * @package Modulus\Components\Style
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
class Io extends OutputStyle implements IoInterface
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
     * {@inheritdoc}
     */
    public function block($messages, $type = null, $style = null, $prefix = ' ', $padding = false, $escape = true)
    {
        $messages = is_array($messages) ? array_values($messages) : array($messages);

        $this->autoPrependBlock();
        $this->writeln($this->createBlock($messages, $type, $style, $prefix, $padding, $escape));
        $this->newLine();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function comment($message,$padding=false)
    {
        $this->block($message, null, 'comment', ' ', $padding, false);
    }

    /**
     * {@inheritdoc}
     */
    public function success($message,$padding=true)
    {
        $this->block($message, 'OK', 'fg=black;bg=green', ' ', $padding);
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
    public function note($message,$padding=false)
    {
        $this->block($message, 'NOTE', 'fg=yellow', ' ! ',$padding);

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
    public function info($message,$padding=false)
    {
        $this->block($message, null, "info", ' ', $padding, false);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function askForMissingArgument(
        $argument,
        $question,
        $default = null,
        $validator = null,
        $maxAttempts = null,
        $comment = false,
        $commentFormat = "Argument [%s] set to: %s"
    )
    {
        $argumentValue = $this->input->getArgument($argument);
        $answer = $this->askForMissingValue($argumentValue, $question, $default, $validator, $maxAttempts);
        $this->input->setArgument($argument, $answer);
        if( $comment ) {
            $this->comment(sprintf($commentFormat, $argument, $answer));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function askForMissingOption(
        $option,
        $question,
        $default = null,
        $validator = null,
        $maxAttempts = null,
        $comment = false,
        $commentFormat = "Option [%s] set to: %s"
    ) {
        $optionValue = $this->input->getOption($option);
        $answer = $this->askForMissingValue($optionValue, $question, $default, $validator, $maxAttempts);
        $this->input->setOption($option, $answer);
        if( $comment ) {
            $this->comment(sprintf($commentFormat, $option, $answer));
        }
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
    public function askHidden($question, $validator = null, $maxAttempts = null)
    {
        $question = new Question($question);

        $question->setHidden(true);
        $question->setValidator($validator);
        $question->setMaxAttempts($maxAttempts);

        return $this->askQuestion($question);
    }

    /**
     * {@inheritdoc}
     */
    public function confirm($question, $default = true)
    {
        return $this->askQuestion(new ConfirmationQuestion("<question>".$question."</question>", $default));
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function newLine($count = 1)
    {
        parent::newLine($count);
        $this->bufferedOutput->write(str_repeat("\n", $count));
    }

    /**
     * {@inheritdoc}
     */
    public function progressStart($max = 0)
    {
        $this->progressBar = $this->createProgressBar($max);
        $this->progressBar->start();
    }

    /**
     * {@inheritdoc}
     */
    public function progressAdvance($step = 1)
    {
        $this->getProgressBar()->advance($step);
    }

    /**
     * {@inheritdoc}
     */
    public function progressFinish()
    {
        $this->getProgressBar()->finish();
        $this->newLine(2);
        $this->progressBar = null;
    }

    /**
     * {@inheritdoc}
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
     * Asks for missing value
     * @param $value
     * @param $question
     * @param $default
     * @param $validator
     * @param $maxAttempts
     *
     * @return string
     */
    private function askForMissingValue($value, $question, $default, $validator, $maxAttempts)
    {
        if( is_null($value) ) {
            return $this->ask($question, $default, $validator, $maxAttempts);
        } else {
            if( is_callable($validator) ) {
                try {
                    return $validator($value);
                } catch( RuntimeException $e ) {
                    return $this->ask($question, $default, $validator, $maxAttempts);
                }
            } else {
                throw \Exception("Validator must be a callable or null");
            }
        }
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