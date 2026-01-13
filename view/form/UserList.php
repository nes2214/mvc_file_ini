<div id="content">
    <fieldset>
        <legend>User list</legend>
        <?php
            if (isset($content)) {
                echo <<<EOT
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Role</th>
                            <th>Active</th>
                        </tr>
EOT;
                foreach ($content as $user) {
                    echo <<<EOT
                        <tr>
                            <td>{$user->getUsername()}</td>
                            <td>{$user->getAge()}</td>
                            <td>{$user->getRole()}</td>
                            <td>{$user->isActive()}</td>
                        </tr>
EOT;
                }
                echo <<<EOT
                    </table>
EOT;
            }
        ?>
    </fieldset>
</div>