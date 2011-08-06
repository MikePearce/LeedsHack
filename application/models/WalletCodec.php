<?php

class WalletCodec
{
    const CIPHER = MCRYPT_RIJNDAEL_256;
    const MODE = MCRYPT_MODE_CBC;

    static public function get()
    {
        return new self;
    }

    public function encode(array $data, $passphrase)
    {
        $iv = $this->generateIv();
        $ciphertext = mcrypt_encrypt(self::CIPHER, $this->getKey($passphrase),
                                     json_encode($data), self::MODE, $iv);
        return base64_encode($iv) . ',' . base64_encode($ciphertext);
    }

    public function decode($blob, $passphrase)
    {
        list($iv, $ciphertext) = explode(",", $blob, 2);
        $plaintext = rtrim(mcrypt_decrypt(self::CIPHER, $this->getKey($passphrase),
                                          base64_decode($ciphertext), self::MODE,
                                          base64_decode($iv)));
        return json_decode($plaintext, true);
    }

    protected function generateIv()
    {
        $iv = "";
        for ($i = 0; $i < 8; $i++) $iv .= pack("i", mt_rand());
        return $iv;
    }

    protected function getKey($passphrase)
    {
        return hash('sha256', $passphrase, true);
    }
}
