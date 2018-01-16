<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;

class UserSerializerService
{
    const SERIALIZED_USER_USERNAME_KEY = 'username';
    const SERIALIZED_USER_PASSWORD_KEY = 'password';
    const SERIALIZED_USER_KEY_KEY = 'key';
    const SERIALIZED_USER_IV_KEY = 'iv';

    /**
     * @var string
     */
    private $surrogateKey;

    /**
     * @var string
     */
    private $iv;

    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = md5($key);
        $this->surrogateKey = md5(rand());

        $isSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $this->iv = mcrypt_create_iv($isSize, MCRYPT_RAND);
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function serialize(User $user)
    {
        return array(
            self::SERIALIZED_USER_USERNAME_KEY => $this->encrypt($user->getUsername(), $this->surrogateKey),
            self::SERIALIZED_USER_PASSWORD_KEY => $this->encrypt($user->getPassword(), $this->surrogateKey),
            self::SERIALIZED_USER_KEY_KEY => $this->encrypt($this->surrogateKey, $this->key),
            self::SERIALIZED_USER_IV_KEY => $this->iv,
        );
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function serializeToString(User $user)
    {
        $serializedUser = $this->serialize($user);

        foreach ($serializedUser as $key => $value) {
            $serializedUser[$key] = base64_encode($value);
        }

        return base64_encode(json_encode($serializedUser));
    }

    /**
     * @param string $user
     *
     * @return User
     */
    public function unserializedFromString($user)
    {
        $base64EncodedUserValues = json_decode(base64_decode($user), true);
        if (!is_array($base64EncodedUserValues)) {
            return null;
        }

        if (empty($base64EncodedUserValues)) {
            return null;
        }

        $expectedKeys = [
            self::SERIALIZED_USER_USERNAME_KEY,
            self::SERIALIZED_USER_PASSWORD_KEY,
            self::SERIALIZED_USER_KEY_KEY,
            self::SERIALIZED_USER_IV_KEY,
        ];

        foreach ($expectedKeys as $expectedKey) {
            if (!isset($base64EncodedUserValues[$expectedKey])) {
                return null;
            }
        }

        $userValues = [];

        foreach ($base64EncodedUserValues as $key => $value) {
            $base64DecodedValue = base64_decode($value);
            if ($base64DecodedValue == '') {
                return null;
            }

            $userValues[$key] = $base64DecodedValue;
        }

        return $this->unserialize($userValues);
    }

    /**
     * @param array $serializedUser
     *
     * @return User
     */
    public function unserialize($serializedUser)
    {
        $this->iv = $serializedUser[self::SERIALIZED_USER_IV_KEY];
        $this->surrogateKey = $this->decrypt($serializedUser[self::SERIALIZED_USER_KEY_KEY], $this->key);

        $user = new User();
        $user->setUsername(
            trim($this->decrypt($serializedUser[self::SERIALIZED_USER_USERNAME_KEY], $this->surrogateKey))
        );
        $user->setPassword(
            trim($this->decrypt($serializedUser[self::SERIALIZED_USER_PASSWORD_KEY], $this->surrogateKey))
        );

        return $user;
    }

    /**
     * @param string $plaintext
     * @param string $key
     *
     * @return string
     */
    private function encrypt($plaintext, $key)
    {
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plaintext, MCRYPT_MODE_ECB, $this->iv);
    }

    /**
     * @param string $ciphertext
     * @param string $key
     *
     * @return string
     */
    private function decrypt($ciphertext, $key)
    {
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $ciphertext, MCRYPT_MODE_ECB, $this->iv);
    }
}
