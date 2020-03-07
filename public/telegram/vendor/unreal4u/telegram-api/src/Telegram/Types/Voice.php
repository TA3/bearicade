<?php

declare(strict_types = 1);

namespace unreal4u\TelegramAPI\Telegram\Types;

use unreal4u\TelegramAPI\Abstracts\TelegramTypes;

/**
 * This object represents a voice note
 *
 * Objects defined as-is july 2016
 *
 * @see https://core.telegram.org/bots/api#voice
 */
class Voice extends TelegramTypes
{
    /**
     * Unique identifier for this file
     * @var string
     */
    public $file_id = '';

    /**
     * Duration of the audio in seconds as defined by sender
     * @var int
     */
    public $duration = 0;

    /**
     * Optional. Mime type of the file as defined by sender
     * @var string
     */
    public $mime_type = '';

    /**
     * Optional. File size
     * @var int
     */
    public $file_size = 0;
}
