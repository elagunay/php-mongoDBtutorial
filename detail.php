<?php

//  Show item (29)
if (isset($_POST['item_id'])) {
    //  MongoDB connection created (30)
    $connection = new MongoDB\Driver\Manager('mongodb://localhost:27017');
    //  Database and collection selected (31)
    $collection = 'elagunay.data';

    //  Get value (32)
    $id = $_POST['item_id'];

    //  Update indexes for item (33)
    if (isset($_POST['name'])) {
        //  Write function called (34)
        $update_item = new MongoDB\Driver\BulkWrite();

        //  Indexes update settings (35)
        $update_item->update(
            ['_id' => new MongoDB\BSON\ObjectID($id)],
            ['$set' => [
                'name' => $_POST['name'],
                'latitude' => (float) $_POST['latitude'],
                'longitude' => (float) $_POST['longitude'],
                'tree_height' => (float) $_POST['tree_height'],
            ]
            ],
            ['multi', true]
        );

        //  Indexes updated for item and statement returned (36)
        $update_statement = $connection->executeBulkWrite($collection, $update_item);
    }

    //  ID filter selected for item (37)
    $filter = [
        '_id' => new MongoDB\BSON\ObjectID($id)
    ];

    //  Query function called (38)
    $query = new MongoDB\Driver\Query($filter, []);

    //  Get filter data from collection (39)
    $data = $connection->executeQuery($collection, $query);

    //  Get item from data (40)
    foreach ($data as $d) {
        $item = $d;
    }
?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>
            Ela Günay
        </title>
    </head>

    <body>
        <a href="index.php">< Go Back</a>

        <h1>
            Input Tree
        </h1>

        <form method="POST">
            <input type="hidden" name="item_id" value="<?php echo $item->_id; ?>">

            <p>
                Name:
            </p>
            <input type="text" name="name" placeholder="Name" required
                   value="<?php echo $item->name; ?>">
            <br>

            <p>
                Latitude:
            </p>
            <input type="text" name="latitude" placeholder="39.7323452" required
                   value="<?php echo $item->latitude; ?>">
            <br>

            <p>
                Longitude:
            </p>
            <input type="text" name="longitude" placeholder="32.1234125" required
                   value="<?php echo $item->longitude; ?>">
            <br>

            <p>
                Tree Height:
            </p>
            <input type="text" name="tree_height" placeholder="5.23" required
                   value="<?php echo $item->tree_height; ?>">
            <br><br>

            <input type="submit" value="Submit">
            <br><br>

            <?php
            //  Show response for update statement (41)
            if (isset($update_statement) && $update_statement) {
                echo "Updated successfully!";
            } else if (isset($update_statement) && !$update_statement) {
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

<?php
} else {
    //  Redirect index (42)
    header('Location: index.php');
}
?>