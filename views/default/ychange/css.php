<?php
/**
 * CSS YCHANGE theme
 *
 */
?>
/* <style> /**/

/*******************************
    Ychange general styles
********************************/
body {
  background: url(<?php echo elgg_get_simplecache_url('backgrounds/site-bg.jpg'); ?>) no-repeat center center fixed;
}

.elgg-body {
  background: #fff;
  padding: 10px;
}

.elgg-sidebar {
  border: none;
  background: #fff;
  padding: 10px;
}

.ychange-satellite-link {
    border: 1px solid #AAAAAA;
    padding: 5px;
    margin: 2.5px;
    display: inline-block;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.ychange-satellite-link:hover {
    border-color: #4690D6;
}

.ychange-satellite-link img.ychange-satellite-image {
    opacity: 0.75;
}

.ychange-satellite-link img.ychange-satellite-image:hover {
    opacity: 1;
}

.ychnage-project-label {
    display: block;
}

.ychange-satellite-image-edit {
    display: inline-block;
    text-align: center;
}

.ychange-satellite-image-edit .ychange-satellite-link {
    display: block;
}

.ychange-map-input {
    width: 100%;
    height: 320px;
}

.ychange-required label::after {
    content: ' *';
    color: red;
    vertical-align: middle;
}
