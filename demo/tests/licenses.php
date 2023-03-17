<?php

use Adrian\CLMSGraph\SubscribedSKU;
use Adrian\CLMSGraph\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = User::byID($_POST['userID'] ?? '');
    if (isset($_POST['assign'])) {
        $user->assignLicense($_POST['assign']);
    } elseif (isset($_POST['unassign'])) {
        $user->unassignLicense($_POST['unassign']);
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);

    exit;
}

$allLicenses = SubscribedSKU::all();
$allUsers = User::all();

?>
<details open>
    <summary>Subscriptions</summary>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Available licenses</th>
                <th>Assigned licenses</th>
                <th>Applies to</th>
                <th>Capability status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($allLicenses as $license): ?>
                <?php
                $stats = $license->stats();
                $used = $stats->consumed;
                $active = $stats->enabled + $stats->warning;
                ?>
                <tr>
                    <td><?= $license->name() ?></td>
                    <td><?= $active - $used ?></td>
                    <td>
                        <progress value="<?= $used ?>" max="<?= $active ?>"></progress>
                        <?= $used ?>/<?= $active ?>
                    </td>
                    <td><?= $license->getAppliesTo() ?></td>
                    <td><?= $license->getCapabilityStatus() ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</details>
<?php foreach($allUsers as $user): ?>
    <form method="POST">
        <input type="hidden" name="userID" value="<?= $user->getId() ?>" />
        <details>
            <summary>Licenses assigned to <strong><?= $user->getMail() ?></strong></summary>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Assigned</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allLicenses as $license): ?>
                        <?php
                        $userLicenses = $user->licenses();
                        $isAssigned = false;
                        foreach ($user->licenses() as $userLicense) {
                            if ($userLicense->getSkuId() === $license->getSkuId()) {
                                $isAssigned = true;

                                break;
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $license->name() ?></td>
                            <td><input type="checkbox" onclick="return false;" <?= $isAssigned ? 'checked' : '' ?>></td>
                            <td>
                            
                                <?php if($isAssigned): ?>
                                    <button type="submit" name="unassign" value="<?= $license->getSkuId() ?>" class="inline small">
                                        Unassign
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="assign" value="<?= $license->getSkuId() ?>"  class="inline small">
                                        Assign
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </details>
    </form>
<?php endforeach; ?>
