<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use DateTimeInterface;
use Microsoft\Graph\Model\User as MSUser;
use RuntimeException;

class User extends MSUser {
    public static function current(): ?User {
        // TODO: use delegated token
        return null;
        // return Graph::instance()->createRequest('GET', '/me')->setReturnType(User::class)->execute();
    }

    public static function byID(string $id): ?User {
        return Graph::instance()->createRequest('GET', "/users/{$id}")->setReturnType(User::class)->execute();
    }

    public static function byEmailAddress(string $emailAddress): ?User {
        $result = new Collection("/users?\$filter=mail eq '{$emailAddress}'", User::class);

        return $result[0] ?? null;
    }

    /**
     * @return User[]
     */
    public static function all(): Collection {
        return new Collection('/users', User::class);
    }

    /**
     * @return Event[]
     */
    public function events(?DateTimeInterface $start = null, ?DateTimeInterface $end = null) {
        if ($start === null && $end === null) {
            return new Collection("/users/{$this->getId()}/events", Event::class);
        }
        if ($start === null) {
            throw new RuntimeException('Start must be provided if end is used');
        }
        if ($end === null) {
            throw new RuntimeException('End must be provided if start is used');
        }
        $filters = [
            'startDateTime' => $start->format(DateTimeInterface::ATOM),
            'endDateTime' => $end->format(DateTimeInterface::ATOM),
        ];
        $query = http_build_query($filters);

        return new Collection("/users/{$this->getId()}/calendar/calendarView?{$query}", Event::class);
    }

    /**
     * @return Calendar[]
     */
    public function calendars() {
        return new Collection("/users/{$this->getId()}/calendars", Calendar::class);
    }

    public function assignLicense(string $skuID, array $disabledPlans = []): void {
        $request = Graph::instance()->createRequest('POST', "/users/{$this->getId()}/assignLicense");
        $request->attachBody([
            'addLicenses' => [['skuId' => $skuID, 'disabledPlans' => $disabledPlans]],
            'removeLicenses' => [],
        ]);
        $request->execute();
    }

    public function unassignLicense(string $skuID): void {
        $request = Graph::instance()->createRequest('POST', "/users/{$this->getId()}/assignLicense");
        $request->attachBody([
            'addLicenses' => [],
            'removeLicenses' => [$skuID],
        ]);
        $request->execute();
    }

    /**
     * @return LicenseDetails[]
     */
    public function licenses() {
        return iterator_to_array(new Collection("/users/{$this->getId()}/licenseDetails", LicenseDetails::class, Collection::NO_PAGINATION));
    }
}
