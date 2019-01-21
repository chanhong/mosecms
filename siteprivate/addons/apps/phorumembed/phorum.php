<?php

defined('_MOSECMS') or die('NOT allowed!');
// Author: Maurice Makaay
// This is a simple example of the embed phorum module.
// The stub example will asume that a user with user_id 1 is logged
// in. This user should normally exist within a Phorum install,
// since the administrator that is setup at install time is created
// using that id.
// The location of our Phorum software.
//$PHORUM_DIR = $_SERVER["DOCUMENT_ROOT"] . "/phorum5125";
$PHORUM_DIR = "phorum5125";

require_once("$PHORUM_DIR/mods/embed_phorum/PhorumConnectorBase.php");
require_once("$PHORUM_DIR/mods/embed_phorum/connectors/mosecms/connector.php");
global $PHORUM_CONNECTOR;
$PHORUM_CONNECTOR = new PhorumConnector();

// Run Phorum and let the PhorumConnector class transfer the output
// to our website framework.
include "$PHORUM_DIR/mods/embed_phorum/run_phorum.php";
?>
