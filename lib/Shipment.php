<?php

namespace Trunkrs\SDK;

use Trunkrs\SDK\Enum\ShipmentService;
use Trunkrs\SDK\Exception\GeneralApiException;
use Trunkrs\SDK\Exception\NotSupportedException;
use Trunkrs\SDK\Exception\ShipmentNotFoundException;
use Trunkrs\SDK\Util\Defaults;
use Trunkrs\SDK\Util\ResultUnwrapper;

/**
 * Class Shipment
 */
class Shipment {
    private static function applyV1(Shipment $shipment, $json) {
        $shipment->id = $json->shipmentId;
        $shipment->trunkrsNr = $json->trunkrsNr;
        $shipment->label = new LabelUrls($json);

        $shipment->sender = new Address($json->sender);
        $shipment->recipient = new Address($json->recipient);
        $shipment->timeSlot = new TimeSlot($json->timeSlot);
    }

    private static function applyV2(Shipment $shipment, $json) {
        $shipment->trunkrsNr = $json->trunkrsNr;
        $shipment->sender = new Address($json->sender);
        $shipment->recipient = new Address($json->recipient);

        $shipment->parcels = array_map(function ($parcelJson) {
            return new Parcel($parcelJson);
        }, $json->parcels);

        $shipment->timeSlot = new TimeSlot($json->timeSlot);
        $shipment->state = new ShipmentState($json->state);
        $shipment->featureCodes = new FeatureCodes($json->featureCodes);
        $shipment->service = $json->service;
    }

    /**
     * Creates a new shipment for the specified shipment details.
     *
     * @param ShipmentDetails $shipment The details of the shipment.
     * @return array The created shipments in an array as instance of Shipment.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\ServerValidationException When the request payload doesn't match the expectation of the API.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public static function create(ShipmentDetails $shipment): array {
        $response = RequestHandler::post('shipments', $shipment->serialize());
        $results = ResultUnwrapper::unwrap($response);

        if (!is_array($results)) {
            $results = [$results];
        }

        return array_map(function ($result) {
            return new Shipment($result);
        }, $results);
    }

    /**
     * Find the details for the specified shipment by its identifier.
     *
     * @param string $trunkrsNr The shipment Trunkrs number.
     * @return Shipment A shipment instance.
     * @throws ShipmentNotFoundException When the specified shipment couldn't be found.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws GeneralApiException When the API responds with an unexpected answer.
     * @throws NotSupportedException When using API version 1 and calling this method.
     */
    public static function find(string $trunkrsNr): Shipment {
        if (Settings::$apiVersion == 1) {
            throw new NotSupportedException('Please use Shipment::findById in combination with the shipment id instead.');
        }

        try {
            $json = RequestHandler::get(sprintf("shipments/%s", $trunkrsNr));
            return new Shipment(ResultUnwrapper::unwrap($json));
        } catch (GeneralApiException $exception) {
            $isShipmentNotFound = $exception->getStatusCode() == 404;
            if ($isShipmentNotFound)  {
                throw new ShipmentNotFoundException($trunkrsNr);
            }
            throw $exception;
        }
    }

    /**
     * Find the details for the specified shipment by its identifier.
     *
     * @deprecated
     * @param int $id The shipment identifier.
     * @return Shipment A shipment instance.
     * @throws ShipmentNotFoundException When the specified shipment couldn't be found.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws GeneralApiException When the API responds with an unexpected answer
     * @throws NotSupportedException When this functionality is called against the V2 API.
     */
    public static function findById(int $id): Shipment {
        if (Settings::$apiVersion == 2) {
            throw new NotSupportedException('Please use the Trunkrs number instead of the shipment id.');
        }

        try {
            $json = RequestHandler::get(sprintf("shipments/%d", $id));
            return new Shipment(ResultUnwrapper::unwrap($json));
        } catch (GeneralApiException $exception) {
            $isShipmentNotFound = $exception->getStatusCode() == 404;
            if ($isShipmentNotFound)  {
                throw new ShipmentNotFoundException($id);
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
        $params = [];
        switch (Settings::$apiVersion) {
            case 1:
                $params = ['page' => $page];
                break;
            case 2:
                $params = ['offset' => $page - 1 * 50, 'limit' => 50];
                break;
        }

        $response = RequestHandler::get('shipments', $params);
        $jsonResult = ResultUnwrapper::unwrap($response);

        return array_map(function ($json) {
            return new Shipment($json);
        }, $jsonResult);
    }

    private static function __cancel($trunkrsNrOrId) {
        try {
            RequestHandler::delete(sprintf('shipments/%d', $trunkrsNrOrId));
        } catch (GeneralApiException $exception) {
            $isShipmentNotFound = $exception->getStatusCode() == 404;
            if ($isShipmentNotFound)  {
                throw new ShipmentNotFoundException($trunkrsNrOrId);
            }

            throw $exception;
        }
    }

    /**
     * Cancels the shipment by its Trunkrs number. Only available on the V2 API.
     *
     * @param string $trunkrsNr The shipment id of the shipment to cancel.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     * @throws NotSupportedException When this functionality is called against the V1 API.
     */
    public static function cancelByTrunkrsNr(string $trunkrsNr) {
        if (Settings::$apiVersion == 1) {
            throw new NotSupportedException('Please use the shipment id instead of the Trunkrs number.');
        }

        self::__cancel($trunkrsNr);
    }

    /**
     * Cancels the shipment by its identifier. Only available on the V1 API.
     *
     * @deprecated
     * @param int $id The shipment id of the shipment to cancel.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     * @throws Exception\NotSupportedException When this functionality is called against the V2 API.
     */
    public static function cancelById(int $id) {
        if (Settings::$apiVersion == 2) {
            throw new NotSupportedException('Please use the Trunkrs number instead of the shipment id.');
        }

        self::__cancel($id);
    }

    /**
     * @deprecated The shipment id is deprecated in favor of the Trunkrs number.
     * @var int The shipment identifier.
     */
    public $id;

    /**
     * @var string The Trunkrs number of the shipment.
     */
    public $trunkrsNr;

    /**
     * @var Address $sender The pick-up address details for this shipment.
     */
    public $sender;

    /**
     * @var Address $recipient The delivery address for this shipment.
     */
    public $recipient;

    /**
     * @var TimeSlot The timeslot in which the shipment has been created.
     */
    public $timeSlot;

    /**
     * @var LabelUrls The label urls of the shipment.
     */
    public $label;

    /**
     * @var ShipmentState The current state of the shipment. Only available as property under the V2 API.
     */
    public $state;

    /**
     * @var FeatureCodes The feature codes set for this shipment.
     * @since 2.0.0
     */
    public $featureCodes;

    /**
     * @see ShipmentService
     * @var string The service level requested for this shipment.
     * @since 2.0.0
     */
    public $service;

    /**
     * @var Parcel[] The parcels within this shipment.
     * @since 2.0.0
     */
    public $parcels;

    /**
     * Shipment constructor.
     *
     * @param array|null $json Optional associative array to decode shipment from.
     */
    public function __construct($json = null) {
        $this->service = Defaults::getDefaultService();
        $this->featureCodes = Defaults::getDefaultFeatureCodes();

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

    /**
     * Creates a tracking URL for this shipment.
     *
     * @return string The tracking url.
     */
    public function getTrackingUrl(): string {
        return sprintf(
            "%s/%s/%s",
            Settings::$trackingBaseUrl,
            $this->trunkrsNr,
            str_replace(" ", "", $this->recipient->postal)
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
     * Downloads the label file in the specified format.
     * @param string $type The label format type. Either pdf or zpl.
     * @see Trunkrs\SDK\Enum\ShipmentLabelType
     * @return Label The label wrapper class.
     */
    public function downloadLabel(string $type): Label {
        return Label::download(
            $type,
            $this->trunkrsNr,
            $this->recipient->postal
        );
    }

    /**
     * Cancels the shipment.
     * @throws Exception\NotAuthorizedException When the credentials are invalid, not set or expired.
     * @throws Exception\GeneralApiException When the API responds with an unexpected answer.
     */
    public function cancel() {
        switch (Settings::$apiVersion) {
            case 1:
                self::cancelById($this->id);
                break;
            case 2:
                self::cancelByTrunkrsNr($this->trunkrsNr);
                break;
        }
    }
}
