<?php

declare(strict_types = 1);

namespace unreal4u\TelegramAPI\Telegram\Types\Inline\Query\Result;

use unreal4u\TelegramAPI\Telegram\Types\Inline\Query\Result;
use unreal4u\TelegramAPI\Telegram\Types\InputMessageContent;

/**
 * Represents a venue. By default, the venue will be sent by the user. Alternatively, you can use input_message_content
 * to send a message with the specified content instead of the venue.
 *
 * Objects defined as-is april 2016
 *
 * @see https://core.telegram.org/bots/api#inlinequeryresultvenue
 */
class Venue extends Result
{
    /**
     * Type of the result, must be venue
     * @var string
     */
    public $type = 'venue';

    /**
     * Location latitude in degrees
     * @var float
     */
    public $latitude = 0.0;

    /**
     * Location longitude in degrees
     * @var float
     */
    public $longitude = 0.0;

    /**
     * Title for the result
     * @var string
     */
    public $title = '';

    /**
     * Address of the venue
     * @var string
     */
    public $address = '';

    /**
     * Optional. Foursquare identifier of the venue if known
     * @var string
     */
    public $foursquare_id = '';

    /**
     * Optional. Url of the thumbnail for the result
     * @var string
     */
    public $thumb_url = '';

    /**
     * Optional. Width of the thumbnail
     * @var int
     */
    public $thumb_width = 0;

    /**
     * Optional. Height of the thumbnail
     * @var int
     */
    public $thumb_height = 0;

    /**
     * Optional. Content of the message to be sent instead of the audio/document/voice message/video/sticker/etc.
     * @var InputMessageContent
     */
    public $input_message_content;
}
