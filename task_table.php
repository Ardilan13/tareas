<?php include 'db_connect.php'; ?>
<tbody>
    <?php
    $connect = new PDO("mysql:host=localhost;dbname=tareas", "root", "");

    $column = array("id", "task", "due_date", "name", "status", "accion");

    $query = "SELECT * FROM task_list";
    /*     $qry = $conn->query("SELECT t.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as name FROM task_list t inner join employee_list e on e.id = t.employee_id $where order by unix_timestamp(t.date_created) asc"); */
    if (isset($_POST["search"]["value"])) {
        $query .= "WHERE id LIKE '%" . $_POST["search"]["value"] . "%' 
                   OR task LIKE '%" . $_POST["search"]["value"] . "%'
                   OR due_date LIKE '%" . $_POST["search"]["value"] . "%'
                   OR name LIKE '%" . $_POST["search"]["value"] . "%'
                   OR status LIKE '%" . $_POST["search"]["value"] . "%'";
    }
    if (isset($_POST["order"])) {
        $query .= ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
    } else {
        $query .= ' ORDER BY id DESC';
    }

    $query1 = '';

    if ($_POST['length'] != -1) {
        $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $statement = $connect->prepare($query);

    $statement->execute();

    $number_filter_row = $statement->rowCount();

    $result = $connect->query($query . $query1);

    $data = array();

    foreach ($result as $row) {
        $sub_array = array();
        $sub_array[] = $row['customer_id'];
        $sub_array[] = $row['customer_first_name'];
        $sub_array[] = $row['customer_last_name'];
        $sub_array[] = $row['customer_email'];
        $sub_array[] = $row['customer_gender'];
        $data[] = $sub_array;
    }

    function count_all_data($connect)
    {
        $query = "SELECT * FROM customer_table";

        $statement = $connect->prepare($query);

        $statement->execute();

        return $statement->rowCount();
    }

    $output = array(
        "draw"        =>    intval($_POST["draw"]),
        "recordsTotal"    =>    count_all_data($connect),
        "recordsFiltered"    =>    $number_filter_row,
        "data"    =>    $data
    );

    echo json_encode($output);
