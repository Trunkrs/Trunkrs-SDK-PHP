<?php


namespace Trunkrs\SDK;

class PackageOwnerV2MappingTest extends APIV2TestCase
{
    public function testShouldMapV2Response() {
        $srcOwner = Mocks::getFakePackageOwner();
        $json = MockV2Responses::getFakePackageOwnerBody($srcOwner);

        $owner = new PackageOwner($json);

        $this->assertAttributeEquals($srcOwner->type, 'type', $owner);
        $this->assertAttributeEquals($srcOwner->name, 'name', $owner);
        $this->assertAttributeEquals($srcOwner->addressLine, 'addressLine', $owner);
        $this->assertAttributeEquals($srcOwner->postal, 'postal', $owner);
        $this->assertAttributeEquals($srcOwner->city, 'city', $owner);
        $this->assertAttributeEquals($srcOwner->country, 'country', $owner);
    }
}