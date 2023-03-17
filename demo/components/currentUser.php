<section>
    <h4><?= $currentUser->getDisplayName() ?></h4>
    <dl>
        <dt>id</dt>
        <dd><?= $currentUser->getId() ?></dd>
        <dt>mail</dt>
        <dd><?= $currentUser->getMail() ?></dd>
        <dt>preferredLanguage</dt>
        <dd><?= $currentUser->getPreferredLanguage() ?></dd>
    </dl>
</section>