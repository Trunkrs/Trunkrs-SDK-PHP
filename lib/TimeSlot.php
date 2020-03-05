<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\JsonDateTime;

/**
 * Class TimeSlotWindow
 */
class TimeSlotWindow {
    private static function applyV1(TimeSlotWindow $window, array $json) {
        $window->from = JsonDateTime::from($json['from']);
        $window->to = JsonDateTime::from($json['to']);
    }

    /**
     * @var \DateTime $from The start of the window.
     */
    public $from;

    /**
     * @var \DateTime $to The end of the window.
     */
    public $to;

    /**
     * TimeSlotWindow constructor.
     * @param array|null $json An optional associative array for parsing the window.
     */
    public function __construct(array $json = null)
    {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
            }
        }
    }
}

/**
 * Class TimeSlot
 */
class TimeSlot {
    private static function applyV1(TimeSlot $timeSlot, array $json) {
        $timeSlot->id = $json['id'];
        $timeSlot->dataCutOff = JsonDateTime::from($json['dataWindow']);
        $timeSlot->senderId = $json['senderId'];

        $timeSlot->deliveryWindow = new TimeSlotWindow($json['deliveryWindow']);
        $timeSlot->collectionWindow = new TimeSlotWindow($json['collectionWindow']);
    }

    /**
     * Retrieves next available time slots for shipments. Can optionally be retrieved for a country.
     *
     * @param string $country An optional country specifier.
     * @return array An array of Timeslot instances.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function retrieve(string $country = 'NL'): array {
        $jsonResult = RequestHandler::get('timeslots', ['country' => $country]);

        return array_map(function ($json) {
            return new TimeSlot($json);
        }, $jsonResult);
    }

    /**
     * @var int $id The timeslot identifier.
     */
    public $id;

    /**
     * @var int $senderId The sender identifier to which this timeslot belongs.
     */
    public $senderId;

    /**
     * @var TimeSlotWindow $deliveryWindow The delivery window in which shipments for this slot will be delivered.
     */
    public $deliveryWindow;

    /**
     * @var TimeSlotWindow $collectionWindow The collection window in which the collection of the shipment will happen.
     */
    public $collectionWindow;

    /**
     * @var \DateTime $dataCutOff Data cut-off time for the time slot.
     */
    public $dataCutOff;

    /**
     * TimeSlot constructor.
     * @param array|null $json An optional associative array for parsing the timeslot.
     */
    public function __construct(array $json = null) {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
            }
        }
    }
}