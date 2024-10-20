<?php
include ("../../conexion.php");

if(isset($_POST['id']))
{
    $id = $_POST['id'];
    $delete_query = mysqli_query($conn, "delete FROM tblfechasreservadas WHERE id='$id'");
}
?>