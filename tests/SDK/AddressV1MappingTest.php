<?php

namespace Trunkrs\SDK;

class AddressV1MappingTest extends APIV1TestCase {
    public function testAddressV1ResponseMapping() {
        $srcAddress = Mocks::getFakeAddress();
        $json = MockV1Responses::getFakeAddressBody($srcAddress);

        $address = new Address($json);

        $this->assertAttributeEquals($srcAddress->companyName, 'contactName', $address);
        $this->assertAttributeEquals($srcAddress->addressLine, 'addressLine', $address);
        $this->assertAttributeEquals($srcAddress->postal, 'postal', $address);
        $this->assertAttributeEquals($srcAddress->city, 'city', $address);
        $this->assertAttributeEquals($srcAddress->country, 'country', $address);
        $this->assertAttributeEquals($srcAddress->phone, 'phone', $address);
        $this->assertAttributeEquals($srcAddress->email, 'email', $address);
        $this->assertAttributeEquals($srcAddress->remarks, 'remarks', $address);
    }

    public function testAddressV1RequestMapping() {
        $prefix = Mocks::getGenerator()->word;
        $address = Mocks::getFakeAddress();

        $json = $address->serialize($prefix);

        $this->assertEquals($address->companyName, $json[$prefix . 'Name']);
        $this->assertEquals($address->contactName, $json[$prefix . 'Contact']);
        $this->assertEquals($address->addressLine, $json[$prefix . 'Address']);
        $this->assertEquals($address->postal, $json[$prefix . 'PostCode']);
        $this->assertEquals($address->city, $json[$prefix . 'City']);
        $this->assertEquals($address->country, $json[$prefix . 'Country']);
        $this->assertEquals($address->phone, $json[$prefix . 'Tell']);
        $this->assertEquals($address->email, $json[$prefix . 'Email']);
        $this->assertEquals($address->remarks, $json[$prefix . 'Remarks']);
    }
}