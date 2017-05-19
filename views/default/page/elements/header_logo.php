<?php
/**
 * Ychange header logo
 */

$site = elgg_get_site_entity();
$site_name = $site->name;
$site_description = $site->description;
$site_url = elgg_get_site_url();
?>

<h1>
    <a class="elgg-heading-site" href="<?php echo $site_url; ?>">
        <?php echo $site_name; ?>
    </a>
</h1>

<h2 class="ychange-description-site">
    <?php echo $site_description; ?>
</h2>
