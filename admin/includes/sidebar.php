<aside class="sidebar">

    <div class="admin-card">
        <div class="admin-avatar">R</div>

        <div class="admin-info">
            <h4>
                <?= htmlspecialchars($_SESSION['admin_name']) ?>
            </h4>
            <p>Admin</p>
        </div>
    </div>

    <div class="menu-title">
        Main Menu
    </div>

    <ul>

        <li class="menu-dropdown">

            <a href="#" class="dropdown-toggle">
                Forms
                <span class="arrow">▼</span>
            </a>

            <ul class="submenu">
                <li>
                    <a href="forms.php">
                        All Forms
                    </a>
                </li>

                <li>
                    <a href="create-form.php">
                        Create Form
                    </a>
                </li>
            </ul>

        </li>

        <li>
            <a href="manage-submissions.php">
                Submissions
            </a>
        </li>

        <li>
            <a href="logout.php">
                Logout
            </a>
        </li>

    </ul>

</aside>