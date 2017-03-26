<?php
require_once 'pdo.php';
session_destroy();
header('Location: login.php');