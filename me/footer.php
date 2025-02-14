<?php
$language = new Language();
?>

<footer>
    <div class="footer-container">
        <ul>
            <li><a href="<?= $language->get('footer-about-link'); ?>"><?= $language->get('footer-about-text'); ?></a>
            </li>
            <li>
                <a href="<?= $language->get('footer-contact-link'); ?>"><?= $language->get('footer-terms-of-use-text'); ?></a>
            </li>
            <li>
                <a href="<?= $language->get('footer-privacy-policy-link'); ?>"><?= $language->get('footer-privacy-policy-text'); ?></a>
            </li>
            <li>
                <a href="<?= $language->get('footer-contact-link'); ?>"><?= $language->get('footer-contact-text'); ?></a>
            </li>
        </ul>
        <p>&copy; <?php echo date('Y'); ?> <?= $language->get('website-name'); ?>
            - <?= $language->get('footer-text'); ?></p>
    </div>
</footer>
</body>
</html>
