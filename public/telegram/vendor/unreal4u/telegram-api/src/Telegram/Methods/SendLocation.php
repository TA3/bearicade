<?php

declare(strict_types = 1);

namespace unreal4u\TelegramAPI\Telegram\Methods;

use unreal4u\TelegramAPI\Abstracts\KeyboardMethods;
use unreal4u\TelegramAPI\Abstracts\TelegramMethods;

/**
 * Use this method to send point on the map. On success, the sent Message is returned.
 *
 * Objects defined as-is october 2017
 *
 * @see https://core.telegram.org/bots/api#sendlocation
 */
class SendLocation extends TelegramMethods
{
    /**
     * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     * @var string
     */
    public $chat_id = '';

    /**
     * Latitude of location
     * @var float
     */
    public $latitude = 0.0;

    /**
     * Longitude of location
     * @var float
     */
    public $longitude = 0.0;

    /**
     * Optional. Period in seconds for which the location will be updated (see Live Locations), should be between 60 and
     * 86400
     * @var int
     */
    public $live_period = 0;

    /**
     * Optional. Sends the message silently. iOS users will not receive a notification, Android users will receive a
     * notification with no sound.
     * @see https://telegram.org/blog/channels-2-0#silent-messages
     * @var bool
     */
    public $disable_notification = false;

    /**
     * If the message is a reply, ID of the original message
     * @var int
     */
    public $reply_to_message_id = 0;

    /**
     * Optional. Additional interface options. A JSON-serialized object for a custom reply keyboard, instructions to
     * hide keyboard or to force a reply from the user.
     * @var KeyboardMethods
     */
    public $reply_markup;

    public function getMandatoryFields(): array
    {
        return [
            'chat_id',
            'latitude',
            'longitude',
        ];
    }
}
