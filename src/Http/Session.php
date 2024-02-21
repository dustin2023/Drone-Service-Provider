<?php

declare(strict_types=1);

namespace Maurerd\Webentwicklung\Http;

class Session
{
    /**
     *
     */
    private const LOGGED_IN_INDICATOR = 'isLoggedIn';

    /**
     * @param array $options
     * @return bool
     */
    public function start(array $options = []): bool
    {
        return session_start($options);
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        return session_destroy();
    }

    /**
     * @param int $customerId
     * @return void
     */
    public function login(int $customerId): void
    {
        $this->setValue(static::LOGGED_IN_INDICATOR, true);
        $this->setValue('customer_id', $customerId);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->clearValue(static::LOGGED_IN_INDICATOR);
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->hasValue(static::LOGGED_IN_INDICATOR) &&
            $this->getValue(static::LOGGED_IN_INDICATOR);
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $_SESSION;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue(string $name): mixed
    {
        return $_SESSION[$name];
    }


    /**
     * @param string $name
     * @param $value
     * @return void
     */
    public function setValue(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values): void
    {
        $_SESSION = $values;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    /**
     * @param string $name
     * @return void
     */
    public function clearValue(string $name): void
    {
        unset($_SESSION[$name]);
    }
}
