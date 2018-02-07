<?php
require_once '../DataBaseInteraction.php';

$conn = new DataBaseInteraction();

$conn->createTable();