<?php
$categoryDAO = CategoryFileDAO::getInstance();
$categories = $categoryDAO->listAll();

$catNames = [];
foreach ($categories as $category) {
    $catNames[$category->getId()] = $category->getName();
}
?>
<div id="content">
    <fieldset>
        <legend>Product list</legend>    
        <?php
            if (isset($content)) {
                echo <<<EOT
                    <table>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Price (€)</th>
                            <th>Description</th>
                            <th>Category</th>
                        </tr>
EOT;
                foreach ($content as $product) {
                    $catName = isset($catNames[$product->getCategory()]) ? $catNames[$product->getCategory()] : 'Unknown';
                    echo <<<EOT
                        <tr>
                            <td>{$product->getId()}</td>
                            <td>{$product->getName()}</td>
                            <td>{$product->getPrice()}</td>
                            <td>{$product->getDescription()}</td>
                            <td>{$catName}</td>
                        </tr>
EOT;
                }
                echo <<<EOT
                    </table>
EOT;
            } else {
                echo "<p>No products found.</p>";
            }
        ?>
    </fieldset>
</div>
