<?php

//  MongoDB connection created (1)
$connection = new MongoDB\Driver\Manager('mongodb://localhost:27017');
//  Database and collection selected (2)
$collection = 'elagunay.data';

//  Write data to collection (3)
if (isset($_POST['name'])) {
    //  Write function called (4)
    $new_item = new MongoDB\Driver\BulkWrite();

    //  Data insert settings (5)
    $new_item->insert([
            'name' => $_POST['name'],
            'latitude' => (float) $_POST['latitude'],
            'longitude' => (float) $_POST['longitude'],
            'tree_height' => (float) $_POST['tree_height'],
    ]);

    //  Data inserted to collection and statement returned (6)
    $insert_statement = $connection->executeBulkWrite($collection, $new_item);
}

//  Delete data from collection (7)
if (isset($_POST['delete_item_id'])) {
    //  Write function called (8)
    $process = new MongoDB\Driver\BulkWrite();

    //  Data delete settings (9)
    $process->delete([
            '_id' => new MongoDB\BSON\ObjectID($_POST['delete_item_id'])
    ]);

    //  Data deleted from collection and statement returned (10)
    $delete_statement = $connection->executeBulkWrite($collection, $process);
}

//  Sort data (11)
if (isset($_POST['tree_height_sort'])) {
    //  Get value in type int (12)
    $tree_height_sort = (int) $_POST['tree_height_sort'];

    if ($tree_height_sort == 0) {
        //  Query function called (13)
        $query = new MongoDB\Driver\Query([]);

        //  Get sort data from collection (14)
        $data = $connection->executeQuery($collection, $query);
    } else {
        //  Options selected for sort (15)
        $options = [
            'sort' => [
                'tree_height' => $tree_height_sort
            ]
        ];

        //  Query function called (16)
        $query = new MongoDB\Driver\Query([], $options);

        //  Get sort data from collection (17)
        $data = $connection->executeQuery($collection, $query);
    }
}
//  Filter data (18)
else if (isset($_POST['tree_height_filter'])) {
    //  Get value (19)
    $tree_height_filter = (float) $_POST['tree_height_filter'];

    //  Filters selected for filter (20)
    $filter = [
            'tree_height' => [
                    '$gt' => $tree_height_filter
            ]
    ];

    //  Query function called (21)
    $query = new MongoDB\Driver\Query($filter, []);

    //  Get filter data from collection (22)
    $data = $connection->executeQuery($collection, $query);
}
//  All data (23)
else {
    //  Query function called (24)
    $query = new MongoDB\Driver\Query([]);

    //  Get all data from collection (25)
    $data = $connection->executeQuery($collection, $query);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>
        Ela Günay
    </title>

    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        td {
            text-align: center;
        }
    </style>

</head>

<body>

    <h1>
        Sort and Greater Then Filter
    </h1>

    <form method="POST">
        <select name="tree_height_sort" required>
            <option value="0">
                Default
            </option>
            <option value="-1" <?php if ($tree_height_sort == -1) echo "selected"; ?>>
                High to Low
            </option>
            <option value="1" <?php if ($tree_height_sort == 1) echo "selected"; ?>>
                Low to High
            </option>
        </select>
        <input type="submit" value="Sort">
    </form>

    <br>

    <form method="POST">
        <input type="text" name="tree_height_filter" placeholder="2.62" value="<?php if (isset($tree_height_filter)) echo $tree_height_filter; ?>" required>
        <input type="submit" value="Filter">
    </form>

    <br>

    <h1>
        Table
    </h1>

    <table>

        <tr>
            <th>
                Name
            </th>
            <th>
                Latitude
            </th>
            <th>
                Longitude
            </th>
            <th>
                Tree Height
            </th>
            <th>
                Process
            </th>
        </tr>

        <?php
        //  List data (26)
        foreach ($data as $item) {
            ?>
            <tr>
                <td>
                    <?php echo $item->name; ?>
                </td>
                <td>
                    <?php echo $item->latitude; ?>
                </td>
                <td>
                    <?php echo $item->longitude; ?>
                </td>
                <td>
                    <?php echo $item->tree_height; ?>
                </td>
                <td>
                    <form method="POST" action="detail.php">
                        <input type="hidden" name="item_id" value="<?php echo $item->_id; ?>">
                        <input style="color: green;" type="submit" value="Update">
                    </form>
                    <form method="POST">
                        <input type="hidden" name="delete_item_id" value="<?php echo $item->_id; ?>">
                        <input style="color: red;" type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php
        }
        ?>

    </table>

    <br>

    <?php
    //  Show response for delete statement (27)
    if (isset($delete_statement) && $delete_statement) {
        echo "Deleted successfully!";
    } else if (isset($delete_statement) && !$delete_statement) {
        echo "Error!";
    }
    ?>

    <br>

    <h1>
        Input Tree
    </h1>

    <form method="POST">
        <p>
            Name:
        </p>
        <input type="text" name="name" placeholder="Name" required>
        <br>

        <p>
            Latitude:
        </p>
        <input type="text" name="latitude" placeholder="39.7323452" required>
        <br>

        <p>
            Longitude:
        </p>
        <input type="text" name="longitude" placeholder="32.1234125" required>
        <br>

        <p>
            Tree Height:
        </p>
        <input type="text" name="tree_height" placeholder="5.23" required>
        <br><br>

        <input type="submit" value="Submit">
        <br><br>

        <?php
        //  Show response for insert statement (28)
        if (isset($insert_statement) && $insert_statement) {
            echo "Inserted successfully!";
        } else if (isset($insert_statement) && !$insert_statement) {
            echo "Error!";
        }
        ?>
    </form>

    <br><br>

    <h1>
        Ela Günay
    </h1>

</body>

</html>
