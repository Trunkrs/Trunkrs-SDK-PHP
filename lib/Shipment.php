<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\ShipmentNotFoundException;

/**
 * Class Shipment
 */
class Shipment {
    private static function applyV1(Shipment $shipment, array $json) {
        $shipment->id = $json['shipmentId'];
        $shipment->trunkrsNr = $json['trunkrsNr'];
        $shipment->labelUrl = $json['label'];

        $shipment->pickupAddress = new Address($json['sender']);
        $shipment->deliveryAddress = new Address($json['recipient']);
        $shipment->timeSlot = new TimeSlot($json['timeSlot']);
    }

    /**
     * Creates a new shipment for the specified shipment details.
     *
     * @param ShipmentDetails $details The details of the shipment.
     * @param Address $pickup The pickup address for the shipment.
     * @param Address $delivery The delivery address for the shipment.
     * @param int $timeslotId An optional timeslot, if not specified will be placed in next available.
     * @return array The created shipments in an array as instance of Shipment.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\ServerValidationException When the request payload doesn't match the expectation of the API.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function create(
        ShipmentDetails $details,
        Address $pickup,
        Address $delivery,
        int $timeslotId = -1
    ): array {
        $body = [];
        switch (Settings::$apiVersion){
            case 1:
                $body = array_merge(
                    $pickup->serialize('pickup'),
                    $delivery->serialize('delivery'),
                    $details->serialize()
                );
                if ($timeslotId > 0) {
                    $body['timeslotId'] = $timeslotId;
                }

                break;
        }

        $jsonResult = RequestHandler::post("shipments", $body);
        if (!is_array($jsonResult)) {
            $jsonResult = [$jsonResult];
        }

        return array_map(function ($result) {
            return new Shipment($result);
        }, $jsonResult);
    }

    /**
     * Find the details for the specified shipment by its identifier.
     *
     * @param int $shipmentId The shipment identifier.
     * @return Shipment A shipment instance.
     * @throws ShipmentNotFoundException When the specified shipment couldn't be found.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     */
    public static function find($shipmentId): Shipment {
        try {
            $json = RequestHandler::get(sprintf("shipments/%d", $shipmentId));
            return new Shipment($json);
        } catch (GeneralApiException $exception) {
            $isShipmentNotFound = $exception->getStatusCode() == 404;
            if ($isShipmentNotFound)  {
                throw new ShipmentNotFoundException($shipmentId);
            }
            throw $exception;
        }
    }

    /**
     * Retrieves all shipments in a paginated fashion.
     *
     * @param int $page The optional page of shipments to retrieve.
     * @return array An array of Shipment
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function retrieve(int $page = 1): array {
        $jsonResult = RequestHandler::get('shipments', [ 'page' => $page ]);

        return array_map(function ($json) {
            return new Shipment($json);
        }, $jsonResult);
    }

    /**
     * Cancels the shipment by its identifier.
     *
     * @param int $id The shipment id of the shipment to cancel.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function cancelById(int $id) {
        RequestHandler::delete(sprintf('shipments/%d', $id));
    }

    /**
     * @var int The shipment identifier.
     */
    public $id;

    /**
     * @var string The trunrks number of the shipment.
     */
    public $trunkrsNr;

    /**
     * @var Address $pickupAddress The pick-up address details for this shipment.
     */
    public $pickupAddress;

    /**
     * @var Address $deliveryAddress The delivery address for this shipment.
     */
    public $deliveryAddress;

    /**
     * @var TimeSlot $timeSlot The timeslot in which the shipment has been created.
     */
    public $timeSlot;

    /**
     * @var string The label url of shipment.
     */
    public $labelUrl;

    /**
     * Shipment constructor.
     *
     * @param array|null $json Optional associative array to decode shipment from.
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

    /**
     * Creates a tracking URL for this shipment.
     *
     * @return string The tracking url.
     */
    public function getTrackingUrl(): string {
        return sprintf("
            %s/%s/%s",
            Settings::$trackingBaseUrl,
            $this->trunkrsNr,
            str_replace(" ", "", $this->deliveryAddress->postal)
        );
    }

    /**
     * Retrieves the current state for the shipment.
     *
     * @return ShipmentState The current shipment state.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public function getState(): ShipmentState {
        return ShipmentState::forShipment($this->id);
    }

    /**
     * Cancels the shipment.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public function cancel() {
        self::cancelById($this->id);
    }
}