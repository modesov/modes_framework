<?php

namespace Modes\Framework\Session;

class Session implements SessionInterface
{
    private const FLASH_KEY = 'flash';

    public function start(): void
    {
        session_start();
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove($key): void
    {
        unset($_SESSION[$key]);
    }

    public function getFlash(string $type): array
    {
        $flash = $this->getFlashs();
        $messages = [];
        if (isset($flash[$type])) {
            $messages = $flash[$type];
            unset($flash[$type]);
            $this->set(key: self::FLASH_KEY, value: $flash);
        }

        return $messages;
    }

    public function setFlash(string $type, string $message): void
    {
        $flash = $this->getFlashs();
        $flash[$type][] = $message;
        $this->set(key: self::FLASH_KEY, value: $flash);
    }

    public function hasFlash(string $type): bool
    {
        $flash = $this->getFlashs();
        return isset($flash[$type]);
    }

    public function clearFlash(): void
    {
        $this->remove(self::FLASH_KEY);
    }

    private function getFlashs()
    {
        return $this->get(key: self::FLASH_KEY, default: []);
    }
}