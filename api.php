<?php

require_once('db_config.php');

header('Content-Type: application/json');

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            get_mahasiswa($id);
        } else {
            get_mahasiswas();
        }
        break;
    case 'POST':
        insert_mahasiswa();
        break;
    case 'PUT':
        $id = intval($_GET["id"]);
        update_mahasiswa($id);
        break;
    case 'DELETE':
        $id = intval($_GET["id"]);
        delete_mahasiswa($id);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_mahasiswas()
{
    global $conn;
    $query = "SELECT * FROM mahasiswa";
    $result = $conn->query($query);
    $mahasiswas = array();
    while ($row = $result->fetch_assoc()) {
        $mahasiswas[] = $row;
    }
    echo json_encode($mahasiswas);
}

function get_mahasiswa($id)
{
    global $conn;
    $query = "SELECT * FROM mahasiswa WHERE id=" . $id;
    $result = $conn->query($query);
    $mahasiswa = $result->fetch_assoc();
    echo json_encode($mahasiswa);
}

function insert_mahasiswa()
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $nama = $data["nama"];
    $nim = $data["nim"];
    $jurusan = $data["jurusan"];
    $query = "INSERT INTO mahasiswa(nama, nim, jurusan) VALUES ('$nama', '$nim', '$jurusan')";
    if ($conn->query($query) === TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Mahasiswa added successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to add Mahasiswa.'
        );
    }
    echo json_encode($response);
}

function update_mahasiswa($id)
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $nama = $data["nama"];
    $nim = $data["nim"];
    $jurusan = $data["jurusan"];
    $query = "UPDATE mahasiswa SET nama='$nama', nim='$nim', jurusan='$jurusan' WHERE id=" . $id;
    if ($conn->query($query) === TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Mahasiswa updated successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to update Mahasiswa.'
        );
    }
    echo json_encode($response);
}

function delete_mahasiswa($id)
{
    global $conn;
    $query = "DELETE FROM mahasiswa WHERE id=" . $id;
    if ($conn->query($query) === TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Mahasiswa deleted successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to delete Mahasiswa.'
        );
    }
    echo json_encode($response);
}
?>