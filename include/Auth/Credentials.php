<?php

namespace Auth;

/**
 * A user-password tuple
 */
class Credentials {

    /**
     * The username
     * @var string
     */
    private $username;

    /**
     * The password
     * @var string
     */
    private $password;

    /**
     * Constructs a new `Credentials` object
     * 
     * @param string $username The username
     * @param string $password The password
     */
    public function __construct(string $username, string $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Returns a textual representation of this object
     */
    public function __toString(): string {
        return $this->username.':'.$this->password;
    }

    /**
     * The username
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * The password
     */
    public function getPassword(): string {
        return $this->password;
    }

}
