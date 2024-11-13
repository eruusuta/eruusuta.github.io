<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: admin.html");
    exit();
}

include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="main.css">
    <style>
        .form-control64 {
            align-self: center;
            width: 90%;
            padding: 12px 15px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(42, 42, 42, 0.8);
            color: grey;
            transition: background-color 0.3s, border-color 0.3s;
            position: relative;
            margin-left: 35px;
            margin-bottom: 15px;
        }

        .form-control64:focus {
            background-color: rgba(51, 51, 51, 0.9);
            border-color: #5bc0de;
            box-shadow: 0 0 5px rgba(91, 192, 222, 0.8);
        }

        .form-control64::placeholder {
            color: rgba(200, 200, 200, 0.8);
        }

        .button2 {
            background-color: grey;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }

        .button2:hover {
            background-color: transparent;
        }

        .delete-button {
            padding: 5px 10px;
            background-color: darkred;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
        }

        .delete-button:hover {
            background-color: red;
        }

        .edit-button {
            background-color: goldenrod;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        .edit-button:hover {
            background-color: gold;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="inventory_management.html" class="back-button">Back</a>
        <h1>First Aid Kit Inventory</h1>

        <?php
        if (isset($_SESSION['item_added'])) {
            echo "<div style='color: skyblue; font-weight: bold; text-align: center;'>" . $_SESSION['item_added'] . "</div>";
            unset($_SESSION['item_added']); 
        }
        ?>

        <!--search bar-->
        <section id="search-section">
            <input type="text" id="search-input" placeholder="Search FirstAid" onkeyup="searchInventory()" class="form-control64">
        </section>

        <section id="inventory">
            <h2>Inventory List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="inventory-body"></tbody>
            </table>
        </section>

        <section id="add-item-form" style="display:none;">
            <h2>Add New Item</h2>
            <form action="add_item.php" method="POST">
                <input type="text" name="item_name" placeholder="Item Name" required class="form-control64">
                <input type="number" name="quantity" placeholder="Quantity" required class="form-control64">
                <input type="text" name="location" placeholder="Location" required class="form-control64">
                <button type="submit" class="button2">Add First Aid</button>
            </form>
        </section>

        <!--edit first aid-->
        <section id="edit-item-form" style="display:none;">
            <h2>Edit Item</h2>
            <form action="edit_item.php" method="POST">
                <input type="hidden" id="edit-item-id" name="id">
                <input type="text" id="edit-item-name" name="item_name" placeholder="Item Name" required class="form-control64">
                <input type="number" id="edit-item-quantity" name="quantity" placeholder="Quantity" required class="form-control64">
                <input type="text" id="edit-item-location" name="location" placeholder="Location" required class="form-control64">
                <button type="submit" class="button2">Save Changes</button>
                <button type="button" class="button2" onclick="cancelEdit()">Cancel</button>
            </form>
        </section>

        <button class="button2 toggle" id="toggle-button" onclick="toggleForm()">Add New Item</button>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchInventory();

            function fetchInventory(searchTerm = '') {
                fetch(`get_inventory.php?search=${searchTerm}`)
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.getElementById('inventory-body');
                        tbody.innerHTML = ''; 
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.item_name}</td>
                                <td>${item.quantity}</td>
                                <td>${item.location}</td>
                                <td>
                                    <button class="edit-button" onclick="editItem(${item.id})">Edit</button>
                                    <button class="delete-button" onclick="deleteItem(${item.id})">Delete</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    })
                    .catch(error => console.error("Error fetching inventory:", error));
            }

            window.deleteItem = function(id) {
                const isConfirmed = confirm("Are you sure you want to delete this item?");
                if (isConfirmed) {
                    fetch(`delete_item.php?id=${id}`, { method: 'GET' })
                        .then(() => fetchInventory())
                        .catch(error => console.error("Error deleting item:", error));
                }
            };

            //toggle item form to visible
            window.toggleForm = function() {
                const form = document.getElementById('add-item-form');
                const button = document.getElementById('toggle-button');
                
                //hide the Edit form if its visible
                document.getElementById('edit-item-form').style.display = 'none';

                //toggle visibility of the add new item form
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                    button.textContent = 'Close'; 
                } else {
                    form.style.display = 'none';
                    button.textContent = 'Add New Item'; 
                }
            };

            window.searchInventory = function() {
                const searchTerm = document.getElementById('search-input').value;
                fetchInventory(searchTerm);
            };

            //edit item
            window.editItem = function(id) {
                fetch(`get_inventory.php?id=${id}`)
                    .then(response => response.json())
                    .then(item => {
                        if (item) {
                            //hide the add new item form
                            document.getElementById('add-item-form').style.display = 'none';
                            document.getElementById('edit-item-form').style.display = 'block';

                            //hide the new item when click edit
                            document.getElementById('toggle-button').style.display = 'none';

                            document.getElementById('edit-item-id').value = item.id;
                            document.getElementById('edit-item-name').value = item.item_name;
                            document.getElementById('edit-item-quantity').value = item.quantity;
                            document.getElementById('edit-item-location').value = item.location;
                        } else {
                            console.error("Item not found");
                        }
                    })
                    .catch(error => console.error("Error fetching item details for edit:", error));
            };

            //cancel edit function
            window.cancelEdit = function() {
                document.getElementById('edit-item-form').style.display = 'none';
                document.getElementById('toggle-button').textContent = 'Add New Item';
                document.getElementById('toggle-button').style.display = 'inline-block';
                
            };
        });
    </script>
</body>
</html>
