<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Domain\Model;

class MongodbConfiguration
{
    /**
     * @var string
     */
    protected string $user = '';

    /**
     * @var string $password The variable that holds the password value.
     */
    protected string $password = '';

    /**
     * @var string $host The variable that holds the host value.
     */
    protected string $host = '';

    /**
     * @var int $port The variable that holds the port number.
     */
    protected int $port = 0;

    /**
     * @var string $dbname The variable that holds the name of the database.
     */
    protected string $dbname = '';

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    public function getDbname(): string
    {
        return $this->dbname;
    }

    public function setDbname(string $dbname): void
    {
        $this->dbname = $dbname;
    }
}
