<?php

use Tests\TestCase;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Illuminate\Testing\Assert;

class ExtensionTest extends TestCase
{

    public function testProvides()
    {
        $addons = app(AddonCollection::class);
        $this->assertEquals('anomaly.module.users::authenticator.default', $addons->instance('anomaly.extension.default_authenticator')->provides());
    }

    public function testToArray()
    {
        return $this->markTestSkipped();

        $addons = app(AddonCollection::class);

        $addon = $addons->instance('anomaly.extension.default_authenticator');

        Assert::assertArraySubset([
            'name'      => $addon->getName(),
            'type'      => $addon->getType(),
            'path'      => $addon->getPath(),
            'slug'      => $addon->getSlug(),
            'vendor'    => $addon->getVendor(),
            'namespace' => $addon->getNamespace(),

            'provides'  => $addon->provides(),
            'enabled'   => $addon->isEnabled(),
            'installed' => $addon->isInstalled(),
        ], $addon->toArray());
    }
}
