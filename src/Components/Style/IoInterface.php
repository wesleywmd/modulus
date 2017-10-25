<?php
namespace Modulus\Components\Style;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Interface IoInterface
 * @package Modulus\Components\Style
 * @author Wesley Guthrie
 * @email therealwesleywmd@gmail.com
 */
interface IoInterface extends StyleInterface
{
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
    public function block($messages, $type = null, $style = null, $prefix = ' ', $padding = false, $escape = true);

    /**
     * Formats a command title.
     *
     * @param string $message
     */
    public function title($message);

    /**
     * Formats a section title.
     *
     * @param string $message
     */
    public function section($message);

    /**
     * Formats a list.
     *
     * @param array $elements
     */
    public function listing(array $elements);

    /**
     * Formats informational text.
     *
     * @param string|array $message
     */
    public function text($message);

    /**
     * Formats a command comment.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function comment($message,$padding=false);

    /**
     * Formats a success result bar.
     *
     * @param string|array $message
     */
    public function success($message,$padding=true);

    /**
     * Formats an error result bar.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function error($message,$padding=true);

    /**
     * Formats an warning result bar.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function warning($message,$padding=true);

    /**
     * Formats a note admonition.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function note($message,$padding=false);

    /**
     * Formats a caution admonition.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function caution($message,$padding=true);

    /**
     * Formats a info admonition.
     *
     * @param string|array $message
     * @param bool         $padding
     */
    public function info($message,$padding=false);

    /**
     * Formats a table.
     *
     * @param array $headers
     * @param array $rows
     */
    public function table(array $headers, array $rows);

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
    public function askForMissingArgument(
        $argument,
        $question,
        $default = null,
        $validator = null,
        $maxAttempts = null,
        $comment = false,
        $commentFormat = "Argument [%s] set to: %s"
    );

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
    public function askForMissingOption(
        $option,
        $question,
        $default = null,
        $validator = null,
        $maxAttempts = null,
        $comment = false,
        $commentFormat = "Option [%s] set to: %s"
    );

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
    public function ask($question, $default = null, $validator = null, $maxAttempts = null);

    /**
     * Asks a question with the user input hidden.
     *
     * @param string        $question
     * @param callable|null $validator
     * @param int|null      $maxAttempts
     *
     * @return string
     */
    public function askHidden($question, $validator = null, $maxAttempts = null);

    /**
     * Asks for confirmation.
     *
     * @param string $question
     * @param bool   $default
     *
     * @return bool
     */
    public function confirm($question, $default = true);

    /**
     * Asks a choice question.
     *
     * @param string          $question
     * @param array           $choices
     * @param string|int|null $default
     *
     * @return string
     */
    public function choice($question, array $choices, $default = null);

    /**
     * Add newline(s).
     *
     * @param int $count The number of newlines
     */
    public function newLine($count = 1);

    /**
     * Starts the progress output.
     *
     * @param int $max Maximum steps (0 if unknown)
     */
    public function progressStart($max = 0);

    /**
     * Advances the progress output X steps.
     *
     * @param int $step Number of steps to advance
     */
    public function progressAdvance($step = 1);

    /**
     * Finishes the progress output.
     */
    public function progressFinish();

    /**
     * @param Question $question
     *
     * @return string
     */
    public function askQuestion(Question $question);
}