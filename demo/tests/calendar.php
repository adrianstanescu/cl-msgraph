<?php

use Adrian\CLMSGraph\User;

$currentUser = User::byID(getenv('TEST_USER_ID'));
$calendars = $currentUser->calendars();

?>
<div style="display: grid; grid-template-columns: 5fr 7fr;">
    <div><?php include __DIR__ . '/../components/currentUser.php'; ?></div>
    <div>
        <?php foreach($calendars as $calendar): ?>
            <details <?= $calendar->getIsDefaultCalendar() ? 'open' : '' ?>>
                <summary><strong><?= $calendar->getName() ?> - first 5 events</strong></summary>
                <ol>
                    <?php foreach($calendar->events() as $i => $event): ?>
                        <li>
                            <h6>
                                <?= $event->getSubject() ?>:
                                <?= $event->startDateTime()->format('D, j M') ?>
                                <?php if (!$event->getIsAllDay()): ?>
                                    <?= $event->startDateTime()->format('H:i') ?> — <?= $event->endDateTime()->format('H:i') ?>
                                <?php endif; ?>
                            </h6>
                            <p><?= $event->getBodyPreview() ?></p>
                        </li>
                        <?php if ($i === 4) {
                            break;
                        } ?>
                    <?php endforeach; ?>
                    </ol>
            </details>
            <details>
                <summary><strong><?= $calendar->getName() ?> - this week</strong></summary>
                <ol>
                    <?php
                        $events = $calendar->events(
                            new DateTime('first day of this month 00:00:00'),
                            new DateTime('last day of this month 23:59:59'),
                        );
                        foreach($events as $i => $event): ?>
                        <li>
                            <h6>
                                <?= $event->getSubject() ?>:
                                <?= $event->startDateTime()->format('D, j M') ?>
                                <?php if (!$event->getIsAllDay()): ?>
                                    <?= $event->startDateTime()->format('H:i') ?> — <?= $event->endDateTime()->format('H:i') ?>
                                <?php endif; ?>
                            </h6>
                            <p><?= $event->getBodyPreview() ?></p>
                        </li>
                        <?php if ($i === 4) {
                            break;
                        } ?>
                    <?php endforeach; ?>
                    </ol>
            </details>
        <?php endforeach; ?>
    </div>
</div>

