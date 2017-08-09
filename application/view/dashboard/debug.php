<?php
    // Load necessary models

    // Load Header
    require APP . 'view/_templates/header.php';

    // Load Navigation
    require APP . 'view/_templates/navigation.php';


echo '<h4>Root</h4>';
echo ROOT;

echo '<h4>APP</h4>';
echo APP;

echo '<h4>URL_PUBLIC_FOLDER</h4>';
echo URL_PUBLIC_FOLDER;

echo '<h4>URL_PROTOCOL</h4>';
echo URL_PROTOCOL;

echo '<h4>URL_DOMAIN</h4>';
echo URL_DOMAIN;

echo '<h4>URL_SUB_FOLDER</h4>';
echo URL_SUB_FOLDER;

echo '<h4>URL</h4>';
echo URL;
    // Load Footer

    require APP . 'view/_templates/footer.php';
?>

