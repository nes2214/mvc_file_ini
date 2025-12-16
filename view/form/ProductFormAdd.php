<?php
require_once "model/persist/CategoryDbDAO.class.php";

// Cargar todas las categorías
$categoryDAO = CategoryDbDAO::getInstance();
$categories = $categoryDAO->listAll();
?>

<div id="content">
    <form method="post" action="">
        <fieldset>
            <legend>Add Product</legend>

            <label>Id *:</label>
            <input type="text" placeholder="Id" name="id"
                value="<?php if (isset($content)) { echo $content->getId(); } ?>" />

            <label>Name *:</label>
            <input type="text" placeholder="Name" name="name"
                value="<?php if (isset($content)) { echo $content->getName(); } ?>" />

            <label>Price *:</label>
            <input type="number" placeholder="Price" name="price" step="0.01"
                value="<?php if (isset($content)) { echo $content->getPrice(); } ?>" />

            <label>Description *:</label>
            <input type="text" placeholder="Description" name="description"
                value="<?php if (isset($content)) { echo $content->getDescription(); } ?>" />

            <label>Category:</label>
            <select name="category">
                <option value="">Select category</option>

                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat->getId() ?>"
                    <?php if (isset($content) && $content->getCategory() == $cat->getId()) echo "selected"; ?>>
                    <?= htmlspecialchars($cat->getName()) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <label>* Required fields</label>

            <input type="submit" name="action" value="add" />
            <input type="reset" value="reset" />
        </fieldset>
    </form>
</div>