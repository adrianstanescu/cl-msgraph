<?php

use Adrian\CLMSGraph\Event;
use Adrian\CLMSGraph\Input\AttendeeInput;
use Adrian\CLMSGraph\Input\BodyInput;
use Adrian\CLMSGraph\Input\EventInput;
use Adrian\CLMSGraph\Input\LocationInput;
use Adrian\CLMSGraph\User;

$currentUser = User::byID(getenv('TEST_USER_ID'));

$createdEvent = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventInput = (new EventInput(
        $currentUser->getId(),
        new DateTime($_POST['start']),
        new DateTime($_POST['end']),
        $_POST['subject']
    ))
        ->withBody(new BodyInput($_POST['body']))
        ->withLocation(new LocationInput($_POST['location']))
        ->withIsOnlineMeeting($_POST['isOnlineMeeting'] === '1')
    ;
    foreach ($_POST['attendees'] ?? [] as $attendee) {
        if (!$attendee) {
            continue;
        }
        $eventInput->attendees[] = new AttendeeInput($attendee);
    }
    $createdEvent = Event::create($eventInput);
}

?>
<style>
    .form-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 0 2rem;
        grid-template-areas:
            "organizer location"
            "start end"
            "attendee-1 attendee-2"
            "subject subject"
            "body body"
            "submit submit";
    }
</style>

<?php if ($createdEvent !== null): ?>
    <h4>Event successfully created <code><?= $createdEvent->getId() ?></code></h4>
<?php endif; ?>

<article>
    <form method="POST">
        <h2>Create event</h2>

        <div class="form-layout">
            <label for="organizer" style="grid-area: organizer;">Organizer
                <select>
                    <option value="<?= $currentUser->getId() ?>" selected><?= $currentUser->getDisplayName() ?></option>
                </select>
            </label>
            
            <label for="start" style="grid-area: start;">Start
                <input type="datetime-local" id="start" name="start" required>
            </label>
            <label for="end" style="grid-area: end;">End
                <input type="datetime-local" id="end" name="end" required>
            </label>

            <label for="subject" style="grid-area: subject;">Subject
                <input type="text" id="subject" name="subject" required>
            </label>
            <label for="body" style="grid-area: body;">Body
                <textarea id="body" name="body" required></textarea>
            </label>

            <label for="attendee-1" style="grid-area: attendee-1;">Attendee 1
                <input type="text" id="attendee-1" name="attendees[]" required>
            </label>
            <label for="attendee-2" style="grid-area: attendee-2;">Attendee 2
                <input type="text" id="attendee-2" name="attendees[]">
            </label>

            <div style="grid-area: location; display: flex; gap: 1rem;">
                <label for="location">Location
                    <input type="text" id="location" name="location" required>
                </label>
                <label for="isOnlineMeeting">
                    Online Meeting<br />
                    <input type="checkbox" id="isOnlineMeeting" name="isOnlineMeeting" value="1" role="switch">
                </label>
            </div>
            <button type="submit" style="grid-area: submit;">Create</button>
        </div>
    </form>
</article>

