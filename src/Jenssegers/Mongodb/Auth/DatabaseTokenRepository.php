<?php

namespace Jenssegers\Mongodb\Auth;

use DateTime;
use DateTimeZone;
use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;
use MongoDB\BSON\UTCDateTime;

class DatabaseTokenRepository extends BaseDatabaseTokenRepository
{
    /**
     * @inheritdoc
     */
    protected function getPayload($email, $token)
    {
        $hashedToken = $this->hasher->make($token);

        return ['email' => $email, 'token' => $hashedToken, 'created_at' => new UTCDateTime(time() * 1000)];
    }

    /**
     * @inheritdoc
     */
    protected function tokenExpired($token)
    {
        /** @var UTCDateTime $token */

        $date = $token->toDateTime();
        $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $token = $date->format('Y-m-d H:i:s');

        return parent::tokenExpired($token);
    }
}
