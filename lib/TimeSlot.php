<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Util\JsonDateTime;
use Trunkrs\SDK\Util\ResultUnwrapper;

/**
 * Class TimeSlot
 */
class TimeSlot {
    private static function applyV1(TimeSlot $timeSlot, \stdClass $json) {
        $timeSlot->id = property_exists($json, 'id') ? $json->id : null;
        $timeSlot->dataCutOff = JsonDateTime::from($json->dataWindow);
        $timeSlot->senderId = $json->senderId;

        $timeSlot->deliveryWindow = new TimeWindow($json->deliveryWindow);
        $timeSlot->collectionWindow = new TimeWindow($json->collectionWindow);
    }

    private static function applyV2(TimeSlot $slot, \stdClass $json) {
        $slot->id = $json->id;
        $slot->dataCutOff = JsonDateTime::from($json->cutOffTime);
        $slot->sender = new Address($json->merchant);
        $slot->deliveryWindow = new TimeWindow($json->deliveryWindow);
        $slot->collectionWindow = new TimeWindow($json->collectionWindow);
        $slot->serviceArea = new ServiceArea($json->serviceArea);
    }

    /**
     * Retrieves next available time slots for shipments. Can optionally be retrieved for a country.
     *
     * @param string $postalCode The postal code for which to retrieve time slots.
     * @param string $country An optional country specifier.
     * @return TimeSlot[] An array of Timeslot instances.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function retrieve(
        string $postalCode,
        string $country = 'NL'
    ): array {
        $response = RequestHandler::get('timeslots', ['postalCode' => $postalCode, 'country' => $country]);
        $results = ResultUnwrapper::unwrap($response);

        return array_map(function ($json) {
            return new TimeSlot($json);
        }, $results);
    }

    /**
     * @var int $id The timeslot identifier.
     */
    public $id;

    /**
     * @deprecated Only available in API version 1.
     * @var int $senderId The sender identifier to which this time slot belongs.
     */
    public $senderId;

    /**
     * @var Address $sender The sender to which this time slot belongs.
     * @note Only available on API version 2.
     * @since 2.0.0
     */
    public $sender;

    /**
     * @var TimeWindow $deliveryWindow The delivery window in which shipments for this slot will be delivered.
     */
    public $deliveryWindow;

    /**
     * @var TimeWindow $collectionWindow The collection window in which the collection of the shipment will happen.
     */
    public $collectionWindow;

    /**
     * @var \DateTime $dataCutOff Data cut-off time for the time slot.
     */
    public $dataCutOff;

    /**
     * @var ServiceArea $serviceArea The service area for which this timeslot is meant.
     * @note Only available on API version 2.
     * @since 2.0.0
     */
    public $serviceArea;

    /**
     * TimeSlot constructor.
     * @param \stdClass|null $json An optional associative array for parsing the timeslot.
     */
    public function __construct($json = null) {
        if ($json) {
            switch (Settings::$apiVersion) {
                case 1:
                    self::applyV1($this, $json);
                    break;
                case 2:
                    self::applyV2($this, $json);
                    break;
            }
        }
    }
}