<?php

namespace Tests\WebClientBundle\Functional\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

class UserSerializerServiceTest extends AbstractBaseTestCase
{
    /**
     * @var UserSerializerService
     */
    private $userSerializerService;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $stringifiedUser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userSerializerService = $this->container->get(
            'simplytestable.services.userserializerservice'
        );

        $this->user = new User('user@example.com', 'password-value');
        $this->stringifiedUser = $this->userSerializerService->serializeToString($this->user);
    }

    public function testSerializeUnserialize()
    {
        $serializedUser = $this->userSerializerService->serialize($this->user);

        $this->assertInternalType('array', $serializedUser);
        $this->assertEquals([
            UserSerializerService::SERIALIZED_USER_USERNAME_KEY,
            UserSerializerService::SERIALIZED_USER_PASSWORD_KEY,
            UserSerializerService::SERIALIZED_USER_KEY_KEY,
            UserSerializerService::SERIALIZED_USER_IV_KEY,
        ], array_keys($serializedUser));

        $this->assertEquals($this->user, $this->userSerializerService->unserialize($serializedUser));
    }

    public function testSerializeToString()
    {
        $this->assertInternalType('string', $this->stringifiedUser);
    }

    /**
     * @dataProvider unserializeFromStringFailureDataProvider
     *
     * @param string $stringifiedUser
     * @param User|null $expectedUser
     */
    public function testUnserializeFromStringFailure($stringifiedUser, $expectedUser)
    {
        if ($stringifiedUser === '{{stringifiedUser}}') {
            $stringifiedUser = $this->stringifiedUser;
        }

        $user = $this->userSerializerService->unserializedFromString($stringifiedUser);

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @return array
     */
    public function unserializeFromStringFailureDataProvider()
    {
        $empty = base64_encode(json_encode([]));

        $invalid = base64_encode(json_encode([
            'foo' => 'bar',
        ]));

        $validKeysEmptyValues = base64_encode(json_encode([
            UserSerializerService::SERIALIZED_USER_USERNAME_KEY => 'username',
            UserSerializerService::SERIALIZED_USER_PASSWORD_KEY => 'password',
            UserSerializerService::SERIALIZED_USER_KEY_KEY => '',
            UserSerializerService::SERIALIZED_USER_IV_KEY => '',
        ]));

        return [
            'not an array' => [
                'stringifiedUser' => 'foo',
                'expectedUser' => null,
            ],
            'empty array' => [
                'stringifiedUser' => $empty,
                'expectedUser' => null,
            ],
            'invalid array' => [
                'stringifiedUser' => $invalid,
                'expectedUser' => null,
            ],
            'empty values' => [
                'stringifiedUser' => $validKeysEmptyValues,
                'expectedUser' => null,
            ],
        ];
    }

    public function testUnserializeFromStringSuccess()
    {
        $this->assertEquals(
            $this->user,
            $this->userSerializerService->unserializedFromString($this->stringifiedUser)
        );
    }
}
