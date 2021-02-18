<?php

namespace Trunkrs\SDK;

class AddressV2MappingTest extends APIV2TestCase {
    public function testAddressV2ResponseMapping() {
        $srcAddress = Mocks::getFakeAddress();
        $json = MockV2Responses::getFakeAddressBody($srcAddress);

        $address = new Address($json);

        $this->assertAttributeEquals($srcAddress->companyName, 'companyName', $address);
        $this->assertAttributeEquals($srcAddress->contactName, 'contactName', $address);
        $this->assertAttributeEquals($srcAddress->addressLine, 'addressLine', $address);
        $this->assertAttributeEquals($srcAddress->postal, 'postal', $address);
        $this->assertAttributeEquals($srcAddress->city, 'city', $address);
        $this->assertAttributeEquals($srcAddress->country, 'country', $address);
        $this->assertAttributeEquals($srcAddress->phone, 'phone', $address);
        $this->assertAttributeEquals($srcAddress->email, 'email', $address);
        $this->assertAttributeEquals($srcAddress->remarks, 'remarks', $address);
    }

    public function testAddressV2RequestMapping() {
        $address = Mocks::getFakeAddress();

        $json = $address->serialize();

        $this->assertEquals($address->companyName, $json['companyName']);
        $this->assertEquals($address->contactName, $json['name']);
        $this->assertEquals($address->addressLine, $json['address']);
        $this->assertEquals($address->postal, $json['postalCode']);
        $this->assertEquals($address->city, $json['city']);
        $this->assertEquals($address->country, $json['country']);
        $this->assertEquals($address->phone, $json['phoneNumber']);
        $this->assertEquals($address->email, $json['emailAddress']);
        $this->assertEquals($address->remarks, $json['additionalRemarks']);
    }
}