<div id="content">
    <form method="post">
        <fieldset>
            <legend>Add user</legend>
            <label>Username *:</label>
            <input type="text" placeholder="Username" name="username"
                value="<?php if (isset($content)) { echo $content->getUsername(); } ?>" />

            <label>Password *:</label>
            <input type="password" placeholder="Password" name="password"
                value="<?php if (isset($content)) { echo $content->getPassword(); } ?>" />

            <label for="age">Age *:</label>
            <input type="text" placeholder="Age" name="age"
                value="<?php if (isset($content)) { echo $content->getAge(); } ?>" />

            <label for="role">Role *:</label>
            <select name="role">
                <option value="basic" <?php if (isset($content) && $content->getRole() === 'basic') echo 'selected'; ?>>
                    Basic</option>
                <option value="advanced"
                    <?php if (isset($content) && $content->getRole() === 'advanced') echo 'selected'; ?>>Advanced
                </option>
            </select>

            <label for="active">Active *:
                <input type="checkbox" name="active" value="1"
                    <?php if (isset($content) && $content->isActive()) echo 'checked'; ?> />
            </label>

            <label>* Required fields</label>
            <input type="submit" name="action" value="search" />
            <input type="submit" name="action" value="modify" />
            <input type="submit" name="action" value="delete" />
            <input type="reset" value="reset" />
        </fieldset>
    </form>
</div>