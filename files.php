<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directory Listing</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .hover\:underline:hover {
            text-decoration: underline;
        }

        .transition {
            transition: all 0.3s ease;
        }

        .search-bar {
            background-color: #ff7f50;
            color: #ffffff;
        }

        .search-bar::placeholder {
            color: #c0c0c0;
        }

        .bg-custom-dark {
            background-color: #797a80;
        }

        .bg-custom-light {
            background-color: #dcdcdc;
        }

        .text-custom-orange {
            color: #ff7f50;
        }

        .text-custom-gray {
            color: #d2cbbe;
        }

        header {
            color: #d2cbbe;
        }

        .header-container {
            background-color: #ff7f50;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .content-container {
            background-color: #333333;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .breadcrumb a {
            display: inline-block;
            margin-right: 5px;
        }

        .breadcrumb span {
            margin-right: 5px;
        }

        .list-item:hover {
            background-color: #ff7f50;
        }

        .directory-list a {
            width: 100%;
            display: flex;
            align-items: center;
        }

        .button-orange {
            background-color: rgba(55, 65, 81, var(--tw-bg-opacity));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            text-align: center;
            cursor: pointer;
            display: inline-block;
        }

        .button-orange:hover {
            background-color: #616161;
        }

        .icon {
            margin-right: 8px;
            width: 20px;
            height: 20px;
        }

        .list-item {
            background-color: transparent;
            transition: background-color 0.3s ease;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #555;
        }
    </style>
</head>

<body class="bg-custom-dark text-custom-gray">
    <header class="header-container text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Directory Listing</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="http://localhost/files.php" class="hover:text-custom-orange transition">Home</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container mx-auto mt-10 p-5 shadow-md rounded-lg flex flex-col content-container">
        <?php
        function listDirectory($dir)
        {
            $items = scandir($dir);
            echo "<ul class='list-none' id='directory-list'>";
            foreach ($items as $item) {
                if ($item == "." || $item == ".." || $item == ".DS_Store" || $item == "webalizer" || $item == "applications.html" || $item == "bitnami.css" || $item == "dashboard" || $item == "" || $item == "" || $item == "favicon.ico" || $item == "files.php") {
                    continue;
                }
                $path = "$dir/$item";
                if (is_dir($path)) {
                    echo "<li class='py-2 px-3 border-b border-gray-700 flex items-center list-item'><img src='https://img.icons8.com/ios-filled/50/000000/folder-invoices.png' alt='Folder' class='icon'><a href='?dir=$path' class='text-custom-gray hover:text-custom-orange hover:underline transition'>$item/</a></li>";
                } else {
                    $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
                    echo "<li class='py-2 px-3 border-b border-gray-700 flex items-center list-item'><img src='https://img.icons8.com/ios-filled/50/000000/file.png' alt='File' class='icon'><a href='$relativePath' target='_blank' class='text-custom-gray hover:text-custom-orange hover:underline transition'>$item</a></li>";
                }
            }
            echo "</ul>";
        }

        function generateBreadcrumbs($path)
        {
            $parts = explode('/', $path);
            $breadcrumb = "<div class='mb-4 p-4 bg-gray-700 rounded breadcrumb'>";
            $breadcrumbPath = "";
            foreach ($parts as $part) {
                if ($part != ".") {
                    $breadcrumbPath .= $part . "/";
                    $breadcrumb .= "<a href='?dir=" . rtrim($breadcrumbPath, '/') . "' class='text-custom-orange hover:underline transition'>$part</a> <span>/</span>";
                }
            }
            $breadcrumb = rtrim($breadcrumb, ' <span>/</span>');
            $breadcrumb .= "</div>";
            echo $breadcrumb;
        }

        $currentDir = isset($_GET['dir']) ? $_GET['dir'] : '.';

        if ($currentDir == '.') {
            echo "<h2 class='text-2xl font-bold mb-4'>Welcome to the Directory Listing</h2>";
            echo "<p class='mb-6 text-custom-gray'>Please select a folder to view its contents. You can also use the search bar to quickly find specific files or folders.</p>";
        } else {
            generateBreadcrumbs($currentDir);
            echo "<h2 class='text-xl font-bold mb-4'>Directory Listing of: $currentDir</h2>";
            if ($currentDir != '.') {
                echo "<a class='text-custom-orange hover:underline transition mb-4 inline-block button-orange' href='?dir=" . dirname($currentDir) . "'>Back</a>";
            }
        }
        ?>

        <!-- Search Bar -->
        <input type="text" id="search-bar" class="w-full p-2 mb-4 rounded search-bar" placeholder="Search..." onkeyup="filterList()">

        <!-- Directory Listing -->
        <?php listDirectory($currentDir); ?>
    </div>

    <script>
        // Filter function for the search bar
        function filterList() {
            const searchInput = document.getElementById('search-bar').value.toLowerCase();
            const listItems = document.querySelectorAll('#directory-list li');

            listItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchInput)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>
